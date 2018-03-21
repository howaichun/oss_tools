<?php
/**
 * Created by IntelliJ IDEA.
 * User: heweijun
 * Date: 2018/3/16
 * Time: 20:09
 */
function p($arr, $open=0){
    echo "\n";
    var_export($arr);
    echo "\n";
    if($open == 1){
        exit;
    }
}

function currentPath(){
    $path = __DIR__;
    return $path;
}

/**
 * @decription 建立文件
 * @param  string $aimUrl
 * @param  boolean $overWrite 该参数控制是否覆盖原文件
 * @return boolean
 */
function createFile($aimUrl, $overWrite = false) {
    if (file_exists_case($aimUrl) && $overWrite == false) {
        return false;
    } elseif (file_exists_case($aimUrl) && $overWrite == true) {
        unlinkFile($aimUrl);
    }
    $aimDir = dirname($aimUrl);
    createDir($aimDir);
    touch($aimUrl);
    return true;
}

/**
 * 建立文件夹
 * @param string $aimUrl
 * @return viod
 */
function createDir($aimUrl) {
    $aimUrl = str_replace('', '/', $aimUrl);
    $aimDir = '';
    $arr = explode('/', $aimUrl);
    $result = true;
    foreach ($arr as $str) {
        $aimDir .= $str . '/';
        if (!file_exists_case($aimDir)) {
            @$result = mkdir($aimDir,0777);
        }
    }
    return $result;
}

/**
 * 删除文件
 * @param string $aimUrl
 * @return boolean
 */
function unlinkFile($aimUrl) {
    if (file_exists_case($aimUrl)) {
        unlink($aimUrl);
        return true;
    } else {
        return false;
    }
}

/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN && APP_DEBUG) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

function storelogs($filepath,$word){
    if(!file_exists_case($filepath)){
        $tmp =	createFile($filepath);
    }
    $fp = fopen($filepath,"a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,$word."\r\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}

//四张图片 todo
function smeta($string){
   $res =   json_decode($string,true);
   return $res;
}

/**
  * todo
  * @Desc : way 存储位置
  */
function smetaImageDeal($string, $way='okaycms'){
    $arr =  json_decode($string,true);
    // p($arr);
    $gatherChangeImageList = array();
    //p($arr['photo']);
    if(!empty($arr['photo'])){
        foreach($arr['photo'] as $k=>&$v) {
            if(strpos(strtolower($v['url']), $way) !==false) continue;
            if(empty($v['url'])) continue;
            if((strpos(strtolower($v['url']), 'okayzhihui') !==false) ||
               (strpos(strtolower($v['url']), 'hotfix') !==false) || 
               (strpos(strtolower($v['url']),'okzhihui')!==false)||
               (strpos(strtolower($v['url']),'web.okay') !== false)){
                $v['url'] = strstr($v['url'], 'upload');
                $v['url'] = str_replace('upload/', '', $v['url']);
            }
            $relativePathfile = $v['url'];
            $gatherChangeImageList[] = array(
                'locateRealPath'   => __DIR__.'/upload_cms/'.$v['url'],
                'relativePathfile' => $relativePathfile
            ); 
            $v['url'] = $way.'/'.$relativePathfile;  //new path url
        } 
    }
    
    if(!empty($arr['thumb'])){
       if((strpos(strtolower($arr['thumb']), 'okayzhihui') !==false) || 
          (strpos(strtolower($arr['thumb']), 'hotfix') !==false) ||
          (strpos(strtolower($arr['thumb']),'okzhihui')!==false) ||
          (strpos(strtolower($arr['thumb']),'web.okay') !== false)){
            $arr['thumb'] = strstr($arr['thumb'], 'upload');
            $arr['thumb'] = str_replace('upload/', '', $arr['thumb']);
       }

       $relativePathfile  = $arr['thumb'];
       $gatherChangeImageList[] = array(
            'locateRealPath'    => __DIR__.'/upload_cms/'.$arr['thumb'],
            'relativePathfile'  => $relativePathfile
       );
       $arr['thumb'] = $way.'/'.$relativePathfile;  //new path url
    }

    $data = array(
        'dealArr' => $gatherChangeImageList,
        'updateArrJson' => json_encode($arr)
    );
    return $data;
}

//编辑器内容处理
function content($string){
    $content = htmlspecialchars_decode($content);
    return $content;
}

//文本图片处理 todo
function contentImageDeal($string){

}

function okayPath($path){
    if(strpos(strtolower($path), 'okaymall') !==false){
        $string = 'okaymall';
    } else if(strpos(strtolower($path), 'okaycms') !==false){
        $string = 'okaycms';
    }

    return $string;
}

//正则匹配文本编辑器的图片地址，并修改
function getFixContentImagePath($string, $way='okaycms'){
    $data = array();
    $gatherChangeImageList = array();
    #匹配img标签的正则表达式
    $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
    preg_match_all($preg, $string, $allImg);
    $data = $allImg[1];
    foreach($data as $key=>$val){
        if(empty($val)) continue;
        if(strpos(strtolower($val), $way) !==false) continue;
        if(empty($val)) continue;
        if((strpos(strtolower($val), 'upload') !==false) ||
             (strpos(strtolower($val), 'hotfix') ===false) || 
             (strpos(strtolower($val),'okzhihui')!==false) ||
             (strpos(strtolower($val),'web.okay') !== false)
         ){
                $val1                    = strstr($val, 'upload');
                $relativePathfile        = str_replace('upload/', '', $val1);
                $gatherChangeImageList[] = array(
                    'locateRealPath'    => __DIR__.'/upload_cms/'.$relativePathfile,
                    'relativePathfile'  => $relativePathfile
                );

                $domain = "https://fe-mall.oss-cn-beijing.aliyuncs.com/";
                $newPath = $domain.$way.'/'.date(Ymd).$relativePathfile;
                $string = str_replace($val,$newPath,$string);
        }
    }
    $result = array(
        'changeImageList'=> $gatherChangeImageList,
        'content'=> $string
    );
    return $result;
}

//获取文本编辑器中的图片
function getMessageType($string){
        $data = array(
            "imgUrl"    => 0,
            'videoUrl'  => 0,
            "type"      => '',
        );
        #匹配img标签的正则表达式
        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $string, $allImg);
        $data['image'] = $allImg[1];
        if(count($data['image']) > 0) $data['imgUrl'] = array_shift($data['image']);
        if(!empty($v)) $data['type'] = "image";

        $pregVideo = '/<iframe(.*?)><\/iframe>/i';
        preg_match($pregVideo, $string , $video);
        $pregVideo = '/.+src=\"(.+?)\".*/i';
        preg_match($pregVideo, $video[1] , $v);
        $data['videoUrl'] = $v[1];
        if(!empty($v)) $data['type'] = "video";
        return $data;
}

/** 
 * @Desc   : 拼凑图片实际地址 (mall)
 */
function getMallImageRealPath($path){
    return __DIR__.'/upload_mall/'.$path;
}

/** 
 * @Desc   : 拼凑图片实际地址 (cms)
 */
function getCmsImageRealPath($path){
     return __DIR__.'/upload_cms/'.$path;
}

/**
 * @Desc  :
 * @Params:  
 *    $way;
 *    官网默认 okaycms 开头
 *    商城默认 okaymall开头
 *    $locateRealPath   文件之前的路径
 *    $ossClient oss启动对象
 *    relativePathfile oss存储路径
 * https://fe-mall.oss-cn-beijing.aliyuncs.com/okaycms/portal/20180316/5aab420412b0d.png
 */
function storeToAliyunOss($ossClient, $locateRealPath ,$relativePathfile,$way='okaymall',$isunlink=false, $bucket='fe-mall'){
    //判断bucketname是否存在，不存在就去创建
    try {
        if( !$ossClient->doesBucketExist($bucket)){
            $ossClient->createBucket($bucket);
        }
    }catch (Exception $e) {
        $e->getErrorMessage();
    }

    $data = array('msg'=>'already upload oss');
    if(strpos(strtolower($locateRealPath), 'fe-mall') !==false){
        return $data;
    }

    if(strpos(strtolower($locateRealPath), 'okaymall') !==false){
        return $data;
    }

    if(strpos(strtolower($locateRealPath), 'okaycms') !==false){
        return $data;
    }

    if(empty($locateRealPath) || empty($relativePathfile)) return $data;

    if(!in_array($way, array('okaymall','okaycms'))) 
        return array('msg'=> 'store path error! Just Fit dir  okaymall or okaycms');

    //OSS所要保存文件的全路径
    $object = $way.'/'.date(Ymd).$relativePathfile;

    p($object);
    p($locateRealPath);

    $oss="";
    try{
        $result = $ossClient->uploadFile($bucket,$object,$locateRealPath);
        // if (isunlink==true){
        //     unlink($locateRealPath);
        // }
    }catch (OssException $e){
        $e->getErrorMessage();
    }
    if(!empty($oss.$result['oss-request-url'])){
         storelogs(  __DIR__."/Logs/".$way."ossFullPath/".date('Ymd',time()).".ossFullPath.log" ,  $oss.$result['oss-request-url'].';');
    } else{
        return '';
    }
    storelogs( __DIR__."/Logs/".$way."ossShortPath/".date('Ymd',time()).".ossShortPath.log"  ,  $object.';');
    $data = array(
        'url_short_store'=> $object,                        //短链接(存储)
        'url_full_path'  => $oss.$result['oss-request-url'] //全连接
    );
    return $data;
}

/**
 * @Desc : 删除bucketFile(单文件删除)
 */
function deleteBucketFile($ossClient,$string,$bucket='fe_mall'){
    //判断bucketname是否存在，不存在就去创建
    // if( !$ossClient->doesBucketExist($bucket)){ $ossClient->createBucket($bucket); }

    try{
        $result = $ossClient->deleteObject($bucket,$string);
    } catch (OssException $e){
        $e->getErrorMessage();
    }
    return $result;
}

/**
 * @Desc :  删除bucketFile(多文件删除)
 */
function deleteBucketFiles($ossClient,$arr,$bucket='fe_mall'){
    //判断bucketname是否存在，不存在就去创建
    //if( !$ossClient->doesBucketExist($bucket)) {  $ossClient->createBucket($bucket);}
    try{
        $result = $ossClient->deleteObjects($bucket, $arr);
    } catch (OssException $e){
        $e->getErrorMessage();
    }
    return $result;
}



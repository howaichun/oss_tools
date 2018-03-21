<?php
/**
 * Created by IntelliJ IDEA.
 * User: heweijun
 * Date: 2018/3/16
 * Time: 21:49
 */
date_default_timezone_set("Asia/Shanghai");


//加载OSS
require_once "common.php";

// 数据库
require_once "db_mall.php";
// 函数库
require_once "functions.php";

require_once "array.php";


$log_path = __DIR__."/Logs/okaymall/".date('Ymd',time()).".update.static.log";
$origin_log_path = __DIR__."/Logs/okaymallOrigin/".date('Ymd',time()).".update.static.log";
$err_path = __DIR__."/Error/okaymall/".date('Ymd',time()).".error.log";

/**
 * @Desc : 遍历对应数据库的需要操作的数据表
 */
foreach($mall_okay as $tableName=>$val){
  // echo count($val)."\n";continue;
  $sql = "select ";   
    foreach($val as $valIndexNum =>$fieldName){

      if($valIndexNum == 0) $tableField = $fieldName;
    $sql .= ' '.$fieldName.',';
  }
  $sql = rtrim($sql, ",");
  $sql .= " from ".$tableName;
 
  $query = $pdo->prepare($sql);
    $query->execute();
    $row = $query->fetchAll(PDO::FETCH_ASSOC);
    // p($row);exit;
    //处理图片
    //获取sql的key值
    foreach($row as $rowKey=>$rowVal){
        $i = 0;
        $m = 0;
        if($i==0){
                $updateSql = ' update `'.$tableName.'`';
                $updateSql .= ' set';

                $originUpdateSql = $updateSql;
        }
        $n = 0;
      foreach($rowVal as $k=>$v){
            if($n==0) $tableFieldVal = $v;
            if($n!=0){
                 if(!empty($v)){
                     
                    //需要遍历 todo
                    // if($k == 'smeta') {
                    //      ++$m;
                    //      $originUpdateSql .= " `".$k."`='".$v. "' ,";  //非空才组装
                    //      $semta = smetaImageDeal($v);
                    //      foreach($semta['dealArr'] as $semtaKey=>$semtaVal){
                    //         $locateFullPath = $relativePath = $result= '';
                    //         //OSS处理上传图片 
                    //          try{
                    //              $locateFullPath = $semtaVal['locateRealPath'];
                    //              $relativePath   = $semtaVal['relativePathfile'];
                    //              $result = storeToAliyunOss($OSSClient, $locateFullPath, $relativePath,'okaycms');  
                    //          } catch (Exception $e) {
                    //             $e->getErrorMessage();
                    //          }
                    //      }
                    //      $updateSql .= " `".$k."`='".$semta['updateArrJson']. "' ,";
                    //      continue;
                    // }    
                    // $locateFullPath = $relativePath = $result = '';
                    // //文本内容需要特殊处理 todo
                    // // if($k == 'post_content'){
                    // if(strpos(strtolower($k), 'content') !==false){
                    //     ++$m;
                    //     $originUpdateSql .= " `".$k."`='".$v. "' ,";  //非空才组装
                    //     $content = getFixContentImagePath($v);
                    //     foreach($content['changeImageList'] as $contentImageKey=>$contentImageVal){
                    //         $locateFullPath = $relativePath = $result= '';
                    //         //OSS处理上传图片 
                    //          try{
                    //              $locateFullPath = $contentImageVal['locateRealPath'];
                    //              $relativePath   = $contentImageVal['relativePathfile'];
                    //              $result = storeToAliyunOss($OSSClient, $locateFullPath, $relativePath,'okaycms');  
                    //          } catch (Exception $e) {
                    //             $e->getErrorMessage();
                    //          }     
                    //     }
                    //     $updateSql .= " `".$k."`='".$content['content']. "' ,";
                    //     continue;

                    // }

                    //普通的URL处理
                    $locateFullPath =  getMallImageRealPath($v);
                    $fileExistYesOrNot = file_exists_case($locateFullPath);
                    p($locateFullPath);
                    p($v);
                    if($fileExistYesOrNot){
                         ++$m;
                         //记录原始更新   
                         $originUpdateSql .= " `".$k."`='".$v. "' ,";

                         //OSS处理上传图片 
                         try{
                             $res = storeToAliyunOss($OSSClient, $locateFullPath, $v,'okaymall');  
                             p($res);
                             if(!empty($res)){
                                $updateSql .= " `".$k."`='".$res['url_short_store']. "' ,";  
                             }
                         } catch (Exception $e) {
                            $e->getErrorMessage();
                         }
                    }
                 }      
            }      
          ++$n;
      }
        $i++;
        $updateSql = rtrim($updateSql,',');
        $updateSql .= ' where `'.$tableField.'`='.$tableFieldVal;
        if($m === 0) { 
            $updateSql='';
        } else {
            //更新URL到最新的OSS图片地址
           // p($updateSql);
            try{
                //p($updateSql);
                $query  = $pdo->prepare($updateSql);
                $result = $query->execute();
            } catch(Exception $e){
                $e->getErrorMessage();
            }
        
            storelogs($log_path,$updateSql.'; ');

             //记录原始还原的更新log
            $originUpdateSql = rtrim($originUpdateSql,',');
            $originUpdateSql .= ' where `'.$tableField.'`='.$tableFieldVal;
            storelogs($origin_log_path,$originUpdateSql.';');

           // p($originUpdateSql);
        } 
       // exit;  //只执行一个
    }

}
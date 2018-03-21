<?php
/**
 * Created by IntelliJ IDEA.
 * User: heweijun
 * Date: 2018/3/16
 * Time: 21:55
 */
require_once __DIR__.'/autoload.php';
use \Aliyun\OSS\OSSClient;

// if (isset($_SERVER['argv'][1])) {
//     $upload_path = $_SERVER['argv'][1];
// }else{
//     echo "Parameter is not specified!";
//     exit;
// }


//上传单文件时 指定该文件在OSS所在路径 (默认为OSS根目录)
$accessKeyId='';
$accessKeySecret='';
$endpoint='https://oss-cn-beijing.aliyuncs.com';
$bucket='';
$aliDomain = 'https://****.oss-cn-beijing.aliyuncs.com';

$OSSClient = new OSSClient($accessKeyId,$accessKeySecret,$endpoint);
try {
	if( !$OSSClient->doesBucketExist($bucket)){
    	$ossClient->createBucket($bucket);
	}
}catch (Exception $e) {
	$e->getErrorMessage();
}






<?php

date_default_timezone_set("Asia/Shanghai");


//加载OSS
require_once "common.php";

// 数据库
require_once "db_cms.php";
// 函数库
require_once "functions.php";

require_once "array.php";


$file_list = file_get_contents(__DIR__.'/Logs/okaymallossShortPath/'.date("Ymd").'.ossShortPath.log');

$file_list_arr = explode(';',$file_list);
p($file_list_arr);
if(!empty($file_list_arr)){
	foreach($file_list_arr as $val){
		if(empty(trim($val))) continue;
		// echo $val."\n";continue;
		$path = trim($val);
		p($path);
		try{
			deleteBucketFile($OSSClient, $path,$bucket );
		}catch(OssException $e){
			$e->getErrorMessage();
		}
	}

	// try{
	// 	deleteBucketFiles($ossClient,$path,$bucket='fe_mall');
	// } catch(OssException $e){
	// 	$e->getErrorMessage;
	// }
}


// $result = deleteBucketFile($OSSClient, 'okaycms/ueditor/20180104/5a4df0e5ea090.jpg',$bucket );
// p($result,1);

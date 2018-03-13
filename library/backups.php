<?php
include "../control/ku/configure.php";
$OldUrl = $conf['SqlUrl'];
$NewUrl = "../backups/".date('Ymd');
if(file_exists($NewUrl) == false){
	//如果根目录不存在存放数据库文件的文件夹，则创建
	if(!file_exists("../backups")){
		mkdir("../backups");
	}
	mkdir($NewUrl);
	//将最新的数据库保存到新建的文件夹中
	CopyFile($OldUrl,$NewUrl);
	//删除超过30天的文件夹
	$FileArray = glob("../backups/*");
	$StarTime = strtotime(date("Ymd"))-3600*24*20;
	foreach($FileArray as $value){
		$SqlArray = explode("/",$value);
		$SqlIndex = count($SqlArray)-1;
		if(strtotime($SqlArray[$SqlIndex]) < $StarTime ){
			DeleteFile($value);
		}
	}
}
/**********文件夹复制函数*********************************/
function CopyFile($OldUrl,$NewUrl){
	if($Old = opendir($OldUrl)){
		while(($file = readdir($Old)) !== false){
			if($file != "." && $file != ".."){
				if(is_dir("{$OldUrl}/{$file}")){//判断给定文件名是否是一个目录
					$bool = CopyFile("{$OldUrl}/{$file}","{$NewUrl}/{$file}");//如果是，则自循环本函数
					if(!$bool){
						LogText("数据库备份","admin","{$OldUrl}/{$file}没有公共读取权限");
					}
				}else{
					copy("{$OldUrl}/{$file}","{$NewUrl}/{$file}");
				}
			}
		}
		closedir($Old);
	}
}
/**********文件夹删除函数*********************************/
function DeleteFile($url){
	//先删除目录下的文件：
	if($Old = opendir($url)){
		while(($file = readdir($Old)) !== false){
			if($file != "." && $file != ".."){
				$Path = "{$url}/{$file}";
				if(is_dir($Path)){
					DeleteFile($Path);
				}else{
					unlink($Path);
				}
			}
		}
		closedir($Old);
	}
	//删除当前文件夹：
	if(rmdir($url)){
		return "DeleteFile Yes";
	}else{
		return "DeleteFile No";
	}
}
?>
<?php
/*
*本页面专门用于存放会员在登录状态下的同步处理
*/
include "openFunction.php";
UserRoot();
/*******第一个判断*************************************/
if($get['type'] == "test"){
	//赋值
	$name = $post['name'];//名称
	//判断
	if(empty($name)){
		$_SESSION['warn'] = "名称不能为空";	
	}else{
		$_SESSION['warn'] = "返回成功";
	}
	
}
/*******返回到之前的页面*************************************/
header("Location:".getenv("HTTP_REFERER"));
?>
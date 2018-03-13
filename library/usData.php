<?php
/*
*本页面专门用于存放会员在登录状态下的异步步处理，PC端和移动端通用
*/
include "openFunction.php";
if($KehuFinger == 2){
    $json['warn'] = "您未登录";
/*******第一个判断*************************************/
}elseif($get['type'] == "test"){
	//赋值
	$name = $post['name'];//名称
	//判断
	if(empty($name)){
		$json['warn'] = "名称不能为空";	
	}else{
		$json['warn'] = "返回成功";
	}
	
}
/*******返回json数据*************************************/
echo json_encode($json);
?>
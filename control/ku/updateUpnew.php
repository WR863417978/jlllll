<?php

$type = $_GET['type'];
if($type == 'update'){
	$id = $_POST['id'];
	$isShow = $_POST['message'];
	$sql = "update upnew set isShow='$isShow' where newid = $id";
	$res = mysql_query($sql);
	if($res){
		$json['status'] = 2;
		$json['message'] = "更新成功";
	}else{
		$json['status'] = 1;
		$json['message'] = "更新失败";
	}
}
echo json_encode($json);
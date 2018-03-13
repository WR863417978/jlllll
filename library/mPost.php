<?php
/*
移动端公共同步处理页
*/
include "openFunction.php";

if($get['type'] == "shopImg"){

	$userId = $post['userId'];#kehuid
	$FileName = "UserShopImgUpload";//上传图片的表单文件域名称
	//$Rule['MaxSize'] = 1000000;//图像的最大容量
	// $Rule['width'] = 800;//图像要求的宽度
	// $Rule['height'] = 800;//图像要求的高度
	// $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
	// $type['name'] = "新增图像";//《更新图像》或《新增图像》
	$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
	$cut['width'] = "";//裁剪宽度
	$cut['height'] = "";//裁剪高度
	$cut['NewWidth'] = 2160;//缩放的宽度
	$cut['MaxHeight'] = 4096;//缩放后图片的最大高度
	$type['name'] = "更新图像";//"更新图像"or"新增图像"
	$type['num'] = 1;//新增图像时限定的图像总数
	$sql = "SELECT * FROM kehu WHERE khid = '$userId' ";//查询图片的数据库代码
	$column = "shopImg";//保存图片的数据库列的名称
	$Url['root'] = "../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
	$suiji = suiji();
	$Url['NewImgUrl'] = "img/shopImgs/{$suiji}.jpg";//新图片保存的网站根目录位置
	$NewImgSql = "UPDATE kehu SET {$column} = '{$Url['NewImgUrl']}' WHERE khid = '$userId' ";//保存图片的数据库代码
	$ImgWarn = "图像上传成功";//图片保存成功后返回的文字内容
	UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
}else if( $get['type'] == 'talkImg' ){
	$userId 	= $post['userId'];	#kehuid
	$goodsId 	= $post['goodsId'];	#goodsIdtalkId
	$talkId 	= $post['talkId'];	#goodsIdtalkId
	$FileName 	= "talkImgUpload";//上传图片的表单文件域名称
	fileExists('talkImg/'.date('Y-m'));
	//$Rule['MaxSize'] = 1000000;//图像的最大容量
	// $Rule['width'] = 800;//图像要求的宽度
	// $Rule['height'] = 800;//图像要求的高度
	// $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
	// $type['name'] = "新增图像";//《更新图像》或《新增图像》
	$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
	$cut['width'] = "";//裁剪宽度
	$cut['height'] = "";//裁剪高度
	$cut['NewWidth'] = 600;//缩放的宽度
	$cut['MaxHeight'] = 600;//缩放后图片的最大高度
	$type['name'] = "新增图像";//"更新图像"or"新增图像"
	$type['num'] = 5;//新增图像时限定的图像总数
	$sql = "SELECT * FROM talkImg WHERE khid = '$userId' AND talkId = '$talkId'";//查询图片的数据库代码
	$column = "img";//保存图片的数据库列的名称
	$Url['root'] = "../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
	$suiji = suiji();
	$Url['NewImgUrl'] = "talkImg/".date('Y-m')."/{$suiji}.jpg";//新图片保存的网站根目录位置
	//$NewImgSql = "INSERT talkImg SET {$column} = '{$Url['NewImgUrl']}' WHERE khid = '$userId' ";//保存图片的数据库代码
	$id = $suiji;
	$NewImgSql = "INSERT INTO `talkImg`(`id`, `talkId`, `img`, `time`) VALUES ('$id','$talkId','{$Url['NewImgUrl']}','$time')";//保存图片的数据库代码
	$ImgWarn = "图像上传成功";//图片保存成功后返回的文字内容
	UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
}
/**
 * 定制样品图片上传
 */
else if( $get['type'] == 'customMade' ){
	$khid		= $post['khid'];
	$goodsId	= $post['goodsId'];
	$skid		= $post['skid'];
	$customId	= $post['customId'];
	$FileName 	= "madeImgUpload";//上传图片的表单文件域名称
	fileExists('customImg/'.date('Y-m'));
	//$Rule['MaxSize'] = 1000000;//图像的最大容量
	// $Rule['width'] = 800;//图像要求的宽度
	// $Rule['height'] = 800;//图像要求的高度
	// $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
	// $type['name'] = "新增图像";//《更新图像》或《新增图像》
	$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
	$cut['width'] = "";//裁剪宽度
	$cut['height'] = "";//裁剪高度
	$cut['NewWidth'] = 600;//缩放的宽度
	$cut['MaxHeight'] = 600;//缩放后图片的最大高度
	$type['name'] = "新增图像";//"更新图像"or"新增图像"
	$type['num'] = 5;//新增图像时限定的图像总数
	$sql = "SELECT * FROM customMade WHERE khid = '$khid' AND goodsId = '$goodsId' AND goodsSkuId = '$skid'";//查询图片的数据库代码
	$column = "img";//保存图片的数据库列的名称
	$Url['root'] = "../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
	$suiji = suiji();
	$Url['NewImgUrl'] = "customImg/".date('Y-m')."/{$suiji}.jpg";//新图片保存的网站根目录位置
	//$NewImgSql = "INSERT talkImg SET {$column} = '{$Url['NewImgUrl']}' WHERE khid = '$userId' ";//保存图片的数据库代码
	$id = $suiji;
	$NewImgSql = "INSERT INTO `customMade`(`id`,`khid`, `goodsId`, `goodsSkuId`,`logoImg`, `updateTime`, `time`) VALUES ('$customId','$khid','$goodsId','$skid','{$Url['NewImgUrl']}','$time','$time')";//保存图片的数据库代码
	$ImgWarn = "定制图像上传成功";//图片保存成功后返回的文字内容
	UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
}
/**
 * 首页搜索
 */
else if( $get['type'] == 'indexKey' ){
	$keywords = $post['keywords'];
	$_SESSION['keywords'] = $keywords;
	if( empty($keywords) ){
		$_SESSION['index']['sql'] = '';
	}else{
		$_SESSION['index']['sql'] = " AND g.name LIKE '%{$keywords}%' ";
	}
}
/*******************返回刚才页面*******************************************/
header("Location:".getenv("HTTP_REFERER"));
?>
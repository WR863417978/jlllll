<?php
/*
系统环境公共处理同步判断页（不对外开放）
*/
include "../control/ku/configure.php";
/*******************通用文章管理-新增或更新图片*******************************************/
if($_GET['type'] == "articleImgEdit"){
	//赋值
	$Target = $post['Target'];//目标对象
	$TargetId = $post['TargetId'];//目标对象ID号
	$imgurl = $post['imgurl'];//保存图片的文件夹名称
	$maxWidth = $post['ImgMaxWidth'];//缩放的宽度
	$id = $post['artcleImgId'];//图片ID号
	//判断
	if(empty($Target)){
		$_SESSION['warn'] = "目标对象为空";
	}elseif(empty($TargetId)){
		$_SESSION['warn'] = "目标对象ID号为空";
	}else{
		$FileName = "articleImg";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = $maxWidth;//缩放的宽度
		$cut['MaxHeight'] = 2000;//缩放后图片的最大高度
		$column = "img";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/ArticleImg/{$imgurl}";//新图片保存的网站根目录位置
		//如果对应文件夹不存在，则创建文件夹
		if(!file_exists($Url['root'].$Url['NewImgUrl'])){
			mkdir($Url['root'].$Url['NewImgUrl']);
		}
		$Url['NewImgUrl'] .= "/{$suiji}.jpg";
		if(empty($id)){
			$type['name'] = "新增图像";//"更新图像"or"新增图像"
			$type['num'] = 100;//新增图像时限定的图像总数
			$sql = "select * from article where TargetId = '$TargetId' and word = '' order by list desc";//查询图片的数据库代码
			$ArticleList = query("article"," TargetId = '$TargetId' order by list desc");
			$ThisList = $ArticleList['list']+1;
			//保存图片的数据库代码
			$NewImgSql = "insert into article (id,Target,TargetId,img,list,UpdateTime,time) 
			values ('$suiji','$Target','$TargetId','$Url[NewImgUrl]','$ThisList','$time','$time')";
			$Anchor = "#{$suiji}";
			$ImgWarn = "图片添加成功";//图片保存成功后返回的文字内容
		}else{
			$type['name'] = "更新图像";
			$type['num'] = "";//新增图像时限定的图像总数
			$sql = " select * from article where id = '$id' ";
			$NewImgSql = "update article set img = '$Url[NewImgUrl]',UpdateTime = '$time' where id = '$id'";//保存图片的数据库代码
			$Anchor = "#{$id}";
			$ImgWarn = "图像修改成功";//图片保存成功后返回的文字内容
		}
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
		header("Location:".getenv("HTTP_REFERER").$Anchor);
		exit(0);
	}
/*******************通用文章管理-删除段落*******************************************/
}elseif(isset($_GET['articleDelete'])){
	//赋值
	$id = $_GET['articleDelete'];
	$article = query("article"," id = '$id' ");
	//判断
	if(empty($id)){
		$_SESSION['warn'] = "段落ID号为空";
	}elseif($article['id'] != $id){
		$_SESSION['warn'] = "未找到此段落";
	}else{
		$ArticleList = $article['list'] - 1;
		$LastArticle = query("article"," TargetId = '$article[TargetId]' and list = '$ArticleList' ");
		//删除图片
		unlink(ServerRoot.$article['img']);
		//删除数据库记录
		$bool = mysql_query(" delete from article where id = '$id' ");
		//返回信息
		$_SESSION['warn'] = "本段内容删除成功";
		//跳转
		$Anchor = "#{$LastArticle['id']}";
		header("Location:".getenv("HTTP_REFERER").$Anchor);
		exit(0);
	}
}
/*******************返回刚才页面*******************************************/
header("Location:".getenv("HTTP_REFERER"));
?>
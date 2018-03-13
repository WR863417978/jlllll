<?php
include "../ku/adfunction.php";
ControlRoot("adContent");
$ThisUrl = root."control/info/AdContentMx.php";
if(empty($_GET['id'])){
	$title = "新建文章";
}else{
	//修正当前页面Url
	$ThisUrl .= "?id=".$_GET['id'];
	//查询本文章明细
	$Content = query("content"," id = '$_GET[id]' ");
	if($Content['id'] != $_GET['id']){
		$_SESSION['warn'] = "您查找的文章不存在";
		header("Location:{$root}control/info/AdContent.php"); 	
		exit(0);
	}
	$EditIco = "&nbsp;<span onclick='document.ContentListIcoForm.UploadContentListIco.click();' class='spanButton'>更新</span>";
	$title = $Content['title'];
	$article = article("普通文章管理",$Content['id'],"content",1200);
}
$onion = array(
    "信息管理" => root."control/info/info.php",
	"普通文章管理" => root."control/info/adContent.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--参数编辑开始-->
<div class="kuang">
<p>
    <img src="<?php echo root."img/images/edit.png";?>">
    文章基本参数
</p>
<form name="ConentForm">
<table class="tableRight">
    <tr>
        <td>文章ID号：</td>
        <td><?php echo kong($Content['id']);?></td>
    </tr>
    <tr>
        <td>列表图像：</td>
        <td><?php echo ProveImgShow($Content['ico']).$EditIco;?></td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;一级分类：</td>
        <td>
        <?php echo RepeatSelect("content","type","type","select","--一级分类--",$Content['type']);?>
        <input name="TypeText" type="text" class="text" value="<?php echo $Content['type'];?>">
        </td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;二级分类：</td>
        <td>
        <select name="classify" class="select">
        <?php echo RepeatOption(" content where type = '$Content[type]' ","classify","--二级分类--",$Content['classify']);?>
        </select>
        <input name="ClassifyText" type="text" class="text" value="<?php echo $Content['classify'];?>">
        </td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;文章标题：</td>
        <td><input name="adContentTitle" type="text" class="text" value="<?php echo $Content['title'];?>"></td>
    </tr>
    <tr>
        <td>副标题：</td>
        <td><input name="subTitle" type="text" class="text" value="<?php echo $Content['subTitle'];?>"></td>
    </tr>
    <tr>
        <td>外部链接：</td>
        <td><input name="outUrl" type="text" class="text" value="<?php echo $Content['outUrl'];?>"></td>
    </tr>
    <tr>
        <td>文章摘要：</td>
        <td><textarea name="summary" class="textarea"><?php echo $Content['summary'];?></textarea></td>
    </tr>
    <tr>
        <td>排序号：</td>
        <td><input name="ContentList" class="text textPrice" value="<?php echo $Content['list'];?>">&nbsp;注：排序号必须为正整数</td>
    </tr>
    <tr>
        <td>点击量：</td>
        <td><?php echo kong($Content['clickRate']);?></td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;前端状态：</td>
        <td><?php echo radio("ContentShow",array("显示","隐藏"),$Content['xian']);?></td>
    </tr>
    <tr>
        <td>更新时间：</td>
        <td><?php echo kong($Content['updateTime']);?></td>
    </tr>
    <tr>
        <td>创建时间：</td>
        <td><?php echo kong($Content['time']);?></td>
    </tr>
    <tr>
        <td><input name="ContentId" type="hidden" value="<?php echo $Content['id'];?>"></td>
        <td><input onclick="Sub('ConentForm',root+'control/ku/data.php?type=adEditContent')" type="button" class="button" value="提交信息"></td>
    </tr>
</table>
</form>
</div>
<!--参数编辑结束-->
<?php echo $article;?>
<!--文件上传隐藏域开始-->
<div class="hide">
<form name="ContentListIcoForm" action="<?php echo root."control/ku/post.php?type=adEditContentIco";?>" method="post" enctype="multipart/form-data">
<input name="UploadContentListIco" type="file" onchange="document.ContentListIcoForm.submit();">
<input name="ContentId" type="hidden" value="<?php echo $Content['id'];?>">
</form>
</div>
<!--文件上传隐藏域结束-->
<script>
$(document).ready(function(){
	var form = document.ConentForm;
	//根据文章一级分类调出二级分类
	form.type.onchange = function(){
		form.TypeText.value = this.value;
		form.ClassifyText.value = "";
		$.post(root + "control/ku/data.php", {adContentTypeGetClassify:this.value},function(data){
			form.classify.innerHTML = data.classify;
		},"json");
	}
	//将二级分类显示在text中
	form.classify.onchange = function(){
		form.ClassifyText.value = this.value;
	}
});
</script>
<?php echo warn().adfooter();?>
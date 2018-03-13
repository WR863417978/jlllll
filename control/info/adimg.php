<?php 
include "../ku/adfunction.php";
ControlRoot("adimg");
$ThisUrl = root."control/info/adimg.php";
$sql = "select * from img ".$_SESSION['adImg']['Sql'];
paging($sql," order by list ",100);
$onion = array(
    "信息管理" => root."control/info/info.php",
	"网站图片管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--查询开始-->
<div class="search">
    <form name="search" action="<?php echo root."control/ku/post.php?type=adSearchImg";?>" method="post">
    图片ID号：<input name="id" type="text" class="text textPrice" value="<?php echo $_SESSION['adImg']['id'];?>">
    <?php echo RepeatSelect("img","type","type","select","--选择分类--",$_SESSION['adImg']['type']);?>
    图片名称：<input name="name" type="text" class="text textPrice" value="<?php echo $_SESSION['adImg']['name'];?>">
    相对路径：<input name="src" type="text" class="text textPrice" value="<?php echo $_SESSION['adImg']['src'];?>">
    <?php echo select("del","select textPrice","--可删除--",array("是","否"),$_SESSION['adImg']['del']);?>
    <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <span onclick="$('[name=ImgForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
    <span onclick="$('[name=ImgForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
    <span ParameterId="" class="spanButton">新增图像</span>
    <span onclick="EditList('ImgForm','deleteImg')" class="spanButton">删除所选</span>
    <span class="pageTop">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<!--查询结束-->
<!--图片列表开始-->
<form name="ImgForm">
<table class="tableMany">
    <tr>
        <td></td>
        <td>ID号</td>
        <td>分类</td>
        <td>名称</td>
        <td>格式</td>
        <td>限宽</td>
        <td>限高</td>
        <td>体积</td>
        <td>排序</td>
        <td>可删除</td>
        <td>更新时间</td>
        <td>预览</td>
        <td style="width:42px;"></td>
    </tr>
    <?php
    if($num > 0){
        while($img = mysql_fetch_array($query)){
            $size = $img['maxSize']/1000;
            if(empty($img['src'])){
                $imgsrc = "未设置";
            }else{
                $imgsrc = "<div class='adimgDiv'><img class='adimgList' src='{$root}{$img['src']}'></div>";
            }
            echo "
            <tr>
                <td><input name='ImgList[]' type='checkbox' value='{$img['id']}'/></td>
                <td>{$img['id']}</td>
                <td title='{$img['type']}'>".zishu($img['type'],20)."</td>
                <td title='{$img['name']}'>".zishu($img['name'],20)."</td>
                <td>{$img['geshi']}</td>
                <td>{$img['width']}PX</td>
                <td>{$img['height']}PX</td>
                <td>{$size}KB</td>
                <td>{$img['list']}</td>
                <td>{$img['del']}</td>
                <td>{$img['updateTime']}</td>
                <td><a title='点击查看大图' target='_blank' href='{$root}{$img['src']}'>{$imgsrc}</a></td>
                <td>
                    <span ParameterId='{$img['id']}' class='spanButton'>参数</span>
                    <div style='padding:2px;'></div>
                    <span EditImgId='{$img['id']}' class='spanButton'>更新</span>
                </td>
            </tr>
            ";
        } 
    }else{
        echo "<tr><td colspan='13'>一张图片都没有</td></tr>";
    }
    ?>
</table>
</form>
<?php echo fenye($ThisUrl,7,"");?>
<!--图片列表结束-->
<!--修改图片参数弹出层开始-->
<div class="hide" id="adimgEdit">
    <div class="dibian"></div>
    <div class="win" style=" height:494px; width:600px; margin: -257px 0px 0px -300px;">
        <p class="winTitle">编辑图片参数<span onclick="$('#adimgEdit').hide()" class="winClose">×</span></p>
        <form name="ParameterForm">
        <table class="tableRight">
            <tr>
                <td>图片ID号：</td>
                <td id="ImgId"></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;划分类别：</td>
                <td>
                <?php echo RepeatSelect("img","type","ImgType","select","--选择已有分类--");?>
                <input name="AdImgTypeText" type="text" class="text short">
                </td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;图片名称：</td>
                <td><input name="AdImgName" type="text" class="text" /></td>
            </tr>
            <tr>
                <td style="width:100px;">链接地址：</td>
                <td><input name="AdImgUrl" type="text" class="text" /></td>
            </tr>
            <tr>
                <td>详细备注：</td>
                <td><input name="AdImgText" type="text" class="text" /></td>
            </tr>
            <tr>
                <td>排序号：</td>
                <td><input name="ImgList" type="text" class="text textPrice"></td>
            </tr>
            <tr>
                <td></td>
                <td class="red smallWord">如下参数必须符合页面设计要求，修改后请及时联系前端设计师</td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;图像格式：</td>
                <td><?php echo select("adImgFormat","select","--选择--",array("JPEG","PNG"),"");?></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;图片宽度：</td>
                <td><input name="adImgWidth" type="text" class="text textPrice">&nbsp;像素</td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;图片高度：</td>
                <td><input name="adImgHeight" type="text" class="text textPrice">&nbsp;像素</td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;最大体积：</td>
                <td><input name="adImgMaxSize" type="text" class="text textPrice">&nbsp;KB</td>
            </tr>
            <tr>
                <td><input name="ImgId" type="hidden"></td>
                <td><input onclick="Sub('ParameterForm','<?php echo root."control/ku/data.php?type=adEditImgParameter";?>')" type="button" class="button" value="确认提交"></td>
            </tr>
        </table>
        </form>
    </div>
</div>
<!--修改图片参数弹出层结束-->
<!--隐藏域开始-->
<div class="hide">
<form name="AdimgForm" action="<?php echo root."control/ku/post.php?type=adEditImg";?>" method="post" enctype="multipart/form-data">
<input name="UploadImg" type="file" onchange="document.AdimgForm.submit();">
<input name="ImgId" type="hidden" value="<?php echo $goods['id'];?>">
</form>
</div>
<!--隐藏域结束-->
<script>
$(document).ready(function(){
	//更新图片
	$("[EditImgId]").click(function(){
	    document.AdimgForm.ImgId.value = $(this).attr("EditImgId");
		document.AdimgForm.UploadImg.click();
	});
	//弹出图片参数层
	$("[ParameterId]").click(function(){
	    $("#adimgEdit").fadeIn();
		$.post(root+"control/ku/data.php?type=adGetImgParameter",{id:$(this).attr("ParameterId")},function(data){
			document.ParameterForm.ImgType.value = data.type;
			document.ParameterForm.AdImgTypeText.value = data.type;
			document.ParameterForm.AdImgName.value = data.name;
			document.ParameterForm.AdImgUrl.value = data.url;
			document.ParameterForm.AdImgText.value = data.text;
			document.ParameterForm.ImgList.value = data.list;
			document.ParameterForm.adImgFormat.value = data.geshi;
			document.ParameterForm.adImgWidth.value = data.width;
			document.ParameterForm.adImgHeight.value = data.height;
			document.ParameterForm.adImgMaxSize.value = data.maxSize;
			document.ParameterForm.ImgId.value = data.id;
			if(data.id == ""){
				$("#ImgId").html("未设置");
			}else{
				$("#ImgId").html(data.id);
			}
		},"json");
	});
	//将商品分类显示在text中
	document.ParameterForm.ImgType.onchange = function(){
		document.ParameterForm.AdImgTypeText.value = this.value;
	}
});
</script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
<?php
include "../ku/adfunction.php";
ControlRoot();
$ThisUrl = root."control/info/AdContentMx.php";
$onion = array(
    "首页管理" => "",
	"专题管理" => root."control/index/special.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="kuang">
<p>
    <img src="<?php echo root."img/images/edit.png";?>">
    新增专题
</p>
<form name="ConentForm">
<table class="tableRight">
    <tr>
        <td>专题名称：</td>
        <td><input name="specialName" type="text" class="text"></td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;前端状态：</td>
        <td><?php echo radio("isShow",array("显示","隐藏"),'显示');?></td>
    </tr>
    <tr>
        <td><input onclick="Sub('ConentForm','<?php echo root;?>control/ku/data.php?type=adspecial')" type="button" class="button" value="提交信息"></td>
    </tr>
</table>
</form>
</div>
<!--参数编辑结束-->
<?php echo $article;?>
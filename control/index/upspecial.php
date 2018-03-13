<?php
include "../ku/adfunction.php";
ControlRoot();
$ThisUrl = root."control/info/AdContentMx.php";
$onion = array(
    "首页管理" => root."control/info/info.php",
	"专题管理" => root."control/info/adContent.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
$id= $_GET['id'];
$sql = "select * from special where spid = $id";
$query = mysql_query($sql);
$data = mysql_fetch_row($query);
?>
<div class="kuang">
<p>
    <img src="<?php echo root."img/images/edit.png";?>">
    更改专题数据
</p>
<form name="ConentForm">
<table class="tableRight">
    <tr>
        <td>专题ID号：</td>
        <td><?php echo $data[0];?></td>
    </tr>
    <input type="hidden" name="id" value="<?php echo $data[0];?>">
    <tr>
        <td>专题标题：</td>
        <td><input name="specialName" type="text" class="text" value="<?php echo $data[1];?>"></td>
    </tr>
    <tr>
        <td>显示条数：</td>
        <td><input name="showPage" type="text" class="text" value="<?php echo $data[5];?>"></td>
    </tr>
    <tr>
        <td>创建时间：</td>
        <td><input name="" type="text" class="text" value="<?php echo $data[3];?>" disabled="disabled"></td>
    </tr>
    <tr>
        <td>更新时间：</td>
        <td><input name="" type="text" class="text" value="<?php echo $data[4];?>" disabled="disabled"></td>
    </tr>
    <tr>
        <td><span class="red">*</span>&nbsp;前端状态：</td>
        <td><?php echo radio("isShow",array("显示","隐藏"),$data[2]);?></td>
    </tr>
    <tr>
        <td><input onclick="Sub('ConentForm','<?php echo root;?>control/ku/data.php?type=upspecial')" type="button" class="button" value="提交信息"></td>
    </tr>
</table>
</form>
</div>
<!--参数编辑结束-->
<?php echo $article;?>
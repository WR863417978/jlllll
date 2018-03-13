<?php
include "../ku/adfunction.php";
ControlRoot("adContent");
$ThisUrl = root."control/info/adContent.php";
$sql = "select * from content ".$_SESSION['adContent']['Sql'];
paging($sql," order by list desc",100);
$onion = array(
    "信息管理" => root."control/info/info.php",
	"普通文章管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--查询开始-->
<div class="search">
    <form name="Search" action="<?php echo root."control/ku/post.php?type=adSearchContent";?>" method="post">
    <?php echo RepeatSelect("content","type","adContentType","select","--一级分类--",$_SESSION['adContent']['type']);?>
    <select name="classify" class="select">
    <?php echo RepeatOption(" content where type = '{$_SESSION['adContent']['type']}' ","classify","--二级分类--",$_SESSION['adContent']['classify']);?>
    </select>
    标题：<input name="adContentTitle" type="text" class="text short" value="<?php echo $_SESSION['adContent']['title'];?>">
    <?php echo select("adContentShow","select textPrice","--状态--",array("显示","隐藏"),$_SESSION['adContent']['xian']);?>
    <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <span onclick="$('[name=AdContentForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
    <span onclick="$('[name=AdContentForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
    <a href="<?php echo root."control/info/adContentMx.php";?>"><span class="spanButton">新增文章</span></a>
    <span class="spanButton" onclick="EditList('AdContentForm','deleteArticle')" >删除所选</span>
    <span class="smallWord floatRight">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<!--查询结束-->
<!--列表开始-->
<form name="AdContentForm">
<table class="tableMany">
    <tr>
        <td></td>
        <td>一级分类</td>
        <td>二级分类</td>
        <td>标题</td>
        <td>状态</td>
        <td>排序</td>
        <td>点击量</td>
        <td>创建时间</td>
        <td style="width:42px;"></td>
    </tr>
    <?php
    if($num == 0){
        echo "<tr><td colspan='9'>没有任何信息</td></tr>";
    }else{
        while($array = mysql_fetch_array($query)){
        echo "
        <tr>
            <td><input name='AdContentList[]' type='checkbox' value='{$array['id']}'/></td>
            <td>{$array['type']}</td>
            <td>{$array['classify']}</td>
            <td>{$array['title']}</td>
            <td>{$array['xian']}</td>
            <td>{$array['list']}</td>
            <td>{$array['clickRate']}</td>
            <td>{$array['time']}</td>
            <td><a href='{$root}control/info/adContentMx.php?id={$array['id']}'><span class='spanButton'>详细</span></a></td>
        </tr>
        ";
        } 
    }
    ?>
</table>
</form>
<?php echo fenye($ThisUrl,7);?>
<!--列表结束-->
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
<script>
$(document).ready(function(){
	//根据内容一级分类查询二级分类
	document.Search.adContentType.onchange = function(){
		$.post(root+"control/ku/data.php",{adContentTypeGetClassify:this.value},function(data){
			$("[name=Search] [name=classify]").html(data.classify);
		},"json");
	}
});
</script>
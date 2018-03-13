<?php
include "../ku/adfunction.php";
ControlRoot("admin");
$ThisUrl = root."control/Internal/admin.php";
$sql="select * from admin ".$_SESSION['Admin']['Sql'];
paging($sql," order by UpdateTime desc",100);
$onion = array(
    "内部管理" => root."control/Internal/adInternal.php",
	"员工管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--查询开始-->
<div class="search">
    <form name="Search" action="<?php echo root."control/ku/post.php?type=adSearchAdmin";?>" method="post">
    <?php echo RepeatSelect("adDuty","department","adDutyDepartment","select textPrice","--部门--",$_SESSION['Admin']['department']);?>
    <select name="adDutyId" class="select textPrice">
    <?php echo IdOption("adDuty where department = '{$_SESSION['Admin']['department']}' and xian = '开启' ","id","name","--职位--",$_SESSION['Admin']['DutyId']);?>
    </select>
    姓名：<input name="adName" type="text" class="text textPrice" value="<?php echo $_SESSION['Admin']['name'];?>">
    <?php echo select("adSex","select textPrice","--性别--",array("男","女"),$_SESSION['Admin']['sex']);?>
    手机号码：<input name="adTel" type="text" class="text textPrice" value="<?php echo $_SESSION['Admin']['tel'];?>">
    电子邮箱：<input name="adEmail" type="text" class="text textPrice" value="<?php echo $_SESSION['Admin']['email'];?>">
    QQ：<input name="adQQ" type="text" class="text textPrice" value="<?php echo $_SESSION['Admin']['qq'];?>">
    <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <a href="<?php echo root."control/Internal/adminDuty.php";?>"><span class="spanButton">职位管理</span></a>
    <span onclick="$('[name=AdminForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
    <span onclick="$('[name=AdminForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
    <a href="<?php echo root."control/Internal/adminMx.php";?>"><span class="spanButton">新建员工</span></a>
    <span class="spanButton" onclick="EditList('AdminForm','deleteAdmin')" >删除所选</span>
    <span class="smallWord floatRight">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<!--查询结束-->
<!--列表开始-->
<form name="AdminForm">
<table class="tableMany">
    <tr>
        <td></td>
        <td>所属部门</td>
        <td>当前职位</td>
        <td>姓名</td>
        <td>性别</td>
        <td>状态</td>
        <td>手机号码</td>
        <td>邮箱</td>
        <td>QQ</td>
        <td>账户余额</td>
        <td>更新时间</td>
        <td></td>
    </tr>
    <?php
    if($num > 0){
        while($admin = mysql_fetch_array($query)){
            $duty = query("adDuty"," id = '$admin[duty]' ");
            echo "
            <tr>
                <td><input name='AdminList[]' type='checkbox' value='{$admin['adid']}'/></td>
                <td>".kong($duty['department'])."</td>
                <td>".kong($duty['name'])."</td>
                <td>{$admin['adname']}</td>
                <td>{$admin['sex']}</td>
                <td>{$admin['state']}</td>
                <td>{$admin['adtel']}</td>
                <td>{$admin['ademail']}</td>
                <td>".kong($admin['adqq'])."</td>
                <td>{$admin['money']}</td>
                <td>{$admin['updateTime']}</td>
                <td><a href='{$root}control/Internal/adminMx.php?id={$admin['adid']}'><span class='spanButton'>详细</span></a></td>
            </tr>
            ";
        } 
    }else{
        echo "<tr><td colspan='11'>一个员工都没有</td></tr>";
    }
    ?>
</table>
</form>
<?php echo fenye($ThisUrl,7);?>
<!--列表结束-->
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
<script>
$(function(){
	//根据部门异步加载职位
	var searchForm = document.Search;
	searchForm.adDutyDepartment.onchange = function(){
	    $.post(root + "control/ku/data.php",{adDutyDepartmentGetName:this.value},function(data){
		    searchForm.adDutyId.innerHTML = data.DutyId;
		},"json");
	}
});
</script>
<?php
include "../ku/adfunction.php";
ControlRoot("admin");
$ThisUrl = root."control/Internal/adminDuty.php";
$sql="select * from adDuty ".$_SESSION['adDuty']['Sql'];
paging($sql," order by list desc",100);
$onion = array(
    "内部管理" => root."control/Internal/adInternal.php",
	"员工管理" => root."control/Internal/admin.php",
	"职位管理" => $ThisUrl
);
$powerSearch = array(
	"adimg" => "网站图片管理",
	"adword" => "网站文字管理",
	"adContent" => "普通文章管理",
	"admin" => "员工管理",
	"adSystem" => "管理制度",
	"adParameter" => "参数管理",
	"adAccount" => "账户管理"
);
if(empty($_SESSION['adDuty']['power'])){
	$TdNum = 11;
}else{
	$powerName = $_SESSION['adDuty']['power'];
	$TdNum = 12;
	$powerTitle = "<td>{$powerName}</td>";
}
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
	<!--查询开始-->
	<div class="search">
		<form name="Search" action="<?php echo root."control/ku/post.php?type=adSearchDuty";?>" method="post">
		<?php echo RepeatSelect("adDuty","department","adDutyDepartment","select","--所属部门--",$_SESSION['adDuty']['department']);?>
		职位名称：<input name="adDutyName" type="text" class="text textPrice" value="<?php echo $_SESSION['adDuty']['name'];?>">
		<?php 
		echo 
		select("adDutyShow","select textPrice","--状态--",array("开启","关闭"),$_SESSION['adDuty']['xian']).
		select("power","select","--权限--",$powerSearch,$_SESSION['adDuty']['power']).
		select("edit","select textPrice","--可编辑--",array("是","否"),$_SESSION['adDuty']['edit']).
		select("del","select textPrice","--可删除--",array("是","否"),$_SESSION['adDuty']['del']);
		?>
		<input type="submit" value="模糊查询">
		</form>
	</div>
	<div class="search">
		<span onclick="$('[name=DutyForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
		<span onclick="$('[name=DutyForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
		<a href="<?php echo root."control/Internal/adminDutyMx.php";?>"><span class="spanButton">新建职位</span></a>
        <span class="spanButton" onclick="EditList('DutyForm','deleteDuty')" >删除所选</span>
		<span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
	</div>
	<!--查询结束-->
	<!--列表开始-->
	<form name="DutyForm">
	<table class="tableMany">
		<tr>
			<td></td>
			<td>所属部门</td>
			<td>职位名称</td>
            <td>基本工资</td>
			<td>状态</td>
			<td>排序</td>
            <?php echo $powerTitle;?>
			<td>可编辑</td>
            <td>可删除</td>
            <td>更新时间</td>
			<td></td>
		</tr>
		<?php
		if($num > 0){
			while($duty = mysql_fetch_array($query)){
				if(!empty($powerTitle)){
					$power = json_decode($duty['power'],true);
					$powerTd = "<td>".implode(",",$power[$powerName])."</td>";
				}
				echo "
				<tr>
					<td><input name='DutyList[]' type='checkbox' value='{$duty['id']}'/></td>
					<td>{$duty['department']}</td>
					<td>{$duty['name']}</td>
					<td>{$duty['basePay']}</td>
					<td>{$duty['xian']}</td>
					<td>{$duty['list']}</td>
					{$powerTd}
					<td>{$duty['edit']}</td>
					<td>{$duty['del']}</td>
					<td>{$duty['updateTime']}</td>
				    <td><a href='".root."control/Internal/adminDutyMx.php?id={$duty['id']}'><span class='spanButton'>详细</span></a></td>
				</tr>
				";
			} 
		}else{
			echo "<tr><td colspan='{$TdNum}'>一个职位都没有</td></tr>";
		}
		?>
	</table>
	</form>
	<?php echo fenye($ThisUrl,7);?>
	<!--列表结束-->
</div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
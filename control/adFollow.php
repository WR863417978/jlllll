<?php
include "ku/adfunction.php";
ControlRoot("adClient");
$ThisUrl = root."control/adFollow.php";
$sql="select * from follow ".$_SESSION['adFollow']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
	"跟进记录" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
	<div class="search">
		<form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchFollow";?>" method="post">
			<?php 
			echo 
			select("target","select","--类型--",array("客户","订单"),$_SESSION['adFollow']['target']).
			IDSelect("admin","adid","select","adid","adname","--跟进员工--",$_SESSION['adFollow']['adid']);
			?>
			对象ID：<input name="targetId" type="text" class="text textPrice" value="<?php echo $_SESSION['adFollow']['targetId'];?>">
            跟进内容：<input name="text" type="text" class="text textPrice" value="<?php echo $_SESSION['adFollow']['text'];?>">
			<input type="submit" value="模糊查询">
		</form>
	</div>
	<div class="search">
		<span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
	</div>
	<!--查询结束-->
	<!--列表开始-->
	<form name="ClientForm">
	<table class="tableMany">
		<tr>
			<td style=" min-width:62px;">对象</td>
            <td style=" min-width:62px;">对象ID</td>
			<td style=" min-width:62px;">员工</td>
            <td>跟进内容</td>
			<td style=" min-width:78px;">创建时间</td>
            <td></td>
		</tr>
		<?php
		if($num > 0){
			while($array = mysql_fetch_array($query)){
				$admin = query("admin"," adid = '$array[adid]' ");
				if($array['target'] == "客户"){
					$url = "<a href='{$root}control/adClientMx.php?id={$array['targetId']}'><span class='spanButton'>详情</span></a>";
				}elseif($array['target'] == "订单"){
					$url = "<a href='{$root}control/adOrderMx.php?id={$array['targetId']}'><span class='spanButton'>详情</span></a>";
				}else{
					$url = "";	
				}
				echo "
				<tr>
					<td>{$array['target']}</td>
					<td>{$array['targetId']}</td>
					<td>{$admin['adname']}</td>
					<td>{$array['text']}</td>
					<td>{$array['time']}</td>
					<td>{$url}</td>
				</tr>
				";
			}
		}else{
			echo "<tr><td colspan='6'>一条跟进都没有</td></tr>";
		}
		?>
	</table>
	</form>
	<?php echo fenye($ThisUrl,7);?>
	<!--列表结束-->
</div>
<?php echo warn().adfooter();?>
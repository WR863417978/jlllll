<?php
include "../ku/adfunction.php";
ControlRoot("admin");
$ThisUrl = root."control/Internal/adminDutyMx.php";
if(empty($get['id'])){
	$title = "新建职位";
}else{
	$id = $get['id'];
	$ThisUrl .= "?id=".$id;
	$duty = query("adDuty"," id = '$id' ");
	$power = json_decode($duty['power'],true);
	if($duty['id'] != $id){
		$_SESSION['warn'] = "未找到此职位";
		header("Location:{$adroot}info/adminDuty.php"); 	
		exit(0);
	}
	$title = $duty['name'];
}
$powerHtml = "";
foreach($powerAll as $type => $module){
	$box = "";
	foreach($module as $key => $value){
		$name = $value['name'];
		unset($value['name']);
		$box .= "<p>{$name}：".checkbox("power[{$key}]",$value,$power[$key])."</p>";
	}
	$powerHtml .= "
	<div class='box'>
		<p>{$type}</p>
		{$box}
	</div>
	";
}
$onion = array(
    "内部管理" => root."control/Internal/adInternal.php",
	"员工管理" => root."control/Internal/admin.php",
	"职位管理" => root."control/Internal/adminDuty.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
	<!--参数编辑开始-->
	<div class="kuang">
		<p>
		    <img src="<?php echo root."img/images/text.png";?>">
			职位基本信息
		</p>
		<form name="DutyForm">
		<table class="tableRight">
			<tr>
			    <td width="100">职位ID：</td>
				<td><?php echo kong($duty['id']);?></td>
			</tr>
			<tr>
				<td><span class="red">*</span>&nbsp;所属部门：</td>
				<td>
					<?php echo RepeatSelect("adDuty","department","department","select","--选择--",$duty['department']);?>
					<input name="DepartmentText" type="text" class="text" value="<?php echo $duty['department'];?>">
				</td>
			</tr>
			<tr>
				<td><span class="red">*</span>&nbsp;职位名称：</td>
				<td><input name="DutyName" type="text" class="text" value="<?php echo $duty['name'];?>"></td>
			</tr>
            <tr>
                <td>基本工资：</td>
                <td><input name="BasePay" type="text" class="text textPrice" value="<?php echo $duty['basePay'];?>"></td>
            </tr>
			<tr>
				<td><span class="red">*</span>&nbsp;职位描述：</td>
				<td><textarea name="DutyText" class="textarea"><?php echo $duty['text'];?></textarea></td>
			</tr>
			<tr>
			    <td><span class="red">*</span>&nbsp;状态：</td>
				<td><?php echo radio("DutyShow",array("开启","关闭"),$duty['xian']);?></td>
			</tr>
			<tr>
			    <td></td>
				<td class="smallWord green">注：一旦关闭本职位，当前拥有该职位的管理员将设为无职位状态，且无法给任何管理员赋予本职位。</td>
			</tr>
			<tr>
			    <td><span class="red">*</span>&nbsp;排序号：</td>
				<td><input name="DutyList" type="text" class="text textPrice" value="<?php echo $duty['list'];?>"></td>
			</tr>
			<tr>
				<td>管辖范围：</td>
				<td><?php echo $powerHtml;?></td>
			</tr>
			<tr>
			    <td>可编辑：</td>
				<td><?php echo kong($duty['edit']);?></td>
			</tr>
			<tr>
			    <td>可删除：</td>
				<td><?php echo kong($duty['del']);?></td>
			</tr>
            <tr>
			    <td>更新时间：</td>
				<td><?php echo kong($duty['updateTime']);?></td>
			</tr>
			<tr>
			    <td>创建时间：</td>
				<td><?php echo kong($duty['time']);?></td>
			</tr>
			<tr>
			    <td><input name="DutyId" type="hidden" value="<?php echo $duty['id'];?>"></td>
				<td><input type="button" class="button" onclick="Sub('DutyForm','<?php echo root;?>control/ku/data.php?type=adDutyEdit')" value="提交"></td>
			</tr>
		</table>
		</form>
	</div>
	<!--参数编辑结束-->
</div>
<?php echo warn().adfooter();?>
<script>
$(document).ready(function(){
	//将下拉菜单中的职位附到text中
	document.DutyForm.department.onchange = function(){
	    document.DutyForm.DepartmentText.value = this.value;
	}
});
</script>
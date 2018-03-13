<?php
include "../ku/adfunction.php";
ControlRoot("adParameter");
$sql="select * from para ".$_SESSION['adPara']['Sql'];
paging($sql," order by list ",100);
$tr = "";
if($num == 0){
	$tr .= "<tr><td colspan='7'>一条记录都没有</td></tr>";
}else{
	while($array = mysql_fetch_array($query)){
		$tr .= "
		<tr>
			<td><input name='ParameterList[]' type='checkbox' value='{$array['paid']}'/></td>
			<td>{$array['paid']}</td>
			<td>{$array['paName']}</td>
			<td>{$array['paValue']}</td>
			<td>{$array['list']}</td>
			<td>{$array['updateTime']}</td>
			<td><span class='spanButton' edit='{$array['paid']}'>编辑</span></td>
		</tr>
		";
	}
}
$onion = array(
    "财务管理" => root."control/finance/adFinancial.php",
	"参数管理" => root."control/finance/adParameter.php"
);
echo head("ad").adheader($onion);
?>
<div class="search">
    <span class="pageTop">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<form name="ParameterForm">
<table class="tableMany">
    <tr>
        <td></td>
        <td>参数ID号</td>
        <td>参数名称</td>
        <td>参数值</td>
        <td>排序号</td>
        <td>更新时间</td>
        <td></td>
    </tr>
    <?php echo $tr;?>
</table>
</form>
<?php echo fenye($ThisUrl,7);?>
<!--修改参数弹出层开始-->
<div class="hide" id="parameterEdit">
	<div class="dibian"></div>
	<div class="win" style="height:324px; width:800px; margin:-162px 0 0 -400px;">
	  <p class="winTitle">编辑核心参数<span onclick="$('#parameterEdit').hide();" class="winClose">×</span></p>
	  <form name="parameterEditForm">
	  <table class="tableRight">
		  <tr>
			  <td style="width:100px;">温馨提示：</td>
			  <td>修改核心参数可能极大的改变网站的计算逻辑，一旦修改成功将立即生效，请慎重考虑</td>
		  </tr>
		  <tr>
			  <td>参数名称：</td>
			  <td><input name="name" type="text" class="text"></td>
		  </tr>
		  <tr>
			  <td>参数值：</td>
			  <td><textarea name="text" class="textarea"></textarea></td>
		  </tr>
		  <tr>
			  <td>登录密码：</td>
			  <td><input name="pas" type="password" class="text textPrice"></td>
		  </tr>
		  <tr>
			  <td><input name="id" type="hidden"></td>
			  <td><input type="button" class="button" value="确认修改" onclick="Sub('parameterEditForm',root+'control/ku/data.php?type=parameterEdit')"></td>
		  </tr>
	  </table>
	  </form>
	</div>
</div>
<!--修改参数弹出层结束-->
<script>
$(document).ready(function(){
    //根据参数ID获取参数信息
	var editForm = document.parameterEditForm;
	$("[edit]").click(function(){
	    $("#parameterEdit").fadeIn();
		$.post(root + "control/ku/data.php?type=parameterShow",{id:$(this).attr("edit")},function(data){
		    editForm.name.value = data.name;
			editForm.text.value = data.text;
			editForm.id.value = data.id;
		},"json");	
	});
});
</script>
<?php echo warn().adfooter();?>
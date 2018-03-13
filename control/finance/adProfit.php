<?php
include "../ku/adfunction.php";
ControlRoot("adProfit");
$ThisUrl = root."control/finance/adProfit.php";
if(power("adProfit","seeAll")){
	$where = "";
}else{
	$where = " and adid = '$Control[adid]' ";
}
$sql="select * from profit where 1=1 ".$where.$_SESSION['adProfit']['Sql'];
//合计金额
$AllMoney = 0;
$AllMoneySql = mysql_query($sql);
while($array = mysql_fetch_array($AllMoneySql)){
	if($array['direction'] == "收入"){
		$AllMoney += $array['money'];
	}else{
		$AllMoney -= $array['money'];
	}
}
if(power("adProfit","del")){
	$ProfitDelete = "<span onclick=\"EditList('ProfitForm','ProfitDelete')\" class='spanButton'>删除所选</span>";
}
paging($sql," order by payDate desc,time desc ",100);
$onion = array(
	"财务管理" => root."control/finance/adFinancial.php",
	"收支平衡" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="search">
    <form name="Search" action="<?php echo root."control/ku/post.php?type=searchProfit";?>" method="post">
        <?php 
        echo 
        select("adProfitDirection","select textPrice","--方向--",array("收入","支出"),$_SESSION['adProfit']['direction']).
        IDSelect("admin","adid","select textPrice","adid","adname","--员工--",$_SESSION['adProfit']['adid']).
        select("auditing","select textPrice","--审核--",array("审核中","已通过","已驳回"),$_SESSION['adProfit']['auditing']);
        ?>
        备注：<input name="adProfitText" type="text" class="text" value="<?php echo $_SESSION['adProfit']['text'];?>">
        <div style="height:10px;"></div>
        结算日：
        <?php
        echo 
        year("year1","select textPrice","new",$_SESSION['adProfit']['DayOne']).
        moon("moon1","select textPrice",$_SESSION['adProfit']['DayOne']).
        day("day1","select textPrice",$_SESSION['adProfit']['DayOne']).
        "-".
        year("year2","select textPrice","new",$_SESSION['adProfit']['DayTwo']).
        moon("moon2","select textPrice",$_SESSION['adProfit']['DayTwo']).
        day("day2","select textPrice",$_SESSION['adProfit']['DayTwo']);
        ?>
        <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <span onclick="$('[name=ProfitForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
    <span onclick="$('[name=ProfitForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
    <span class="spanButton" onclick="$('#adProfitApplyDiv').fadeIn()">填写费用报销申请单</span>
    <?php echo $ProfitDelete;?>
    <span class="smallWord floatRight">
        合计金额：￥<?php echo $AllMoney;?>&nbsp;&nbsp;
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<!--查询结束-->
<!--列表开始-->
<form name="ProfitForm">
<table class="tableMany">
    <tr>
        <td style="width:13px;"></td>
        <td style="width:78px;">发生金额</td>
        <td style="width:28px;">员工</td>
        <td style="width:28px;">审核</td>
        <td>备注</td>
        <td style="width:78px;">结算日</td>
        <td style="width:42px;"></td>
    </tr>
    <?php
    if($num > 0){
        while($array = mysql_fetch_array($query)){
            $admin = query("admin"," adid = '$array[adid]' ");
            if($array['direction'] == "收入"){
                $money = "<span class='green'>+{$array['money']}</span>";
            }elseif($array['direction'] == "支出"){
                $money = "<span class='red'>-{$array['money']}</span>";
            }else{
                $money = "";	
            }
            echo "
            <tr>
                <td><input name='ProfitList[]' type='checkbox' value='{$array['id']}'/></td>
                <td>{$money}</td>
                <td>{$admin['adname']}</td>
                <td>{$array['auditing']}</td>
                <td>{$array['text']}</td>
                <td>{$array['payDate']}</td>
                <td><a href='".root."control/finance/adProfitMx.php?id={$array['id']}'><span class='spanButton'>详情</span></a></td>
            </tr>
            ";
        }
    }else{
        echo "<tr><td colspan='7'>一条信息都没有</td></tr>";
    }
    ?>
</table>
</form>
<?php echo fenye($ThisUrl,7);?>
<!--列表结束-->
<!--费用报销弹出层开始-->
<div class="hide" id="adProfitApplyDiv">
    <div class="dibian"></div>
    <div class="win" style=" height:440px; width:760px; margin:-220px 0 0 -380px;">
        <p onclick="$('#adProfitApplyDiv').hide()" class="winTitle">
            <span id="MoneyTitle">填写费用报销申请单</span>
            <span class="winClose">×</span>
        </p>
        <form name="adProfitApplyForm">
        <table class="tableRight">
            <tr>
            	<td>报销凭证：</td>
                <td><img title="点击替换图片" id="adProfitApplyIcoId" class="smallImg imgHover cursor" src="<?php echo root."img/images/addImg.png";?>"></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;报销金额：</td>
                <td><input name="money" type="text" class="text textPrice"></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;报销事由：</td>
                <td><textarea name="text" class="textarea"></textarea></td>
            </tr>
            <tr>
            	<td><span class="red">*</span>&nbsp;发生日期：</td>
                <td>
				<?php
                echo 
                year("year","select textPrice","new","").
                moon("moon","select textPrice","").
                day("day","select textPrice","");
                ?>
                </td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;登录密码：</td>
                <td><input name="pas" type="password" class="text textPrice"></td>
            </tr>
            <tr>
                <td></td>
                <td><input id="adProfitApplySub" type="button" class="button" value="提交"></td>
            </tr>
        </table>
        </form>
	</div>
</div>
<!--费用报销弹出层结束-->
<!--隐藏表单区域开始-->
<div class="hide">
<form name="profitIcoForm" action="<?php echo root."control/ku/post.php?type=adProfitIco";?>" method="post" enctype="multipart/form-data">
<input name="adProfitIcoFile" id="adOrderRecordIcoFielId" type="file">
<input name="adProfitId" type="hidden">
</form>
</div>
<!--隐藏表单区域结束-->
<script>
$(function(){
	var getType = "<?php echo $get['type'];?>";
	if(getType == "apply"){
		$("#adProfitApplyDiv").fadeIn();
	}
	//选择本地图片
	$("#adProfitApplyIcoId").click(function(){
		document.profitIcoForm.adProfitIcoFile.click();
	});
	$("#adOrderRecordIcoFielId").change(function(){
		$("#adProfitApplyIcoId").attr("src",getFileUrl("adOrderRecordIcoFielId"));
	});
	//提交费用报销弹出层
	$("#adProfitApplySub").click(function(){
		$.post(root+'control/ku/data.php?type=adProfitApply',$("[name=adProfitApplyForm]").serialize(),function(data){
			if(data.warn == 2){
				var ico = document.profitIcoForm.adProfitIcoFile.value;
				if(ico == ""){
					if(data.href){
						window.location.href = data.href;
					}else{
						window.location.reload();
					}
				}else{
					document.profitIcoForm.adProfitId.value = data.id;
					document.profitIcoForm.submit();
				}
			}else{
				warn(data.warn);
			}
		},"json");
	});
});
</script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
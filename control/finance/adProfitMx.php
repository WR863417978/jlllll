<?php
include "../ku/adfunction.php";
ControlRoot("adProfit");
$profit = query("profit"," id = '$get[id]' ");
if(empty($profit['id'])){
	$_SESSION['warn'] = "未找到这条收支记录";
	header("location:{$root}control/finance/adProfit.php");
	exit(0);
}
$admin = query("admin"," adid = '$profit[adid]' ");
if($profit['auditing'] != "已通过"){
	$profitIco = "<span onclick='document.profitIcoForm.adProfitIcoFile.click()' class='spanButton floatRight'>更新</span>";
	$profitWord = "<img class='clickIco' src='{$root}img/images/clickEdit.png' onclick=\"$('#adProfitApplyDiv').fadeIn()\">";
}
if(power("adProfit","auditing")){
	$auditingButton = "&nbsp;&nbsp;<span class='spanButton' onclick=\"$('#auditingDiv').fadeIn()\">审核</span>";
}
if(!empty($profit['projectId'])){
	$project = query("project"," id = '$profit[projectId]' ");
	$projectLi = "<li>所属项目：{$project['name']}</li>";
}
if(!empty($profit['orderId'])){
	$buyCar = query("buyCar"," id = '$profit[orderId]' ");
	$buyCarLi = "<li>所属订单：{$buyCar['name']}</li>";
}
if(empty($profit['ico'])){
	$profitIcoShow = "无";
}else{
	$profitIcoShow = "<img src='{$root}{$profit['ico']}'>";
}
$title = $profit['direction'].$profit['money'];
$onion = array(
	"财务管理" => root."control/finance/adFinancial.php",
	"收支平衡" => root."control/finance/adProfit.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--基本资料开始-->
<div class="profitDiv">
    <div class="profitinside">
        <?php echo $profitWord;?>
        <ul>
            <li>ID：<?php echo $profit['id'];?></li>
            <li>申请人：<?php echo $admin['adname'];?></li>
            <li>方向：<?php echo $profit['direction'];?></li>
            <li>金额：<span class="red">￥<?php echo $profit['money'];?></span></li>
            <li>发生日期：<?php echo $profit['payDate'];?></li>
            <li>状态：<?php echo $profit['auditing'].$auditingButton;?></li>
            <li>更新时间：<?php echo $profit['updateTime'];?></li>
            <li>创建时间：<?php echo $profit['time'];?></li>
            <?php echo $projectLi.$buyCarLi;?>
        </ul>
        <div class="clear"></div>
        <span class="green">备注：<?php echo $profit['text'];?></span>
        <br>
        <span class="green">审核说明：<?php echo kong($profit['auditingText']);?></span>
    </div>
</div>
<div class="planTitle">
    付款凭证
    <?php echo $profitIco;?>
</div>
<div class="planDiv"><?php echo $profitIcoShow;?></div>
<!--基本资料结束-->
<!--费用报销编辑弹出层开始-->
<div class="hide" id="adProfitApplyDiv">
    <div class="dibian"></div>
    <div class="win" style=" height:330px; width:760px; margin:-165px 0 0 -380px;">
        <p onclick="$('#adProfitApplyDiv').hide()" class="winTitle">
            <span id="MoneyTitle">更新费用报销申请单</span>
            <span class="winClose">×</span>
        </p>
        <form name="adProfitApplyForm">
        <table class="tableRight">
            <tr>
                <td><span class="red">*</span>&nbsp;报销金额：</td>
                <td><input name="money" type="text" class="text textPrice" value="<?php echo $profit['money'];?>"></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;报销事由：</td>
                <td><textarea name="text" class="textarea"><?php echo $profit['text'];?></textarea></td>
            </tr>
            <tr>
            	<td><span class="red">*</span>&nbsp;发生日期：</td>
                <td>
				<?php
                echo 
                year("year","select textPrice","new",$profit['payDate']).
                moon("moon","select textPrice",$profit['payDate']).
                day("day","select textPrice",$profit['payDate']);
                ?>
                </td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;登录密码：</td>
                <td><input name="pas" type="password" class="text textPrice"></td>
            </tr>
            <tr>
                <td><input name="adProfitId" type="hidden" value="<?php echo $profit['id'];?>"></td>
                <td><input onclick="Sub('adProfitApplyForm',root+'control/ku/data.php?type=adProfitApply')" type="button" class="button" value="更新"></td>
            </tr>
        </table>
        </form>
	</div>
</div>
<!--费用报销编辑弹出层结束-->
<!--审核弹出层开始-->
<div class="hide" id="auditingDiv">
    <div class="dibian"></div>
    <div class="win" style=" height:286px; width:780px; margin:-143px 0 0 -390px;">
        <p onclick="$('#auditingDiv').hide()" class="winTitle">
            <span id="MoneyTitle">报销审核</span>
            <span class="winClose">×</span>
        </p>
        <form name="auditingForm">
        <table class="tableRight">
            <tr>
                <td><span class="red">*</span>&nbsp;审核状态：</td>
                <td><?php echo radio("auditing",array("审核中","已通过","已驳回"),$profit['auditing']);?></td>
            </tr>
            <tr>
            	<td>审核说明：</td>
                <td><textarea name="auditingText" class="textarea"><?php echo $profit['auditingText'];?></textarea></td>
            </tr>
            <tr>
                <td><span class="red">*</span>&nbsp;登录密码：</td>
                <td><input name="pas" type="password" class="text textPrice"></td>
            </tr>
            <tr>
                <td><input name="adProfitId" type="hidden" value="<?php echo $profit['id'];?>"></td>
                <td><input onclick="Sub('auditingForm',root+'control/ku/data.php?type=adProfitAuditing')" type="button" class="button" value="提交"></td>
            </tr>
        </table>
        </form>
	</div>
</div>
<!--审核弹出层结束-->
<!--文件上传区开始-->
<div class="hide">
<form name="profitIcoForm" action="<?php echo root."control/ku/post.php?type=adProfitIco";?>" method="post" enctype="multipart/form-data">
<input name="adProfitIcoFile" type="file" onchange="document.profitIcoForm.submit();">
<input name="adProfitId" type="hidden" value="<?php echo $profit['id'];?>">
</form>
</div>
<!--文章上传区结束-->
<?php echo warn().adfooter();?>
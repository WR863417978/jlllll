<?php 
include "ku/adfunction.php";
ControlRoot();
//为公司服务的天数
$tian = ceil((strtotime($time)-strtotime($Control['entryTime']))/60/60/24);
//请假时长
$startTime = date("Y-m-01");
$endTime = date('Y-m-d',strtotime("$startTime +1 month"));
$workTime = mysql_fetch_array(mysql_query(" select sum(hour) as 'hour' from work where adid = '$Control[adid]' and time > '$startTime' and time < '$endTime' "));
if(empty($workTime['hour'])){
	$workTime['hour'] = 0;
}
$onion = array(
	"个人中心" => root."control/adpersonal.php"
);
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
	<!--管理员基本资料开始-->
	<div class="kuang">
         <div class="adHeadImg">
             <a href="javascript:;" onclick="document.AdminIcoForm.UploadAdHead.click()" title="点击更新头像">
                 <img src="<?php echo HeadImg($Control['sex'],$Control['touxiang']);?>">
             </a>
         </div>
         <div class="adHeadData">
             <h1 class="adHeadTitle">
				 <?php echo $Control['adname'];?>
                 <span>ID：<?php echo $Control['adid'];?></span>
             </h1>
             <div class="adHeadMx">
                 <ul>
                     <li>
                         <span>性别：</span>
                         <span><?php echo $Control['sex'];?></span>
                     </li>
                     <li>
                         <span>手机号码：</span>
                         <span id="adTelId"><?php echo $Control['adtel'];?></span>
                     </li>
                     <li>
                         <span>QQ号码：</span>
                         <span><?php echo $Control['adqq'];?></span>
                     </li>
                     <li>
                         <span>职位：</span>
                         <span><?php echo $adDuty['name'];?></span>
                     </li>
                     <li>
                         <span>毕业院校：</span>
                         <span><?php echo kong($Control['school']);?></span>
                     </li>
                     <li>
                         <span>所学专业：</span>
                         <span><?php echo kong($Control['schoolMajor']);?></span>
                     </li>
                     <li>
                         <span>毕业日期：</span>
                         <span><?php echo kong($Control['schoolEnd']);?></span>
                     </li>
                     <li>
                         <span>银行名称：</span>
                         <span><?php echo kong($Control['bankName']);?></span>
                     </li>
                     <li>
                         <span>银行卡号：</span>
                         <span><?php echo kong($Control['bankNum']);?></span>
                     </li>
                     <li>
                         <span>当前状态：</span>
                         <span><?php echo kong($Control['state']);?></span>
                     </li>
                     <li>
                         <span>入职时间：</span>
                         <span><?php echo $Control['entryTime'];?></span>
                     </li>
                     <li>
                         <span>您已经兢兢业业的为公司工作了<b class="must"><?php echo $tian;?></b>天</span>
                     </li>
                 </ul>
                 <div class="clear"></div>
             </div>
         </div>
         <div class="clear"></div>
    </div>
    <!--管理员基本资料结束-->
    <!--工资核算开始-->
    <div class="kuang">
        <ul class="adMoney">
            <li>
                <p class="adMoneyTitle">账户余额（元）</p>
                <div class="adMoneyValue">
                    ￥<span id="adMoney" money="<?php echo $Control['money'];?>">0</span>
                    <a target="_blank" href="<?php echo root."control/finance/adAccount.php?adid=".$Control['adid'];?>">
                        <span class="spanButton">账户记录</span>
                    </a>
                </div>
            </li>
            <li>
                <p class="BasePay">基本工资：￥<?php echo $adDuty['basePay'];?></p>
            </li>
            <li>
                <p class="BasePay">请假<span class="must"><?php echo $workTime['hour'];?></span>小时</p>
            </li>
            <li>
                <p class="BasePay"><a href="<?php echo root."control/Internal/adSystem.php";?>">公司管理制度&nbsp;>></a></p>
            	<a href="<?php echo root."control/finance/adProfit.php?type=apply";?>"><span class="spanButton">费用报销</span></a>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
    <!--工资核算结束-->
    <!--管理员认证材料开始-->
    <div class="adStatus kuang">
        <ul>
            <li>
                <p>身份证正面</p>
                <?php echo ProveImgShow($Control['IDCardFront']);?>
            </li>
            <li>
                <p>身份证反面</p>
                <?php echo ProveImgShow($Control['IDCardBack']);?>
            </li>
            <li>
                <p>毕业证</p>
                <?php echo ProveImgShow($Control['diploma']);?>
            </li>
            <li>
                <p>工资卡</p>
                <?php echo ProveImgShow($Control['bankIco']);?>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
    <!--管理员认证材料结束-->
	<!--修改注册手机号码开始-->
    <div class="adEditTel kuang">
        <p>
            <img src="<?php echo root."img/images/text.png";?>">
            修改手机号码
        </p>
    <form name="AdTelForm">
    <table class="tableRight">
        <tr>
            <td>当前注册手机：</td>
            <td><?php echo $Control['adtel'];?></td>
        </tr>
        <tr>
            <td>新手机号码：</td>
            <td><input name="NewTel" type="text" class="text"></td>
        </tr>
        <tr>
            <td>登录密码：</td>
            <td><input name="password" type="password" class="text"></td>
        </tr>
        <tr>
            <td>短信验证码：</td>
            <td>
            <input name="Prove" type="text" class="text textPrice">
            <span onclick="NewTelProve()" class="spanButton">向新手机发送短信验证码</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input onclick="Sub('AdTelForm','<?php echo root."control/ku/data.php?type=adEditTel";?>')" type="button" class="button" value="确认修改" /></td>
        </tr>
    </table>
    </form>
    </div>
    <!--修改登录手机号码结束-->
    <!--修改登录密码开始-->
    <div class="editPas kuang">
        <p>
            <img src="<?php echo root."img/images/doughnut.png";?>">
            修改密码
        </p>
        <form name="AdPasForm">
        <table class="tableRight">
            <tr>
                <td>当前密码：</td>
                <td><input name="pas" type="password" class="text"></td>
            </tr>
            <tr>
                <td>更新密码：</td>
                <td><input name="gxpas" type="password" class="text"  onkeyup="pwStrength(this.value)" onBlur="pwStrength(this.value)"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                <ul class="pas_biao"> 
                <li id="strength_L"></li>
                <li id="strength_M"></li>
                <li id="strength_H"></li>
                </ul>
                </td>
            </tr>
            <tr>
                <td>确认密码：</td>
                <td><input name="qrpas" type="password" class="text"></td>
            </tr>
            <tr>
                <td>短信验证码：</td>
                <td>
                <input name="Prove" type="text" class="text textPrice">
                <span onclick="NewPasProve()" class="spanButton">向注册手机发送短信验证码</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input onclick="Sub('AdPasForm','<?php echo root."control/ku/data.php?type=adEditPas";?>')" type="button" class="button" value="确认修改" /></td>
            </tr>
        </table>
        </form>
    </div>
	<!--修改登录密码结束-->
</div>
<!--隐藏表单区域开始-->
<div class="hide">
<form name="AdminIcoForm" action="<?php echo root."control/ku/post.php?type=adEditAdminHead";?>" method="post" enctype="multipart/form-data">
<input name="UploadAdHead" type="file" onchange="document.AdminIcoForm.submit()">
</form>
</div>
<!--隐藏表单区域结束-->
<script src="<?php echo root."library/intensity.js";?>"></script>
<script>
$(document).ready(function(){
	addMoney();
});
//账户余额累加函数
function addMoney(){
	var MoneyTrue = document.getElementById("adMoney").getAttribute("money");//实际金额
	var MoneyTrueInt = parseFloat(MoneyTrue);//获得累加效果的整数
	var MoneyShow = document.getElementById("adMoney").innerHTML;//显示的金额
	var MoneyShowInt = parseFloat(MoneyShow);//将当前显示的金额强制转换为整数以便累加
	if(MoneyTrueInt > MoneyShowInt){//如果实际金额还是大于当前显示的金额，则继续累加
		MoneyShowInt += parseFloat(MoneyTrueInt * 0.01);
		document.getElementById("adMoney").innerHTML = MoneyShowInt.toFixed(2);
		setTimeout("addMoney()",10);
	}else{
		document.getElementById("adMoney").innerHTML = MoneyTrue;
	}
}
//修改注册手机-短信验证函数
function NewTelProve(){
	$.post(root + "library/libData.php?type=RegisterCheckTel",{tel:document.AdTelForm.NewTel.value},function(data){
		warn(data.warn);
	},"json");
}
//修改密码-短信验证函数
function NewPasProve(){
	$.post(root + "library/libData.php?type=RegisterCheckTel",{tel:$("#adTelId").html()},function(data){
		warn(data.warn);
	},"json");
}
</script>
<?php echo warn().adfooter();?>
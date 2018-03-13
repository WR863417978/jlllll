<?php
include "../ku/adfunction.php";
ControlRoot("adAccount");
$ThisUrl = root."control/finance/adAccount.php";
if($adDuty['name'] == "超级管理员"){
  if(empty($_GET['adid'])){
	  $name = $tel =  "未设置";
	  $AccountWord = "￥&nbsp;0.00";
  }else{
	  $id = FormSub($_GET['adid']);
	  $type = "adid";
	  $ThisUrl .= "?adid={$id}";
	  $admin = query("admin"," adid = '$id' ");
	  if($id != $admin['adid']){
		  $_SESSION['warn'] = "未找到此员工";
		  header("location:{$root}control/finance/adAccount.php");
		  exit(0);
	  }
	  $name = $admin['adname'];
	  $tel = $admin['adtel'];
	  $money = $admin['money'];
  }
  if(!empty($type)){
	  $AccountWord = "
	  <span class='must'>{$money}</span>&nbsp;&nbsp;
	  <input EditMoneyButton='addMoney' type='button' value='添加现金'>
	  <input EditMoneyButton='cutMoney' type='button' class='green' value='扣减现金'>";
  }
}else{
    $id = $Control['adid'];
	$type = "adid";
	$name = $Control['adname'];
	$tel = $Control['adtel'];
	$AccountWord = "<span class='must'>{$Control['money']}</span>";
}
//输出账户记录
$sql = " select * from record where typeid = '$id' and type = '员工现金账户' ";
paging($sql," order by id desc",100);
$onion = array(
    "财务管理" => root."control/finance/adFinancial.php",
	"账户管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--平账系统开始-->
<div class="kuang">
    <p>
        <img src="<?php echo root."img/images/edit.png";?>">
        账户平账系统
    </p>
    <form name="AccountForm">
    <table class="tableRight">
        <tr>
            <td>ID号：</td>
            <td>
            <input name="id" type="text" class="text" value="<?php echo $id;?>">
            <input id="AccountSkip" type="button" value="精确查询">
            </td>
        </tr>
        <tr>
            <td>身份：</td>
            <td><?php echo select("AccountType","select","--选择--",array("adid" => "员工"),$type);?></td>
        </tr>	
        <tr>
            <td>名称：</td>
            <td><?php echo $name;?></td>
        </tr>
        <tr>
            <td>联系手机：</td>
            <td><?php echo $tel;?></td>
        </tr>
        <tr>
            <td>账户余额：</td>
            <td><?php echo $AccountWord;?></td>
        </tr>
    </table>
    </form>
</div>
<!--平账系统结束-->
<!--账户记录开始-->
<div class="kuang">
    <img src="<?php echo root."img/images/text.png";?>">
    账户记录
    <table class="tableMany">
        <tr>
            <td>记录类型</td>
            <td>变动方向</td>
            <td>发生额度</td>
            <td>最新余额</td>
            <td>简要说明</td>
            <td>发生时间</td>
        </tr>
        <?php
        if($num == 0){
            echo "<tr><td colspan='6'>一条记录都没有</td></tr>";
        }else{
            while($record = mysql_fetch_array($query)){
                echo "
                <tr>
                    <td>{$record['type']}</td>
                    <td>{$record['direction']}</td>
                    <td>{$record['money']}</td>
                    <td>{$record['balance']}</td>
                    <td>{$record['text']}</td>
                    <td>{$record['time']}</td>
                </tr>
                ";
            } 
        }
        ?>
    </table>
</div>
<div style="padding:10px;"><?php echo fenye($ThisUrl,7);?></div>
<!--账户记录结束-->
<!--变更客户账户弹出层开始-->
<div class="hide" id="moneyEdit">
    <div class="dibian"></div>
    <div class="win" style="width:400px; height:202px; margin:-101px 0 0 -200px;">
        <p onclick="$('#moneyEdit').hide()" class="winTitle">
            <span id="MoneyTitle">账户平账系统</span>
            <span class="winClose">×</span>
        </p>
        <form name="MoneyForm">
        <table class="tableRight">
            <tr>
                <td>变动额度：</td>
                <td><input name="money" type="text" class="text textPrice"></td>
            </tr>
            <tr>
                <td>简要说明：</td>
                <td><input name="text" type="text" class="text short"></td>
            </tr>
            <tr>
                <td>管理员登录密码：</td>
                <td><input name="password" type="password" class="text textPrice"></td>
            </tr>
            <tr>
                <td>
                <input type="hidden" name="EditMoneyId" value="<?php echo $id;?>">
                <input type="hidden" name="EditMoneyType" value="<?php echo $type;?>">
                <input type="hidden" name="EditMoneyDirection">
                </td>
                <td><input onclick="Sub('MoneyForm',root+'control/ku/data.php?type=accountEdit')" type="button" class="button" value="确认"></td>
            </tr>
        </table>
        </form>
    </div>
</div>
<!--变更客户账户弹出层结束-->
<script>
$(document).ready(function(){
	//跳转
	$("#AccountSkip").click(function(){
		var id = document.AccountForm.id.value;
		var type = document.AccountForm.AccountType.value;
		if(id == ""){
		   warn("请输入id号");
		}else if(type == ""){
		   warn("请选择身份");
		}else{
			window.location.href = root + "control/finance/adAccount.php?"+type+"="+id;
		}
	});
    //弹出账户变更层
	$("[EditMoneyButton]").click(function(){
		$("#MoneyTitle").text($(this).val());
	    document.MoneyForm.EditMoneyDirection.value = $(this).attr("EditMoneyButton");
		$("#moneyEdit").fadeIn();
	});
});
</script>
<?php echo warn().adfooter();?>
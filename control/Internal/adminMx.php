<?php
include "../ku/adfunction.php";
ControlRoot("admin");
if(empty($get['id'])){
	$title = "新建员工";
}else{
	$adid = $get['id'];
	$admin = query("admin"," adid = '$adid' ");
	if($admin['adid'] != $adid){
		$_SESSION['warn'] = "未找到此员工";
		header("Location:{$root}control/Internal/admin.php"); 	
		exit(0);
	}
	$title = $admin['adname'];
	$duty = query("adDuty"," id = '$admin[duty]' ");
	$adImg = "
    <div class='adStatus kuang'>
        <ul>
            <li>
                <p>头像</p>
                <div>".ProveImgShow($admin['touxiang'])."</div>
            </li>
            <li>
                <img src='{$root}img/images/clickEdit.png' onclick='document.adIDCardFrontForm.IDCardFront.click();' class='clickIco'>
                <p>身份证正面</p>
                <div>".ProveImgShow($admin['IDCardFront'])."</div>
            </li>
            <li>
                <img src='{$root}img/images/clickEdit.png' onclick='document.adIDCardBackForm.adIDCardBackUpload.click();' class='clickIco'>
                <p>身份证背面</p>
                <div>".ProveImgShow($admin['IDCardBack'])."</div>
            </li>
            <li>
                <img src='{$root}img/images/clickEdit.png' onclick='document.adDiplomaForm.adDiplomaUpload.click();' class='clickIco'>
                <p>毕业证</p>
                <div>".ProveImgShow($admin['diploma'])."</div>
            </li>
            <li>
                <img src='{$root}img/images/clickEdit.png' onclick='document.adminBankIcoForm.adminBankIcoUpload.click();' class='clickIco'>
                <p>工资卡</p>
                <div>".ProveImgShow($admin['bankIco'])."</div>
            </li>
        </ul>
        <div class='clear'></div>
    </div>
	";
	$Account = "
	账户余额：<b class='red'>￥{$admin['money']}</b>&nbsp;
    <a target='_blank' href='{$root}control/finance/adAccount.php?adid={$admin['adid']}'><span class='spanButton'>账户管理</span></a>
    ";
}
$onion = array(
    "内部管理" => root."control/Internal/adInternal.php",
	"员工管理" => root."control/Internal/admin.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--参数编辑开始-->
<div class="kuang">
    <p>
        <img src="<?php echo root."img/images/text.png";?>">
        员工基本信息
    </p>
    <form name="AdminForm">
    <table class="tableRight">
        <tr>
            <td width="100">核心参数：</td>
            <td>
            <span class="interval">id号：<?php echo kong($admin['adid']);?></span>
            <?php echo $Account;?>
            </td>
        </tr>
        <tr>
            <td><span class="red">*</span>&nbsp;员工姓名：</td>
            <td><input name="adname" type="text" class="text" value="<?php echo $admin['adname'];?>"></td>
        </tr>
        <tr>
            <td><span class="red">*</span>&nbsp;选择：</td>
            <td>
            <span class="interval">性别：<?php echo radio("sex",array("男","女"),$admin['sex']);?></span>
            当前状态：<?php echo radio("state",array("在岗","离职"),$admin['state']);?>
            </td>
        </tr>
        <tr>
            <td><span class="red">*</span>&nbsp;手机号码：</td>
            <td><input name="adtel" type="text" class="text" value="<?php echo $admin['adtel'];?>"></td>
        </tr>
        <tr>
            <td><span class="red">*</span>&nbsp;电子邮箱：</td>
            <td><input name="adEmail" type="text" class="text" value="<?php echo $admin['ademail'];?>"></td>
        </tr>
        <tr>
            <td>QQ号码：</td>
            <td><input name="adQQ" type="text" class="text" value="<?php echo $admin['adqq'];?>"></td>
        </tr>
        <tr>
            <td><span class="red">*</span>&nbsp;岗位：</td>
            <td>
            <?php if(empty($duty)){?>
                <?php
                    $sql = "select department from adduty group by department";
                    $pdo = new PDO('mysql:host='.$GLOBALS['conf']['ServerName'].';dbname='.$GLOBALS['conf']['DatabaseName'], $GLOBALS['conf']['UserName'], $GLOBALS['conf']['password'] );
                    $pdo->query('set names utf8');
                    $a = $pdo->query($sql);
                    $data = $a->fetchAll(PDO::FETCH_ASSOC);
                 ?>
             <select class="select" name="adDutyDepartment">
                <option value="">--所属部门--</option>
                <?php foreach($data as $k=>$v){?>
                    <option value=<?php echo $v['department']?>><?php echo $v['department']?></option>
                <?php }?>
             </select>
            <?php }else{?>
            <?php echo RepeatSelect("adDuty where xian = '开启' order by list ","department","adDutyDepartment","select","--所属部门--",$duty['department']);?>
            <?php }?><select name="adDutyId" class="select"><?php echo IdOption("adDuty where department = '$duty[department]' and xian = '开启' order by list ","id","name","--当前职位--",$duty['id']);?></select>
            </td>
        </tr>
        <tr>
            <td>学历：</td>
            <td>
            毕业院校：<input name="adSchool" type="text" class="text" value="<?php echo $admin['school'];?>">
            所学专业：<input name="adSchoolMajor" type="text" class="text" value="<?php echo $admin['schoolMajor'];?>">
            </td>
        </tr>
        <tr>
            <td>工资卡：</td>
            <td>
            银行名称：<input name="adBankName" type="text" class="text" value="<?php echo $admin['bankName'];?>">
            银行卡号：<input name="adBankNum" type="text" class="text" value="<?php echo $admin['bankNum'];?>">
            </td>
        </tr>
        <tr>
            <td>备注：</td>
            <td><textarea name="adminText" class="textarea"><?php echo $admin['text'];?></textarea></td>
        </tr>
        <tr>
            <td>毕业日期：</td>
            <td>
            <?php 
            echo 
            year("GraduationYear","select","old",$admin['schoolEnd']).
            moon("GraduationMoon","select",$admin['schoolEnd']).
            day("GraduationDay","select",$admin['schoolEnd']);
            ?>
            </td>
        </tr>
        <tr>
            <td>入职时间：</td>
            <td>
            <?php 
            echo 
            year("EntryYear","select","new",$admin['entryTime']).
            moon("EntryMoon","select",$admin['entryTime']).
            day("EntryDay","select",$admin['entryTime']);
            ?>
            </td>
        </tr>
        <tr>
            <td>离职时间：</td>
            <td>
            <?php 
            echo 
            year("quitYear","select","new",$admin['quitTime']).
            moon("quitMoon","select",$admin['quitTime']).
            day("quitDay","select",$admin['quitTime']);
            ?>
            </td>
        </tr>
        <tr>
            <td>更新时间：</td>
            <td><?php echo kong($admin['updateTime']);?></td>
        </tr>
        <tr>
            <td>创建时间：</td>
            <td><?php echo kong($admin['time']);?></td>
        </tr>
        <tr>
            <td><input name="adminId" type="hidden" value="<?php echo $admin['adid'];?>"></td>
            <td><input type="button" class="button" onclick="Sub('AdminForm',root+'control/ku/data.php?type=adminEdit')" value="提交"></td>
        </tr>
    </table>
    </form>
</div>
<!--参数编辑结束-->
<?php echo $adImg;?>
<!--隐藏文件上传区开始-->
<div class="hide">
<form name="adIDCardFrontForm" action="<?php echo root."control/ku/post.php?type=adEditAdminIDCardFront";?>" method="post" enctype="multipart/form-data">
<input name="IDCardFront" type="file" onchange="document.adIDCardFrontForm.submit();">
<input name="adminId" type="hidden" value="<?php echo $admin['adid'];?>">
</form>
<form name="adIDCardBackForm" action="<?php echo root."control/ku/post.php?type=adEditAdminIDCardBack";?>" method="post" enctype="multipart/form-data">
<input name="adIDCardBackUpload" type="file" onchange="document.adIDCardBackForm.submit();">
<input name="adminId" type="hidden" value="<?php echo $admin['adid'];?>">
</form>
<form name="adDiplomaForm" action="<?php echo root."control/ku/post.php?type=adEditAdminDiploma";?>" method="post" enctype="multipart/form-data">
<input name="adDiplomaUpload" type="file" onchange="document.adDiplomaForm.submit();">
<input name="adminId" type="hidden" value="<?php echo $admin['adid'];?>">
</form>
<form name="adminBankIcoForm" action="<?php echo root."control/ku/post.php?type=adEditAdminBank";?>" method="post" enctype="multipart/form-data">
<input name="adminBankIcoUpload" type="file" onchange="document.adminBankIcoForm.submit();">
<input name="adminId" type="hidden" value="<?php echo $admin['adid'];?>">
</form>
</div>
<!--隐藏文件上传区结束-->
<script>
$(document).ready(function(){
	var form = document.AdminForm;
	//根据部门选择职位
	form.adDutyDepartment.onchange = function(){
	    $.post(root+"control/ku/data.php",{adDutyDepartmentGetName:this.value},function(data){
		    form.adDutyId.innerHTML = data.DutyId;
		},"json");
	}
});
</script>
<?php echo warn().adfooter();?>
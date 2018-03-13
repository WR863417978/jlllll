<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20
 * Time: 11:53
 */
//供应商详细页
include "ku/adfunction.php";
ControlRoot("adSupplier");
if(empty($_GET['id'])){
    $title = "供应商详情";
}else {
    $supplier = query("admin", " adid = '$get[id]' ");
    if ($supplier['adid'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个供应商的信息";
        header("location:{$root}control/adSupplier.php");
        exit(0);
    }
}
$onion = array(
    "供应商管理" => root."control/adSupplier.php",
    "供应商详情" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--供应商详细开始-->
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <form name="textForm">
                <table class="tableRight">
                    <tr>
                        <td>&nbsp;商户创建时间：</td>
                        <td><?php echo kong($supplier['time']);?></td>
                        <td>&nbsp;账户金额：</td>
                        <td><?php echo kong($supplier['money']);?></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;供应商姓名：</td>
                        <td><input name="sname" type="text" class="text" value="<?php echo $supplier['adname'];?>"></td>
                        <td><span class="red">*</span>&nbsp;联系方式：</td>
                        <td><input name="tel" type="text" class="text" value="<?php echo $supplier['adtel'];?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;供应商所属职位：</td>
                        <td colspan="3"> <?php echo IDSelect("adDuty order by list ","dutyId","select","id","name","--职位选择--",$supplier['duty']);?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;性别：</td>
                        <td><input name="sex" type="text" class="text" value="<?php echo $supplier['sex'];?>"></td>
                        <td>&nbsp;联系QQ：</td>
                        <td><input name="qq" type="text" class="text" value="<?php echo $supplier['adqq'];?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;电子邮箱：</td>
                        <td><input name="email" type="text" class="text" value="<?php echo $supplier['ademail'];?>"></td>
                        <td>&nbsp;银行卡号：</td>
                        <td><input name="bankId" type="text" class="text" value="<?php echo $supplier['bankNum'];?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;银行名称：</td>
                        <td colspan="3"><input name="bankName" type="text" class="text" value="<?php echo $supplier['bankName'];?>"></td>
                    </tr>
                    <tr>
                        <td><input name="supplierId" type="hidden" value="<?php echo $supplier['adid']; ?>"></td>
                        <td colspan="3"><input onclick="Sub('textForm',root+'control/ku/addata.php?type=supplierAdd')" type="button" class="button" value="提交"></td>
                    </tr>
                </table>
            </form>
            <!--参数编辑结束-->
            <ul>
                <li>
                    <p>头像</p>
                    <div><?php echo ProveImgShow($supplier['touxiang'],"暂无图片");?></div>
                </li>
                <li>
                    <img src='http://www.yumukeji.com/project/juli/img/images/clickEdit.png' onclick='document.adIDCardFrontForm.IDCardFront.click();' class='clickIco'>
                    <p>身份证正面</p>
                    <div><?php echo ProveImgShow($supplier['IDCardFront'],"暂无图片");?></div>
                </li>
                <li>
                    <img src='http://www.yumukeji.com/project/juli/img/images/clickEdit.png' onclick='document.adIDCardBackForm.adIDCardBackUpload.click();' class='clickIco'>
                    <p>身份证背面</p>
                    <div><?php echo ProveImgShow($supplier['IDCardBack'],"暂无图片");?></div>
                </li>
                <li>
                    <img src='http://www.yumukeji.com/project/juli/img/images/clickEdit.png' onclick='document.adminBankIcoForm.adminBankIcoUpload.click();' class='clickIco'>
                    <p>银行卡 </p>
                    <div><?php echo ProveImgShow($supplier['bankIco'],"暂无图片");?></div>
                </li>
            </ul>
            <div class='clear'></div>
        </div>
        <!--供应商明细结束-->
    </div>
    <!--隐藏文件上传区开始-->
    <div class="hide">
        <form name="adIDCardFrontForm" action="http://www.yumukeji.com/project/juli/control/ku/post.php?type=adEditAdminIDCardFront" method="post" enctype="multipart/form-data">
            <input name="IDCardFront" type="file" onchange="document.adIDCardFrontForm.submit();">
            <input name="adminId" type="hidden" value="<?php echo $supplier['adid'];?>">
        </form>
        <form name="adIDCardBackForm" action="http://www.yumukeji.com/project/juli/control/ku/post.php?type=adEditAdminIDCardBack" method="post" enctype="multipart/form-data">
            <input name="adIDCardBackUpload" type="file" onchange="document.adIDCardBackForm.submit();">
            <input name="adminId" type="hidden" value="<?php echo $supplier['adid'];?>">
        </form>
        <form name="adminBankIcoForm" action="http://www.yumukeji.com/project/juli/control/ku/post.php?type=adEditAdminBank" method="post" enctype="multipart/form-data">
            <input name="adminBankIcoUpload" type="file" onchange="document.adminBankIcoForm.submit();">
            <input name="adminId" type="hidden" value="<?php echo $supplier['adid'];?>">
        </form>
    </div>
    <!--隐藏文件上传区结束-->
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
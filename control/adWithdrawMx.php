<?php
//提现管理详情页
include "ku/adfunction.php";
ControlRoot("adWithdraw");
if(empty($get['id'])){
    $title = "adWithdraw";
}else {
    $withdraw = query("withdraw", "id ='$get[id]'");
    if ($withdraw['id'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个提现信息";
        header("location:{$root}control/adWithdraw.php");
        exit(0);
    }
    $kname = query("kehu","khid='$withdraw[khid]'");
    $aname = query("admin","adid='$withdraw[actionId]'");
    $title = $kname['name'];
}
$onion = array(
    "提现管理" => root."control/adWithdraw.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--提现明细开始-->
        <?php echo $top;?>
        <div class="kuang">
            <p>申请时间：</p>
            <p><p><?php echo $withdraw['time']; ?></p></p>
            <p>审批时间：</p>
            <p><p><?php echo $withdraw['updateTime']; ?></p></p>
        </div>
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <form name="textForm">
                <table class="tableRight">
                    <tr>
                        <td>审核状态：</td>
                        <td>
                            <?php echo select("workFlow","select","--审核状态--",array("审核中","未通过","已支付"),$withdraw['workFlow']);?>
                        </td>
                    </tr>
                    <tr>
                        <td>申请人姓名：</td>
                        <td><?php echo kong($kname['name']);?></td>
                    </tr>
                    <tr>
                        <td>提现金额：</td>
                        <td><?php echo $withdraw['moneny']?></td>
                    </tr>
                    <tr>
                        <td>操作人：</td>
                        <td><?php echo kong($aname['adname']);?></td>
                    </tr>
                    <tr>
                        <input name="actionId"  type="hidden" value="<?php echo $Control['adid']?>"/>
                        <td><input name="withId" type="hidden" value="<?php echo $withdraw['id']; ?>"></td>
                        <td><input onclick="Sub('textForm',root+'control/ku/addata.php?type=Withdraw')" type="button" class="button" value="提交"></td>
                    </tr>
                </table>
            </form>
            <?php echo $other;?>
        </div>
        <!--申请码明细结束-->
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
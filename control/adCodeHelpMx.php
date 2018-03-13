<?php
//邀请码申请详情页
include "ku/adfunction.php";
ControlRoot("adCodeHelp");
if(empty($get['id'])){
    $title = "邀请码详情";
}else {
    $code = query("codeExplain", "id ='$get[id]'");
    if ($code['id'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个邀请码的申请信息";
        header("location:{$root}control/adCodeHelp.php");
        exit(0);
    }
    $title = $code['id'];
}
$onion = array(
    "申请码管理" => root."control/adCodeHelp.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--申请码明细开始-->
        <?php echo $top;?>
        <div class="kuang">
            <p>申请时间：</p>
            <p><p><?php echo $code['time']; ?></p></p>
            <p>审批时间：</p>
            <p><p><?php echo $code['updateTime']; ?></p></p>
        </div>
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <form name="textForm">
                <table class="tableRight">
                    <tr>
                        <td>审核状态：</td>
                        <td>
                            <?php echo select("status","select","--审核状态--",array("待审核","未通过","已通过"),$code['status']);?>
                        </td>
                    </tr>
                    <tr>
                        <td>默认推荐码：</td>
                        <td><?php echo $code['defaultCode']?></td>
                    </tr>
                    <tr>
                        <td>更改推荐码：</td>
                        <td><?php echo $code['changeCode']?></td>
                    </tr>
                    <tr>
                        <td>邀请人姓名：</td>
                        <td><?php echo $code['shareName']?></td>
                    </tr>
                    <tr>
                        <td>邀请人手机：</td>
                        <td><?php echo $code['shareTel']?></td>
                    </tr>
                    <tr>
                        <td>申请人姓名：</td>
                        <td><?php echo $code['explainName']?></td>
                    </tr>
                    <tr>
                        <td>申请人手机：</td>
                        <td><?php echo $code['explainTel']?></td>
                    </tr>
                    <tr>
                        <input name="actionId"  type="hidden" value="<?php echo $Control['adid']?>"/>
                        <td><input name="codeId" type="hidden" value="<?php echo $code['id']; ?>"></td>
                        <td><input onclick="Sub('textForm',root+'control/ku/addata.php?type=codeHelp')" type="button" class="button" value="提交"></td>
                    </tr>
                </table>
            </form>
            <?php echo $other;?>
        </div>
        <!--申请码明细结束-->
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
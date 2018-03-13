<?php
//邀请码申请详情页
include "ku/adfunction.php";
ControlRoot("adDemand");
if (empty($get['id'])) {
    $title = "需求详情";
} else {
    $demand = query("demand", "id ='$get[id]'");
    if ($demand['id'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个需求的信息";
        header("location:{$root}control/adDemand.php");
        exit(0);
    }
    $title = $demand['id'];
}
$onion = array(
    "需求详情" => root . "control/adCodeHelp.php",
    $title => $ThisUrl,
);
echo head("ad") . adheader($onion);
?>
    <div class="minHeight">
        <!--申请码明细开始-->
        <?php echo $top; ?>
        <div class="profitDiv">
            <div class="profitinside">
                <ul>
                    <li>需求ID：<?php echo $demand['id']; ?></li>
                    <li>
                        客户ID：<?php echo $demand['khid']; ?>&nbsp;
                        <a href="<?php echo $root; ?>control/adClientMx.php?id=<?php echo $demand['khid']; ?>"><span class="spanButton">查看客户详情</span></a>
                    </li>
                    <li>需求主题：<?php echo $demand['theme']; ?></li>
                    <li>礼品分类：<?php echo $demand['giftType']; ?></li>
                    <li>采购数量：<?php echo $demand['num']; ?></li>
                    <li>结束时间：<?php echo $demand['endTime']; ?></li>
                    <li>联系电话：<?php echo $demand['tel']; ?></li>
                    <li>需求描述：<?php echo $demand['text']; ?></li>
                    <li>需求状态：<?php echo $demand['status']; ?></li>
                    <li>操作人员ID： <?php echo $demand['actionId']; ?>&nbsp;<a href="<?php echo $root; ?>control/Internal/adminMx.php?id=<?php echo $demand['actionId']; ?>"><span class="spanButton">查看操作人员详情</span></a></li>
                    <li>更新时间：<?php echo $demand['updateTime']; ?></li>
                    <li>创建时间：<?php echo $demand['time']; ?></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <form name="textForm">
                <table class="tableRight">
                    <tr>
                        <td>审核状态：</td>
                        <td>
                            <?php echo select("status", "select", "--需求状态--", array("已发布", "在商洽", "已合作", "不能接"), $demand['status']); ?>
                        </td>
                    </tr>
                    <tr>
                        <input name="actionId"  type="hidden" value="<?php echo $Control['adid'] ?>"/>
                        <td><input name="demandId" type="hidden" value="<?php echo $demand['id']; ?>"></td>
                        <td><input onclick="Sub('textForm',root+'control/ku/addata.php?type=adDemandSub')" type="button" class="button" value="提交"></td>
                    </tr>
                </table>
            </form>
            <?php echo $other; ?>
        </div>
        <!--申请码明细结束-->
    </div>
<?php echo PasWarn(root . "control/ku/data.php") . warn() . adfooter(); ?>
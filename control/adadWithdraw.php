<?php
/*
 * 提现管理
 */
include "ku/adfunction.php";
ControlRoot("adadWithdraw");
$sql="select * from withdraw ".$_SESSION['SearchWithdraw']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "提现管理" => $ThisUrl,
);
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
    <div class="search">
        <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchcoupon";?>" method="post">
            提现状态：
            <?php echo select("workFlow","select textPrice","--状态--",array("审核中","未通过","已支付"),$_SESSION['SearchWithdraw']['workFlow']);?>
            <input type="submit" value="模糊查询">
        </form>
    </div>
    <div class="search">
        <span onclick="$('[name=CouponForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
        <span onclick="$('[name=CouponForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
        <span onclick="EditList('WithdrawForm','deleteWithdraw')" class="spanButton">删除所选</span>
        <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
    </div>
    <!--查询结束-->
    <!--列表开始-->
    <form name="WithdrawForm">
        <table class="tableMany">
            <tr>
                <td></td>
                <td>申请人</td>
                <td>申请金额</td>
                <td>状态</td>
                <td>审核人</td>
                <td>审核时间</td>
                <td>提出时间</td>
                <td style="width:54px;">操作</td>
            </tr>
            <?php
            if($num > 0){
                while($adadWithdraw = mysql_fetch_array($query)){
                    $kehuname = query("kehu","khid='$adadWithdraw[khid]'");
                    $adname = query("admin","adid='$adadWithdraw[actionId]'");
                    echo "
                        <tr>
                          <td><input name='WithdrawList[]' type='checkbox' value='{$adadWithdraw['id']}'/></td>
                          <td>{$kehuname['name']}</td>
                          <td>{$adadWithdraw['moneny']}</td>
                          <td>{$adadWithdraw['workFlow']}</td>
                          <td>{$adname['adname']}</td>
                          <td>{$adadWithdraw['updateTime']}</td>
                          <td>{$adadWithdraw['time']}</td>
                          <td><a href='{$root}control/adadWithdrawMx.php?id={$adadWithdraw['id']}'><span class='spanButton'>审核</span></a></td>
                        </tr>
                        ";
                }
            }else{
                echo "<tr><td colspan='8'>没有提现申请记录</td></tr>";
            }?>
        </table>
    </form>
    <?php echo fenye($ThisUrl,7);?>
    <!--列表结束-->
</div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>

<?php
/*
 * 优惠券列表页
 */
include "ku/adfunction.php";
ControlRoot("adCoupon");
$sql="select * from coupon ".$_SESSION['adSearchcoupon']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "优惠券管理" => $ThisUrl,
);
echo head("ad").adheader($onion);
?>
<div class="minHeight">
    <div class="search">
        <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchcoupon";?>" method="post">
            优惠券金额：<input name="money" type="text" class="text textPrice" value="<?php echo $_SESSION['adSearchcoupon']['money'];?>">
            <input type="submit" value="模糊查询">
        </form>
    </div>
    <div class="search">
        <span onclick="$('[name=CouponForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
        <span onclick="$('[name=CouponForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
        <a href="<?php echo root."control/adCouponMx.php";?>"><span class="spanButton">新建优惠券</span></a>
        <span onclick="EditList('CouponForm','deleteCoupon')" class="spanButton">删除所选</span>
        <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
    </div>
    <!--查询结束-->
    <!--列表开始-->
    <form name="CouponForm">
        <table class="tableMany">
            <tr>
                <td></td>
                <td>ID</td>
                <td>优惠金额</td>
                <td>优惠条件</td>
                <td>开始时间</td>
                <td>结束时间</td>
                <td>优惠券张数</td>
                <td>创建时间</td>
                <td style="width:54px;">操作</td>
            </tr>
            <?php
            if($num > 0){
                while($adCoupon = mysql_fetch_array($query)){
                    echo "
                        <tr  {$trColor}>
                          <td><input name='couponList[]' type='checkbox' value='{$adCoupon['id']}'/></td>
                          <td>{$adCoupon['id']}</td>
                          <td>{$adCoupon['moeny']}</td>
                          <td>{$adCoupon['amountMoeny']}</td>
                          <td>{$adCoupon['starTime']}</td>
                          <td>{$adCoupon['endTime']}</td>
                          <td>{$adCoupon['num']}</td>
                          <td>{$adCoupon['time']}</td>
                          <td><a href='{$root}control/adCouponMx.php?id={$adCoupon['id']}'><span class='spanButton'>详情</span></a></td>
                        </tr>
                        ";
                }
            }else{
                echo "<tr><td colspan='12'>一张优惠券都没有</td></tr>";
            }?>
        </table>
    </form>
    <?php echo fenye($ThisUrl,7);?>
    <!--列表结束-->
</div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
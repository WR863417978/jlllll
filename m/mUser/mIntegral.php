<?php
include "../../library/mFunction.php";
echo head('m');
Integral::getIntegral($kehu['khid']);
$integral = Integral::$canUseTotal;
?>
    <!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root; ?>m/mUser/mUser.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的积分</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<div class="container mui-mbottom60 mui-ptopsmaple">
    <div class="integral-top">
        <dl>
            <dt>我的积分</dt>
            <dd>
                <label><?php echo $integral;?></label>
                <a href="<?php echo root; ?>m/mUser/mIntegraLog.php">明细</a>
            </dd>
        </dl>
    </div>
    <ul class="mui-mtop10 user-wrap-style1">
        <li>
            <a href="<?php echo root; ?>m/mIntegral.php" class="mui-dis-flex">
                <span class="flex1">积分商城</span>
                <label><span class="more">&#xe62e;</span></label>
            </a>
        </li>
        <li>
            <a href="<?php echo root; ?>m/mUser/mIntegralExchange.php" class="mui-dis-flex">
                <span class="flex1">兑换历史</span>
                <label><span class="more">&#xe62e;</span></label>
            </a>
        </li>
    </ul>
</div>
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
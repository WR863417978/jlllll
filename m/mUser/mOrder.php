<?php
include "../../library/mFunction.php";
echo head('m');
$browseTraces = browseTraces::index();      #最近浏览的商品
$kehuType = kehuTypeBuild($kehu['khid']);   #客户类型 及 升级按钮 build
Integral::getIntegral($kehu['khid']);       #获取积分信息
$integral = Integral::$canUseTotal;         #获取能够使用的积分
if( empty($kehu['shopImg']) ){
    $imgSrc = img('wOZ84129241GJ');
}else{
    $imgSrc = root . $kehu['shopImg'];
}
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:;" onclick='windowBack();' class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的订单</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-->
<div class="container">
    <div class="user mui-mbottom60 order-top mui-pt45">
        <div class="userCenter">
            <div class="userCenter_top userCenter_top2">
                <div class="usercenter-title3 order-title2 usercenter-title2">
                    <dl>
                        <dt class="mui-dis-flex">
                            <img src='<?php echo $imgSrc; ?>'>
                            <p class="flex1">
                                <label><span><?php echo $kehu['name'];?></span><i><?php echo $kehuType['type'];?>普通会员</i></label><br/>
                                <em>积分：<?php echo $integral;?></em>
                            </p>
                        </dt>
                        <?php echo $kehuType['toBeMember'];?>
                        <dd class="upgrade">
                            <a href="<?php echo root;?>m/mMemberBuy.php"><img src="<?php echo img('FOF87885852Uf');?>"/></a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <!--会员中心横向导航-->
        <dl class="user-nav user-nav2 mui-dis-flex order-nav">
            <dd><a href='mAllOrder.php'><i><img src="<?php echo img('jCs87954458Mt');?>"/></i><br/><span>全部订单</span></a></dd>
            <dd><a href='mAllOrder.php?type=noPay'><i><img src="<?php echo img('kRG87954688rz');?>"/></i><br/><span>待付款</span></a></dd>
            <dd><a href="mAllOrder.php?type=tosend"><i><img src="<?php echo img('jCs87954458Mi');?>"/></i><br/><span>待发货</span></a></dd>
            <dd><a href='mAllOrder.php?type=hasSend'><i><img src="<?php echo img('oJd87954715uP');?>"/></i><br/><span>待收货</span></a></dd>
            <dd><a href='mAllOrder.php?type=waitTalk'><i><img src="<?php echo img('exW87954738xe');?>"/></i><br/><span>待评价</span></a></dd>
            <dd><a><i><img src="<?php echo img('SMB87954758bX');?>"/></i><br/><span>售后/投诉</span></a></dd>
        </dl>
        <!--//-->
        <!--我的足迹-->
        <div class="product">
            <!--类别-->
            <div class="key_title">我的足迹</div>
            <ul class="product-lists mui-dis-flex">
            <?php echo $browseTraces;?>
                <!-- <li>
                    <a>
                        <img src='<?php //echo img('wOZ84129241GJ'); ?>'>
                        <p class="nameSpc">【旗舰店正品】美肤宝茶爽冰膜面膜贴保湿补水晒后修复舒缓肌肤</p>
                        <p class="textSale">
                            <em class="text-price">￥89.00</em>
                            <em class="text-sale">销量:60</em>
                        </p>
                    </a>
                </li>
                <li>
                    <a>
                        <img src='<?php //echo img('wOZ84129241GJ'); ?>'>
                        <p class="nameSpc">【旗舰店正品】美肤宝茶爽冰膜面膜贴保湿补水晒后修复舒缓肌肤</p>
                        <p class="textSale">
                            <em class="text-price">￥89.00</em>
                            <em class="text-sale">销量:60</em>
                        </p>
                    </a>
                </li> -->
            </ul>
        </div>
        <!--//-->
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
})
</script>
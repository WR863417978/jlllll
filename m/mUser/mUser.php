<?php
include "../../library/mFunction.php";
echo head('m');

$kehuTpeArr = kehuTypeBuild($kehu['type']);
$dataArr = mUser::index($kehu['khid']); #array 底部数据统计
if( !empty($kehu['shopImg']) ){
    $imgSrc = root . $kehu['shopImg'];
}else{
    if( $kehu['wxSex'] == '男' ){
        $imgSrc = imgt('replaceMan');
    }else if( $kehu['wxSex'] == '女' ){
        $imgSrc = imgt('replaceWoman');    
    }else {
        $imgSrc = imgt('replaceMan');
    }
}

?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"><a href="javascript:;" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">会员信息</p>
        </div>
        <a href="" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-->
<div class="container">
    <div class="user mui-pt45 mui-mbottom60">
        <div class="userCenter">
            <div class="userCenter_top">
                <div class="usercenter-title">
                    <dl>
                        <dt class="mui-dis-flex">
                            <img  src="<?php echo $imgSrc; ?>"/>
                            <p class="flex1 pl10">
                                <label><!-- <span><?php //echo $kehu['shopName'];?></span> --><i><?php echo $kehuTpeArr['type'];?></i></label>
                                <em>唯一ID：<?php echo $kehu['khid'];?></em>
                            </p>
                        </dt>
                        <dd><em>手机号</em><span><?php echo phoneBuild($kehu['tel']);?></span></dd>
                        <dd><em>邀请人</em><span><?php echo $kehu['shareId'];?></span></dd>
                    </dl>
                </div>
                <?php echo $kehuTpeArr['toBeMember'];?>
                <!--  <p class="upgrade toBeMember" data-key=''>
                    <a><img src="<?php echo imgt('FOF87885852Uf');?>"/></a>
                </p> -->
            </div>
        </div>
        <!--会员中心横向导航-->
        <dl class="user-nav mui-dis-flex">
            <dd><a href="<?php echo root;?>m/mUser/mInfo.php"><img src="<?php echo imgt('nLc87872724IU');?>"/><br/><span>我的资料</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mRevenue.php"><img src="<?php echo imgt('TPG87873169iP');?>"/><br/><span>我的钱包</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mOrder.php"><img src="<?php echo imgt('kQg87873191bX');?>"/><br/><span>订单</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mBuyCar.php"><img src="<?php echo imgt('IUF87873235JL');?>"/><br/><span>订货单</span></a></dd>
            <dd><a href="<?php echo root."m/mUser/mShare.php?shareId={$kehu['khid']}"?>"><img src="<?php echo imgt('HVZ87873256tG');?>"/><br/><span>我的分享</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mAddress.php"><img src="<?php echo imgt('opN87873283GT');?>"/><br/><span>收货地址</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mCoupons.php"><img src="<?php echo imgt('gbg87873307ju');?>"/><br/><span>优惠券</span></a></dd>
            <dd><a href="<?php echo root;?>m/mUser/mIntegral.php"><img src="<?php echo imgt('tNC87873330qW');?>"/><br/><span>我的积分</span></a></dd>
        </dl>
        <!--//-->
        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="javascript:;" class="mui-dis-flex">
                    <span class="flex1">本周加入<em class="user-btn" name='invite-btn'><span>邀请好友</span></em></span>
                    <span><i class="hui"><?php echo $dataArr['thisWeek'];?>人</i></span><span class="more invite-btn">&#xe62e;</span>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="mui-dis-flex">
                    <span class="flex1">新增收入</span>
                    <span><i class="hui"><?php echo $dataArr['todayFree'];?>元</i></span><span class="more">&#xe62e;</span>
                </a>
            </li>
        </ul>

        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="javascript:;" class="mui-dis-flex">
                    <span class="flex1">总收入</span>
                    <span><i class="hui"><?php echo $dataArr['totalFree'];?>元</i></span><span class="more"></span>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="mui-dis-flex">
                    <span class="flex1">邀请人数</span>
                    <span><i class="hui"><?php echo $dataArr['total'];?>人</i></span><span class="more"></span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
    $('.header-return').click(function(){
        window.history.back(-1);
    });
    $('.toBeMember').on('click',function(){
        var $this = $(this)
            key = $this.data('key');
        location.href = root + 'm/mMemberBuy.php?type=' + key ;
    });
    $('.show-this').click(function(){
       location.href = root + "m/mUser/mRevenue.php"; 
    });
    //邀请
    $("[name='invite-btn']").on('click',function(){
       location.href = root + "m/mUser/mCode.php"; 
    });
})
</script>
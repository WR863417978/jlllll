<?php
include "../library/mFunction.php";
//isRegister();#判断是否注册
echo head('m');
$khType =  $kehu['type'];#客户等级类型
$norData = findOne('img',"id = 'myh84324058lV'");   #普通会员数据
$vipData = findOne('img',"id = 'pah84324212yj'");   #高级会员数据
/* $norDataHtml = explode('、',$norData['text']);
$vipDataHtml = explode('、',$norData['text']); */
#会员轮播图
$memberImg = memberBannerBuild();
#普通、VIP价格数据
$norMem = explode( '、' , para('normalMember') );
$vipMem = explode( '、' , para('vipMember') );

#会员优惠劵列表
$result = findAll('coupon',"goodsId = '会员优惠劵' AND starTime <= '$time' AND endTime >= '$time' ORDER BY endTime DESC");
//echo "SELECT * FROM coupon WHERE goodsId = '会员优惠劵' AND starTime <= '$time' AND endTime <= '$time' ORDER BY endTime DESC";
if( $result )
{
    foreach ($result as $key => $val)
    {
        $html .= "
        <div name='coupons' class='stamp stamp04' data-key='{$val['id']}'>
            <div class='par'><sub class='sign'>￥</sub><span>".floatval($val['moeny'])."</span><p>订单满".floatval($val['amountMoeny'])."元</p></div>
            <div class='copy'><a href='javascript:;'>领取</a></div>
            <i></i>
        </div>";
    }
}
?>
<body style="background: #fff">
<!--头部-->
<div class="header header-fixed">
    <div class="nesting">
        <a href="javascript:;" class="header-btn header-return" onclick="window.history.back(-1);"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">聚礼会员</p>
        </div>
        <a href="#" class="header-btn header-login"></a>
    </div>
</div>
<!--//-->
<div class="container mui-mbottom60 mui-pt45">
    <div class="open-user">
       <!--  <label class="mui-dis-flex"><span class="flex1">成为会员获得收益</span><a class='toBeMember'>立即开通</a></label> -->
       <a href="<?php echo root;?>m/mMemberBuy.php"> <img src="<?php echo img('sZZ87891883GX')?>"/></a>
    </div>
    <!--轮播-->
    <div id="slideBox" class="slideBox">
        <div class="swiper-wrapper">
            <?php echo $memberImg;?>
            <!-- <div class='swiper-slide'>
                <a href=''><img src="<?php echo img('wOZ84129241GJ');?>"/></a>
            </div> -->
        </div>
        <div class="swiper-pagination"> </div>
    </div>
    <!--//-->
    <!--领劵-->
   <div class="coupons">
        <?php echo $html;?>
        <!-- <div name='coupons' class="stamp stamp04">
            <div class="par"><sub class="sign">￥</sub><span>50</span><sub>优惠券</sub><p>订单满100元</p></div>
            <div class="copy"><p>2015-08-13<br>2016-08-13</p><a href="#">领取</a></div>
            <i></i>
        </div> -->
    </div>
    <!--//-->
    <!--广告区-->
    <div class="team-lists">
        <dl>
            <dd><img src="<?php echo imgt('dWs87953787qf');?>" /></dd>
        </dl>
    </div>
    <!--//-->
    <!--产品列表-->
    <div class="product">
        <!--类别-->
        <div class="key_title">获赠商品</div>
        <ul class="product-lists product-lists mui-dis-flex">
            <li class='memberType memberType2' data-key='nor'>
                <a>
                    <img src="<?php echo root,$norData['src'];?>"/>
                    <p class="nameSpc"><?php echo $norData['name'];?></p>
                    <p class="textSale">
                        <em class="text-price">￥<?php echo $norMem['0'];?></em>
                    </p>
                    <span>普通会员</span>
                </a>
            </li>
            <li class='memberType memberType2' data-key='vip'>
                <a>
                    <img src="<?php echo root,$vipData['src'];?>"/>
                    <p class="nameSpc"><?php echo $vipData['name'];?></p>
                    <p class="textSale">
                        <em class="text-price">￥<?php echo $vipMem['0'];?></em>
                    </p>
                    <span>高级会员</span>
                </a>
            </li>
            <!-- <li>
                <a>
                    <img src="<?php echo img('wOZ84129241GJ');?>"/>
                    <p class="nameSpc">【旗舰店正品】美肤宝茶爽冰膜面膜贴保湿补水晒后修复舒缓肌肤</p>
                    <p class="textSale">
                        <em class="text-price">￥89.00</em>
                        <em class="text-sale">销量:60</em>
                    </p>
                </a>
            </li> -->
        </ul>
        <!--//-->
    </div>
<?php echo footerLine();?>
</div>
<!--回到顶部-->
<a href="javascript:;" title="回到顶部" id="gotop-btn"><img src="<?php echo img('dyf84130064pc');?>" /></a>
<!--//-->

</div>
<!--底部-->
<div id='member-pro' class="cover hide">
    <div class="cover-con member">
        <dl class="member">
            <dt name='closePrpo' onclick="$('#member-pro').hide();">
                <span>X</span>
            </dt>
            <dd><img src="p4BE31500E41507456836665-1508747789/images/smile.png"></dd>
            <dd>亲，成为会员即可领取</dd>
            <dd><input id='memberClick' type="button" value="成为会员"/></dd>
        </dl>
    </div>
</div>
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
$(function() {
    //关闭弹窗
    /****导航栏变色***/
    changeNav();
    /***菜单显隐****/
    nav();
    /****首页轮播****/
    window.addEventListener("load", function(e) {
        // 首页轮播图
        var swiperObj = new Swiper('#slideBox', {
            autoplay: 2500,
            autoplayDisableOnInteraction: false,
            loop: true,
            pagination: '.swiper-pagination',
        });
    }, false);
});
/****回到顶部****/
window.onload = function() {
    var gotop_btn = document.getElementById("gotop-btn"); //获取回到顶部按钮ID
    var clientHeight = document.documentElement.client; //获取可视区域的高度
    var timer = null; //定义一个定时器
    var isTop = true; //定义一个布尔值，判断是否到达顶部

    window.onscroll = function() { //滚动事件
        //获取滚动条的滚动高度
        var osTop = document.documentElement.scrollTop || document.body.scrollTop;

        //判断回到顶部按钮的显示与隐藏
        if(osTop > 0) {
            gotop_btn.style.display = "block";
        } else {
            gotop_btn.style.display = "none";
        }

        //主要用于判断当 点击回到顶部按钮后 滚动条在回滚过程中，若手动滚动滚动条，则清除定时器
        if(!isTop) {
            clearInterval(timer);
        }
        isTop = false;
    }

    //回到顶部点击事件
    gotop_btn.onclick = function() {
        //设置一个定时器
        timer = setInterval(function() {
            //获取滚动条的高度
            var osTop = document.documentElement.scrollTop || document.body.scrollTop;
            //用于设置速度差 用于产生缓存效果
            var speed = Math.floor(-osTop / 8);
            document.documentElement.scrollTop = document.body.scrollTop = osTop + speed;
            isTop = true; //用于阻止滚动事件清除定时器
            if(osTop == 0) {
                clearInterval(timer);
            }
        }, 30);
    }

    //领取优惠劵
    $("[name='coupons']").on('click',function(){
        var khType = '<?php echo $khType;?>';
        var khTel = '<?php echo $kehu['tel'];?>'
        var khid = '<?php echo $kehu['khid'];?>';
        var id = $(this).data('key');
        if( $.trim(khTel).length == 0 ){
            location.href = root + "m/mRegister.php";   
        }else if( $.trim(khType).length == 0 ){
            //mwarn('亲,成为会员即可领取');
            $('#member-pro').show();
            $('#memberClick').click(function(){
                location.href = root + 'm/mMemberBuy.php';
            });
        }else{
            console.log(1111);
            $.post(root+"library/mData.php?type=getMemberCoupon",{khid:khid,id:id},function(data){
                if(data.warn == 2){
                    mwarn('领取成功');
                }else{
                    mwarn(data.warn);
                }
            },'json');
        }
    });
    //成为会员
    $('.toBeMember').on('click',function(){
        var khType = '<?php echo $khType;?>';
        if( $.trim(khType).length == 0 ){
            //mwarn('亲,成为会员即可领取');
            location.href = root + 'm/mMemberBuy.php';
        }
    });
    //点击下方商品跳转
    $('.memberType').on('click',function(){
        var khTel = '<?php echo $kehu['tel'];?>'
        var $this = $(this)
            key = $this.data('key');
        if( $.trim(khTel).length == 0 ){
            location.href = root + 'm/mRegister.php';  
        }else if( key == 'nor' ){
            location.href = root + "m/mMemberBuy.php?type=nor";
        }else if( key == 'vip' ){
            location.href = root + "m/mMemberBuy.php?type=vip";
        }
    })
};
</script>
    </body>
</html>
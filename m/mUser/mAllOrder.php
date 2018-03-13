<?php
include "../../library/mFunction.php";
echo head('m');
$type = $get['type'];
switch ($type) {
    case 'tosend':  //待发货
        $orderHtml = UserOrder::tosend($kehu['khid']);
        break;
    case 'noPay':  //代付款
        $orderHtml = UserOrder::dontPay($kehu['khid']);
        break;
    case 'hasSend':
        $orderHtml = UserOrder::hasSend($kehu['khid']);
        break;
    case 'waitTalk':
        $orderHtml = UserOrder::waitTalk($kehu['khid']);
        break;
    default:
        $orderHtml = UserOrder::allOrder($kehu['khid']);
        break;
}

?>
<body>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">全部订单</p>
        </div>
        <a href="javascript:;" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--订单管理-->

<div class="container">
    <div class="mui-mbottom60 order-list mui-ptopsmaple">
        <div class="order-meun">
            <ul class="mui-dis-flex">
                <li class="<?php echo myMenuGet('type',['all',''],'nav-order-on');?>"><a href="mAllOrder.php?type=all">全部</a></li>
                <li class="<?php echo myMenuGet('type','noPay','nav-order-on');?>"><a href="mAllOrder.php?type=noPay">待付款</a></li>
                <li class="<?php echo myMenuGet('type','tosend','nav-order-on');?>"><a href="mAllOrder.php?type=tosend">待发货</a></li>
                <li class="<?php echo myMenuGet('type','hasSend','nav-order-on');?>"><a href="mAllOrder.php?type=hasSend">待收货</a></li>
                <li class="<?php echo myMenuGet('type','waitTalk','nav-order-on');?>"><a href="mAllOrder.php?type=waitTalk">待评价</a></li>
            </ul>
        </div>
        <style>
            .order2 dt img{width: 80px;height: 80px;border:1px solid #f0f0f0;margin: 5px 3px;}
            .order2 dt{margin: 10px;background: #fd65821c;}
            .order2 dd em{text-align: right;display: inline-block;width: 100%;padding-right: 10px;padding-top:10px;}
            .order2 dd:last-of-type{border-top:1px solid #f0f0f0;}
            .order2 dd em input{background: none !important;}
        </style>
        <div class="order2">
            <?php echo $orderHtml['html'];?>
        </div>
    </div>
</div>
<form id='payForm' action='<?php echo root;?>pay/wxpay/wxpay.php' method='post'>
    <input type='hidden' name='orderType' value='购物车'>
    <input type='hidden' name='url' value='<?php echo root."mUser/mAllOrder.php"?>'>
    <input type='hidden' name='bid' value=''>
    <input type='hidden' name='pid' value=''>
    <input type='hidden' name='orderId' value=''>
    <input type='hidden' name='type' value=''>
    <input type='hidden' name='money' value=''>
</form>
<!--//-->
<!--底部-->
<?php echo mFooter();echo mWarn(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
    //待评价
    $('.wait-talk').on('click',function(){
//        var id = $(this).data('gid');
//        var bid = $(this).data('bid');
//        location.href =  "<?php //echo root;?>//m/mUser/mOrderAppraise.php?gid=" + id + "&bid=" + bid;
        mwarn('完善中');
    });
    //再次购买
    $('.buyAgain').on('click',function(){
//        var gid = $(this).data('gid');
//        location.href = "<?php //echo root;?>//m/mGoodsMx.php?gid=" + gid;
        mwarn('完善中');
    });
    //去支付
//    $('.goPay').on('click',function(){
////        var bid = $(this).data('bid');
////        location.href =  "<?php ////echo root;?>////m/mEditOrder.php?type=one&bid=" + bid;
//        mwarn('完善中');
//    });
    //去评价
    $('.jumpBtn').on('click',function(){
        var $this = $(this)
            pid = $this.data('pid');
        if( $this.attr('name') == 'goPay' ){
            console.log(1);
        }else{
            location.href =  "<?php echo root;?>m/mUser/mOrderMx.php?pid=" + pid;
        }
    });
    //去支付
    $("[name='goPay']").on('click',function(){
        var $this = $(this)
        var pid = $this.data('pid')
        var khid = '<?php echo $kehu['khid'];?>';

        $.post("<?php echo root;?>library/mData.php?type=toGoPay",{pid:pid,khid:khid},function(data){
            if(data.warn == 2){
                $("#payForm [name='money']").val( data.money );
                $("#payForm [name='orderId']").val( pid );
                $("#payForm").submit();
            }else{
                mwarn(data.warn);
            }
        },'json');


//        mwarn('完善中');


    });
    //再次购买
    $("[name='buyAgain']").on('click',function(){
//        var pid = $(this).data('pid');
//        $.post("<?php //echo root;?>//library/mData.php?type=buyAgain",{pid:pid},function(data){
//            if(data.warn == 2){
//                location.href = data.href;
//            }else{
//                mwarn(data.warn);
//            }
//        },'json');
        mwarn('完善中');
    });
    //查看物流
    $("[name='showLogistics']").click(function(){
//       var bid = $(this).data('bid') ;
//       location.href =  '<?php //echo root;?>//m/mUser/mTrack.php?bid=' + bid;
        mwarn('完善中');
    });
    //评价=>去评价
    $("[name='toTalk']").click(function(){
        var gid,bid;
        gid = $(this).data('gid');
        bid = $(this).data('bid');
        location.href =  "<?php echo root;?>m/mUser/mOrderAppraise.php?gid=" + gid + '&bid=' + bid;
    });
    //申请退款/退货
    $("[name='applyback']").click(function () {
        var order_sn = $(this).data('pid');
        $.post("<?php echo root;?>library/mData.php?type=applyBackGoods",{order_sn:order_sn},function(data){
            if(data.warn == 2){
                mwarn(data.msg);
                setTimeout(function () {
                    location.reload();
                },1000)
            }else{
                mwarn(data.warn);
            }
        },'json');

    });

    $("[name='onlyToShow']").click(function () {
        mwarn('请等待退款');
    });
    $("[name='toWriteExpress']").click(function () {
        mwarn('暂无页面');
    });
    $("[name='waitAgree']").click(function () {
        mwarn('请等待商城同意退货');
    });
})
</script>
<?php
include "../library/mFunction.php";
//$_SESSION['khid'] = '3072317051';
$_SESSION['khid'] = '1062952937';
$khid = '1062952937';
header("location:{$root}m/mUser/mUser.php");
exit();
echo head('m'); 
echo head('m');
$type 	= $get['type'];	#one all
$bid 	= $get['bid'];	#buyCarId
$pid 	= $get['pid'];	#payId
/* $addressData    = findOne('address',"id = '{$kehu['address']}'");					#查找
$region         = myRegion($addressData['regionId']);								#地址格式生成 */

$regionArr 		= buyCarAddressShow();													#地址展示
$orderList      = mBuyCar::orderList($ehu['khid'],$type,$bid,$pid);						#购物车列表
$buyCarFree		= mBuyCar::getBuyCarFree($kehu['khid'],$type,$bid,$pid);					#获取购物车总价
/* if( $type == 'one' ){
	$dataArr = findOne('buyCar',"id = '$bid'");
}else if( $type == 'all' ){
	$dataArr = findOne('buyCar',"khid = '{$kehu['khid']}' AND type = '普通订单' AND workFlow = '已选定' ORDER BY id DESC");
} */


//$couponData = GoodsCoupon::getMaxCoupon($kehu['khid']);		#获取优惠劵信息
$_SESSION['totalPrice'] = $buyCarFree['totalPrice'];
$couponInfo = GoodsCoupon::getCouponMoney();

if( $couponInfo['0']['couponNum'] > 0 ){
	$couponHtml = "<li class='useCoupon'><span>优惠劵</span><label>可用<i class='more'>&#xe62e;</i></label></li>";
}else{
	$couponHtml = "<li class='useCoupon'><span>优惠劵</span><label>无可用<i class='more'>&#xe62e;</i></label></li>";
}
#费用
	#优惠劵费用
if( !empty($_SESSION['coupon']['money']) ){
	console_log(11);
	$couponData['money'] = $_SESSION['coupon']['money'];
	$couponData['couponId'] = $_SESSION['coupon']['couponId'];
}else{
	console_log(22);
	$couponData['money'] = $couponInfo['0']['moeny'];
	$couponData['couponId'] = $couponInfo['0']['couponId'];
}
#优惠劵数据处理
if( empty($couponData['money']) ){
	$couponData['money'] = 0;
}
?>
<form id='payForm' action="<?php echo root,'pay/wxpay/pay/alipaywap/alipayapi.php';?>" method='post'>
    <div class="buycart-ctrl  mui-wrap-style1 mui-sheet mui-fixed">
        <div class="shop-total mui-dis-flex">
            <p class="flex1 buycar-btn">
                <em>实付款：￥<span class="shop-total-amount ShopTotal"><?php echo ( $buyCarFree['totalPrice'] - $couponData['money'] + $orderList['taxFree'] + $orderList['shippingFree']);?></span></em>
                <a href="javascript:;" class="settlement">提交订单</a>
            </p>
        </div>
    </div>
    <input type="hidden" name="orderType" value='购物车'>
    <input type="hidden" name="orderId" id="orderId">
    <input type="hidden" name='type' value='<?php echo $get['type'];?>'>
    <input type="hidden" name="url" value='<?php root.'m/mUser/mAllOrder.php';?>'>
    <input type="hidden" name="money">
    <input type="hidden" name="coupon">
    <input type="hidden" name="couponId">
    <input type="hidden" name="bid" value='<?php echo $get['bid'];?>'>
    <input type="hidden" name='userPayType' value='<?php echo $_SESSION['buyCar']['payType'];?>'>
</form>
<form name="checkFrom">
    <input type="hidden" name="total" value='<?php echo $buyCarFree['totalPrice'];?>'>
    <input type="hidden" name="taxFree" value='<?php echo $orderList['taxFree']?>'>
    <input type="hidden" name="shippingFree" value='<?php echo $orderList['shippingFree']?>'>
    <input type="hidden" name="coupon" value='<?php echo $couponData['money'];?>'>
    <input type="hidden" name="bid" value='<?php echo $get['bid'];?>'>
    <input type="hidden" name="type" value='<?php echo $get['type'];?>'>
    <input type="hidden" name='userPayType' value='<?php echo $_SESSION['buyCar']['payType'];?>'>
    <input type="hidden" name="couponId" value='<?php echo $couponData['couponId'];?>'>
</form>
<input type="button" value='支付宝' class='settlement'>
<?php echo mWarn();?>
<script>
$('.settlement').click(function(){
    var payType,invoice;
    payType = '<?php echo $dataArr['delivery'];?>';
    invoice = '<?php echo $dataArr['companyName'];?>';
    
    $.post(root+"library/mData.php?type=subPayForm",$("[name='checkFrom']").serialize(),function(data){
        if(data.warn == 2){
            if( data.href ){
                location.href = data.href;	
            }else{
                $("#payForm [name='money']").val( data.data.total );
                $("#payForm [name='coupon']").val( data.data.coupon );
                $("#payForm [name='couponId']").val( data.data.couponId );
                $("#payForm [name='orderId']").val( data.data.byId );
                $("#payForm [name='type']").val( data.data.type );
                $("#payForm").submit();
            }
        }else{
            mwarn(data.warn);
        }
    },'json');
});
</script>
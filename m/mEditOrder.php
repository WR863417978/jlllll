<?php
include "../library/mFunction.php";
echo head('m');
$type 	= $get['type'];	#one all
$bid 	= $get['bid'];	#buyCarId
$pid 	= $get['pid'];	#payId
$oldUrl = http_build_query($get);
$root = root;
/* $addressData    = findOne('address',"id = '{$kehu['address']}'");					#查找
$region         = myRegion($addressData['regionId']);								#地址格式生成 */

$regionArr 		= buyCarAddressShow();													#地址展示
//print_r($regionArr);
$orderList      = mBuyCar::orderList($ehu['khid'],$type,$bid,$pid);						#购物车列表

$buyCarFree		= mBuyCar::getBuyCarFree($kehu['khid'],$type,$bid,$pid);					#获取购物车总价
/* if( $type == 'one' ){
	$dataArr = findOne('buyCar',"id = '$bid'");
}else if( $type == 'all' ){
	$dataArr = findOne('buyCar',"khid = '{$kehu['khid']}' AND type = '普通订单' AND workFlow = '已选定' ORDER BY id DESC");
} */


//$couponData = GoodsCoupon::getMaxCoupon($kehu['khid']);		#获取优惠劵信息
$_SESSION['totalPrice'] = $buyCarFree['totalPrice'];
//$couponInfo = GoodsCoupon::getCouponMoney();
$couponInfo['0']['couponNum'] = 0;
if( $couponInfo['0']['couponNum'] > 0 ){
	$couponHtml = "<li class='useCoupon'><span>优惠劵</span><label>有可用优惠券<i class='more'>&#xe62e;</i></label></li>";
}else{
	$couponHtml = "<li class='useCoupon'><span>优惠劵</span><label>无可用优惠券<i class='more'>&#xe62e;</i></label></li>";
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
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">填写订单</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<div class="container mui-mbottom60 mui-ptopsmaple">
	<div class="orderMx">
		<!--收货地址-->
        <?php
        if(empty($regionArr['name'])){
            echo <<<EOF
            <dl>
                <a  class="mui-dis-flex" href="{$root}m/mUser/mAddress.php?{$oldUrl}">
				<dd class="flex1 order-address-box">
					<p><label> 请添加收获地址</label><span></span></p>
					<p><i class="address-ico">&#xe7ca;</i>无地址</p>
				</dd>
				<dt class="address-more"><i class="more">&#xe62e;</i></dt>
                </a>
			</dl>
EOF;
        }else{
            echo <<<EOF
            <dl>
                <a  class="mui-dis-flex" href="{$root}m/mUser/mAddress.php?{$oldUrl}">
				<dd class="flex1 order-address-box">
					<p><label> {$regionArr["name"]}</label><span>{$regionArr['tel']}</span></p>
					<p><i class="address-ico">&#xe7ca;</i>{$regionArr['region']}</p>
				</dd>
				<dt class="address-more"><i class="more">&#xe62e;</i></dt>
                </a>
			</dl>
EOF;

        }
        ?>
		<!--//-->
		<!--订单商品明细-->
		<div class="order-goods">
            <?php echo $orderList['html'];?>
			<!-- <div class="order-goods-mx">
				<img src="<?php echo img('wOZ84129241GJ');?>"/>
				<p>描述描述描述描述描述描述描述描述<br/><span>订量：50</span></p>
			</div> -->
			<p class="pay-order">
				<a href="<?php echo root,"m/mPay.php?type=".$get['type']."&bid=".$get['bid'];?>">
					<span>支付配送</span>
					<label class="mui-dis-flex">
						<em class="flex1">
							<span><?php echo $_SESSION['buyCar']['payType'];?><!-- 在线支付 --></span><br />
							<span><?php echo $_SESSION['buyCar']['logistiscName'];?><!-- 物流快递 --></span>
						</em>
						<i class="more">&#xe62e;</i>
					</label>
				</a>
			</p>
			<ul class="order-goods-price">
				<li class='mInvoice'><span>发票</span><label><?php echo $_SESSION['buyCar']['companyName'];?><i class="more">&#xe62e;</i></label></li>
				<!-- <li><span>优惠劵</span><label>无可用<i class="more">&#xe62e;</i></label></li> -->
				<?php echo $couponHtml;?>
			</ul>
			
			<ul class="order-goods-price">
				<li><span>商品金额</span><label class="mui-red">￥<?php echo $buyCarFree['totalPrice']?></label></li>
				<li><span>优惠劵</span><label class="mui-red">￥<?php echo $couponData['money'];?></label></li>				
				<li><span>发票税点</span><label class="mui-red">￥<?php echo $orderList['taxFree'];?></label></li>
				<li><span>运费物流</span><label class="mui-red">￥<?php echo $orderList['shippingFree'];?></label></li>
			</ul>
		</div>
		<!--//-->
	</div>
	<!-- 店铺合计 -->
	<form id='payForm' action="<?php echo root,'pay/wxpay/wxpay.php';?>" method='post'>
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
</form> 
</div>
<?php echo mWarn();?>
<script>
	window.onpageshow = function(event){
		if (event.persisted) {
			window.location.reload();
		}
	}
	//发票
	$('.mInvoice').click(function(){
		var type,bid;
		type 	= '<?php echo $get['type'];?>';
		bid 	= '<?php echo $get['bid'];?>';		
		location.href = "<?php echo root; ?>" + "m/mInvoice.php?type=" + type + "&bid=" + bid;
	});
	//优惠劵
	$('.useCoupon').click(function(){
		location.href =  "<?php echo root; ?>" + "m/mUser/mCoupons.php?type=buyCar";
	});
	//提交订单
	$('.settlement').click(function(){
		var payType,invoice;
		payType = '<?php echo $dataArr['delivery'];?>';
		invoice = '<?php echo $dataArr['companyName'];?>';
		
		$.post( "<?php echo root; ?>"+"library/mData.php?type=subPayForm",$("[name='checkFrom']").serialize(),function(data){
			if(data.warn == 2){
				if( data.href ){
					location.href = data.href;
				}else{
					$("#payForm [name='money']").val( data.data.total );
					$("#payForm [name='coupon']").val( data.data.coupon );
					$("#payForm [name='couponId']").val( data.data.couponId );
					$("#payForm [name='orderId']").val( data.data.byId );
					$("#payForm [name='type']").val( data.data.type );
					console.log($("#payForm [name='type']"));
					$("#payForm").submit();
				}
//                location.href = "<?php //echo root; ?>//"+"library/mData.php
			}else{
				mwarn(data.warn);
			}
		},'json');
	});
</script>
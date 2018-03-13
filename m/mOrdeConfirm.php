<?php
include "../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="#" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">确认订单</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>

<!--//-->
<!-- 一个店铺 -->
<div class="container mui-ptopsmaple mb180">
	<div class="user">
		<div class="order-address-box">
			<a>
			<dl>
				<dd><img src="<?php echo img('kFq84156316pg');?>"/></dd>
				<dt class="mui-dis-flex">
					<i class="address-ico">&#xe627;</i>
					<label>
						<em><span>张三</span> <span>18999999999</span></em><br/>
						<em>重庆市南岸区国际社区2栋2-1</em>
					</label>
					<span class="more">&#xe62e;</span>
				</dt>
				<dd><img src="<?php echo img('kFq84156316pg');?>"/></dd>
			</dl>
			</a>
		</div>
		<ul class="mui-mtop10 user-wrap-style1">
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">账户余额</span>
					<label>￥666.12</label>
				</a>
			</li>
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">点单金额</span>
					<span>￥12.12</span>
				</a>
			</li>
		</ul>
		<ul class="mui-mtop10 user-wrap-style1">
			
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">配送方式</span>
					<label><em>顺丰速运</em><span class="more">&#xe62e;</span></label>
				</a>
			</li>
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">物流费用</span>
					<label>￥0.00</label>
				</a>
			</li>
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">支付方式</span>
					<label><i>微信支付</i></label>
				</a>
			</li>
		</ul>
		<ul class="mui-mtop10 user-wrap-style1">
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">替他人下单</span>
					<label><input type="text" placeholder="请输入需要下单的会员的独立ID"/></label>
				</a>
			</li>
		</ul>
		<ul class="mui-mtop10 user-wrap-style1">
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">发票信息 ： 重庆雨木科技有限公司 12345679897946</span>
					<label><span class="more">&#xe62e;</span></label>
				</a>
			</li>
		</ul>
		<ul class="mui-mtop10 user-wrap-style1">
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">可用代金券</span>
					<label>2张</label>
				</a>
			</li>
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">抵扣金额</span>
					<label>￥10</label>
				</a>
			</li>
			<li>
				<a href="#" class="mui-dis-flex">
					<span class="flex1">可获得积分</span>
					<label>30</label>
				</a>
			</li>
		</ul>
	</div>
	<!-- 订单合计 -->
	<div class="buycart-ctrl  mui-wrap-style1 mui-sheet mui-fixed">
	    <div class="shop-total mui-dis-flex">
	    	<em>还需支付：￥<span class="shop-total-amount ShopTotal">99.99</span></em>
			<p class="flex1 buycar-btn">
				<a href="javascript:;" class="settlement">提交订单</a>
			</p>
		</div>
	</div>
</div>

<script type="text/javascript" src="js/jquery-2.0.3.min.js" ></script>
</body>

</html>
<?php
include "../library/mFunction.php";
echo head('m');
$listHtml = Integral::index();
?>
	<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">积分商城</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--..-->
<div class="container mui-mbottom60">
	<!--产品列表-->
	<div class="product mui-ptopsmaple">
		<!--类别-->
		<ul class="product-lists mui-dis-flex">
        <?php echo $listHtml;?>
			<!-- <li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li>
			<li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li>
			<li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li>
			<li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li>
			<li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li>
			<li>
				<a>
					<img src="<?php echo img('wOZ84129241GJ');?>"/>
					<p class="nameSpc">【旗舰店正品】充电宝</p>
					<p class="textSale">
						<label>所需积分：<em class="text-price">￥89.00</em></label> 
						<a class="text-sale integral-btn">兑换</a> 
					</p>
					<p class="textSale">
						<label>已兑换：<em>22</em>|</label> 
						<label>剩余：<span>99</span></label> 
					</p>
				</a>
			</li> -->
		</ul>
	</div>
</div>
<!--兑换弹窗-->
<div class="cover">
	<div class="cover-con integral-cover">
		<h3 class="mui-dis-flex"><label>兑换详情</label><img src="<?php echo img('Qsj84143043aZ');?>" class="close"/></h3>
		<div class="order">
			<div class="order-lists">
				<!--<h2 class="mui-dis-flex"><span class="flex1">订单号：12345678798</span></h2>-->
				<dl>
					<dd><img src="<?php echo img('wOZ84129241GJ');?>"/></dd>
					<dd class="info">
						<p> 绿侬寿山石桶珠藏式隔珠腰珠顶珠散珠子手链手串DIY星月菩提配饰</p>
						<p><span>所需积分：136</span></p>
					</dd>
					<dd></dd>
				</dl>
			</div>
		</div>
		<div class="order-address-box">
			<a>
			<dl>
				<dd><img src="<?php echo img('kFq84156316pg');?>"/></dd>
				<dt class="mui-dis-flex">
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
		<input type="button" class="addPassenger_btn" value="确认兑换"/>
	</div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter();?>
<!--//-->
<script type="text/javascript" src="js/jquery-2.0.3.min.js" ></script>
<script>
$(function(){
	$(".integral-btn").on("click",function(){
		//$(".cover").show();
	});
	$(".close").on("click",function(){
		$(".cover").hide();
	});
	$('.integral-detail').on('click',function(){
		var _this = $(this);
		var id = _this.data('id');
		var skid = _this.data('skid')
		location.href = root + "m/mIntegralMx.php?gid=" + id + '&skid=' + skid;
	});
});
</script>
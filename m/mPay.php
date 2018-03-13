<?php
include "../library/mFunction.php";
echo head('m');
if( empty($kehu['bankName']) || empty($kehu['bankUserName']) ){
	$bankArr = FALSE;
}else{
	$bankArr = TRUE;
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" onclick='windowBack();' class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">选择支付配送方式</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<!--会员中心-编辑信息-推荐码-->
<div class="container">
	<div class="mui-pt45 mui-mbottom60 pay">
		<dl class="header-search">
			<dd><p class="mui-dis-flex"><i>&#xe650;</i><span>支付方式</span></p></dd>
			<dd>
				<div class="select-pay">
					<div class="select-pay-title mui-dis-flex">
						<span class='on payType' data-key='online'>在线支付</span>
						<span class="payType" data-key='bank'>银行转账</span>
					</div>
					<div class="select-pay-con">
						<div class='bank hide'>
							<ul>
								<li>
									<h3>说明：</h3>
									<label>
										<i></i>
										<em>请填写详细备注明细，填写好后请耐心等待，客户会在一个工作日更改订单状态</em>
									</label>
								</li>
								<li><span>户名：</span><em><?php echo para('AccountName');?></em></li>
								<li><span>账户：</span><em><?php echo para('Account');?></em></li>
								<li><span>开户行：</span><em><?php echo para('OpenBank');?></em></li>
							</ul>
						</div>
						<div></div>
					</div>
				</div>
			</dd>
		</dl>
		<dl class="header-search">
			<dd><p  class="mui-dis-flex"><i>&#xe60b;</i><span>配送方式</span></p></dd>
			<dd>
			<form name="payOrder">
				<ul class="delivery mui-dis-flex">
					<li><span>物流快递</span>
						<select name='logistiscName'>
							<?php echo option( '',explode( '、',para('logisticsName') ),'' );?>
						</select>
					</li>
					<li class='getType getType-on'>物流到付</li>
					<li class='getType'>物流自提</li>
				</ul>
				<input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
				<input type="hidden" name="payType" value='online'>
				<input type="hidden" name="getType" value='物流到付'>
				<input type="hidden" name="type" value='<?php echo $get['type'];?>'>
				<input type="hidden" name="bid" value='<?php echo $get['bid'];?>'>
			</form>
			</dd>
		</dl>
		<input type="button" class="addPassenger_btn" value="确 定"/>
	</div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
$(function(){
	changeNav();
	$(".select-pay-title span").on("click",function(){
		var li_index = $(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$(".select-pay-con div").eq(li_index).show().siblings().hide();
	});
	//支付方式
	$('.payType').click(function(){
		var $this = $(this)
			key = $this.data('key');
		if( key == 'bank' ){
			$('.bank').show();
		}else{
			$('.bank').hide();
		}
		$("[name='payOrder'] [name='payType']").val( key );
	});
	//取货方式
	$('.getType').click(function(){
		var $this = $(this)
			getType = $this.html();
		$this.addClass('getType-on').siblings().removeClass('getType-on');
		$("[name='payOrder'] [name='getType']").val( getType );
		console.log(getType);
	});
	//确定
	$('.addPassenger_btn').click(function(){
		$.post("<?php echo root;?>"+"library/mData.php?type=setPayType",$("[name='payOrder']").serialize(),function(data){
			if(data.warn == 2){
				window.history.back(-1);
			}else{
				mwarn(data.warn);
			}
		},'json');
	});
})
</script>
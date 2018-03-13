<?php
include "../library/mFunction.php";
echo head('m');
$gid            	= $get['gid'];
$skid				= $get['skid'];
$getIntegral 		= Integral::getIntegral($kehu['khid']);
$canUseTotal 		= Integral::$canUseTotal;
$getGoodsDetal  	= Integral::getGoodsDetal($gid);
$getGoodsIntegral 	= Integral::getGoodsIntegral($gid);
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">商品详情</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<!-- 商品详情 -->
<div class="container mui-ptopsmaple mb180">
	<div class="buycart">
			<dl class="goodsmx">
				<dt>
                    <?php echo $getGoodsDetal['img'];?>
                    <!-- <img src="<?php echo img('bDh84129637RF');?>"/> -->
                </dt>
				<dd>
                    <?php echo $getGoodsDetal['word'];?>
					<!-- <p>商品描商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述商品描述述</p> -->
				</dd>
			</dl>
		<!-- 立即兑换 -->
		<div class="buycart-ctrl  mui-wrap-style1 mui-sheet mui-fixed" style='margin-bottom:50px'>
		    <div class="shop-total mui-dis-flex">
				<!--<label class="option-btn"> 
					<span>
						<input type="checkbox" class="goods-check ShopCheck">
					</span>
				</label>
				<span>全选</span>-->
				<p class="flex1 buycar-btn">
					<em>单价：<span class="shop-total-amount ShopTotal"><?php echo $getGoodsIntegral;?>积分</span></em>
					<a href="javascript:;" class="settlement">立即兑换</a>
				</p>
			</div>
		  <!--  <div class="mui-btn-wrap mui-mtop10"> 
		    	<a href="javascript:;" class="settlement">去结算</a>
		    </div>-->
		</div>
	</div>
</div><a href=""></a>
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
$(function(){
    $('.settlement').click(function(){
		var gid = '<?php echo $gid;?>';
        var skid = '<?php echo $skid;?>';		
		mwarn('确认兑换吗？');
		if( $('#coverP').html().indexOf('确认兑换吗？') != -1 ){
			$('#coverSure').one('click',function(){
				var myIntegral = '<?php echo $canUseTotal;?>';
				var needIntesgral = '<?php echo $getGoodsIntegral?>';
				if( needIntesgral > myIntegral ){
					mwarn('你的积分不足');
				}else{
					$.post(root+"library/mData.php?type=integralExchange",{goodsId:gid,skid:skid},function(data){
						if(data.warn == 2){
							mwarn('兑换成功，请到我的订单中查询');
							$('#coverSure').on('click',function(){
								location.href = data.href;
							});
						}else{
							mwarn(data.warn);
						}
					},'json');
				}
			});
		}	
    });
});
</script>
<?php
include "../../library/mFunction.php";
echo head('m');
$bid = $get['bid'];
$res = findOne('buyCar',"id = '$bid'");
if( $res ){
	$html = kdQuery($res['logisticsNum']);
}else{
	$html = '暂无物流信息';
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:windowBack();" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">物流跟踪</p>
		</div>
		<a href="javascript:;" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<style>
    .mto30{margin-top:30px}
</style>
<div class="mto30">
	<div class="track-rcol">
			<div class="track-list">
				<ul>
					<?php echo $html;?>
					<!-- <li class="first">
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">感谢您在**购物，欢迎您再次光临！</span>
					</li>
					<li>
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">【申通快递】配送员【申国龙】已出发，联系电话【1234567987，感谢您的耐心等待。】</span>
					</li>
					<li>
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">感谢您在**购物，欢迎您再次光临！</span>
					</li>
					<li>
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">感谢您在**购物，欢迎您再次光临！</span>
					</li>
					<li>
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">感谢您在**购物，欢迎您再次光临！</span>
					</li>
					<li>
						<i class="node-icon"></i>
						<span class="time">2016-03-10 18:07:15</span>
						<span class="txt">感谢您在**购物，欢迎您再次光临！</span>
					</li> -->
				</ul>
			</div>
		</div>
</div>
<?php echo mFooter().mWarn();?>

<script>
$(function(){
	changeNav();
})
</script>
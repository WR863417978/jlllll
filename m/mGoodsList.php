<?php
include "../library/mFunction.php";
echo head('m');
$oid 	= $get['oid'];	#一级分类id
$tid 	= $get['tid'];	#一级分类id
$goodsType = para('goodsClassShow');
if( !empty($oid) || $goodsType == '一级分类' ){
	$res = findOne('goodsOne',"id = '$oid' AND xian = '显示'  ORDER BY list");
	//echo "SELECT * FROM goodsOne WHERE id = '$oid' xian = '显示'  ORDER BY list";
}else if( $goodsType == '二级分类' ){
	$res = findOne('goodsTwo',"id = '$tid' AND xian = '显示'  ORDER BY list");
}
if( $res )
{
	$goodsList = GoodsList::index($get);
}else if( $get['sec'] == 'search' ){
	$goodsList = GoodsList::index($get,true);
}else{
	header("location:".root."m/mIndex.php");
	exit();
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting">
		<span class="logo-text">聚礼优选</span>
		<div class="align-content">
			<form class="search-form" method='get' action="<?php echo root."m/mGoodsList.php";?>">
				<input type="hidden" name="sec" value='search'>
                <input id="search-form-input" class="search" name='keywords' type="text"  placeholder="礼品名称">
            </form>
		</div>
		<a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-login">我的</a>
	</div>
</div>
<!--//-->
<div class="container mui-mbottom60">
	<div class="content mui-pt45">
		<!--轮播-->
		<div id="slideBox" class="slideBox">
			<div class="swiper-wrapper">
                <div class='swiper-slide'>
					<a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
				</div>
				<div class='swiper-slide'>
					<a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
				</div>
				<div class='swiper-slide'>
					<a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
				</div>
				<div class='swiper-slide'>
					<a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
				</div>
			</div>
			<div class="swiper-pagination"> </div>
		</div>
		<!--//-->
	</div>
	<!--产品列表-->
	<div class="product">
		<style>
		.screen {background: #fff;margin:10px 0 0 0;padding:10px 0;}
		.screen label{width: 50%;text-align: center;}
		</style>
		<!--类别-->
		<div class="mui-dis-flex screen">
			<label>
				<select class="select-down" data-key='price'>
					<?php echo option('--价格--',['de'=>'默认','down'=>'从高到低','up'=>'从低到高'],$get['orp']);?>
				</select>
			</label>
			<label>
				<select class="select-down" data-key='sales'>
                    <?php echo option('--销量--',['de'=>'默认','down'=>'从多到少','up'=>'从少到多'],$get['ors']);?>
				</select>
			</label>
		</div>
		<ul class="product-lists mui-dis-flex">
			<?php echo $goodsList['html'];?>
			<!-- <li>
				<a>
					<img src="img/goods.png"/>
					<p class="nameSpc">【旗舰店正品】美肤宝茶爽冰膜面膜贴保湿补水晒后修复舒缓肌肤</p>
					<p class="textSale">
						<em class="text-price">￥89.00</em> 
						<em class="text-sale">销量:60</em> 
					</p>
				</a>
			</li> -->
		</ul>
	</div>
	<!--回到顶部-->
	<a href="javascript:;" title="回到顶部" id="gotop-btn"><img src="<?php echo img('dyf84130064pc');?>"/></a>
	<!--//-->
        <?php echo footerLine();?>
</div>
<!--底部-->
<?php echo mFooter();?>
<!--//-->
<script>
$(function(){
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
window.onload=function(){
var gotop_btn = document.getElementById("gotop-btn");//获取回到顶部按钮ID
var clientHeight  = document.documentElement.client;//获取可视区域的高度
var timer = null;//定义一个定时器
var isTop = true;//定义一个布尔值，判断是否到达顶部
window.onscroll = function(){  //滚动事件
	//获取滚动条的滚动高度
	var osTop = document.documentElement.scrollTop || document.body.scrollTop;
	//判断回到顶部按钮的显示与隐藏
	if(osTop > 0){
		gotop_btn.style.display = "block";
	}else{
		gotop_btn.style.display = "none";
	}
	//主要用于判断当 点击回到顶部按钮后 滚动条在回滚过程中，若手动滚动滚动条，则清除定时器
	if(!isTop){
		clearInterval(timer);
	}
	isTop = false;
}
//回到顶部点击事件
gotop_btn.onclick = function(){
	//设置一个定时器
	timer = setInterval(function(){
		//获取滚动条的高度
		var osTop = document.documentElement.scrollTop || document.body.scrollTop;
		//用于设置速度差 用于产生缓存效果
		var speed = Math.floor(-osTop / 8);
		document.documentElement.scrollTop = document.body.scrollTop = osTop + speed;
	    isTop =true;  //用于阻止滚动事件清除定时器
            if(osTop == 0){
                clearInterval(timer);
            }
		},30);
	}
}

	function getUrlParam(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
	}
    //下拉框变化
    $('.select-down').change(function(){
        var $this = $(this)
            key = $this.data('key')
            val = $this.val()
			oid = getUrlParam('oid');
		var url = location.href;
		console.log(url);
		if( url.indexOf('sec=search') !== -1 ){
			location.href = url + "&type=" + key + '&orp=' + val;
		}else if( key == 'price' ){
            location.href = root + "m/mGoodsList.php?oid=" + oid + "&type=" + key + '&orp=' + val;
        }else if( key == 'sales' ){
            location.href = root + "m/mGoodsList.php?oid=" + oid + "&type=" + key + '&ors=' + val;    
        }
    });
	
</script>
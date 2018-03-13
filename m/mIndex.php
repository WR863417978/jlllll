<?php
include "../library/mFunction.php";
//isRegister();
echo head('m');
$oid = $get['oid']; #一级分类id
$navList    = navListShow($oid);    #移动端顶部一级分类导航
$advHtml    = advShow();            #广告专区
$areaHtml   = areaShow();           #专区推荐
/*$pdo = newPdo();
$a = $pdo->query($sql);
$select = $a->fetchAll(PDO::FETCH_ASSOC);*/
#限时和上新地址
$limitTime 	= findOne('goodsOne',"name = '限时'");
$limitTime 	= root.'m/mGoodsList.php?oid='.$limitTime['id'];
$newUp 		= findOne('goodsOne',"name = '上新'");
$newUp 		= root.'m/mGoodsList.php?oid='.$newUp['id'];
#轮播图
$indexBanner = indexBanner();
#专区图片
$topicImg = topicImg();
#ico图片
$icoImg = icoImgBuild();

//
if( $_SESSION['khid'] == '2715343303' || $_SESSION['khid'] == '1994753883' )
{
    //echo share($ThisUrl,'首页',$imgUrl,$desc);
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting">
		<span class="logo-text">聚礼优选</span>
		<div class="align-content">
			<form class="search-form" method='get' action="<?php echo root."m/mGoodsList.php"?>">
                <input id="search-form-input" name='keywords' class="search" type="text"  placeholder="">
				<input type="hidden" name="sec" value='search'>
            </form>
		</div>
		<a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-login header-btn2">&#xe641;</a>
	</div>
	<!--产品导航-->
	<div class="nav-meun"><a href=""></a>
		<ul class="mui-dis-flex">
<!--			 <li class="nav-meun-on"><a>首页推荐</a></li>-->
            <?php echo $navList['2'];#移动端首页底部一级分类?>
		</ul>
		<label id="nav-meun-btn"><img src="<?php echo img('wzu84130248Ln');?>"/></label>
		<dl class="nav-meun-more">
			<!-- <dd><a>首页推荐</a></dd> -->
			<?php echo $navList['1'];#移动端一级分类更多?>
		</dl>
	</div>
	<!--//-->
</div>
<!--//-->
<div class="container mui-mbottom60">
	<div class="content mui-ptop45">
		<!--轮播begin-->
		<div id="slideBox" class="slideBox">
			<div class="swiper-wrapper">
				<!-- <div class='swiper-slide'>
					<a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
				</div> -->
				<?php echo $indexBanner;#轮播图?>
			</div>
			<div class="swiper-pagination"> </div>
		</div>
		<!--轮播end-->

		<!--产品导航begin-->
		<div class="classly">
			<!-- ico -->
			<ul class="classly2 mui-dis-flex">
				<?php echo $icoImg;#ico图标?>
				<!-- <li><a href="<?php echo root;?>m/mMember.php"><span class="strategy"><i><img src="<?php echo img('QzD87416755Px');?>" /></i></span><p>会员</p></a></li>
				<li><a><span class="culture"><i><img src="<?php echo img('PZa87419988Nc');?>"/></i></span><p>领劵</p></a></li>
				<li><a href="<?php echo $limitTime;?>"><span class="activity"><i><img src="<?php echo img('weR87420668kd');?>"/></i></span><p>限时</p></a></li>
				<li><a href="<?php echo $newUp;?>"><span class="join"><i><img src="<?php echo img('yLO87420739UN');?>"/></i></span><p>上新</p></a></li> -->
			</ul>
		</div>
		<!--产品导航end-->

		<!--广告区begin-->
		<div class="team-lists">
			<ul class="mui-dis-flex">
				<?php echo $advHtml;#广告专区?>
			</ul>
			<dl>
				<?php echo $topicImg;#专区图片?>
			</dl>
		</div>
		<!--广告区end-->
	</div>
    
	<!--产品列表begin-->
	<div class="product">
		<!--类别-->
        <?php echo $areaHtml;#专区推荐?>
		<!--类别end-->
	</div>
    <!--产品列表end-->
	<!--回到顶部-->
	<a href="javascript:;" title="回到顶部" id="gotop-btn"><img src="<?php echo img('dyf84130064pc');?>"/></a>
	<!--回到顶部end-->
	<div class="footer-line" style="display:none">
		<p>我是有底线的</p>
	</div>
</div>
<!--底部-->
<?php echo mFooter();?>
<!--//-->
<script>
$(function(){
	$("[name='keywords']").blur(function(){
		if( $.trim( $(this).val() ).length > 0 ){
			console.log(11);
			location.href = root + "m/mGoodsList.php?sec=search&keywords=" + $(this).val();
		}else{
			console.log(22);
		}
	});
});
$(function(){
	/****导航栏变色***/
	changeNav();  //根据URL来控制，最下方的图标变色；
	/***菜单显隐****/
	nav();    //控制分类导航的更多显示；
	/****首页轮播****/
	var swiperNum = $('.swiper-wrapper img').length;
	console.log(swiperNum);
	if( swiperNum > 1 ){
		window.addEventListener("load", function(e) {
			// 首页轮播图
			var swiperObj = new Swiper('#slideBox', {
				autoplay: 2500,
				autoplayDisableOnInteraction: false,
				loop: true,
				pagination: '.swiper-pagination',
			});
		}, false);
	}
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
</script>
<script type="text/javascript">
//判断整个文档到底部
$(window).scroll(function(){
    //滚动条所在位置的高度
    var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
    //当前文档高度   小于或等于   滚动条所在位置高度  则是页面底部
    if(($(document).height()) <= totalheight) {
        //页面到达底部
        $(".footer-line").css("display","block");
    }
});
</script>
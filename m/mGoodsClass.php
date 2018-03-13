<?php
include "../library/mFunction.php";
echo head('m');
$type   = $get['type'];     #分类 | 价格
$tid    = $get['tid'];      #banner id
if( empty($type) )
{
    $tid    = $get['tid'];      #banner id
    $typeBannerHtml     = Goods::typeLeftBanner($tid);  #分类
    $typeGoodsListHtml  = Goods::typeGoodsList($tid);   #商品详情
    $defaultId          = Goods::$defaultTypeId;
    empty($tid) ? $defaultId = $defaultId : $defaultId = $tid;
    $goodsType = para('goodsClassShow');
    if( $goodsType == '一级分类' ){
        $urlParam = "m/mGoodsList.php?oid={$defaultId}";
    }else if( $goodsType == '二级分类' ){
        $urlParam = "m/mGoodsList.php?tid={$defaultId}";
    }
}else if( $type == 'monList' ){
    $price  = $get['pr'];       #价格区间
    $typeBannerHtml     = Goods::monTypeBanner($price);     #分类
    $typeGoodsListHtml  = Goods::monGoodsList($price);   #商品详情
    $hide = 'hide';
}
$classImg = goodsClassBanner();

?>
<style>
.mySelect{width: 100%;height: 35px;border-radius: 4px;}
</style>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting">
        <span class="logo-text">聚礼优选</span>
        <div class="align-content">
            <form class="search-form" action='<?php echo root."m/mGoodsList.php";?>'>
				<input id="search-form-input" name='keywords' class="search" type="text"  placeholder="礼品名称">
                <input type="hidden" name="sec" value='search'>
            </form>
        </div>
        <a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-login">我的</a>
    </div>
</div>
<!--//-->
    
<div class="container mui-pt45 mui-mbottom60">
    <!--轮播begin-->
    <div id="slideBox" class="slideBox">
        <div class="swiper-wrapper">
            <?php echo $classImg;#分类轮播图?>
            <!-- <div class='swiper-slide'>
                <a href=''><img src='<?php echo img('bDh84129637RF');?>'></a>
            </div> -->
        </div>
        <div class="swiper-pagination"> </div>
    </div>
    <!--轮播end-->
    <div class="mclass">
        <div class="mclass-content mui-dis-flex">
            <!--分类导航-->
            <div class="mclass-menu">
                <select name="" id="goodsType" class='mySelect'>
                    <option value="typeList" <?php echo empty($type) ? 'selected':'';?>>按品类</option>
                    <option value="monList" <?php echo $type == 'monList' ? 'selected':'';?>>按价格</option>                   
                </select>
                <ul id="mclass">
                <?php echo $typeBannerHtml;?>
                </ul>
        </div>
       <script>
        $(function(){
            $("#mclass >li").on("click",function(){
                $(this).addClass("current").siblings().removeClass("current");
            })
        })
       </script>
       <!--分类商品-->
       <div class="mclass-panel flex-ratio">
            <div class="mclass-advert"></div>
            <div class="product">
               
                <label class="see-more"><em>热门推荐</em><span class='<?php echo $hide;?>'><a href="<?php echo root."$urlParam";?>">查看更多</a></span></label>
                <ul class="product-lists product-lists2 mui-dis-flex mui-mbottom60">
                    <?php echo $typeGoodsListHtml;?>
                </ul>
            </div>
            <div class="page"></div>
        </div>
        <!--//-->
        </div> 
    </div>
</div>
<!--底部-->
<?php echo mFooter();?>
<!--//-->

<script>
$(function(){
    changeNav();
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
    //
    $('#goodsType').change(function(){
        var _this = $(this);
        if( _this.val() == 'monList' ){
            location.href = root + "m/mGoodsClass.php?type=monList";
        }else{
            location.href = root + "m/mGoodsClass.php";
        }
    });
})
</script>
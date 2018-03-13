<?php
include "../library/mFunction.php";
include "../control/ku/newfunction.php";
//isRegisterUp($kehu);	
echo head('m');
$id = $_GET['id'];
$data = findsql('member_goods',"id = '$id'");
/**
 * 商品图文详情
 */
$articel = myArticleMx($gid,'商品明细');

//$evalData = evaluateShow($gid);
$evalData = goodsEvalBuild($gid,0,10);
?>

<!--头部 begin-->
<div class="header header-fixed">
    <div class="nesting">
    		<a href="javascript:;" class="header-btn header-return" style="position:absolute;" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
        <div class="align-content goods-con" style="margin:0;">
            <ul class="goods-con-title mui-dis-flex">
                <li style="line-height:45px;">会员赠送商品</li>
            </ul>
        </div>
        <!-- <a href="#" class="header-btn"></a>  -->
    </div>
</div>
<!--头部end-->

<div class="container">
    <div class="content mui-pt45">
        <!--轮播-->
        <div id="slideBox" class="slideBox">
            <!--<div class="swiper-wrapper">-->
                <img src="<?php echo $data['goods_img']; ?>" />
            <!--</div>-->
            <div class="swiper-pagination"> </div>
        </div>
        <!--//-->
    </div>
    <!--产品详情-->
    <div class="goodsMx mui-mbottom60">
        <p class="goodMx-title"><?php echo $data['goods_name']?></p>
        <div class="goods-title">
            <dl>
                <dd>
                    <ul class="mui-dis-flex">
                    <?php echo $centerHtml;?>
                        <!-- <li><span>零售价 : </span><i>￥<?php echo $fistSkuProfit['price'];?></i></li>
                        <li><span>批发价 : </span><i>￥<?php echo $fistSkuProfit['retailPrice'];?></i></li>
                        <li><span>利润 : </span><i>￥<?php echo $fistSkuProfit['profit'];?></i></li> -->
                    </ul>
                </dd>
                <dd>
                    <ul class="mui-dis-flex">
                        <li style="width:100%;text-align:right;font-size:14px;"  class="orange"><span>价值 : </span><?php echo $data['goods_money'];?></li>
                    </ul>
                </dd>
                
            </dl>
        </div>
        <!-- <dl class="goodMx-price">
            <dd class="mui-dis-flex hide" name='default-select'>
                <p class="flex1">
                    <span>请选择规格 ：</span>
                    <i class="inventory">颜色：<em id="inventory-val"><?php echo $defaultGoodsSku['name'];?></em></i>
                    <i class="inventory">数量：<em id="inventory-val"><?php echo $defaultGoodsSku['thePatch'];?></em></i>
                </p>
                <span class="more">&#xe62e;</span>
            </dd>
        </dl> -->
        <div class="goods-con">
        	<p style="border-bottom:2px #dedede solid;"></p>
            <div class="goods-con-box">
                <div style="display: block;">
                    <ul>
                        <?php echo $data['content'];#商品图文详情?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--//-->
</div>
 
<!--底部-->
<div class="footer mui-fixed">
    <dl class="mui-dis-flex footer2">
       <dt>
            <a href="<?php echo root;?>m/mIndex.php" class='buyCarBtn'><span class="cart-ico">&#xe606;</span><i>首页</i></a>
            <a style="border-right: 1px solid #ccc;" onclick='easemobim.bind({configId: "<?php echo para('hxConfigId'); ?>"})'><span class="service-ico">&#xe641;</span><i>客服</i></a>

       </dt>
       <dd>
            <a href="mMemberBuy.php" class="book-btn" style="float:right;">立即成为会员</a>
       </dd>
    </dl>
</div>

<!--//-->
</body>
</html>
<?php
include "../library/mFunction.php";
isRegisterUp($kehu);
echo head('m');
$gid        = $get['gid'];
$videoAndWinHtml    = GoodsMx::videoAndImgShow($gid);   #视频 || 橱窗图展示
                      GoodsMx::goodsSkuBuild($gid);     #商品一级规格名称
$goodsData          = GoodsMx::$goodsData;              #商品数据
$goodsSkuDataArr    = GoodsMx::$goodsSkuDataArr;        #商品规格
$fistSkuProfit      = GoodsMx::$fistSkuProfit;          #每一个规格显示的利润信息
$defaultGoodsSku    = GoodsMx::$defaultGoodsSku;        #默认选中的规格
$goodsSkuTypeTwo    = GoodsMx::$goodsSkuTypeTwo;        #商品二级规格名称
$goodsSkuTypeOne    = GoodsMx::$goodsSkuTypeOne;        #商品规格
$goodsCustomMade    = GoodsMx::goodsCustomMade();       #商品是否支持定制
$goodsCoupon        = GoodsMx::goodsCoupon($gid);       #商品优惠劵 及 弹窗
//print_r($goodsCoupon);die;
//die;
$goodsDefaultData   = GoodsMx::$goodsDefaultData;       #商品默认数据


insertCookie($gid);  #浏览商品插入cookie
 
if( $kehu['type'] == '普通会员' || $kehu['type'] == '高级会员' ){
    $centerHtml = "
    <li><span>零售价 : </span><i>￥".floatval($goodsDefaultData['price'])."</i></li>
    <li><span>批发价 : </span><i>￥".floatval($goodsDefaultData['retailPrice'])."</i></li>
    <li><span>利润 : </span><i>￥".floatval($goodsDefaultData['profit'])."</i></li>";
}
else if( empty( $kehu['tel'] ) )
{
    $centerHtml = "
    <li><span>起批量 : </span><i>{$goodsDefaultData['minThePatch']}</i></li>
    <li><span>销量 : </span><i>{$goodsDefaultData['salesVolume']}</i></li>";
}else{
    $centerHtml = "
    <p class='textSale'>
        <em class='text-price'>批发价：￥".floatval($goodsDefaultData['retailPrice'])."</em>
        <em class='text-price'>利润:￥".floatval($goodsDefaultData['profit'])."</em>
    </p>";
}
/**
 * 商品图文详情
 */
$articel = myArticleMx($gid,'商品明细');
/**
 * 商品评论
 * @author r7 
 * @param [type] $goodsId
 * @return void
 */
function evaluateShow($goodsId)
{
    $data = [];
    $sql = "SELECT id FROM talk WHERE targetId = '$goodsId' ORDER BY time DESC LIMIT 0,11";
    $res = mysql_query($sql);
    if($res)
    {
        $data['num'] = mysql_num_rows($res);
        $data['data'] = goodsEvalBuild(1,$goodsId); 
    }else{
        $data['num'] = 0;
    }
    return $data;
}
//$evalData = evaluateShow($gid);
$evalData = goodsEvalBuild($gid,0,10);
?>

<!--头部 begin-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
        <div class="align-content goods-con">
            <!-- <p class="align-text"><?php //echo $goodsData['name']?></p> -->
            <ul class="goods-con-title mui-dis-flex">
                <li class="goods-title-on">商品</li>
                <li>详情</li>
                <li>评价</li>
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
            <div class="swiper-wrapper">
                <?php echo $videoAndWinHtml;#视频展示区 || 橱窗图?>   
            </div>
            <div class="swiper-pagination"> </div>
        </div>
        <!--//-->
    </div>
    <!--产品详情-->
    <div class="goodsMx mui-mbottom60">
        <p class="goodMx-title"><?php echo $goodsData['name']?></p>
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
                        <li><span>库存 : </span><i><?php echo $goodsDefaultData['number'],$goodsDefaultData['skuUnit'];?></i></li>
                        <li><span>发货地 : </span><i><?php echo $goodsDefaultData['shippingPlace'];?></i></li>
                        <li><span><?php echo $goodsDefaultData['thePatch'];?>套起批</span></li>
                        <li  class="orange"><span>采购员 : </span><i><?php echo $goodsDefaultData['clerk'];?></i></li>
                    </ul>
                </dd>
                <dt class='get-coupons'>
<!--                <?php //echo $goodsCoupon['showHtml'];?>-->
                </dt>
                <?php echo $goodsCustomMade;#?>
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
            <div class="goods-con-box">
                <div style="display: block;">
                    <ul>
                        <?php echo $articel['orderList'];#商品图文详情?>
                    </ul>
                </div>
                <div class="goods-item hide">
                    <dl>
                        <dd>
                            <label>
                                <span>品名</span>
                                <em><?php echo $goodsData['name'];?></em>
                            </label>
                        </dd>
                        <dd>
                            <label>
                                <span>参数</span>
                                <em><?php echo $goodsData['parameter'];?></em>
                            </label>
                        </dd>
                        <dd>
                            <label>
                                <span>摘要</span>
                                <em><?php echo $goodsData['summary'];?></em>
                            </label>
                        </dd>
                        
                    </dl>
                </div>
                <div class="appraise hide" id='goodsEval'>
                    <div class='content-drop'>
                        <!-- 评价 -->
                        <?php echo $evalData['html'];?>     
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--//-->
</div>
    <!--规格筛选-->
    <div class="goods-cover">
        <div class="goods-cover-con">
            <div class="sku-pro">
                <div>
                    <div class="sku-img">
                        <p><img src="" id='goodsSkuImg'></p>
                        <div class="goods-specs">
                            <p><?php echo $goodsData['name'];?></p>
                            <ul class="mui-dis-flex">
                                <li><span>零售价 : </span><i name='sPrice'>￥<?php echo $fistSkuProfit['price'];?></i></li>
                                <li><span>批发价 : </span><i name='sRetailPrice'>￥<?php echo $fistSkuProfit['retailPrice'];?></i></li>
                                <li><span>利润 : </span><i name='sProfit'>￥<?php echo $fistSkuProfit['profit'];?></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="sku-pro-info">
                        <dl>
                            <dt>
                                <ul class="mui-dis-flex">
                                    <?php echo $goodsSkuTypeOne;#一级规格?>
                                </ul>
                            </dt>
                            <dd>
                                <ul>
                                    <?php echo $goodsSkuTypeTwo;#二级规格?>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <div class="sku-closed"></div>
                </div>
            </div>
        </div>
    </div>
    <!--//-->
    <!-- 领劵弹出层begin -->
    <div class='coupons-cover'>
        <div class='mycoupons'>
            <div>
                <div class='coupons-img' onclick="$('.coupons-cover').hide();"><img src='<?php echo img('Qsj84143043aZ');?>'></div>
                <span class='coupons-s1'>优惠劵</span>
                <span class='coupons-s2'>可领优惠劵</span>
            </div>
            <?php echo $goodsCoupon['couponHtml'];?>
        </div>
    </div>
    <!-- 领劵弹出层end -->
<!--底部-->
<div class="footer mui-fixed">
    <dl class="mui-dis-flex footer2">
       <dt>
            <a href="<?php echo root;?>m/mIndex.php" class='buyCarBtn'><span class="cart-ico">&#xe606;</span><i>首页</i></a>
            <a onclick='easemobim.bind({configId: "<?php echo para('hxConfigId'); ?>"})'><span class="service-ico">&#xe641;</span><i>客服</i></a>

       </dt>
       <dd>
            <a class="cart-btn">加入订货单</a>
            <a class="book-btn">立即订购</a>
       </dd>
    </dl>
</div>

<form name="addBuyCar">
    <input type="hidden" name="goodsTypeId" value=''>
    <input type="hidden" name="goodsType" value=''>
    <input type="hidden" name="goodsNum" value=''>
    <input type="hidden" name="goodsId" value='<?php echo $gid;?>'>
</form>
<?php echo mWarn().easemobBuild(root.$goodsData['ico']);?>
<!--//-->
<script>
$(function(){
    /***********************导航栏变色****************************/
    changeNav();
    /**************************首页轮播******************************/
    window.addEventListener("load", function(e) {
        // 首页轮播图
        var swiperObj = new Swiper('#slideBox', {
            autoplay: 2500,
            autoplayDisableOnInteraction: false,
            loop: true,
            pagination: '.swiper-pagination',
        });
        //
    }, false);
    //数量填写
    $(".amount-value").blur(function(){
        var $this = $(this)
            min = $this.data('min')
            max = $this.data('max');
        if( parseInt( $this.val() ) >= max ){
            $("[name='addBuyCar'] [name='goodsNum']").val( max );
            $this.val(max);
        }else if( parseInt( $this.val() ) <= min ){
            $("[name='addBuyCar'] [name='goodsNum']").val( min );
            $this.val(min);
        }
    })
    //规格点击
    $(".goodsSku").on('click',function(){
        var $this = $(this);
        //价格、批发价、利润的变化  
        $("[name='sPrice']").html( '￥' + $this.data('price') );
        $("[name='sRetailPrice']").html( '￥' + $this.data('retailprice') );
        $("[name='sProfit']").html( '￥' + $this.data('profit') );
        //规格图片
        $("#goodsSkuImg").attr('src', $this.data('skuimg') );
    });
    // 数量减 
    $(".minus").click(function() {
        var $this = $(this);
        var min = $this.data('min');
        var t = $(this).parent().find('.am-num-text');
        if( parseInt( t.val() ) <= min ){
            t.val( min );
        }else{
            t.val(parseInt(t.val()) - 1);
        }
        $("[name='addBuyCar'] [name='goodsNum']").val( parseInt(t.val()) );
        if(t.val() <= 1) {
            t.val(1);
            $("[name='addBuyCar'] [name='goodsNum']").val(1);
        }
    });
    // 数量加 
    $(".plus").click(function() {
        var $this = $(this);
        var max = $this.data('max');
        var t = $(this).parent().find('.am-num-text');
        console.log( 'max' + max + 'now' + t.val() );
        if( parseInt( t.val() ) >= max ){
            t.val( max );
        }else{
            t.val(parseInt(t.val()) + 1);
        }
        //数量
        $("[name='addBuyCar'] [name='goodsNum']").val( parseInt(t.val()) );
        if(t.val() <= 1) {
            t.val(1);
            //数量
            $("[name='addBuyCar'] [name='goodsNum']").val(1);
        }
    });
    /* //商品数量输入
    $(".amount-value").blur(function(){
        var $this = $(this)
            min = $this.data('min')
            max = $this.data('max');
        var this_val = parseInt($this.val());
        if( this_val > max ){
            $this.val(max);
        }else if( this_val < min ){
            $this.val(min);
        }
        $("[name='addBuyCar'] [name='goodsNum']").val($this.val());
    }) */
    /*********选择商品类型**********/
    $(".goods-type >li").click(function(){
        $(this).addClass("on-type").siblings().removeClass("on-type");
        $("#prive-val").html("50");
        $("#inventory-val").html("10");
    });
    /******商品详情选项卡切换********/
    $(".goods-con-title").on("click","li",function(){
        var li_index = $(this).index();
        $(this).addClass("goods-title-on").siblings().removeClass("goods-title-on");
        $(".goods-con-box div").eq(li_index).show().siblings().hide();
        $(".goods-con-box").offset().top = 0;
    });
    /*********选择商品规格弹窗*******/
    //#立即购买弹出弹窗
    var mask = false;//
    $(".book-btn").on("click",function(){

        var src = '<?php echo root.GoodsMx::$defaultSkuImg;?>';
        $('#goodsSkuImg').attr('src',src);
        //$(".goods-cover").show();
        var skidHidden = $("[name='addBuyCar'] [name='goodsTypeId']")
            typeHidden = $("[name='addBuyCar'] [name='goodsType']")
            gid  = $("[name='addBuyCar'] [name='goodsId']")
            khTel = '<?php echo $kehu['tel'];?>';
        if( $.trim(khTel).length == 0 ){
            console.log(11);
            location.href =  "<?php echo root;?>m/mRegister.php";
        }else{
            if( $.trim(skidHidden.val()).length > 0 ){
                console.log(22);
                $.post("<?php echo root;?>library/mData.php?type=addBuyCar&status=select",$("[name='addBuyCar']").serialize(),function(data){
                    if(data.warn == 2 && data.href){
                        $(".sku-closed").click();
                        location.href = data.href;
                    }else{
                        mwarn(data.warn);
                    }
                },'json');
            }else if( mask == false ){
                $('.goods-cover').show();
                mask = true;
            }else if( mask ){
                mwarn('请选择起批量');
            }
        }
    });
    //加入购物车
    $('.cart-btn').on('click',function(){
        //规格图片加载
        var src = '<?php echo root.GoodsMx::$defaultSkuImg;?>';
        $('#goodsSkuImg').attr('src',src);

        var skidHidden = $("[name='addBuyCar'] [name='goodsTypeId']")
        var   typeHidden = $("[name='addBuyCar'] [name='goodsType']")
        var    gid  = $("[name='addBuyCar'] [name='goodsId']")
        var    khTel = '<?php echo $kehu['tel'];?>';
        if( $.trim(khTel).length == 0 ){
            location.href = "<?php echo root?>" + "m/mRegister.php";
        }else{
            if( $.trim(skidHidden.val()).length > 0 ){
//                console.log($("[name='addBuyCar']").serialize());
                $.post("<?php echo root?>"+"library/mData.php?type=addBuyCar&status=noselect",$("[name='addBuyCar']").serialize(),function(data){
                    if(data.warn == 2){
                        mwarn('加入购物车成功');
                    }else{
                        mwarn(data.warn);
                    }
                },'json');


            }else if( mask == false ){
                $('.goods-cover').show();
                mask = true;
            }else if( mask ){
                mwarn('请选择起批量');
            }
        }
    });
    
    $(".sku-closed").on("click",function(){
        $(".goods-cover").hide();
        //关闭规格弹窗 清除数据
        $("[name='addBuyCar'] [name='goodsTypeId']").val('');   //规格id
        $("[name='addBuyCar'] [name='goodsType']").val('');     //数量
        $("[name='addBuyCar'] [name='goodsNum']").val('');      //数量
        mask = false;
        //移除选中效果
        $('.goodsSku').removeClass('goods-on');
        var firstObj = $($('.goodsSku')[0]);
        var typeOneName = firstObj.html();
        
        firstObj.addClass('goods-on');
        $("[name='typeTwo']").removeClass('on');
        
        $(".skuTypeTwo").each(function(){
            if( $(this).data('typeone') == typeOneName ){
                $(this).removeClass('my-hide');
            }else{
                $(this).addClass('my-hide');
            }
        });
    });
    //已选规格尺寸弹窗
    $("[name='default-select']").click(function(){
        $(".goods-cover").show();
        var src = '<?php echo root.GoodsMx::$defaultSkuImg;?>';
        $('#goodsSkuImg').attr('src',src);
    });
    //
    $('.goodsSku').on('click',function(){
        var _this = $(this);
        $(this).addClass('goods-on').siblings().removeClass('goods-on');
        //点击变化时 已选规格变化
        $('.hasSelect').html( $(this).html() );
        //获取规格名称
        var skuname = _this.html();
        console.log(skuname);
        $(".skuTypeTwo").each(function(){
            if( $(this).data('typeone') == skuname ){
                $(this).removeClass('my-hide');
            }else{
                $(this).addClass('my-hide');
            }
        });
        $("[name='addBuyCar'] [name='goodsTypeId']").val( $(this).data('skuid') );
    });
    //定制
    $('.customMade').click(function(){
        var keTel = '<?php echo $kehu['tel'];?>';
        if( $.trim(keTel).length == 0 ){
            location.href =  '<?php echo root;?>m/mRegister.php';
        }else{
            location.href =  '<?php echo root;?>m/mGoodsMade.php';
        }
    });
    //宣传素材
    $('.publicity').click(function(){
        var gid = $(this).data('gid');
        console.log('<?php echo root;?>m/mGoodsMaterail.php?gid=' + gid);
        location.href =  '<?php echo root;?>m/mGoodsMaterail.php?gid=' + gid;
    });
    //等级规格
    $('.type-two1').click(function(){
        var _this = $(this);
        _this.parent().parent().parent().find('.had-select').removeClass('had-select');
        _this.addClass('had-select');
        
        var skidHidden = $("[name='addBuyCar'] [name='goodsTypeId']");  //规格id
        var numHidden = $("[name='addBuyCar'] [name='goodsNum']");      //数量obj
        var num = _this.parent().parent().find('.amount-value');        //数量
        var typeHidden = $("[name='addBuyCar'] [name='goodsType']");    //商品类型
        console.log(num.val());
        numHidden.val( num.val() ); //数量
        typeHidden.val( _this.data('type') );
        var skid = _this.data('typeid');
        skidHidden.val( skid );//规格
    });
    //等级规格
    $("[name='typeTwo']").click(function(){
        var $this = $(this);
        $this.addClass('on').siblings().removeClass('on');  //添加选中效果
        //价格、批发价、利润的变化  
        $("[name='sPrice']").html( '￥' + $this.data('price') );
        $("[name='sRetailPrice']").html( '￥' + $this.data('retailprice') );
        $("[name='sProfit']").html( '￥' + $this.data('profit') );
        //规格id
        var skidHidden = $("[name='addBuyCar'] [name='goodsTypeId']");  //规格id obj
        var numHidden = $("[name='addBuyCar'] [name='goodsNum']");      //数量   obj
        var num = $this.find('.amount-value');                          //数量
        //赋值
        skidHidden.val( $this.data('skid') );   //规格id
        numHidden.val( num.val() );             //数量
    });
    //领劵弹出层
    $('.get-coupons').on('click',function(){
        $('.coupons-cover').show();
    });
    //点击领劵
    $('.get-coupon').on('click',function(){
        var _this = $(this)
            couId = _this.data('couid')
            keTel = '<?php echo $kehu['tel'];?>'
            arr = <?php echo json_encode($goodsCoupon['coupon']);//用户优惠劵张数 arr {1:1,2:0} 第一张优惠劵 有，第二张优惠劵 无?>;
            if($.isArray(arr)){
                var key = _this.data('key');
                console.log(key);
                console.log(arr);
                if( $.trim(keTel).length == 0 ){
                    location.href = '<?php echo root;?>m/mRegister.php';
                }else if( arr[key] >= 1 ){
                    mwarn('你已经领取过该优惠劵');
                }else{
                    $.post(root+"library/mData.php?type=getCoupon",{couId:couId},function(data){
                        if(data.warn == 2){
                            mwarn('领取成功');
                        }else{
                            mwarn(data.warn);
                        }
                    },'json');
                };
            }


    });
});
</script>
<script>
 $(function() {
    $('#goodsEval').click(function(){
        $(this).show(); 
    });
    // 页数
    var page = 0;
    // 每页展示10个
    var size = 10;
    //goodsId
    var goodsId = '<?php echo $gid;?>';
    // dropload
    $('#goodsEval').dropload({
        scrollArea : window,
        loadDownFn : function(me){
            page++;
            // 拼接HTML
            var result = '';
            $.ajax({
                type: 'POST',
                url: root+'library/mData.php?type=goodsEval&page='+page+'&size='+size+'&goodsId='+goodsId,
                dataType: 'json',
                success: function(data){
                    var arrLen = data.data.length;
                    $('.dropload-down').show();
                    if(arrLen > 0){
                        resutl = data.html;
                    // 如果没有数据
                    }else{
                        console.log(22222);
                        // 锁定
                        me.lock();
                        // 无数据
                        me.noData();
                    }
                    // 为了测试，延迟1秒加载
                    setTimeout(function(){
                        // 插入数据到页面，放到最后面
                        $('.content-drop').append(data.html);
                        // 每次数据插入，必须重置
                        me.resetload();
                    },1000);
                },
                error: function(xhr, type){
                    alert('Ajax error!');
                    // 即使加载出错，也得重置
                    me.resetload();
                }
            });
        }
    });
});
</script>
</body>
</html>
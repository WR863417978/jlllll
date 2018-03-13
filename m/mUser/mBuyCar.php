<?php
include "../../library/mFunction.php";
echo head('m',NULL);
processBuyCarNotSelect($kehu['khid']);  //将所有购物车表中的选中商品，变为未选中；
$buyCarList     = mBuyCar::index();    //组装商品列表；
$info           = mBuyCar::$goodsData;
//$defaultRegion  = mBuyCar::defaultRegion();
//$shoppingPerson = mBuyCar::shoppingName();
$intrgralCode = getInegralAndProfit($kehu['khid']);  //计算所有商品，预计获得的积分数量；未生效；

//购货人
if( empty($_SESSION['contacts']['kehuName']) )
{
    if(!empty($kehu['name'])){
        $shoppingPerson = $kehu['name'];
    }else{
        $shoppingPerson = $kehu['wxNickName'];
    }
    $shoppingKhid = $kehu['khid'];
}else{
    $shoppingPerson = $_SESSION['contacts']['kehuName'];
    $shoppingKhid = $_SESSION['contacts']['otherKhid'];
}

?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting">
        <a href="<?php echo root; ?>m/mUser/mUser.php" class="header-btn header-return">
            <span class="return-ico">&#xe614;</span>
        </a>
        <div class="align-content">
            <p class="align-text">订货单</p>
        </div>
        <a href="javascript:;" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!-- 一个店铺 -->
<div class="container mui-ptopsmaple mb180">
	<!--更改购货人-->
		<dl class="address-lists">
			<dt><a class="mui-dis-flex" href="<?php echo root;?>m/mUser/mPurchaseMx.php"><label class="flex1"><i class="return-people">&#xe64e;</i><span><?php echo $shoppingPerson;?></span><em>【更改购货人】</em> </label><span class="return-ico">&#xe62e;</span></a></dt>
			<dd>
				<h2><span><?php echo $shoppingPerson;//$_SESSION['contacts']['kehuName'];?></span><em>(<?php echo $shoppingKhid;//$_SESSION['contacts']['otherKhid'];?>)</em></h2>
				<ul class="mui-dis-flex">
					<li><span name='bNum'><?php echo $info['num'];?></span><br/><em>商品总数</em></li>
					<li><span name='bCode'><?php echo $intrgralCode['intrgralCode'];?></span><br/><em>可获积分</em></li>
					<li><span name='bTotal'><?php echo $info['totalMon'];?></span><br/><em>总金额</em></li>
				</ul>
			</dd>
		</dl>
		<!--//-->
		<p class="delete-all mui-dis-flex">
            <span class="flex1">产品信息</span>
            <label class='delAll'>全部删除</label>
        </p>
		<p class="go-on mui-dis-flex continue-browsing">
            <label class="flex1"><i>继续购买</i></label>
            <span></span>
        </p>
        <div class="buycart">
            <ul>
                <?php echo $buyCarList;#订货单列表?>
                <!-- <li class="one-goods">
                    <div class="goods-msg"><br />
                        <label class="option-btn">
                            <span>
                                <input type="checkbox" class="goods-check GoodsCheck">
                            </span>
                        </label>
                        <img src="<?php echo img('wOZ84129241GJ'); ?>"/>
                        <div class="goods-num">
							<h2>描述描述描述描述描述描述描</h2>
							<p class="price">￥<span class="shop-total-amount GoodsPrice">30.00</span></p>
							<p class="mui-dis-flex">
								<label><span>利：20.00</span></label>
								<button type="button" class="minus amount-btn amount-push">-</button>
								<input type="text" class="am-num-text amount-value" value="1" />
								<button type="button" class="plus amount-btn amount-#a41203uce">+</button>
							</p>
						</div>
                    </div>
                    <div class="options"><a class="delete">&#xe607;</a></div>
                </li> -->
            </ul>
            <!-- 店铺合计 -->
            <div class="buycart-ctrl  mui-wrap-style1 mui-sheet mui-fixed">
                <div class="shop-total mui-dis-flex">
                    <label class="option-btn">
                        <span>
                            <input type="checkbox" class="goods-check ShopCheck">
                        </span>
                    </label>
                    <span>全选</span>
                    <p class="flex1 buycar-btn">
                        <em>金额合计：￥<span class="shop-total-amount ShopTotal">0</span></em>
                        <a href="javascript:;" class="settlement">去提交</a>
                        <!-- <a href="<?php echo root;?>m/mEditOrder.php" class="settlement">去提交</a> -->
                    </p>
                </div>
            </div>
        </div>
</div>
<?php echo mWarn();?>
<script>
    $(function(){
        $(".settlement").on("click",function(){
            $(".cover").show();
        });
        $("#close").on("click",function(){
            $(".cover").hide();
        });
    });
    // 数量减
    $(".minus").click(function() {
        var bid = $(this).data('bid');
        var t = $(this).parent().find('.am-num-text');
        var priceObj = $(this).parent().parent().find('.GoodsPrice');
        t.val(parseInt(t.val()) - 1);
        if(t.val() <= 1) {
            t.val(1);
        }
        TotalPrice();
//        changeBuyNum(priceObj,bid,t.val());
        var id = bid;
        var num = t.val();
        $.post("<?php echo root?>"+"library/mData.php?type=changeBuyNum",{id:id,num:num},function(data){
            if(data.warn == 2){
                $("[name='bNum']").html( data.arr.shopNum );
                $("[name='bCode']").html( data.arr.intrgralCode );
                $("[name='bTotal']").html( data.arr.totalPrice );
                console.log( $("[name='bNum']").html() );
                console.log( $("[name='bCode']").html() );
                console.log( $("[name='bTotal']").html() );
                priceObj.html( data.price );
            }else{
                if(data.type == 'onlyone'){
                    t.val(data.nowNum);
                }
                mwarn(data.warn);
            }
        },'json');
    });
    // 数量加
    $(".plus").click(function() {
        var bid = $(this).data('bid');
        var t = $(this).parent().find('.am-num-text');
        var priceObj = $(this).parent().parent().find('.GoodsPrice');
        console.log( priceObj.html() );
        t.val(parseInt(t.val()) + 1);
        if(t.val() <= 1) {
            t.val(1);
        }
        TotalPrice();
//        changeBuyNum(priceObj,bid,t.val());
        var id = bid;
        var num = t.val();
        $.post("<?php echo root?>"+"library/mData.php?type=changeBuyNum",{id:id,num:num},function(data){
            if(data.warn == 2){
                $("[name='bNum']").html( data.arr.shopNum );
                $("[name='bCode']").html( data.arr.intrgralCode );
                $("[name='bTotal']").html( data.arr.totalPrice );
                console.log( $("[name='bNum']").html() );
                console.log( $("[name='bCode']").html() );
                console.log( $("[name='bTotal']").html() );
                priceObj.html( data.price );
            }else{
                if(data.type == 'onlyone'){
                    t.val(data.nowNum);
                }
                mwarn(data.warn);
            }
        },'json');

    });

    $(".amount-value").blur(function () {
        console.log($(this).next('.plus'));
        var bid = $(this).next('.plus').data('bid');
        var t = parseInt($(this).val());
        var priceObj = $(this).parent().parent().find('span.GoodsPrice');
        console.log(bid,t,priceObj);
        TotalPrice();
//        changeBuyNum(priceObj,bid,t);
        var id = bid;
        var num = t.val();
        $.post("<?php echo root?>"+"library/mData.php?type=changeBuyNum",{id:id,num:num},function(data){
            if(data.warn == 2){
                $("[name='bNum']").html( data.arr.shopNum );
                $("[name='bCode']").html( data.arr.intrgralCode );
                $("[name='bTotal']").html( data.arr.totalPrice );
                console.log( $("[name='bNum']").html() );
                console.log( $("[name='bCode']").html() );
                console.log( $("[name='bTotal']").html() );
                priceObj.html( data.price );
            }else{
                if(data.type == 'onlyone'){
                    t.val(data.nowNum);
                }
                mwarn(data.warn);
            }
        },'json');
    });
    // 点击商品按钮
    $(".GoodsCheck").click(function() {
        var goods = $(this).closest(".buycart").find(".GoodsCheck"); //获取本店铺的所有商品
        var goodsC = $(this).closest(".buycart").find(".GoodsCheck:checked"); //获取本店铺所有被选中的商品
        var Shops = $(this).closest(".buycart").find(".ShopCheck"); //获取本店铺的全选按钮



        //jia

        if($(this).prop("checked")){
            $(this).parent().css("background","#e32b2b");
        }else{
            $(this).parent().css("background","#fff");
        }

        //

        if(goods.length == goodsC.length) { //如果选中的商品等于所有商品
            Shops.prop('checked', true); //店铺全选按钮被选中
            if($(".ShopCheck").length == $(".ShopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
                $("#AllCheck").prop('checked', true); //全选按钮被选中

                //jia
                $(".ShopCheck").parent().css("background","#e32b2b");
                //

                TotalPrice();
            } else {
                $("#AllCheck").prop('checked', false); //else全选按钮不被选中

                //jia
                $(".ShopCheck").parent().css("background","#fff");
                //


                TotalPrice();
            }
        } else { //如果选中的商品不等于所有商品
            Shops.prop('checked', false); //店铺全选按钮不被选中
            $("#AllCheck").prop('checked', false); //全选按钮也不被选中

            //jia
            $(".ShopCheck").parent().css("background","#fff");
            //

            // 计算
            TotalPrice();
            // 计算
        }
    });
    // 点击店铺按钮
    $(".ShopCheck").change(function() {
        if($(this).prop("checked") == true) { //如果店铺按钮被选中
            $(this).parents(".buycart").find(".goods-check").prop('checked', true); //店铺内的所有商品按钮也被选中
            if($(".ShopCheck").length == $(".ShopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
                $("#AllCheck").prop('checked', true); //全选按钮被选中

                //jia
                $(this).parent().css("background","#e32b2b");
                $(".GoodsCheck").parent().css("background","#e32b2b");
                //

                TotalPrice();
            } else {
                $("#AllCheck").prop('checked', false); //else全选按钮不被选中

                //jia
                $(".ShopCheck").parent().css("background","#fff");
                $(".GoodsCheck").parent().css("background","#fff");
                //

                TotalPrice();
            }
        } else { //如果店铺按钮不被选中
            $(this).parents(".buycart").find(".goods-check").prop('checked', false); //店铺内的所有商品也不被全选
            $("#AllCheck").prop('checked', false); //全选按钮也不被选中

            //jia
            $(".ShopCheck").parent().css("background","#fff");
            $(".GoodsCheck").parent().css("background","#fff");
            //

            TotalPrice();
        }
    });
    // 点击全选按钮
    $("#AllCheck").click(function() {
        if($(this).prop("checked") == true) { //如果全选按钮被选中
            $(".goods-check").prop('checked', true); //所有按钮都被选中
            TotalPrice();
        } else {
            $(".goods-check").prop('checked', false); //else所有按钮不全选
            TotalPrice();
        }
        $(".ShopCheck").change(); //执行店铺全选的操作
    });

    function TotalPrice() {
        var allprice = 0; //总价
        $(".buycart").each(function() { //循环每个店铺
            var oprice = 0; //店铺总价
            $(this).find(".GoodsCheck").each(function() { //循环店铺里面的商品
                if($(this).is(":checked")) { //如果该商品被选中
                    var num = parseInt($(this).parents(".one-goods").find(".am-num-text").val()); //得到商品的数量
                    var price = parseFloat($(this).parents(".one-goods").find(".GoodsPrice").text()); //得到商品的单价
                    var total = price * num; //计算单个商品的总价
                    oprice += total; //计算该店铺的总价
                }
                $(this).closest(".buycart").find(".ShopTotal").text(oprice.toFixed(2)); //显示被选中商品的店铺总价
            });
            var oneprice = parseFloat($(this).find(".ShopTotal").text()); //得到每个店铺的总价
            allprice += oneprice; //计算所有店铺的总价
        });
        $("#AllTotal").text(allprice.toFixed(2)); //输出全部总价
    }
    //数量变化
    function changeBuyNum(priceObj,id,num){
        $.post("<?php echo root?>"+"library/mData.php?type=changeBuyNum",{id:id,num:num},function(data){
            if(data.warn == 2){
                $("[name='bNum']").html( data.arr.shopNum );
                $("[name='bCode']").html( data.arr.intrgralCode );
                $("[name='bTotal']").html( data.arr.totalPrice );
                console.log( $("[name='bNum']").html() );
                console.log( $("[name='bCode']").html() );
                console.log( $("[name='bTotal']").html() );
                priceObj.html( data.price );
            }else{
                mwarn(data.warn);
            }
        },'json');
    }
    //删除
    $('.deleteBtn').on('click',function(){
        var bid = $(this).data('bid');
        $.post("<?php echo root?>"+"library/mData.php?type=delBuyCar",{id:bid},function(data){
            if(data.warn == 2){
                location.reload();       
            }else{
                mwarn(data.warn);
            }
        },'json'); 
    });
    //提交
    $('.settlement').on('click',function(){
        var jsonObj = {};
        var jsonArr = [];
        var otherKhid = '<?php echo $_SESSION['contacts']['otherKhid'];?>';
        $('.GoodsCheck').each(function(){
            if( $(this).is(':checked') && $.trim($(this).data('bid')).length > 0 ){
                console.log( $(this).data('bid') );
                jsonArr.push( $(this).data('bid') );
                jsonObj['id'] = jsonArr;
            }
        });
        $.post("<?php echo root?>"+"library/mData.php?type=subBuyCar",jsonObj,function(data){
            if(data.warn == 2 && data.href){
                location.href = data.href;
            }else{
                mwarn(data.warn);
            }
        },'json');
        /* if( $.trim(otherKhid).length > 0 ){
            $.post(root+"library/mData.php?type=subBuyCar",jsonObj,function(data){
                if(data.warn == 2 && data.href){
                    location.href = data.href;
                }else{
                    mwarn(data.warn);
                }
            },'json');
        }else{
            mwarn('请选择购货人');   
        } */
    });
    //订货单删除
    $('.delAll').on('click',function(){
        var num = 0
            jsonObj = {}
            jsonArr = [];
        jsonObj['khid'] = '<?php echo $kehu['khid'];?>';
        $('.GoodsCheck').each(function(){
            var $this = $(this);
            if( $this.is(':checked') ){
                ++num;
                jsonArr.push( $this.data('bid') );
                jsonObj['id'] = jsonArr;
            }
        });
        if( num >= 1 ){
            $.post(root+"library/mData.php?type=delMyBuyCar",jsonObj,function(data){
                if(data.warn == 2){
                    location.reload();
                }else{
                    mwarn(data.warn);
                }
            },'json');    
        }else{
            mwarn('请选择要删除的商品');   
        }
        console.log(jsonObj);        
        console.log(num);
    });
    $('.continue-browsing').click(function(){
        location.href = "<?php echo root?>" + 'm/mIndex.php';
    });
</script>
</body>

</html>
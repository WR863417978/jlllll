<?php
include "../../library/mFunction.php";
include "../../control/ku/newfunction.php";
$ordernum = $_GET['ordernum'];
$order = findsql('order',"order_sn = '$ordernum'");
$ordergood = findsql('order_goods',"order_sn = '$ordernum'");

$addtime = date('Y年m月d日 H:i:s',strtotime($ordergood['addTime']));
switch ($order['workFlow']) {
    case '0':  //未支付
        $state = '未支付';
        break;
    case '1':  //代发货
        $state = '代发货';
        break;
    case '2':
        $state = '待收货';
        break;
    case '3':
        $state = '已收货';
        break;
 	case '4':
        $state = '待评价';
        break;
	case '5':
        $state = '已完成';
        break;
	case '6':
        $state = '已申请退款';
        break;
	case '7':
        $state = '已退款';
        break;
	case '8':
        $state = '已申请退货';
        break;
	case '9':
        $state = '同意退货';
        break;
}

echo head('m');
?>
<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../library/css/order_detail.css">
<body>
    <div class="order">
        <!-- 页头 -->
        <div class="order-header">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="title">订单详情</span>
            <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
        </div>
        <!-- 内容 -->
        <div class="order-content">
            <!-- 店铺 -->
            <div class="shop">
                <div class="shop-info">
                    <p class="shop-name">聚礼优选</p>
                    <span>订单编号：<?php echo $ordernum; ?></span> <br>
                    <span>订购时间：<?php echo $addtime; ?></span> <br>
                    <span>订单状态：<?php echo $state; ?></span>

                </div>
                <div class="img">
                    <img src="../../favicon.ico" alt="">
                </div>
            </div>
            <!-- 商品信息 -->
            <div class="goods">
                <div class="goods-info">
                    <!--<div class="goods-add">icon</div>-->
                    <div class="goods-id">
                        <span>收货人：<?php echo $order['address_name']; ?></span>
                        <span style="margin:0;">收货人电话：<?php echo $order['address_tel']; ?></span>
                        <p>收获地址：<?php echo $order['address_detail'];?></p>
                    </div>
                </div>
                <div class="goods-info2">
                    <!--<div class="goods-add">icon</div>-->
                    <div class="goods-id">
                        <span>发票抬头：<?php echo $order['tax_title']; ?></span>
                        <p>税号：<?php echo $order['tax_num']; ?></p>
                    </div>
                </div>
            </div>
            <!-- 产品 -->
            <div class="pro">
                <div class="pro-item">
                    <div class="pro-img">
                        <img src="/<?php echo $ordergood['goods_icon']; ?>" alt="">
                    </div>
                    <div class="pro-info">
                        <div class="pri-old">
                            <span><?php echo $ordergood['goodsName']; ?></span>
                            <span class="price">￥<?php echo $ordergood['buyPrice']; ?></span>
                        </div>
                        <!--<div class="pri-old">
                            <span>产品名称</span>
                            <span class="price old">￥4566</span>
                        </div>-->
                        <div class="pri-old rules">
                            <span class="">商品规格：<?php echo $ordergood['goodsSkuName']; ?></span>
                            <span class="price">*<?php echo $ordergood['buyNumber']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 产品详情 -->
            <div class="pro-detail">
                <div class="price-all">
                    <div class="price-part">
                        <span>商品总价：</span>
                        <span class="price">￥56789.00</span>
                    </div>
                    <div class="price-part">
                        <span>运费：</span>
                        <span class="price">￥6789.00</span>
                    </div>
                    <div class="price-part">
                        <span>税点：</span>
                        <span class="price">￥789.00</span>
                    </div>
                    <div class="price-part">
                        <span>优惠卷使用：</span>
                        <span class="price">￥89.00</span>
                    </div>
                    <div class="line"></div>
                    <div class="price-part all">
                        <span>实付款：</span>
                        <span class="price">￥89.00</span>
                    </div>
                </div>
                <div class="price-back">
                    <div class="price-part">
                        <span>返佣金额：</span>
                        <span class="price">￥123.00</span>
                    </div>
                    <div class="price-part">
                        <span>自购积分：</span>
                        <span class="price">12345</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- 底部 -->
        <div class="order-foot">
            <button class="btn btn-default" type="submit">查看物流</button>
            <button class="btn btn-default" type="submit">申请售后</button>
        </div>
    </div>
<?php echo mFooter();echo mWarn(); ?>
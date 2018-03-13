<?php
include "../../library/mFunction.php";
echo head('m');
function intrfralExchange($khid)
{
    $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.type = '积分订单' AND b.khid = '{$khid}' AND b.goodsId = g.id ORDER BY b.id DESC";
    $res = myQuery($sql);
    if( $res['0']['sqlRow'] > 0 ){
        foreach ($res as $val) {
            $html .= "
            <div class='order-lists'>
                <!--<h2 class='mui-dis-flex'><span class='flex1'>张三：+86 12345678798</span></h2>-->
                <dl>
                    <dd><img src='".root."{$val['ico']}'/></dd>
                    <dd class='info'>
                        <p>{$val['goodsName']}</p>
                        <p><span class='mui-red'>耗费积分：{$val['buyPrice']}</span> <!--<span>已发货</span></p>
                        <p>重庆市南岸区国际社区4-4-3</p>-->
                    </dd>
                    <dd></dd>
                </dl>
            </div>";
        }
    }
    return $html;
}
$html = intrfralExchange($kehu['khid']);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root; ?>m/mUser/mIntegral.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">积分兑换历史</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--订单管理-->
<div class="container">
    <div class="mui-mbottom60 order-list mui-ptopsmaple">
        <div class="order">
            <?php echo $html;?>
            <!-- <div class="order-lists">
                <h2 class="mui-dis-flex"><span class="flex1">张三：+86 12345678798</span></h2>
                <dl>
                    <dd><img src="<?php echo img('wOZ84129241GJ'); ?>"/></dd>
                    <dd class="info">
                        <p> 绿侬寿山石桶珠藏式隔珠腰珠顶珠散珠子手链手串DIY星月菩提配饰</p>
                        <p><span class="mui-red">耗费积分：126</span> <span>已发货</span></p>
                        <p>重庆市南岸区国际社区4-4-3</p>
                    </dd>
                    <dd></dd>
                </dl>
            </div> -->
        </div>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
})
</script>
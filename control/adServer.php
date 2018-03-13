<?php
//订单管理列表页
include "ku/adfunction.php";
include "ku/easemob.php";//环信接口类
ControlRoot("adOrder");
$options = [
    'client_id'=>'YXA6aVUj8NWvEeeUJhu7NiDc8A',
    'client_secret'=>'YXA6aIjirOEWGogJtQOQpak-foq5K-M',
    'org_name'=>'1119171130115579',
    'app_name'=>'juli'
];
$ease = new Easemob($options);
$token = $ease->getToken();
echo '<pre>';
    print_r($token);
echo '</pre>';
$username = 'r7';
$password = 123456;
$createUser = $ease->createUser($username,$password);
echo '<pre>';
    print_r($createUser);
echo '</pre>';
$onion = array(
    "聊天管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="column minHeight">
        <div class="search">
            <form name="search" action="<?php echo root."control/ku/adpost.php?type=adSearchOrder";?>" method="post">
                订单号：<input name="SearchOrderGoodsId" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['id'];?>">
                商品名称：<input name="SearchGoodsName" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['goodsName'];?>">
                收货人姓名：<input name="SearchOrderKhName" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['addressName'];?>">
                手机号码：<input name="SearchOrderKhtel" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['addressTel'];?>">
                收货地址：<input name="SearchOrderAddress" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['addressMx'];?>">
                <?php echo select("WorkFlow","select textPrice","--订单状态--",array("未选定","已选定","已付款","已发货","已收货","已评价","已退款"),$_SESSION['SearchOrder']['workFlow']);?>
                <input type="submit" value="模糊查询">
            </form>
        </div>
        <div class="search">
            <span onclick="$('[name=OrderForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <span onclick="$('[name=OrderForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条订单&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!-- 列表开始-->
        <form name="OrderForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td style="width:180px">商品名称</td>
                    <td>规格名称</td>
                    <td>物流单号</td>
                    <td>订单号</td>
                    <td>收货人</td>
                    <td>手机号码</td>
                    <td>收货地址</td>
                    <td>购买数量</td>
                    <td>购买单价</td>
                    <td>订单状态</td>
                    <td>付款时间</td>
                    <td></td>
                </tr>
                <?php echo $list;?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--订单列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
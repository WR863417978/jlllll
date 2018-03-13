<?php
//订单详情页
include "ku/adfunction.php";
ControlRoot("adOrder");
if(empty($_GET['id'])){
    $title = "订单详情";
    die('订单不存在');
}else {
    $title = "订单详情";
    $sql = "select * from `order` as o left join order_goods as og  on o.order_sn=og.order_sn WHERE o.order_sn='$get[id]' ";
    $pdo = newPdo();
    $a = $pdo->query($sql);
    $order = $a->fetchAll(PDO::FETCH_ASSOC);
//    print_r($order);
    if ($order[0]['order_sn'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个订单的信息";
        header("location:{$root}control/adOrder.php");
        exit(0);
    }

    $purcharseKh = findOne('kehu',"khid={$order['0']['pay_khid']}");
    empty($purcharseKh['name']) ? $purcharseKhName = $purcharseKh['wxNickName'] : $purcharseKhName = $purcharseKh['name'];

}
//物流信息；




$onion = array(
    "订单管理" => root."control/adOrder.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--订单明细开始-->
        <?php echo $top;?>
        <div class="profitDiv">
            <div class="profitinside">
                <div class="layui-row">
                    <div class="layui-col-md6">
                        <ul>
                            <li>订单号：<?php echo $order[0]['order_sn']; ?></li>
                            <li>
                                下单客户：<?php echo $purcharseKhName.'('.$order[0]['pay_khid'].')'; ?>&nbsp;
                                <a target="_blank" href="adClientMx.php?id=<?php echo $order[0]['pay_khid']; ?>"><span class="spanButton">查看客户详情</span></a>
                            </li>
                            <li>支付方式：<?php echo $order[0]['pay_type'];?></li>
                            <li>订单状态：<cc id="cc"> <?php echo showWorkFlow($order[0]);?> </cc>
                                <?php
                                switch ($order[0]['workFlow']){
                                    case '0':
                                        echo <<<eof
                <button id="tipToPay" class="layui-btn layui-btn-normal layui-btn-sm">提醒支付</button>
eof;
                                        break;
                                    case '1':
                                        echo <<<fds
                <button id="writeExpressData" class="layui-btn layui-btn-normal layui-btn-sm">填写物流单号</button>
fds;
                                    case '2':

                                        break;

                                        break;
                                }
                                ?>
                            </li>
                            <?php if($order[0]['workFlow']==6||$order[0]['workFlow']==7){?>
                                <?php
                                $sql = "select * from refundReason where order_sn = ".$order[0]['order_sn'];
                                $b = $pdo->query($sql);
                                $reason = $b->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <li>申请原因： <textarea disabled><?php echo $reason['reason'];?></textarea></li>
                                <li>申请时间：<?php echo $reason['time']?> &nbsp;&nbsp;
                                    <b target="_blank" id="tuikuan" href="#" ordersn=<?php echo $order[0]['order_sn']; ?>><span class="spanButton">一键退款</span></b></li>
                            <?php }?>

                            <li>下单时间：<?php echo $order[0]['ctime']; ?></li>
                        </ul>
                    </div>
                    <div class="layui-col-md6">
                        <li>收件人：<?php echo $order[0]['address_name'];?></li>
                        <li>收件人电话：<?php echo $order[0]['address_tel']?></li>
                        <li>收件人地址：<?php echo $order[0]['address_detail'];?></li>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <img src="<?php echo root."img/images/text.png";?>">
        物流信息
        <div class="profitDiv">
            <?php
            if($order['0']['workFlow'] == 1){
                echo <<<eof
            <div class="profitinside">
                <ul>
                    '填写订单号后才能查询物流信息'
                </ul>
                <div class="clear"></div>
            </div>
eof;
            }else{
                echo <<<OFF
            <div class="layui-row">
                    <div class="layui-col-md6">
                        <ul>
                            <li>物流名称:{$order[0]['express_name']}</li>
                            <li>运单号:{$order[0]['express_number']}</li>
                        </ul>
                    </div>
            </div>
            <div class="profitinside">
                <br/>
                <button id="getExpress" data-express="{$order['0']['express_number']}" class="layui-btn">查询物流信息</button>
                <div class="clear"></div>
            </div>
OFF;
            }
            ?>

        </div>
        <div class="kuang">
            <form name='OrderForm'>
                <table class='tableMany'>
                    <tr>
                        <td>订单操作</td>
                        <td>商品ID</td>
                        <td>商品名称</td>
                        <td>商品规格</td>
                        <td>商品单价</td>
                        <td>购买数量</td>
                    </tr>
                        <?php foreach($order as $k=>$v){?>
                    <tr>
                        <td><span class='spanButton'>操作</span></td>
                            <td><?php echo $v['goodsId']?></td>
                            <td><?php echo $v['goodsName']?></td>
                            <td><?php echo $v['goodsSkuName']?></td>
                            <td><?php echo $v['buyPrice']?></td>
                            <td><?php echo $v['buyNumber']?></td>
                            </tr>
                        <?php }?>
                </table>
            </form>
        </div>
        <!--订单明细结束-->
    </div>
    <!--状态修改信息弹出层开始-->
    <div class="hide" id="order">
        <div class="dibian"></div>
        <div class="win" style="width: 600px; height:auto!important; margin: -174px 0px 0px -300px;">
            <p class="winTitle">编辑订单<span onclick="$('#order').hide()" class="winClose">×</span></p>
            <form name="talkForm">
                <table class="tableRight">
                    <tr>
                        <td>当前订单状态：</td>
                        <td><p id="workFlow"></p></td>
                    </tr>
                    <tr>
                        <td>物流单号：</td>
                        <td><input name="logisticsNum" type="text" class="text" value=""/></td>
                    </tr>
                    <tr>
                        <td>物流公司：</td>
                        <td><textarea name="logisticsName" style="width:410px; height: 80px;"></textarea></td>
                    </tr>
                    <tr>
                        <td>订单状态：</td>
                        <td>
                            <?php echo select("workFlow","select","--订单状态--",array("待发货","已发货","已退款"));?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input name="orderId" type="hidden"/>
                        </td>
                        <td><input type="button" class="button" onclick="Sub('talkForm','<?php echo root."control/ku/addata.php?type=OrderText";?>')" value="确认提交"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <!--状态修改信息弹出层结束-->
    <script>
        $(function(){
//            var layer = layui.layer;
            $("[eidterOrder]").click(function(){
                var id = $(this).attr("eidterOrder");
                $.post("<?php echo root;?>control/ku/addata.php?type=lookOrder",{id:id},function(data){
                    $("input[name=logisticsNum]").val(data.warn['logisticsNum']);
                    $("textarea[name=logisticsName]").html(data.warn['logisticsName']);
                    $("select[name=workFlow]").val(data.warn['workFlow']);
                    $("input[name=orderId]").val(data.warn['id']);
                },"json");
                $("#order").show();
            });
            //
            $("#tuikuan").click(function(){
                var order_sn = $(this).attr("ordersn");
                $.post("<?php echo root;?>control/ku/refund.php?type=refund",{order_sn:order_sn},function(data){
                    if(data.code == 200){
                        $("#cc").html("已退款");
                    }
                },"json");
            });
            //获取物流信息；
            $('#getExpress').click(function (e) {
                var target = e.target;
                var expressNumber = $(target).data('express');
                $.post("<?php echo root;?>control/ku/addata.php?type=getExpressDataAdmin",{'express_number':expressNumber},function (data) {
                    var jsonData = $.parseJSON(data);
                    console.log(jsonData.html);
                    $(target).parent().prepend($(jsonData.html));
                });
            });
            //提示支付
            $('#tipToPay').click(function (e) {
                layui.use('layer', function(){
                    var layer = layui.layer;
                    layer.msg('暂时未做');
                });
            });
            //填写物流信息
            $('#writeExpressData').click(function () {
                layui.use('layer', function(){
                    var layer = layui.layer;
                    layer.open({
                        type:1,
                        title:'填写物流信息',
                        content: $('#express_area')
                        ,btn: ['保存']
                        ,yes: function(index, layero){
                            var ceng = $(layero);
                            var express_name = ceng.find('input[name=express_name]').val();
                            var express_number = ceng.find('input[name=express_number]').val();
                            var order_sn = ceng.find('input[name=express_number]').data('order');
                            if(express_name == '' || express_number==''){
                                layer.msg('物流信息均不能为空');
                            }else{
                                $.post('<?php echo root;?>control/ku/addata.php?type=writeOrderExpressData',{expressName:express_name,expressNumber:express_number,orderSN:order_sn},function (data) {
                                    var jsonData = $.parseJSON(data);
                                    if(jsonData.code == 0){
                                        layer.msg('保存成功',function(){
                                            layer.close(index);
                                            location.reload();
                                        });
                                    }else{
                                        layer.msg('失败，请重新填写');
                                    }
                                });
                            }
//475439644543
                        }
                    });

                });
            });

        })
    </script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
<div id="express_area" style="display: none">
    <div class="layui-form-item">
        <label class="layui-form-label">物流公司</label>
        <div class="layui-input-block">
            <input type="text" name="express_name" placeholder="请输入物流" autocomplete="off" class="layui-input">
        </div>
    <div class="layui-form-item">
        <label class="layui-form-label">运单号</label>
        <div class="layui-input-block">
            <input type="text" name="express_number" placeholder="请输入运单号" autocomplete="off" class="layui-input" data-order="<?php echo $order[0]['order_sn']?>">
        </div>
    </div>
</div>

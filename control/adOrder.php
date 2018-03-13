<?php
//订单管理列表页
include "ku/adfunction.php";
ControlRoot("adOrder");
$list = "";
$ThisUrl = $adroot."adOrder.php?".$_SERVER['QUERY_STRING'];
$sql = "select * from `order` WHERE 1=1 ".$_SESSION['SearchOrder']['Sql'];
paging($sql," order by updateTime desc",4);
$select = getSelect();
$select1 = getSelecttype();
if($num == 0){
    $list .= "<tr><td colspan='13'>一个订单都没有</td></tr>";
}else{
    while($array = mysql_fetch_assoc($query)){
        $array = showOrder($array);
        $receiveUser = query("kehu","khid='{$array['target_khid']}'");
        empty($receiveUser['name'])? $receiveUserName = $receiveUser['wxNickName'] :  $receiveUserName=$receiveUser['name'];

//        <td><input name='OrderList[]' type='checkbox' value='{$array['id']}'/></td>
        $list .= "
            <tr>
                  <td><a href='/control/adOrderMx.php?id={$array['order_sn']}'>{$array['order_sn']}</a></td>
                  <td>{$array['pay_type']}</td>
                  <td>{$array['pay_type_online']}</td>
                  <td><a href='/control/adClientMx.php?id={$receiveUser['khid']}'>{$receiveUserName}</a></td>
                  <td>{$array['o_type']}</td>
                  <td>{$array['money']}元</td>
                  <td>{$array['workFlow']}</td>
                  <td>{$array['updateTime']}</td>
                  <td>{$array['ctime']}</td>
                  <td>
                    <a style='color:white' href='{$adroot}adOrderMx.php?id={$array['order_sn']}' class='layui-btn layui-btn-normal'>详情</a>
                    <button class='layui-btn layui-btn-normal'>提醒支付</button>
                  </td>
            </tr>";
    }
}

$onion = array(
    "订单管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<style>
    .search2{
        margin-top:15px;
        margin-bottom:-10px;
        border-left:1px solid #CAD9EA;
        border-top:1px solid #CAD9EA;
        border-right:1px solid #CAD9EA;
    }
    .order_list {
        float: left;
        display:inline-block;
    }

    .search2 .order_list li{
        float:left;
        display:inline-block;
        padding:5px;
        line-height: 30px;
        width:80px;
        text-align: center;
    }
    .layui-form-item{
        display: inline-block;
    }
    .nav-order-on{
        background: #EBF2F8;
    }



</style>
<!--<script type="text/javascript" src="ku/js/jquery-1.4.2.min.js"></script>-->


<script type="text/javascript">
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#begin_time'
            , type: 'datetime'
            , range: false
        });
        laydate.render({
            elem: '#end_time'
            , type: 'datetime'
            , range: false
        });
    });
</script>
    <div class="minHeight">
        <div class="search">
            <form name="search" class="layui-form" action="<?php echo root."control/ku/adpost.php?type=adSearchOrder";?>" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">订单号</label>
                    <div class="layui-input-block">
                        <input name="order_sn" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchOrder']['order_sn'];?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单状态</label>
                    <div class="layui-input-block">
                        <select name="workFlow" style="display: inline-block;padding: 4px;" class="textPrice">
                            <option value=''>全部</option>
                            <?php for($i=1;$i<11;$i++){?>
                                <option value=<?php echo $i;?>
                                        <?php if($_SESSION['SearchOrder']['workFlow']==$i&&$_SESSION['SearchOrder']['workFlow']!=''){
                                            echo 'selected';
                                        }?> ><?php echo $select[$i]?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单类型</label>
                    <div class="layui-input-block">
                        <select name="o_type" class="textPrice" style="display: inline-block;padding: 4px;">
                            <option value=''>全部</option>
                            <?php for($i=1;$i<3;$i++){?>
                                <option value=<?php echo $i;?>
                                        <?php if($_SESSION['SearchOrder']['o_type']==$i&&$_SESSION['SearchOrder']['o_type']!=''){
                                            echo 'selected';
                                        }?> ><?php echo $select1[$i]?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <br/>
                <div class="layui-form-item">
                    <label class="layui-form-label">下单时间</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <!--<input type="text" name="rstime" value="--><?php //echo $_SESSION['SearchOrder']['rstime'];?><!--" class="text textPrice datepicker">-->
                                <input type="text" name="rstime" class="layui-input" id="begin_time" placeholder="开始时间" value="<?php echo $_SESSION['SearchOrder']['rstime'];?>">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <!--<input type="text" name="rdtime" class="text textPrice datepicker" value="--><?php //echo $_SESSION['SearchOrder']['rdtime'];?><!--">-->
                                <input type="text" name="rdtime" class="layui-input" id="end_time" placeholder="结束时间" value="<?php echo $_SESSION['SearchOrder']['rdtime'];?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">支付方式</label>
                    <div class="layui-input-block">
                        <?php echo select("pay_type","textPrice","全部",array("在线支付","银行转账"),$_SESSION['SearchOrder']['pay_type']);?>
                    </div>
                </div>
                <input type="submit" class="layui-btn" value="筛选订单">
            </form>
        </div>
        <div>
            <div class="search2">
                <ul class="order_list">
                    <li class="<?php echo myMenuGet('ordertype',['singleAll',''],'nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleAll";?>">全部</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleNoPay','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleNoPay";?>">待付款</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleToSend','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleToSend";?>">待发货</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleHadSend','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleHadSend";?>">待收货</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleWaitTalk','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleWaitTalk";?>">待评价</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleApplyBackMoney','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleApplyBackMoney";?>">申请退款</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleApplyBackGoods','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleApplyBackGoods";?>">申请退货</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleAgreeBackGoods','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleAgreeBackGoods";?>">同意退货</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleHaveBackMoney','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleHaveBackMoney";?>">已退款</a></li>
                    <li class="<?php echo myMenuGet('ordertype','singleTradeSuccess','nav-order-on');?>"><a href="<?php echo root."control/ku/adpost.php?type=singleOrder&ordertype=singleTradeSuccess";?>">交易完成</a></li>
                </ul>
                <div style="clear: both;"></div>
            </div>
            <!--查询结束-->
            <!-- 列表开始-->
            <form name="OrderForm">
                <table class="tableMany" style="text-align: center">
                    <tr>
                        <td style="width:200px">订单号</td>
                        <td>付款方式</td>
                        <td>支付方式</td>
                        <td>下单客户</td>
                        <td>订单类型</td>
                        <td>订单金额</td>
                        <td>订单状态</td>
                        <td>更新时间</td>
                        <td>创建时间</td>
                        <td></td>
                    </tr>
                    <?php echo $list;?>
                </table>
            </form>
        </div>
        <div>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条订单&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		    </span>
        </div>
        <?php echo fenye($ThisUrl,5);?>
        <!--订单列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
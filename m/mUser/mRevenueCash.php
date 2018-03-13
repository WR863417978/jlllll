<?php
include "../../library/mFunction.php";
echo head('m');
$withdraw = Income::withdraw($kehu['khid'],TRUE);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mRevenue.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">提现明细</p>
        </div>
        <a href="#" class="header-btn" id="screen"></a>
    </div>
</div>
<!--//-->
<!--收入查询-->
<div class="container mui-pt45  mui-mbottom60">
    <div class="wait-pay">
        <dl>
            <dt class="mui-dis-flex"><label class="flex1">提现审核7个工作日，到账金额扣除手续费</label></dt>
        </dl>
        <style>
            .cash-list {padding-bottom: 30px;}
            .cash-list ul{margin:10px;border:1px solid #f5f5f5;border-radius: 3px;}
            .cash-list ul li{padding:6px 10px;font-size: 14px;}
            .cash-list ul li span{color: red;}
        </style>
        <div class="cash-list">
            <?php echo $withdraw;?>
            <!-- <ul>
                <li>提现时间： 2017-11-09 16:28:55</li>
                <li>提现金额：555:22元</li>
                <li><label>状态：</label><span>已经支付成功</span></li>
            </ul> -->
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
    $("#screen").on("click",function(){
        $(".cover-revenue").show();
    });
    $(".addPassenger_btn").on("click",function(){
        $(".cover-revenue").hide();
    });
})
</script>
<?php
include "../../library/mFunction.php";
echo head('m');
$dataArr = Income::tobeSettled($kehu['khid'],TRUE);
?>
<style>
.mui-dis-flex{border-bottom :1px solid white}
.wait-pay dl dt label span{width:48%}
</style>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">待结算金额</p>
        </div>
        <a href="#" class="header-btn" id="screen"></a>
    </div>
</div>
<!--//-->
<!--收入查询-->
<div class="container mui-pt45  mui-mbottom60">
    <div class="wait-pay">
        <dl>
            <?php echo $dataArr['html'];?>
            <!-- <dt class="mui-dis-flex"><label class="flex1">待结算金额<i>(审核中)</i><span>50</span></label><em>元</em></dt> -->
            <dd>为了保障财务安全，客户确认收货后30个工作日进入可提现金额</dd>
        </dl>
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
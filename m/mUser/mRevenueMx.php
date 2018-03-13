<?php
include "../../library/mFunction.php";
echo head('m');
$html = Filter::index($kehu['khid'],$get);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mRevenue.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">收入明细</p>
        </div>
        <a href="javascript:;" class="header-btn" id="screen">筛选</a>
    </div>
</div>
<!--//-->
<!--收入查询-->
<div class="container mui-pt45  mui-mbottom60">
    <div class="edit-top">
        <h3>全部收入</h3>
        <label>所有时间及团队</label>
    </div>
    <!--收入列表-->
    <div class="revenue-list">
        <?php echo $html;?>
        <!-- <dl class="mui-dis-flex">
            <dt>
                <p>李丽</p>
                <p>ID：123456798</p>
            </dt>
            <dd class="flex1">
                <ul>
                    <li>订单号：12345679810</li>
                    <li>销售额：1024</li>
                    <li>交易时间：2017-11-09 15:01</li>
                    <li>返佣额：300</li>
                </ul>
            </dd>
        </dl> -->
    </div>
    <!--//-->
    <!--规格筛选-->
    <div class="cover cover-revenue">
        <div class="cover-con revenue-permutation">
            <dl class='order-time-btn'>
                <dt>按时间</dt>
                <dd class="<?php echo myMenuGet('time',['','all'],'revenue-on');?> order-time" data-time='all'>全部</dd>
                <dd class='<?php echo myMenuGet('time','yes','revenue-on');?> order-time' data-time='yes'>昨天</dd>
                <dd class='<?php echo myMenuGet('time','week','revenue-on');?> order-time' data-time='week'>本周</dd>
                <dd class='<?php echo myMenuGet('time','month','revenue-on');?> order-time' data-time='month'>本月</dd>
            </dl>
            <dl class='order-type-btn'>
                <dt>按类别</dt>
                <dd class="<?php echo myMenuGet('type',['','all'],'revenue-on');?> order-type" data-type='all'>全部</dd>
                <dd class='<?php echo myMenuGet('type','team','revenue-on');?> order-type' data-type='team'>团队</dd>
                <dd class='<?php echo myMenuGet('type','my','revenue-on');?> order-type' data-type='my'>个人</dd>
                <dd class='<?php echo myMenuGet('type','share','revenue-on');?> order-type' data-type='share'>推荐</dd>
            </dl>
            <input type="button" class="addPassenger_btn" value="确 定"/>
            <input type="hidden" name="order_time" value='all'>
            <input type="hidden" name="order_type" value='all'>            
        </div>
    </div>
    <!--//-->
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
    //确定
    $(".addPassenger_btn").on("click",function(){
        var time  = $("[name='order_time']").val();
        var type  = $("[name='order_type']").val();
        
        location.href = root + "m/mUser/mRevenueMx.php?time=" + time + "&type=" + type;        
    });
    //
    $('.order-time').on('click',function(){
        var _this = $(this);
       _this.addClass('revenue-on').siblings().removeClass('revenue-on');
       $("[name='order_time']").val(_this.data('time'));
    });
    $('.order-type').on('click',function(){
       var _this = $(this);
       _this.addClass('revenue-on').siblings().removeClass('revenue-on');
       $("[name='order_type']").val(_this.data('type'));
    });
})
</script>
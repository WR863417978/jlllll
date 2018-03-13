<?php
include "../../library/mFunction.php";
echo head('m');
$pid 	= $get['pid'];	#payId
$res  = findOne('pay',"targetId = '{$_SESSION['khid']}' AND id = '$pid'");
if( $res )
{
    $jsonStr = "('".implode("','" ,json_decode( $res['orderIdGroup'],true ) )."')";
    $sql = "SELECT b.*,g.ico,g.name FROM buyCar b,goods g WHERE b.id IN $jsonStr AND b.goodsId = g.id";
    $info = myQuery($sql);
    if( $info['0']['sqlRow'] > 0 )
    {
        foreach ($info as $val)
        {
            $orderBtn = '';
            if( $val['workFlow'] == '已发货' )
            {
                $orderBtn = "<input class='my-btn btn-warning' type='button' data-pid='{$pid}' data-bid='{$val['id']}' data-gid='{$val['goodsId']}' name='toSure' value='确认收货'>";
            }else if( $val['workFlow'] == '已收货' ){
                $orderBtn = "<input class='my-btn btn-success' type='button' data-bid='{$val['id']}' data-gid='{$val['goodsId']}' name='toTalk' value='去评价'>";
            }else{
                $orderBtn = "<input class='my-btn btn-inverse' type='button' data-bid='{$val['id']}' data-gid='{$val['goodsId']}' value='未发货'>";
            }
            $html .= "
            <div class='order-goods-mx'>
				<img src='".root."{$val['ico']}'/>
				<p>{$val['name']}<br/><span>订量：{$val['buyNumber']}</span>{$orderBtn}</p>
			</div>";
        }    
    }
}
?>
<style>
.btn-primary{background-color:#0059CC !important;color:white !important;border:none}
.btn-info{background-color:#3EA4C2 !important;color:white !important;border:none}
.btn-success{background-color:#5BB75B !important;color:white !important;border:none}
.btn-warning{background-color:#F9A022 !important;color:white !important;border:none}
.btn-danger{background-color:#D74C46 !important;color:white !important;border:none}
.btn-inverse{background-color:#9e9a9a !important;color:white !important;border:none}
.my-btn{margin-right: 10px;display: inline-block;float: right;width:70px;height:30px;border-radius:2px;}
</style>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">订单</p>
		</div>
		<a href="javascirpt:;" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<div class="container mui-mbottom60 mui-ptopsmaple">
	<div class="orderMx">
		<!--订单商品明细-->
		<div class="order-goods">
        <?php echo $html;?>
			<!-- <div class="order-goods-mx">
				<img src="<?php echo img('wOZ84129241GJ');?>"/>
				<p>描述描述描述描述描述描述描述描述<br/><span>订量：50</span></p>
			</div> -->
		</div>
		<!--//-->
	</div>
</div>
<?php echo mFooter(),mWarn();?>
<script>
$(function(){
   //去评价
   $("[name='toTalk']").on('click',function(){
       var gid,bid;
       gid = $(this).data('gid');
       bid = $(this).data('bid');
       location.href = root + "m/mUser/mOrderAppraise.php?gid=" + gid + '&bid=' + bid;
    });
   //确认收货 
   $("[name='toSure']").on('click',function(){
        var $this,bid,khid;
        $this = $(this);
        khid = '<?php echo $kehu['khid'];?>';
        bid = $this.data('bid')
        pid = $this.data('pid');
        $.post(root+"library/mData.php?type=toSure",{bid:bid,khid:khid,pid:pid},function(data){
            if(data.warn == 2){
                location.reload();
            }else{
                mwarn(data.warn);
            }
        },'json');
    });
});
</script>
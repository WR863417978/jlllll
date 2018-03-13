<?php
include "../library/mFunction.php";
echo head('m');
$type = $get['type'];#等级类型 nor  vip
empty($type) ? $type = 'nor' : '';
function showMemberType($type)
{
    $normal = findOne('img',"id = 'myh84324058lV'");
    $vip    = findOne('img',"id = 'pah84324212yj'");
    /* $normalArr = explode('、',$normal['text']);
    $vipArr = explode('、',$vip['text']); */
    $norPrice 	= explode('、',para('normalMember'));

    $vipPrice 	= explode('、',para('vipMember'));
    $data = [];
    $data['nor'] = "
    <div class='normal'>
        <p class='mui-dis-flex user-price'>
            <span class='flex1'>应付金额</span>
            <label><i>￥{$norPrice['0']}</i><s>￥{$norPrice['1']}</s></label>
        </p>
        <div class='user-free'>
            <p><label>{$normal['name']}</label></p>
            <p><img src='".img('myh84324058lV')."'/></p>
            <input type='button' class='addPassenger_btn' value='立即支付' data-key='nor'/>
            <p><span>购买即为同意</span><label><a href='".root."m/mMemberProtocol.php'  class='red'>《聚礼优选会员协议》</a></label></p>
        </div>
    </div>";
    $data['vip'] = "
    <div class='vip'>
        <p class='mui-dis-flex user-price'>
            <span class='flex1'>应付金额</span>
            <label><i>￥{$vipPrice['0']}</i><s>￥{$vipPrice['1']}</s></label>
        </p>
        <div class='user-free'>
            <p><label>{$vip['name']}</label></p>
            <p><img src='".img('pah84324212yj')."'/></p>
            <input type='button' class='addPassenger_btn' value='立即支付' data-key='vip'/>
            <p><span>购买即为同意</span><label><a href='".root."m/mMemberProtocol.php'>《聚礼优选会员协议》</a></label></p>
        </div>
    </div>";
        return $data;
}
$memberHtml = showMemberType($type);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:history.back(-1);" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">成为会员</p>
        </div>
        <a href="#" class="header-btn header-login"></a>
    </div>
</div>
<!--//-->
<div class="container mui-pt45">
    <div class="open-user user-succeed open-user2">
        <label class="mui-dis-flex">
            <span class="flex1">会员类别</span>
            <select class="select-xia" name='userType'>
                <?php echo option('',array('nor'=>'普通会员','vip'=>'高级会员'),$type);?>
            </select>
        </label>
    </div>
    <?php 
        /* echo $memberHtml['normal'];#普通会员
        echo $memberHtml['vip'];#高级会员 */
        echo $memberHtml[$type];
    ?>
    <!-- <p class="mui-dis-flex user-price">
        <span class="flex1">应付金额</span>
        <label><i>￥360</i><s>￥680</s></label>
    </p>
    <div class="user-free">
        <p><label>获得会员赠品；价值360元的美妆一套</label></p>
        <p><img src="<?php echo img('wOZ84129241GJ');?>"/></p>
        <input type="button" class="addPassenger_btn" value="立即支付"/>
        <p><span>购买即为同意</span><label>《聚礼优选会员协议》</label></p>
    </div> -->
    <form id="payForm" action='<?php echo root,'pay/wxpay/wxpay.php'?>' method='post'>
        <input type="hidden" name="orderType" value='购买会员'>
        <input type="hidden" name="orderId">
        <input type="hidden" name="key">
    </form>
</body>
</html>
<?php echo mWarn();?>
<script>
$(function(){
    $("[name='userType']").change(function(){
        var type = $(this).val();
        console.log(type);
        if( type == 'nor' ){
            $('.vip').hide();
            $('.normal').show();
            location.href = root + "m/mMemberBuy.php?type=nor";
        }else if( type == 'vip' ){
            $('.normal').hide();
            $('.vip').show();
            location.href = root + "m/mMemberBuy.php?type=vip";
        }
    });
    //支付
    $(".addPassenger_btn").on('click',function(){
        var $this = $(this)
            key = $(this).data('key')
            type = '<?php echo $kehu['type'];?>';
        console.log(key);
        if( type == '普通会员' && key == 'nor'){
            mwarn('你已经是普通会员了');
        }else if( type == '高级会员' && key == 'vip' ){
            mwarn('你已经是高级会员了');
        }else{
            $.post(root+"library/mData.php?type=checkVip",{type:key},function(data){
                if(data.warn == 2){
                    $("#payForm [name='key']").val( data.data );
                    $("#payForm [name='orderId']").val( data.orderId );
                    $("#payForm").submit();
                }else{
                    mwarn(data.warn);
                }
            },'json');    
        }
    });
});


</script>
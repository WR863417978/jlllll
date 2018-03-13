<?php
include "../../library/mFunction.php";
echo head('m');
$type = $get['type'];   #order 由提交订单时跳转到该页面
$list = myCouponList($kehu['khid'],$type);
function myCouponList($khid,$type = NULL)
{
    $sql = "SELECT k.*,c.moeny,c.moeny,c.amountMoeny,c.starTime,c.endTime FROM kehuCoupon k,coupon c WHERE k.khid = '$khid' AND k.couponId = c.id ORDER BY k.time DESC";
    $res = myQuery($sql);
    $arr = [];
    if( $res['0']['sqlRow'] > 0 ){
        foreach ($res as $key => $val)
        {
            if( $val['status'] == '已使用' ){
                $arr['yes'] .= "
                    <div class='mui-shopcoupon-item hadUse hide'>
                        <div class='mui-shopcoupon-main'>
                            <div class='mui-shopcoupon-top'>
                                <div class='mui-shopcoupon-tl'><span class='unit'>￥</span><span class='number'>{$val['moeny']}</span></div>
                                <div class='mui-shopcoupon-tr'>
                                    <p>满{$val['amountMoeny']}元使用</p>
                                </div>
                            </div>
                            <div class='mui-shopcoupon-bottom'>
                                有效期 ".date('Y-m-d',strtotime($val['starTime']))."-".date('Y-m-d',strtotime($val['endTime']))."
                            </div>
                        </div>
                        <div class='mui-shopcoupon-handler'>
                            <span class='gap'></span>
                        </div>
                    </div>";
            }else if( $val['status'] == '未使用' ){
                if( strtotime($val['starTime']) <= time() && strtotime($val['endTime']) >= time()  ){
                    $canBeUse = 'yes';
                }else{
                    $canBeUse = 'no';
                }
                $arr['no'] .= "
                    <div class='mui-shopcoupon-item notUse' data-key='{$val['id']}' data-canbeuse='{$canBeUse}' data-free='{$val['moeny']}'>
                        <div class='mui-shopcoupon-main'>
                            <div class='mui-shopcoupon-top'>
                                <div class='mui-shopcoupon-tl'><span class='unit'>￥</span><span class='number'>{$val['moeny']}</span></div>
                                <div class='mui-shopcoupon-tr'>
                                    <p>满{$val['amountMoeny']}元使用</p>
                                </div>
                            </div>
                            <div class='mui-shopcoupon-bottom'>
                                有效期 ".date('Y-m-d',strtotime($val['starTime']))."-".date('Y-m-d',strtotime($val['endTime']))."
                            </div>
                        </div>
                        <div class='mui-shopcoupon-handler'>
                            <span class='gap'></span>
                        </div>
                    </div>";
            }
        }
    }else{
        $arr = ['yes' => '暂无优惠劵','no' => '暂无优惠劵'];
    }
    return $arr;
}
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting">
        <a href="javascript:;" onclick='windowBack();' class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的优惠券</p>
        </div>
        <a href="#" class="header-btn header-login"></a>
    </div>
</div>
<!--//-->
<div class="container mui-ptopsmaple mb180">
    <div class="coupons-box">
        <ul class="mui-dis-flex">
            <li class='on coupons-xx' data-key='notUse'>未使用</li>
            <li class="coupons-xx" data-key='hadUse'>已使用</li>
        </ul>
        <?php echo $list['yes'],$list['no'];?>
       <!-- <div class="mui-shopcoupon-item">
            <div class="mui-shopcoupon-main">
                <div class="mui-shopcoupon-top">
                    <div class="mui-shopcoupon-tl"><span class="unit">￥</span><span class="number">10</span></div>
                    <div class="mui-shopcoupon-tr">
                        <p>满188元使用</p>
                    </div>
                </div>
                <div class="mui-shopcoupon-bottom">
                    有效期 2017.11.13-2017.12.31
                </div>
            </div>
            <div class="mui-shopcoupon-handler">
                <span class="gap"></span>
            </div>
        </div> -->
    </div>
</div>
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
    
$(function(){
    //选项卡切换
    $('.hadUse').hide();
    $('.coupons-xx').on('click',function(){
        var $this = $(this)
            key = $this.data('key');
        $this.addClass('on').siblings('li').removeClass('on');
        if( key == 'hadUse' ){
            $('.hadUse').show();
            $('.notUse').hide();
        }else if( key == 'notUse' ){
            $('.hadUse').hide();
            $('.notUse').show();
        }
    });
    //使用优惠劵
    $(".notUse").on('click',function(){
        var totalPrice = '<?php echo $_SESSION['totalPrice'];?>'
            key = $(this).data('key')
            canbeuse = $(this).data('canbeuse')
            free = $(this).data('free');
            console.log(totalPrice);
            console.log(free);            
        if( canbeuse == 'yes' ){
            console.log(1);
            if( parseInt(totalPrice) < parseInt(free)  ){
                mwarn('给优惠劵不能使用');
            }else{
                $.post(root+"library/mData.php?type=useCoupon",{id:key},function(data){
                    if(data.warn == 2){
                        window.history.back(-1);
                    }else{
                        mwarn(data.warn);
                    }
                },'json');
            }
        }else if( canbeuse == 'no' ){
            console.log(2);
            mwarn('该优惠劵已过期');
        }
    });
});
</script>
<?php
include "../../library/mFunction.php";
echo head('m');
Income::availableFree($kehu['khid']);       #可提现
$tobeSettled    = Income::$tobeSettled;     #待结算数据
$available      = Income::$available;       #可提现数据
$hasWithdraw    = Income::$hasWithdraw;     #已提现数据
$waitWithdraw   = Income::$waitWithdraw;    #提现审核数据
$totalFree      = Income::$totalFree;       #总费用
//Income::withdraw($kehu['khid']);
//Income::tobeSettled($kehu['khid']);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">收入查询</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--收入查询-->
<div class="container">
    <div class="user mui-pt45 mui-mbottom60">
        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="<?php echo root;?>m/mUser/mRevenueMx.php" class="mui-dis-flex">
                    <span class="flex1">历史总收益</span>
                    <label><i>明细</i><span class="more">&#xe62e;</span></label>
                </a>
            </li>
        </ul>
        <dl class="revenue">
            <dt>
                <p><label><?php echo $totalFree;?></label><span>元</span></p>
                <p><em>总收益=待结算金额+可提现金额+已提现金额</em></p>
            </dt>
            <dd>
                <ul class="mui-dis-flex">
                    <li>
                        <p><span><?php echo $tobeSettled;#待结算金额?></span></p>
                        <p><label>待结算金额</label></p>
                        <p><a href="<?php echo root;?>m/mUser/mRevenueWite.php"><em class="user-btn">详情</em></a></p>
                    </li>
                    <li>
                        <p><span class='get-money'><?php echo $available;#可提现金额?></span></p>
                        <p><label>可提现金额</label></p>
                        <p><a href="#"><em class="user-btn getMoney">提现</em></a></p>
                    </li>
                    <li>
                        <p><span><?php echo $hasWithdraw + $waitWithdraw;#已提现?></span></p>
                        <p><label>已提现金额</label></p>
                        <p><a href="<?php echo root;?>	m/mUser/mRevenueCash.php"><em class="user-btn">提现明细</em></a></p>
                    </li>
                </ul>
            </dd>
        </dl>
        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">团队业绩分析</span>
                    <label><span class="more">&#xe62e;</span></label>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
    $('.getMoney').click(function(){
        var kehuType = '<?php echo $kehu['type'];?>';
        var getDay = '<?php echo para('DateOfPresentation');?>';
        var money = '<?php echo $available;?>';
        var nowDay = '<?php echo date('d',time());?>';
        if( getDay == nowDay ){
            if( $.trim(money).length > 0 && kehuType == '高级会员' ){
                withdraw();
            }else if( kehuType == '普通会员' || kehuType == '' ){
                mwarn('只有高级会员才能提现哟');
            }else{
                mwarn('余额不足');
            }
        }else{
            mwarn('提现的日子还没到了');
        }
    });
    function withdraw(){
        mwarn('确认提现？');
        $('#coverSure').off('click').one('click',function(){ 
            $('#cover').hide();
            var mon = $('.get-money').html();
            if( mon > 0 ){
                $.post(root+"library/mData.php?type=getMoney",{mon:mon},function(data){
                    if(data.warn == 2){
                        mwarn('发起提现成功');
                    }else{
                        mwarn(data.warn);
                    }
                },'json');
            }
        });
    }
})
</script>
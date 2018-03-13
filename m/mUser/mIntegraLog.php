<!DOCTYPE html>
<?php
include "../../library/mFunction.php";
echo head('m');
$sql = "SELECT * FROM integral WHERE khid = '{$kehu['khid']}' ORDER BY time DESC";
$res = myQuery($sql);

Integral::getIntegral($kehu['khid']);
$canUseTotal = Integral::$canUseTotal;
foreach ($res as $key => $val) {
    if( $val['type'] == '增加' ){
        $str = '购买商品获得积分';
        $change = '+';        
    }else if( $val['type'] == '支出' ){
        $str = '兑换商品使用积分';
        $change = '-';        
    }else if( $val['type'] == '过期' ){
        $str = '积分过期';
        $change = '-';        
    }
    $html .= "
    <dl>
        <dt class='mui-dis-flex'><label class='flex1'>{$change} {$val['changeCode']}</label><span>{$canUseTotal}</span></dt>
        <dd><p>{$str}</p></dd>
        <dd><label>{$val['time']}</label></dd>
    </dl>";
    if( $val['type'] == '增加' ){
        $canUseTotal -= $val['changeCode'];
    }else if( $val['type'] == '支出' ){
        $canUseTotal += $val['changeCode'];
    }else if( $val['type'] == '过期' ){
        $canUseTotal += $val['changeCode'];
    }
}
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root; ?>m/mUser/mIntegral.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">积分收支明细</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<div class="edit-info mui-ptopsmaple account">
    <?php echo $html;?>
    <!-- <dl>
        <dt class="mui-dis-flex"><label class="flex1">+10</label><span>709</span></dt>
        <dd><p>购买商品获得积分</p></dd>
        <dd><label>2017-11-15 12:55</label></dd>
    </dl>
    <dl>
        <dt class="mui-dis-flex"><label class="flex1">-12</label><span>699</span></dt>
        <dd><p>兑换“剃须刀”消费积分</p></dd>
        <dd><label>2017-11-15 12:55</label></dd>
    </dl> -->
</div>
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
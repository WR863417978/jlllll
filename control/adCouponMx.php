<?php
//邀优惠券详情页
include "ku/adfunction.php";
ControlRoot("adCoupon");
if(empty($get['id'])){
    $title = "新建优惠券";
}else {
    $coupon = query("coupon", "id ='$get[id]'");
    if ($coupon['id'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个优惠券的信息";
        header("location:{$root}control/adCouponMx.php");
        exit(0);
    }
    $title = "优惠券详情";
}
//优惠券当前所属商品
$goodOptionHtml = "";
$goodOption = query("goods","id='$coupon[goodsId]'");
if(!empty($goodOption)){
    $goodOptionHtml .= "<option value='{$goodOption[id]}' selected>{$goodOption[name]}</option>";
}elseif($coupon['goodsId'] == "会员优惠券"){
    $goodOptionHtml .= "<option value='会员优惠券' selected>会员优惠券</option>";
}else{
    $goodOptionHtml .= "<option>--所属商品--</option>";
}
//查询所属商品
$option = "";
$sql = "SELECT id,name FROM goods";
$cres = mysql_query($sql);
$num = mysql_num_rows($cres);
if($num > 0){
    while ($goodsid = mysql_fetch_assoc($cres)){
        $option .= "<option value='{$goodsid[id]}'>{$goodsid[name]}</option>";
    }
}
$option.="<option id='会员优惠券'>会员优惠券</option>";
//优惠券发放列表
$couponMx = "";
$sql = "SELECT c.*,k.* FROM coupon AS c INNER JOIN kehuCoupon AS k ON c.id=k.couponId WHERE c.id='$get[id]'";
$res = mysql_query($sql);
$cnum = mysql_num_rows($res);
if($cnum > 0){
    while ($val = mysql_fetch_assoc($res)){
        $kehuname = query("kehu","khid='$val[khid]'");
        $couponMx .="<tr>
        <td>{$kehuname['name']}</td>
        <td>{$val['moeny']}</td>
        <td>{$val['status']}</td>
        <td>{$val['time']}</td>
        <td>已发放</td>
        
      </tr>";
    }
}else{
    $couponMx .="<tr><td colspan='5'>暂未发放任何客户</td></tr>";
}
$adcoupon .="
  <form>
  <table class='tableMany'>
    <tr>
      <td>发放客户姓名</td>
      <td>优惠金额</td>
      <td>使用状态</td>
      <td>领取时间</td>
      <td><span EditRule='{$get[id]}' class='spanButton' >继续发放</span></td>
    </tr>
    {$couponMx}
  </table>
    </form>
  ";
$onion = array(
    "优惠券管理" => root."control/adCoupon.php",
    $title => '优惠券详情',
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="kuang">
            <p>创建时间：</p>
            <p><p><?php echo $coupon['time'];?></p></p>
        </div>
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <form name="couponMxForm">
                <table class="tableRight">
                    <tr>
                        <td>所属商品：</td>
                        <td>
                            <select name="goodsId">
                                <?php echo $goodOptionHtml.$option;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>金额：</td>
                        <td><input name="moeny" class="text TextPrice"  type="text" value="<?php echo $coupon['moeny'];?>"/></td>
                    </tr>
                    <tr>
                        <td>使用条件：</td>
                        <td><input name="amountMoeny" class="text TextPrice" placeholder="满足多少金额"  type="text" value="<?php echo $coupon['amountMoeny']?>"/></td>
                    </tr>
                    <tr>
                        <td>开始时间：</td>
                        <td>
                            <?php echo year("StartYear","select textPrice","new",$coupon['starTime']).
                                moon("StartMoon","select textPrice",$coupon['starTime']).
                                day("StartDay","select textPrice",$coupon['starTime'])
                            ?></td>
                    </tr>
                    <tr>
                        <td>结束时间：</td>
                        <td>
                            <?php echo year("endYear","select textPrice","new",$coupon['endTime']).
                                moon("endMoon","select textPrice",$coupon['endTime']).
                                day("endDay","select textPrice",$coupon['endTime'])
                            ?></td>
                    </tr>
                    <tr>
                        <td>优惠券张数：</td>
                        <td><input name="num" class="text TextPrice"  type="text" value="<?php echo $coupon['num']?>"/></td>
                    </tr>
                    <tr>
                        <td><input name="couponId" type="hidden" value="<?php echo $coupon['id']; ?>"></td>
                        <td><input onclick="Sub('couponMxForm',root+'control/ku/addata.php?type=coupon')" type="button" class="button" value="提交"></td>
                    </tr>
                </table>
            </form>
            <?php echo $adcoupon;?>
        </div>
    </div>
    <!--弹出发放优惠券-->
    <div class="hide" id="couponRule">
        <div class="dibian"></div>
        <div class="win" style="width: 600px; height:119px; margin: -174px 0px 0px -300px;">
            <p class="winTitle">发放优惠券<span onclick="$('#couponRule').hide()" class="winClose">×</span></p>
            <form name="couponRule">
                <table class="tableRight">
                    <tr>
                        <td>发放对象：</td>
                        <td>
                            <select name="kehuid">
                                <?php echo IdOption("kehu","khid","name","--客户姓名--",$kehu['khid']);?>
                            </select>
                    </tr>
                    <tr>
                        <td>
                            <input name="couponid" type="hidden" value="<?php echo $get['id']?>"/>
                        </td>
                        <td><input type="button" class="button" onclick="Sub('couponRule','<?php echo root."control/ku/addata.php?type=CouponSend";?>')" value="确认发放"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!--弹出发放优惠券结束-->
    <script>
        $(function () {
            $("[EditRule]").click(function(){
                var cid = $(this).attr("EditRule");

                $("#couponRule").show();
            });
        })
    </script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
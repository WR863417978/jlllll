<?php
//邀优惠券详情页
include "ku/adfunction.php";
ControlRoot("adShare");
    $share = query("kehu", "khid ='$get[id]'");
    if ($share['khid'] != $get['id']) {
        $_SESSION['warn'] = "未找到这个分享人的信息";
        header("location:{$root}control/adShareMx.php");
        exit(0);
    }
    $title = "分享详情";
    //分享列表
    $shareMx = "";
    $sql = "SELECT * FROM income WHERE srcKhid='$share[khid]'";
    $res = mysql_query($sql);
    $cnum = mysql_num_rows($res);
    if($cnum > 0){
        while ($val = mysql_fetch_assoc($res)){
            $shareMx .="<tr>
                            <td>{$val['type']}</td>
                            <td>{$val['orderTime']}</td>
                            <td>{$val['sales']}</td>
                            <td>{$val['free']}</td>
                            <td>{$val['time']}</td>
                        </tr>";
        }
    }else{
        $shareMx .="<tr><td colspan='5'>没有任何分享</td></tr>";
    }
    $adShare .="
  <form>
  <table class='tableMany'>
    <tr>
      <td>分享类型</td>
      <td>交易时间</td>
      <td>销售额</td>
      <td>返佣费用</td>
      <td>创建时间</td>
    </tr>
    {$shareMx}
  </table>
    </form>
  ";
    $onion = array(
        "分享管理" => root."control/adCoupon.php",
        $title => '分享详情',
    );

echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="kuang">
            <p>创建时间：</p>
            <p><p><?php echo $share['time'];?></p></p>
            <p>分享人姓名：</p>
            <p><p><?php echo $share['name'];?> <a target="_blank" href="adClientMx.php?id=<?php echo $share['khid']; ?>"><span class="spanButton">查看分享人</span></a></p></p>
        </div>
        <div class='adStatus kuang'>
            <div class='clear'></div>
            <?php echo $adShare;?>
        </div>
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
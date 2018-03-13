<?php
/*
 * 分享管理列表页
 */
include "ku/adfunction.php";
ControlRoot("adShare");
$sql="SELECT * FROM kehu WHERE 1=1".$_SESSION['adShare']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "分享管理" => $ThisUrl,
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="search">
            <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchShare";?>" method="post">
                分享人姓名：<input name="shareName" type="text" class="text textPrice" value="<?php echo $_SESSION['adShare']['name'];?>">
                <input type="submit" value="模糊查询">
            </form>
        </div>
        <div class="search">
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        <!--查询结束-->
        <!--列表开始-->
        <form name="ShareForm">
            <table class="tableMany">
                <tr>
                    <td>分销商负责人</td>
                    <td>店铺</td>
                    <td>返佣金额</td>
                    <td style="width:54px;">操作</td>
                </tr>
                <?php
                if($num > 0){
                    while($adShare = mysql_fetch_assoc($query)){
                        $fresql = "SELECT SUM(free) total,khid,srcName,srcKhid FROM income WHERE khid='$adShare[khid]' GROUP BY khid ORDER BY total DESC";
                        $res = mysql_query($fresql);
                        while ($free = mysql_fetch_assoc($res)){
                            echo "
                        <tr>
                          <td>{$free['srcName']}</td>
                         <td>{$adShare['shopName']}</td>
                          <td>{$free['total']}</td>
                          <td><a href='{$root}control/adShareMx.php?id={$adShare['khid']}'><span class='spanButton'>详情</span></a></td>
                        </tr>";
                        }
                    }
                }else{
                    echo "<tr><td colspan='4'>没有分享成果</td></tr>";
                }?>
            </table>
        </form>

        </div>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
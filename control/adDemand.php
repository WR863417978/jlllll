<?php
/**
 * 需求管理
 */
include "ku/adfunction.php";
ControlRoot("adDemand");
$sql="select * from demand ".$_SESSION['SerachDemand']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "需求管理" => $ThisUrl,
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="search">
            <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSerachDemand";?>" method="post">
                需求主题：<input name="theme" type="text" class="text textPrice" value="<?php echo $_SESSION['SerachDemand']['theme'];?>">
                礼品分类：
                <select name="giftType" class="select">
                    <?php echo option('--类型--',explode('，',para('giftType')),$_SESSION['SerachDemand']['giftType']);?>
                </select>
                需求状态：
                <?php echo select("status","select textPrice","--需求状态--",array("已发布","在商谈","已合作","不能接"),$_SESSION['SerachDemand']['status']);?>
                <input type="submit" value="模糊查询">
            </form>
        </div>
        <div class="search">
            <span onclick="$('[name=DemandForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=DemandForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <span onclick="EditList('DemandForm','deleteDemand')" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="DemandForm">
            <table class="tableMany" style="font-size: !important;">
                <tr>
                    <td></td>
                    <td>需求主题</td>
                    <td>礼品分类</td>
                    <td>采购数量</td>
                    <td>需求描述</td>
                    <td>结束时间</td>
                    <td>客户姓名</td>
                    <td>联系电话</td>
                    <td>操作人姓名</td>
                    <td>需求状态</td>
                    <td>更新时间</td>
                    <td>创建时间</td>
                    <td style="width:54px;">操作</td>
                </tr>
                <?php
                if($num > 0){
                    while($adDemand = mysql_fetch_array($query)){
                        $kehuName = query("kehu","khid='$adDemand[khid]'");
                        $admiName = query("admin","adid='$adDemand[actionId]'");
                        echo "
                        <tr  {$trColor}>
                          <td><input name='DemandList[]' type='checkbox' value='{$adDemand['id']}'/></td>
                          <td>{$adDemand['theme']}</td>
                          <td>{$adDemand['giftType']}</td>
                          <td>{$adDemand['num']}</td>
                          <td>".zishu(kong($adDemand[text]),10)."</td>
                          <td>{$adDemand['endTime']}</td>
                          <td>{$kehuName['name']}</td>
                          <td>{$adDemand['tel']}</td>
                          <td>{$admiName['adname']}</td>
                          <td>{$adDemand['status']}</td>
                          <td>{$adDemand['updateTime']}</td>
                          <td>{$adDemand['time']}</td>
                          <td><a href='{$root}control/adDemandMx.php?id={$adDemand['id']}'><span class='spanButton'>详情</span></a></td>
                        </tr>
                        ";
                    }
                }else{
                    echo "<tr><td colspan='13'>一条需求都没有</td></tr>";
                }
                ?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
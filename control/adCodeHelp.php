<?php
/**
 * 邀请码申诉列表页
 */
include "ku/adfunction.php";
ControlRoot("adCodeHelp");
$sql="select * from codeExplain ".$_SESSION['SerachCodeHelp']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "邀请码申诉管理" => $ThisUrl,
);
echo head("ad").adheader($onion);
?>
<div class="minHeight">
    <div class="search">
        <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adCodeHelpSerach";?>" method="post">
            邀请人姓名：<input name="shareName" type="text" class="text textPrice" value="<?php echo $_SESSION['SerachCodeHelp']['shareName'];?>">
            申请人姓名：<input name="explainName" type="text" class="text textPrice" value="<?php echo $_SESSION['SerachCodeHelp']['explainName'];?>">
            <?php echo select("status","select textPrice","--订单状态--",array("待审核","未通过","已通过"),$_SESSION['SerachCodeHelp']['status']);?>
            <input type="submit" value="模糊查询">
        </form>
    </div>
    <div class="search">
        <span onclick="$('[name=CodeForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
        <span onclick="$('[name=CodeForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
        <span onclick="EditList('CodeForm','deleteCode')" class="spanButton">删除所选</span>
        <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
    </div>
    <!--查询结束-->
    <!--列表开始-->
    <form name="CodeForm">
        <table class="tableMany" style="font-size: !important;">
            <tr>
                <td></td>
                <td>默认推荐码</td>
                <td>更改推荐码</td>
                <td>邀请人姓名</td>
                <td>邀请人手机</td>
                <td>申请人姓名</td>
                <td>申请人手机</td>
                <td>申请状态</td>
                <td>审核人姓名</td>
                <td>更新时间</td>
                <td>创建时间</td>
                <td style="width:54px;">操作</td>
            </tr>
            <?php
            if($num > 0){
                while($adCodeHelp = mysql_fetch_array($query)){
                        $actionName = query("admin","adid='$adCodeHelp[actionId]'");
                    echo "
                        <tr  {$trColor}>
                          <td><input name='CodeList[]' type='checkbox' value='{$adCodeHelp['id']}'/></td>
                          <td>{$adCodeHelp['defaultCode']}</td>
                          <td>{$adCodeHelp['changeCode']}</td>
                          <td>{$adCodeHelp['shareName']}</td>
                          <td>{$adCodeHelp['shareTel']}</td>
                          <td>{$adCodeHelp['explainName']}</td>
                          <td>{$adCodeHelp['explainTel']}</td>
                          <td>{$adCodeHelp['status']}</td>
                          <td>{$actionName['adname']}</td>
                          <td>{$adCodeHelp['updateTime']}</td>
                          <td>{$adCodeHelp['time']}</td>
                          <td><a href='{$root}control/adCodeHelpMx.php?id={$adCodeHelp['id']}'><span class='spanButton'>详情</span></a></td>
                        </tr>
                        ";
                }
            }else{
                echo "<tr><td colspan='11'>一条申请都没有</td></tr>";
            }
            ?>
        </table>
    </form>
    <?php echo fenye($ThisUrl,7);?>
    <!--列表结束-->
</div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
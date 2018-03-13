<?php
//一级商品分类列表页\
include "ku/adfunction.php";
ControlRoot("adGoods");
$sql="select * from goodsOne ".$_SESSION['adGoods']['Sql'];
paging($sql," order by time desc",20);
$onion = array(
    "商品管理" => root."control/adGoods.php",
    "一级分类列表" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="search">
            <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <a href="<?php echo root."control/adGoodsOne.php";?>"><span class="spanButton">一级分类</span></a>
            <a href="<?php echo root."control/adGoodsOneMx.php";?>"><span class="spanButton">新建一级分类</span></a>
            <span onclick="EditList('GoodsOneForm','deleteGoodsOne')" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="GoodsOneForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td style="width:266px;">商品一级分类</td>
                    <td style="min-width:109px;">排序</td>
                    <td style="width:188px;">显示状态</td>
                    <td style="width:419px;">更新时间</td>
                    <td></td>
                </tr>
                <?php
                if($num > 0){
                    while($goodsOne = mysql_fetch_array($query)){
                        echo "
        <tr>
          <td><input name='GoodsOneList[]' type='checkbox' value='{$goodsOne['id']}'/></td>
          <td>{$goodsOne['name']}</td>
          <td>{$goodsOne['list']}</td>
          <td>{$goodsOne['xian']}</td>
          <td>{$goodsOne['updateTime']}</td>
          <td><a href='{$adroot}adGoodsOneMx.php?id={$goodsOne['id']}'><span class='spanButton'>详情</span></a></td>
        </tr>
        ";
                    }
                }else{
                    echo "<tr><td colspan='6'>没有分类</td></tr>";
                }

                ?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
<?php
//二级商品分类列表页\
include "ku/adfunction.php";
ControlRoot("adGoods");
$ThisUrl = root."control/adGoodsTwo.php";
$sql="select * from goodsTwo ".$_SESSION['goodsTwo']['Sql'];
paging($sql," order by updateTime desc",20);
$onion = array(
    "商品管理" => root."control/adGoods.php",
    "二级分类商品" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--查询开始-->
        <div class="search">
            <form name="search" action="<?php echo root."control/ku/adpost.php?type=searchGoodsTwo";?>" method="post">
                <?php
                echo
                    IDSelect("goodsOne","goodsOne","select","id","name","--商品一级分类--",$_SESSION['goodsTwo']['one']).
                    select("goodsTypeTwoShow","select","--状态--",array("显示","隐藏"),$_SESSION['goodsTwo']['xian']);
                ?>
                <input type="submit" value="模糊查询">
            </form>
            <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <a href="<?php echo root."control/adGoodsTwo.php";?>"><span class="spanButton">二级分类</span></a>
            <a href="<?php echo root."control/adGoodsTwoMx.php";?>"><span class="spanButton">新建二级分类</span></a>
            <span onclick="EditList('GoodsTwoForm','deleteGoodsTwo')" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="GoodsTwoForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td style="width:266px;">一级分类名称</td>
                    <td style="min-width:109px;">二级分类名称</td>
                    <td style="min-width:109px;">排序</td>
                    <td style="width:188px;">显示状态</td>
                    <td style="width:419px;">更新时间</td>
                    <td></td>
                </tr>
                <?php
                if($num == 0){
                    echo "<tr><td colspan='7'>一个商品都没有</td></tr>";
                }else{
                    while($array = mysql_fetch_array($query)){
                        $goodsTypeOne = query("goodsOne"," id = '$array[goodsTypeOneId]' ");
                        echo "
        <tr>
          <td><input name='adGoodsTypeTwoList[]' type='checkbox' value='{$array['id']}'/></td>
          <td>{$goodsTypeOne['name']}</td>
          <td>{$array['name']}</td>
          <td>{$array['list']}</td>
          <td>{$array['xian']}</td>
          <td>{$array['updateTime']}</td>
          <td><a href='{$root}control/adGoodsTwoMx.php?id={$array['id']}'><span class='spanButton'>详情</span></a></td>
        </tr>
        ";
                    }
                }
                ?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
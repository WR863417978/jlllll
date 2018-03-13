<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 11:06
 */
//商品管理列表页\
include "ku/adfunction.php";
ControlRoot("adGoods");
$sql="select * from goods ".$_SESSION['SearchGoods']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "商品管理" => root."control/adGoods.php"
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="search">
            <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchGoods";?>" method="post">
                <?php
                echo
                    IDSelect("goodsOne","goodsOne","select","id","name","--商品一级分类--",$_SESSION['SearchGoods']['goodsOneId']).
                    select("SearchShow","select","--状态--",array("显示","隐藏"),$_SESSION['goodsTwo']['xian']);
                ?>
                商品名称：<input name="name" type="text" class="text textPrice" value="<?php echo $_SESSION['SearchGoods']['name'];?>">
                <input type="submit" value="模糊查询">
            </form>
        </div>
        <div class="search">
            <span onclick="$('[name=GoodsForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=GoodsForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <a href="<?php echo root."control/adGoodsOne.php";?>"><span class="spanButton">一级商品分类</span></a>
            <a href="<?php echo root."control/adGoodsTwo.php";?>"><span class="spanButton">二级商品分类</span></a>
            <a href="<?php echo root."control/adGoodsMx.php";?>"><span class="spanButton">新建商品</span></a>
            <span onclick="EditList('GoodsForm','deleteGoods')" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="GoodsForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td>商品名称</td>
                    <td>商品一级分类</td>
                    <td>商品二级分类</td>
                    <td>商品列表图</td>
                    <td class="summary">摘要</td>
                    <td>销量</td>
                    <td>显示状态</td>
                    <td>更新时间</td>
                    <td style="width:54px;">操作</td>
                </tr>
                <?php
                if($num > 0){
                    while($adgoods = mysql_fetch_array($query)){
                        $goodsTypeTwo = query("goodsTwo","id = '$adgoods[goodsTwoId]'");
                        $goodsTypeOne = query("goodsOne","id = '$adgoods[goodsOneId]'");
                        echo "
                        <tr  {$trColor}>
                          <td><input name='goodsList[]' type='checkbox' value='{$adgoods['id']}'/></td>
                          <td>".kong($adgoods['name'])."</td>
                          <td>{$goodsTypeOne['name']}</td>
                          <td>{$goodsTypeTwo['name']}</td>
                          <td><a target='_blank' href='{$root}{$adgoods['ico']}' title='点击查看大图'><img class='smallImg imgHover' src='{$root}{$adgoods['ico']}' alt='暂无图片'></a></td>
                          <td class='summary'>".zishu(kong($adgoods['summary']),20)."</td>
                          <td>{$adgoods['salesVolume']}</td>
                          <td>{$adgoods['xian']}</td>
                          <td>{$adgoods['updateTime']}</td>
                          <td><a href='{$root}control/adGoodsMx.php?id={$adgoods['id']}'><span class='spanButton'>详情</span></a></td>
                        </tr>
                        ";
                    }
                }else{
                    echo "<tr><td colspan='12'>一个商品都没有</td></tr>";
                }

                ?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
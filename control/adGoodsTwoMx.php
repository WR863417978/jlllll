<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 11:04
 */

//商品管理-二级分类添加
include "ku/adfunction.php";
ControlRoot("adGoods");
if(empty($_GET['id'])){
    $title = "新建二级分类";
    $button = "新建";
}else{
    $goodsTypeTwo = query("goodsTwo"," id = '$_GET[id]' ");
    if($goodsTypeTwo['id'] != $_GET['id']){
        $_SESSION['warn'] = "未找到此二级分类";
        header("location:{$root}control/adGoodsTwo.php");
        exit(0);
    }
    $goodsTwo = query("goodsTwo"," id = '$goodsTypeTwo[id]' ");
    $title = $goodsTwo['name'];
    $button = "更新";
}
$onion = array(
    "商品管理" => root."control/adGoods.php",
    "新建二级分类" => root."control/adGoodsTwo.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!-- 二级商品分类开始-->
        <?php echo $top;?>
        <div class="kuang">
            <form name="goodsTwoForm">
                <table class="tableRight">
                    <tr>
                        <td>商品二级分类ID号：</td>
                        <td><?php echo kong($goodsTwo['id']);?></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;商品一级分类：</td>
                        <td>
                            <?php echo IDSelect("goodsOne","goodsOne","select","id","name","--商品一级分类--",$goodsTwo['goodsTypeOneId']);?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;商品二级分类名称：</td>
                        <td><input name="name" type="text" class="text" value="<?php echo $goodsTwo['name'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;排序：</td>
                        <td><input name="list" type="text" class="text" value="<?php echo $goodsTwo['list'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;前端状态：</td>
                        <td>
                            <?php echo radio("xian",array("显示","隐藏"),$goodsTwo['xian']);?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;更新时间：</td>
                        <td><input type="text" class="text" value="<?php echo kong($goodsTwo['updateTime']); ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;创建时间：</td>
                        <td><input type="text" class="text"  value="<?php echo kong($goodsTwo['time']); ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><input name="goodsTwoId" type="hidden" value="<?php echo $goodsTwo['id']; ?>"></td>
                        <td><input onclick="Sub('goodsTwoForm',root+'control/ku/addata.php?type=GoodsTwoMx')" type="button" class="button" value="<?php echo $button;?>"></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php echo $other;?>
        <!-- 二级商品分类结束-->
    </div>

<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
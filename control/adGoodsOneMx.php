<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 11:04
 */

//商品管理-一级分类添加
include "ku/adfunction.php";
ControlRoot("adGoods");
if(empty($_GET['id'])){
    $title = "新建分类";
    $button = "新建";
}else{
    $id = FormSubArray($_GET['id']); //格式化ID
    $goodsOne = query("goodsOne"," id = '$id' ");
    if(empty($goodsOne['id'])){
        $_SESSION['warn'] = "参数错误";
        header("location:{$root}control/adGoodsOne.php");
        exit(0);
    }
    $title = $goodsOne['name'];
    $button = "更新";
}
$onion = array(
    "商品管理" => root."control/adGoods.php",
    "商品分类" => root."control/adGoodsOne.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>

    <div class="minHeight">
        <!-- 一级商品分类添加开始-->
        <?php echo $top;?>
        <div class="kuang">
            <form name="goodsOneForm">
                <table class="tableRight">
                    <tr>
                        <td>&nbsp;&nbsp;商品分类ID：</td>
                        <td><?php echo $goodsOne['id']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;商品分类名称：</td>
                        <td><input name="name" type="text" class="text" value="<?php echo $goodsOne['name']; ?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;序号：</td>
                        <td><input name="list" type="text" class="text" value="<?php echo $goodsOne['list']; ?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;前端状态：</td>
                        <td>
                            <?php echo radio("xian",array("显示","隐藏"),$goodsOne['xian']);?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;更新时间：</td>
                        <td><input type="text" class="text" value="<?php echo kong($goodsOne['updateTime']); ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;&nbsp;创建时间：</td>
                        <td><input type="text" class="text"  value="<?php echo kong($goodsOne['time']); ?>" disabled="disabled"></td>
                    </tr>

                    <tr>
                        <td><input name="goodsOneId" type="hidden" value="<?php echo $goodsOne['id']; ?>"></td>
                        <td><input onclick="Sub('goodsOneForm',root+'control/ku/addata.php?type=adGoodsOneMx')" type="button" class="button" value="<?php echo $button;?>"></td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- 一级商品分类添加结束-->
        <?php echo $other;?>
    </div>

<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
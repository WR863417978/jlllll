<?php
//供应商列表页
include "ku/adfunction.php";
ControlRoot("adSupplier");
$sql="select *from admin WHERE duty='Kmn85624891nR'".$_SESSION['adSupplier']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "供应商管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <div class="search">
            <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchSupplier";?>" method="post">
                供应商名称：<input name="companyName" type="text" class="text textPrice" value="<?php echo $_SESSION['adSupplier']['adname'];?>">
                品牌名称：<input name="contactName" type="text" class="text textPrice" value="<?php echo $_SESSION['adSupplier']['adname'];?>">
                联系电话：<input name="contactTel" type="text" class="text textPrice" value="<?php echo $_SESSION['adSupplier']['adtel'];?>">
                认证状态：<?php echo select("status","select","--认证状态--",array("未认证","未通过","已通过"),$_SESSION['adSupplier']['certificationStatus']);?>
                <input type="submit" value="模糊查询">
            </form>
        </div>
        <div class="search">
            <span onclick="$('[name=SupplierForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=SupplierForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <a href="<?php echo root."control/adSupplierMx.php";?>"><span class="spanButton">添加供应商</span></a>
            <span onclick="EditList('SupplierForm','deleteSupplier')" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="SupplierForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td style="min-width:100px;">供应商名称</td>
                    <td style="width:100px;">性别</td>
                    <td style="width:100px;">联系电话</td>
                    <td style="width:100px;">联系QQ</td>
                    <td style="width:100px;">电子邮箱</td>
                    <td style="min-width:100px;">更新时间</td>
                    <td></td>
                </tr>
                <?php
                if($num > 0){
                    while($supplier = mysql_fetch_array($query)){
                        echo "
				<tr {$trColor}>
					<td><input name='SupplierList[]' type='checkbox' value='{$supplier['adid']}'/></td>
					<td>".kong($supplier['adname'])."</td>
					<td>".kong($supplier['adname'])."</td>
					<td>".kong($supplier['adtel'])."</td>
					<td>".kong($supplier['money'])."</td>
					<td>".kong($supplier['certificationStatus'])."</td>
					<td>".kong($supplier['updateTime'])."</td>					
					<td><a href='{$root}control/adSupplierMx.php?id={$supplier['adid']}'><span class='spanButton'>详情</span></a></td>
				</tr>
				";
                    }
                }else{
                    echo "<tr><td colspan='8'>一个供应商都没有</td></tr>";
                }
                ?>
            </table>
        </form>
        <?php echo fenye($ThisUrl,7);?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
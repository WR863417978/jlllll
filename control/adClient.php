<?php
include "ku/adfunction.php";
ControlRoot("adClient");
$sql="select * from kehu ".$_SESSION['adClient']['Sql'];
paging($sql," order by time desc",100);
$onion = array(
    "客户管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="minHeight">
    <div class="search">
        <form name="Search" action="<?php echo root."control/ku/adpost.php?type=adSearchClient";?>" method="post">
            公司名称：<input name="companyName" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['CompanyName'];?>">
            联系人：<input name="contactName" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['ContactName'];?>">
            联系手机：<input name="contactTel" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['ContactTel'];?>">
            联系QQ：<input name="contactQQ" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['ContactQQ'];?>">
            微信号：<input name="contactWx" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['ContactWx'];?>">
            <?php echo RepeatSelect("region","province","province","select textPrice","--省份--",$_SESSION['adClient']['province']);?>
            <select name="city" class="select textPrice">
                <?php echo RepeatOption(" region where province = '{$_SESSION['adClient']['province']}' ","city","--城市--",$_SESSION['adClient']['city']);?>
            </select>
            <select name="area" class="select textPrice">
                <?php echo IdOption(" region where province = '{$_SESSION['adClient']['province']}' and city = '{$_SESSION['adClient']['city']}' ","id","area","--区域--",$_SESSION['adClient']['area']);?>
            </select>
            详细地址：<input name="addressMx" type="text" class="text textPrice" value="<?php echo $_SESSION['adClient']['AddressMx'];?>">

            <input type="submit" value="模糊查询">
        </form>
    </div>
    <div class="search">
        <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
        <span onclick="$('[name=ClientForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
        <span onclick="EditList('ClientForm','deleteClient')" class="spanButton">删除所选</span>
        <span class="smallWord floatRight">
			共找到<?php echo $num;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo $AllPage;?>页
		</span>
    </div>
    <!--查询结束-->
    <!--列表开始-->
    <form name="ClientForm"  action="<?php echo root."control/ku/excel.php?type=excelOut";?>" method="post">
        <table class="tableMany">
            <tr>
                <td></td>
                <td>客户姓名</td>
                <td>店铺名称</td>
                <td>微信昵称</td>
                <td>性别</td>
                <td>联系电话</td>
                <td>email</td>
                <td>地址</td>
                <td>店铺logo</td>
                <td>创建时间</td>
                <td>更新时间</td>
                <td></td>
            </tr>
            <?php
            if($num > 0){
                while($kehu = mysql_fetch_array($query)){
                    echo "
				<tr {$trColor}>
					<td><input name='ClientList[]' type='checkbox' value='{$kehu['khid']}'/></td>
					<td>".kong($kehu['name'])."</td>
					<td>".kong($kehu['shopName'])."</td>
					<td>".kong($kehu['wxNickName'])."</td>
					<td>".kong($kehu['wxSex'])."</td>
					<td>".kong($kehu['tel'])."</td>
					<td>".kong($kehu['email'])."</td>
					<td>".kong($kehu['addressMx'])."</td>
					<td>".ProveImgShow($kehu['shopImg'],'暂无图片')."</td>
					<td>".kong($kehu['time'])."</td>
					<td>".kong($kehu['updateTime'])."</td>
					<td><a href='{$root}control/adClientMx.php?id={$kehu['khid']}'><span class='spanButton'>详情</span></a></td>
				</tr>
				";
                }
            }else{
                echo "<tr><td colspan='13'>一个客户都没有</td></tr>";
            }
            ?>
            <input value="导出客户基本信息" type="submit" class="spanButton"/>
        </table>
    </form>
    <?php echo fenye($ThisUrl,7);?>
    <!--列表结束-->
</div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
<script>
    $(function(){
        region("Search","province","city","area");
    });
</script>
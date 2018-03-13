<?php
include "ku/adfunction.php";
ControlRoot("adClient");
if(empty($get['id'])){
    $title = "新建客户";
    $button = "提交";
}else{
    $button = "更新";
    $kehu = query("kehu"," khid = '$get[id]' ");
    if($kehu['khid'] != $get['id']){
        $_SESSION['warn'] = "未找到这个客户的信息";
        header("location:{$root}control/adClient.php");
        exit(0);
    }
    //店铺LOGO
    if(empty($kehu['shopImg'])){
        $logo .="<tr><td><span class='red'>*</span>&nbsp;店铺logo：</td>
                    <td><span onclick='document.shopLogoForm.shopLogoUpload.click();' class='spanButton'>新增</span><span class='smallword'>图像尺寸：宽100px*高100px，最大体积1M</span></td></tr>";
    }else{
        $logo .= " <tr>
    <td>店铺LOGO展示：</td>
    <td>
<!--缩略图展示-->    
  ".ProveImgShow($kehu['shopImg'])."
    <span onclick='document.shopLogoForm.shopLogoUpload.click();' class='spanButton'>更新</span>
    <span class='smallword'>图像尺寸：宽100px*高100px，最大体积1M</span>
    </td>
  </tr>";
    }
    //如果是微信自动注册的客户
    if($kehu['Source'] == "微信自动注册"){
        $title = $kehu['wxNickName'];
        $wxTable = "
		<div class='kuang'>
			<img class='wxIco' src='{$kehu['wxIco']}'>
			<div class='wxData'>
				微信资料：
				<ul>
					<li>openid：{$kehu['wxOpenid']}</li>
					<li>性别：{$kehu['wxSex']}</li>
					<li>昵称：{$kehu['wxNickName']}</li>
					<li>地址：{$kehu['wxAddress']}</li>
					<li>状态：{$kehu['wxFollow']}</li>
				</ul>
			</div>
			<div class='clear'></div>
		</div>
		";
    }else{
        $title = $kehu['name'];
    }
    /*地址处理
     *$quyua 详细地址
     *$quyub 默认地址
     */
    $quyua = query("region","id=$kehu[regionId]");

    $quyub = query("region","id=$kehu[address]");
    //本客户所有订单
    $orderSql = mysql_query(" select * from buyCar where khid = '$kehu[khid]' order by time desc ");
    $orderTr = "";
    if(mysql_num_rows($orderSql) == 0){
        $orderTr .= "<tr><td colspan='9'>还得努力哦，一个订单都没有呢</td></tr>";
    }else{
        while($array = mysql_fetch_array($orderSql)){
            $cycle = (strtotime($array['endTime']) - strtotime($array['signTime'])) / 86400;
            $orderTr .= "
			<tr>
				<td>{$array['id']}</td>
				<td>{$array['goodsName']}</td>
				<td>{$array['addressName']}</td>
				<td>{$array['addressTel']}</td>
				<td>{$array['time']}</td>
				<td>{$array['updateTime']}</td>
				<td>{$array['updateTime']}</td>
				<td>{$array['workFlow']}</td>
				<td><a href='{$root}control/adOrderMx.php?id={$array['id']}'><span class='spanButton'>详情</span></a></td>
			</tr>
			";
        }
    }
    $orderTable = "
	<table class='tableMany'>
		<tr>
			<td>订单编号</td>
			<td>商品名称</td>
			<td>收件人姓名</td>
			<td>收件人电话</td>
			<td>购买时间</td>
			<td>获得积分</td>
			<td>完成时间</td>
			<td>订单状态</td>
			<td></td>
		</tr>
		{$orderTr}
	</table>
	";
    //其他
    $top = "
	<div class='profitDiv'>
		<div class='profitinside'>
			<ul>
				<li>客户ID：{$kehu['khid']}</li>
				<li>推荐人ID：{$kehu['shareId']}</li>
				<li>微信昵称：{$kehu['shareId']}</li>
				<li>微信头像： <img src='{$kehu['wxIco']}' style='width: 100px;'/></li>
				<li>更新时间：{$kehu['updateTime']}</li>
				<li>创建时间：{$kehu['time']}</li>
			</ul>
			<div class='clear'></div>
		</div>
	</div>
	";
    $other = fileUpload("客户",$kehu['khid']).$wxTable.$orderTable.follow("客户",$kehu['khid']);
}
$Region = query("region"," id = '$kehu[RegionId]' ");
$onion = array(
    "客户管理" => root."control/adClient.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--基本资料开始-->
        <?php echo $top;?>
        <div class="kuang">
            <img src="<?php echo root."img/images/text.png";?>">
            客户基本资料
            <form name="ClientForm">
                <table class="tableRight">
                    <tr>
                        <td><span class="red">*</span>&nbsp;客户姓名：</td>
                        <td><input name="kuhuname" type="text" class="text"  value="<?php echo $kehu['name'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;联系电话：</td>
                        <td><input name="tel" type="text" class="text"  value="<?php echo $kehu['tel'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;邮箱：</td>
                        <td><input name="email" type="text" class="text" value="<?php echo $kehu['email'];?>"></td>
                    </tr>
                    <tr>
                        <td>性别：</td>
                        <td><input name="wxSex" type="text" class="text" value="<?php echo $kehu['wxSex'];?>" placeholder="性别"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;会员类型：</td>
                        <td>
                            <select name="viptype" type="type" class="text">
                                <option value="无">--选择会员类型--</option>
                                <option value="普通会员">普通会员</option>
                                <option value="高级会员">高级会员</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;店铺名称：</td>
                        <td><input name="shopName" type="text" class="text" value="<?php echo $kehu['shopName'];?>"></td>
                    </tr>
                    <tr>
                        <?php echo $logo;?>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;身份证号：</td>
                        <td><input name="IdCard" type="text" class="text" value="<?php echo $kehu['IdCard'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;银行名称：</td>
                        <td><input name="bankName" type="text" class="text" value="<?php echo $kehu['bankName'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;银行卡号：</td>
                        <td><input name="bankNum" type="password" class="text" value="<?php echo $kehu['bankNum'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;邮政编码：</td>
                        <td><input name="zipCode" type="text" class="text" value="<?php echo $kehu['zipCode'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;持卡人姓名：</td>
                        <td><input name="bankUserName" type="text" class="text" value="<?php echo $kehu['bankUserName'];?>"></td>
                    </tr>
                    <tr>
                        <td>所属区域：</td>
                        <td>
                            <select name="province" class="city">
                                <?php echo RepeatOption('region','province','--省份--',$quyu['province']);?>
                            </select>
                            <select name="city" class="city">
                                <?php echo RepeatOption(" region where province = '$quyua[province]' ","city","--城市--",$quyua['city']);?>
                            </select>
                            <select name="area" class="area">
                                <?php echo IdOption(" region where province = '$quyua[province]' and city = '$quyua[city]' ","id","area","--区域--",$quyua['id']);?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>详细地址：</td>
                        <td><input name="khAddressMx" type="text" class="text" value="<?php echo $kehu['addressMx'];?>"></td>
                    </tr>
                    <tr>
                        <td>默认地址：</td>
                        <td>
                            <select name="provinces" class="city">
                                <?php echo RepeatOption('region','province','--省份--',$quyu['id']);?>
                            </select>
                            <select name="citys" class="city">
                                <?php echo RepeatOption(" region where province = '$quyub[province]' ","city","--城市--",$quyub['id']);?>
                            </select>
                            <select name="areas" class="areas">
                                <?php echo IdOption(" region where province = '$quyub[province]' and city = '$quyub[city]' ","id","area","--区域--",$quyub['id']);?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input name="adClientId" type="hidden" value="<?php echo $kehu['khid'];?>"></td>
                        <td><input onclick="Sub('ClientForm',root+'control/ku/addata.php?type=adClientEdit')" type="button" class="button" value="<?php echo $button;?>"></td>
                    </tr>
                </table>
            </form>
        </div>
        <!--基本资料结束-->
    </div>
    <!--隐藏域开始-->
    <div class="hide">
        <form name="shopLogoForm" action="<?php echo root."control/ku/adpost.php?type=shopLogo";?>" method="post" enctype="multipart/form-data">
            <input name="shopLogoUpload" type="file" onchange="document.shopLogoForm.submit();">
            <input name="kehuId" type="hidden" value="<?php echo $kehu['khid'];?>">
        </form>
    </div>
    <!--隐藏域结束-->
    <script>
        $(function(){
            region("ClientForm","province","city","area");
        });
    </script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
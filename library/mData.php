<?php
/*
移动端公共异步处理页
*/
include "openFunction.php";
if($get['type']== 'goodsEval'){
	$goodsId 	= $get['goodsId'];
	$page 		= $get['page'];
	$size 		= $get['size'];	
	$json = goodsEvalBuild($goodsId,$page,$size);
#======================用户注册======================#
}else if($get['type'] == 'userRegister'){
	$tel 		= $post['userTel'];
	$verify 	= $post['verify'];
	$userId 	= $post['userId'];
	$shareId 	= $post['shareId'];
	$Pwd 		= $post['Pwd'];
	$rePwd 		= $post['rePwd'];
	$isSure		= $get['isSure'];
	if(empty($tel) || preg_match($CheckTel,$tel) == 0){
		$json['warn'] = '请正确输入手机号码' ;
	}else if( findOne('kehu',"tel = '$tel'") ){
		$json['warn'] = '该手机号已注册' ;
	}else if(empty($verify) || $verify != $_SESSION['Prove']['rand']){
		$json['warn'] = '请正确填写验证码' ;
	}else if( empty($Pwd) || strlen($Pwd) < 8 || strlen($Pwd) > 16 ){
		$json['warn'] = '请填写8-16位的密码' ;
	}else if( empty($rePwd) ){
		$json['warn'] = '请填写确认密码' ;
	}else if( $rePwd != $Pwd ){
		$json['warn'] = '两次密码不一致' ;
	}else if($isSure == 'no'){
		$json['warn'] = '请勾选用户协议' ;
	}else{
		$pwd = md5($Pwd);
		$sql = "UPDATE kehu SET tel= '$tel',`password` = '$pwd' WHERE khid = '{$_SESSION['khid']}'";
		$bool = mysql_query($sql);
		if($bool)
		{
			$json['warn'] = 2 ;
			$json['href'] = root."m/mRegSucced.php";
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
/**
 * 获得手机验证码
 */
}else if($get['type'] == 'getVerify'){
	$tel = $post['tel'];
	$info = sendSMS($tel);
	$json['warn'] = $info;
}
/**
 * 个人信息编辑
 */
else if( $get['type'] == 'editUserInfo' ){
	
	$shopName 	= $post['userShopName'];	#店铺名称
	$name 		= $post['userName'];		
	$sex		= $post['sex'];
	$cardNum 	= $post['userCardNum'];
	$email 		= $post['userEmail'];
	$khid 		= $post['userId'];
	if($khid != $_SESSION['khid']){
		$json['warn'] = '登录用户与session不符' ;
	}else if(empty($shopName)){
		$json['warn'] = '请填写店铺名称' ;
	}else if(empty($name)){
		$json['warn'] = '请填写姓名' ;
	}else if(empty($sex)){
		$json['warn'] = '请填写性别' ;
	}else if(empty($cardNum) || !isCreditNo($cardNum)){
		$json['warn'] = '请正确填写身份证号码' ;
	}else if(empty($email)  || preg_match($CheckEmail,$email) == 0 ){
		$json['warn'] = '请正确填写邮箱';
	}else{
		$sql = "UPDATE kehu SET shopName = '$shopName',`name` = '$name',wxSex = '$sex',IdCard = '$cardNum',email = '$email' WHERE khid = '$khid'";
		$json['sql'] = $sql ;
		$bool = mysql_query($sql);
		if($bool){
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '资料更新错误' ;
		}
	}
}
/**
 * 个人信息 银行卡信息编辑
 */
else if($get['type'] == 'editUserCardInfo'){
	$bankUserName 	= $post['userName'];#持卡人姓名
	$bankNum 		= $post['cardNum'];	#卡号
	$bankName 		= $post['bank'];	#银行名称
	$khid			= $post['khid'];	#khid
	if(empty($khid) || $_SESSION['khid'] != $khid){
		$json['warn'] = '未知错误' ;
	}else if(empty($bankUserName)){
		$json['warn'] = '请输入持卡人姓名' ;
	}else if(empty($bankNum)){
		$json['warn'] = '请输入银行卡卡号' ;
	}else if( !bankVerify($bankNum) ){
		$json['warn'] = '请输入正确的银行卡卡号' ;
	}else if(empty($bankName)){
		$json['warn'] = '请输入银行卡名称' ;
	}else{
		$sql = "UPDATE kehu SET bankUserName = '$bankUserName',bankNum = '$bankNum',bankName = '$bankName' WHERE khid = '$khid'";
		$bool = mysql_query($sql);
		if($bool)
		{
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 用户中心 地址删除
 */
else if( $get['type'] == 'delUserAddress' ){
	$id = $post['id'];
	$res = findOne('address',"id = '$id'");
	if($res){
		$sql = "DELETE FROM `address` WHERE id = '$id'";
		$bool = mysql_query($sql);
		if($bool)
		{
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}else{
		$json['warn'] = '未知错误' ;
	}
}
/**
 * 用户中心 添加 || 编辑 地址
 */
else if( $get['type'] == 'editUserAddress' ){
	$zipCode 		= $post['userZipCode'];		#邮编
	$contactName 	= $post['userName'];		#姓名
	$contactTel 	= $post['userTel'];			#手机号码	
	$regionId 		= $post['area']; 			#regionId
	$addressMx 		= $post['addressMx'];		#明细
	$addressId 		= $post['addressId'];		#addressId
	$defaultAddress = $post['defaultAddress'];	#默认地址
	
	if(empty($zipCode) || preg_match($CheckZipCode,$zipCode) == 0){
		$json['warn'] = '请正确填写邮编';
	}else if(empty($contactName)){
		$json['warn'] = '请填写姓名' ;
	}else if(empty($contactTel) || preg_match($CheckTel,$contactTel) == 0 ){
		$json['warn'] = '请填写手机号码' ;
	}else if(empty($regionId) || !findOne('region',"id = '$regionId'") ){
		$json['warn'] = '请完善地址' ;
	}else if(empty($addressMx)){
		$json['warn'] = '请填写详细地址' ;
	}else if(empty($addressId)){
		#添加
		$id = date('Ymd').rand(1111,9999);
		$sql = "INSERT INTO `address`(`id`, `khid`, `contactName`, `contactTel`, `regionId`, `addressMx`, `zipCode`, `updateTime`, `time`) VALUES ('$id','{$_SESSION['khid']}','{$contactName}','$contactTel','$regionId','$addressMx','$zipCode','$time','$time')";
		$bool = mysql_query($sql);
		if($bool)
		{
			if( $defaultAddress == '是' ){
				$sql = "UPDATE kehu SET address = '$id' WHERE khid = '{$_SESSION['khid']}'";
				mysql_query($sql);	
			}
			$json['warn'] = 2 ;
			$json['href'] = root."m/mUser/mAddress.php";
		}else{
			$json['warn'] = '未知错误' ;
		}
	}else{
		#编辑
		$sql = "UPDATE `address` SET `khid`='{$_SESSION['khid']}',`contactName`='$contactName',`contactTel`='$contactTel',`regionId`='$regionId',`addressMx`='$addressMx',`zipCode`='$zipCode',`updateTime`='$time' WHERE id = '$addressId'";
		$bool = mysql_query($sql);
		$json['sql'] = $sql ;
		if($bool)
		{
			if( $defaultAddress == '是' ){
				$sql = "UPDATE kehu SET address = '$addressId' WHERE khid = '{$_SESSION['khid']}'";
				mysql_query($sql);	
			}
			$json['warn'] = 2 ;
			$_SESSION['warn'] = '编辑成功';
			$json['href'] = root."m/mUser/mAddress.php";
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 用户地址 设置默认地址
 */
else if( $get['type'] == 'addressChoice' ){
	$id = $post['id'];
	$res = findOne('address',"id = '$id'");
	if($res){
		$sql = "UPDATE kehu SET `address` = '$id' WHERE khid = '{$_SESSION['khid']}'";
		$bool = mysql_query($sql);
		if($bool)
		{
			$json['warn'] = 2 ;
			$_SESSION['warn'] = '默认地址设置成功';
		}else{
			$json['warn'] = '未知错误' ;
		}
	}else{
		$json['warn'] = '未知错误' ;
	}
}
/**
 * 购物车地址选中
 */
else if( $get['type'] == 'buyCarAddressChoice' ){
	$id = $post['id'];
	$res = findOne('address',"id = '$id'");
	if($res){
		$_SESSION['addressId'] = $id;
		$json['warn'] = 2 ;
	}else{
		$json['warn'] = '未知错误' ;
	}
}
/**
 * 用户中心 绑定手机号码
 */
else if( $get['type'] == 'bindUserTel' ){
	$tel 	= $post['tel'];
	$verify = $post['verify'];
	if(empty($tel) || $tel == $kehu['tel']){
		$json['warn'] = '请正确输入手机号码' ;
	}else if( $verify != $_SESSION['Prove']['rand'] ){
		$json['warn'] = '请正确输入验证码' ;
	}else{
		$sql = "UPDATE kehu SET tel = '$tel' WHERE khid = '{$_SESSION['khid']}'";
		$bool = mysql_query($sql);
		if($bool){
			$json['warn'] = 2 ;
			$_SESSION['warn'] = '绑定成功';
			$json['href'] = root."m/mUser/mInfo.php";
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 推荐码申述
 */
else if( $get['type'] == 'editUserCode' ){
	$defaultCode 	= $post['defaultCode'];	#默认推荐码
	$changeCode 	= $post['changeCode'];	#更改推荐码
	$shareName 		= $post['shareName'];	#邀请人
	$shareTel 		= $post['shareTel'];	#邀请人
	$explainName 	= $post['explainName'];	#申请人
	$explainTel 	= $post['explainTel'];	#声请人
	$khid 			= $post['kehuId'];		#khid
	if( empty($khid) ){
		$json['warn'] = '未知错误' ;
	}else if( findOne('codeExplain',"khid = '{$khid}' AND status !='审核成功'") ){
		$json['warn'] = '你有待审核的申述信息,请勿重复提交';
	}else if( empty($changeCode) ){
		$json['warn'] = '请填写更改后的推荐码' ;
	}else if( empty($shareName) ){
		$json['warn'] = '请填写邀请人姓名' ;
	}else if( empty($shareTel) || preg_match($CheckTel,$shareTel) == 0 ){
		$json['warn'] = '请填写邀请人手机号码' ;
	}else if( empty($explainName) ){
		$json['warn'] = '请填写声请人姓名' ;
	}else if( empty($explainTel) || preg_match($CheckTel,$explainTel) == 0 ){
		$json['warn'] = '请填写请人手机号码' ;
	}else{
		$id = date('YmdH').mt_rand(1000,9999);
		$sql = "INSERT INTO `codeExplain`(`id`, `khid`, `defaultCode`, `changeCode`, `shareName`, `shareTel`, `explainName`, `explainTel`, `status`, `actionId`, `updateTime`, `time`) VALUES ('$id','$khid','$defaultCode','$changeCode','$shareName','$shareTel','$explainName','$explainTel','待审核','','$time','$time')";
		$bool = mysql_query($sql);
		if($bool)
		{
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 需求发布
 */
else if( $get['type'] == 'addNeed' ){
	$khid 		= $post['khid'];
	$theme 		= $post['project'];
	$giftType 	= $post['goodsType'];
	$num 		= $post['num'];
	$endTime 	= $post['endtime'];
	$tel 		= $post['tel'];
	$text 		= $post['textInfo'];
	$img 		= $post['imgSet'];
	if(!findOne('kehu',"khid = '$khid'")){
		$json['warn'] = '未知错误' ;
	}else if( empty($theme) ){
		$json['warn'] = '请填写需求主题' ;
	}else if( empty($giftType) ){
		$json['warn'] = '请选择礼品类型' ;
	}else if( empty($num) || preg_match($CheckInteger,$num) == 0 ){
		$json['warn'] = '请填写采购数量' ;
	}else if( empty($endTime) || strtotime($endTime) <= time() ){
		$json['warn'] = ' 请正确填写截止日期' ;
	}else if( empty($tel) || preg_match($CheckTel,$tel) == 0 ){
		$json['warn'] = ' 请输入联系方式' ;
	}else {
		$id = 'DE'.date('YmdHis').mt_rand(1000,9999);
		$sql = "INSERT INTO `demand`(`id`, `khid`, `theme`, `giftType`, `num`, `endTime`, `tel`, `text`, `status`, `updateTime`, `time`) VALUES ('$id','$khid','$theme','$giftType','$num','$endTime','$tel','$text','已发布','$time','$time')";
		$bool = mysql_query($sql);
		if($bool)
		{
			if( !empty($img) ){
				foreach ($img as $key => $val)
				{
					fileExists('demandImg/'.date('Y-m'));
					$fileName = uploadImgBase64($val, 'img/demandImg/'.date('Y-m'));
					$path = ServerRoot . $fileName;
					JpegSmallWidth($path, 600);
					$suiji = suiji();
					$sql = "INSERT INTO `talkImg`(`talkId`, `img`, `time`) VALUES ('$id','$fileName','$time')";
					$bool = mysql_query($sql);
				}
			}
			if( $bool ){
				$json['warn'] = 2 ;
				$json['href'] = root."m/mNeedSucced.php";
			}else{
				$json['warn'] = '网络繁忙' ;
			}
		}else{
			$json['warn'] = '网络繁忙' ;
		}
	}
}
/**
 * 添加购物车
 */
else if( $get['type'] == 'addBuyCar' ){
	$goodsTypeId 	= $post['goodsTypeId'];		#规格id
	$goodsNum 		= $post['goodsNum'];		#数量
	$goodsId 		= $post['goodsId'];			#商品id
	$status			= $get['status'];			#状态
    if( empty($_SESSION['khid']) ){
        returnJsonText('请用微信打开');
    }else if( empty($goodsTypeId) ){
        returnJsonText('请选择规格');
    }else if( empty($goodsNum) || preg_match($CheckInteger,$goodsNum) == 0 ){
        returnJsonText('购买数量有误');
    }else if( empty($goodsId) ) {
        returnJsonText('缺少商品id');
    }
	$goodsData = findOne('goods',"id = '$goodsId'");
    if(empty($goodsData)){
        returnJsonText('商品不存在');
    }
	$goodsSkuData = findOne('goodsSku',"id = '$goodsTypeId'");
    if(empty($goodsSkuData)){
        returnJsonText('规格不存在');
    }
    if( $goodsSkuData['number'] < $goodsNum ){
        returnJsonText('加入购物车失败，商品库存不足');
    }//此处检测后，购物时要再次检测；
    //根据商品的类型，来确定接下来的购买流程；
	switch ($goodsSkuData['type']){
        case '定制':
            break;
        case '分类价格':
                if( $status == 'noselect' ){//真正的加入购物车；
                    $res = findOne('buyCar',"khid = '{$_SESSION['khid']}' AND goodsId = '$goodsId' AND goodsSkuId = '$goodsTypeId' AND workFlow = '未选定' ");
                    if(!empty($res)){
                        $firstNum = $res['buyNumber'];
                    }else{
                        $firstNum = 0;
                    }
                    //分出来样品
                    if($goodsSkuData['endPatch'] == 1){
                        if($firstNum == 0){
                            $id = 'JL'.date('YmdHis').mt_rand(0,9999);
                            $sql = "INSERT INTO `buyCar`(`id`, 
                                              `khid`, 
                                              `goodsId`, 
                                              `goodsSkuId`, 
                                              `goodsName`, 
                                              `goodsSkuName`,
                                              `buyNumber`, 
                                              `buyPrice`, 
                                              `workFlow`, 
                                              `updateTime`, 
                                              `time`) 
                                      VALUES ('{$id}',
                                              '{$_SESSION['khid']}',
                                              '{$goodsId}',
                                              '{$goodsTypeId}',
                                              '{$goodsData['name']}',
                                              '{$goodsSkuData['name']}',
                                              '1',
                                              {$goodsSkuData["retailPrice"]},
                                              '未选定',
                                              '$time',
                                              '$time')";
                        }
                        $bool = mysql_query($sql);
                        if($bool){
                            returnJsonText(2) ;
                        }else{
                            returnJsonText('样品只能加入一件哦');
                        }

                    }else{
                        //查找出所有的该规格的起批量；
                        $firstSku = $goodsSkuData['name'];
                        $allSkuPriceData = findAll('goodsSku',"goodsId= '$goodsId' and type='分类价格' and name='$firstSku' and endPatch!=1");
                        $buyAllNum = $firstNum+$goodsNum;
                        foreach ($allSkuPriceData as $key => $val){
                            if($buyAllNum >= $val['thePatch'] && $buyAllNum <= $val['endPatch']){
                                $newPrice = $val['retailPrice'];
                            }
                        }
                        //检测如果没有设置价格，购买数量不对；
                        if(!isset($newPrice)){
                            returnJsonText('加入购物车失败，选择的商品数量没有相应的起批量');
                        }
                        if($firstNum != 0) {
                            $sql = "UPDATE buyCar SET buyNumber = buyNumber + $goodsNum,buyPrice = '$newPrice',updateTime = '$time' WHERE id = '{$res['id']}'";
                        }else{
                            $id = 'JL'.date('YmdHis').mt_rand(0,9999);
                            $sql = "INSERT INTO `buyCar`(`id`, 
                                              `khid`, 
                                              `goodsId`, 
                                              `goodsSkuId`, 
                                              `goodsName`, 
                                              `goodsSkuName`,
                                              `buyNumber`, 
                                              `buyPrice`, 
                                              `workFlow`, 
                                              `updateTime`, 
                                              `time`) 
                                      VALUES ('{$id}',
                                              '{$_SESSION['khid']}',
                                              '{$goodsId}',
                                              '{$goodsTypeId}',
                                              '{$goodsData['name']}',
                                              '{$goodsSkuData['name']}',
                                              '{$goodsNum}',
                                              $newPrice,
                                              '未选定',
                                              '$time',
                                              '$time')";
                        }

                        $bool = mysql_query($sql);
                        if($bool){
                            returnJsonText(2) ;
                        }else{
                            returnJsonText('增加数量/增加到购物车表失败，请重试');
                        }
                    }
                }else if( $status == 'select' ){
                    #立即购买 已选定
                    /* $res = findOne('buyCar',"khid = '{$_SESSION['khid']}' AND goodsId = '$goodsId' AND goodsSkuId = '$goodsTypeId' AND workFlow = '未购买' ");
                    $res ? $firstNum = $res['buyNumber'] : $firstNum = 0;
                    if( ($goodsNum + $firstNum) >= $goodsSkuData['thePatch'] ){
                        $newPrice = $goodsSkuData['retailPrice'];
                    }else{
                        $newPrice = $goodsSkuData['price'];
                    }
                    if($res)
                    {
                        $sql = "UPDATE buyCar SET buyNumber = buyNumber + $goodsNum,buyPrice = '$newPrice',updateTime = '$time' WHERE id = '{$res['id']}'";
                    }else{
                        $id = 'JL'.date('YmdHis').mt_rand(0,9999);
                        $sql = "INSERT INTO `buyCar`(`id`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$_SESSION['khid']}','$goodsId','$goodsTypeId','{$goodsData['name']}','{$goodsSkuData['name']}','$goodsNum','{$newPrice}','已选定','$time','$time')";
                    } */
                    $id = 'JL'.date('YmdHis').mt_rand(0,9999);
                    $sql = "INSERT INTO `buyCar`(`id`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`, `buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$_SESSION['khid']}','$goodsId','$goodsTypeId','{$goodsData['name']}','{$goodsSkuData['name']}','$goodsNum','{$goodsSkuData['retailPrice']}','未提交','$time','$time')";
                    echo $sql;die;
                    $bool = mysql_query($sql);
                    if($bool){
                        $sql = "UPDATE goodsSku SET number = number - $goodsNum WHERE id = '$goodsTypeId'";
                        $bool = mysql_query($sql);
                        if( $bool ){
                            $json['warn'] = '2' ;
                        }else{
                            $json['warn'] = '修改库存失败';
                        }
                        $json['warn'] = 2 ;
                        $json['href'] = root."m/mEditOrder.php?type=one&bid={$id}";
                    }else{
                        $json['warn'] = '未知错误' ;
                        //会发生addressName，没有默认值的错误；
                    }
                }
            break;
        default:
            $json['warn'] = '类型错误';
            break;
    }
}
/**
 * 订货单 购物车数量变化
 */
else if( $get['type'] == 'changeBuyNum' ){
	$id = $post['id'];
	$num = $post['num'];  //该商品的总量；
	$res = findOne('buyCar',"id = '$id'");
	if( empty($res) ){
	    $json['warn'] = '失败，商品不存在';
	    $json['type'] = 'delete';
	    returnJson($json);
//		returnJsonText('失败，商品不存在') ;
	}else if( empty($num) || preg_match($CheckInteger,$num) == 0 ){
		$json['warn'] = '请正确填写数量' ;
		$json['nowNum'] = 1;
	}else {
		$goodsSkuData = findOne('goodsSku',"id = '{$res['goodsSkuId']}'");
        if($goodsSkuData['endPatch'] == '1'){
            $json['warn'] = '样品只能订购一件';
            $json['type'] = 'onlyone';
            $json['nowNum'] = 1;
            mysql_query("UPDATE buyCar SET buyNumber = 1 WHERE id = '$id' ");
            returnJson($json);
//            returnJsonText('样品只能订购一件');
        }
        $firstSku = $goodsSkuData['name'];
        $allSkuPriceData = findAll('goodsSku',"goodsId= '{$res["goodsId"]}' and type='分类价格' and name='$firstSku' and endPatch!=1");
        foreach ($allSkuPriceData as $key => $val){
            if($num >= $val['thePatch'] && $num <= $val['endPatch']){
                $newPrice = $val['retailPrice'];
            }
        }
        if(!isset($newPrice)){
            $json['warn'] = '选择的商品数量没有对应的起批量价格';
            $json['nowNum'] = 1;
            returnJson($json);
//            returnJsonText('选择的商品数量没有对应的起批量价格');
        }
//        var_dump($newPrice);die;

		$sql = "UPDATE buyCar SET buyNumber = $num,buyPrice = '$newPrice' WHERE id = '$id' ";
		$bool = mysql_query($sql);
		if( $bool ){
			$json['warn'] = 2 ;
			$json['price'] = $newPrice;
			$json['nowNum'] = $num;
			$json['arr'] = getInegralAndProfit($kehu['khid']);
            returnJson($json);
		}else{
		    $json['warn'] = '系统错误，请重试！';
		    $json['nowNum'] = 1;
			returnJson($json);
		}
	}
}
/**
 * 订货单 购物车删除
 */
else if( $get['type'] == 'delBuyCar' ){
	$id = $post['id'];
	$res = findOne('buyCar',"id = '$id'");
	if( !$res ){
		$json['warn'] = '未知错误' ;
	}else{
		$sql = "DELETE FROM buyCar WHERE id = '$id'";
		$bool = mysql_query($sql);
		if( $bool ){
			$sql = "UPDATE goodsSku SET number = number + '{$res['buyNumber']}' WHERE id = '{$res['goodsSkuId']}'";
			mysql_query($sql);
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 订货单 购物车提交
 */
else if( $get['type'] == 'subBuyCar' ){
	$idArr = $post['id'];
	if( empty($idArr) ){
		$json['warn'] = '请选择商品后再次提交' ;
	}else{
        foreach ($idArr as $key =>  $val){
            $idArr[$key] = '"'.$val.'"';
        }
	    $idStr = '('.implode(',',$idArr).')';
//        $values = [];
//        foreach($allBuyCarGoods as $val){
//             $values[] = "(NULL,'{$_SESSION['khid']}','$goodsId','$goodsTypeId','{$goodsData['name']}','{$goodsSkuData['name']}','$goodsNum','{$goodsSkuData['retailPrice']}','未提交','$time','$time')";
//        }

        $sql = "UPDATE buyCar SET workFlow = '已选定' WHERE id in {$idStr}";
        if(mysql_query($sql)){
            $json['warn'] = 2 ;
            $json['href'] = root."m/mEditOrder.php?type=all";
        }else{
            returnJsonText('提交失败，系统繁忙');
        }
	}
}
/**
 * 评价
 */
else if( $get['type'] == 'addTalk' ){
	$word 		= $post['text'];	#评论内容
	$goodsId 	= $post['goodsId'];	#goodsId
	$khid 		= $post['userId'];	#khid
	$buyCarId 	= $post['buyId'];	#buyCar Id
	$img 		= $post['imgSet'];
	$res = findOne('goods',"id = '$goodsId'");
	if( $khid != $_SESSION['khid']  ){
		$json['warn'] = '未登录' ;
	}else if( empty($buyCarId) ){
		$json['warn'] = '购物车id为空' ;
	}else if( empty($goodsId) ){
		$json['warn'] = '商品id为空' ;
	}else{
		//$info = findOne('talk',"id = '$talkId'");
		$info = findOne('buyCar',"id = '$buyCarId'");
		if( $info['workFlow'] == '已评价' )
		{
			$json['warn'] = '请勿重复评论';
		}else {
			
			$id = "JL".date('YmdHis').mt_rand(1000,9999);
			$sql = "INSERT INTO `talk`(`id`, `khid`,`target`, `targetId`,`word`, `grade`, `xian`, `time`) VALUES ('$id','$khid','$buyCarId','$goodsId','$word',5,'显示','$time')";
			$bool = mysql_query($sql);
			
			if( $bool )
			{
				$sql = "UPDATE buyCar SET workFlow = '已评价' WHERE id = '$buyCarId'";
				mysql_query($sql);
				foreach ($img as $key => $val) {
					fileExists('talkImg/'.date('Y-m'));
					$fileName = uploadImgBase64($val, 'img/talkImg/'.date('Y-m'));
					$path = ServerRoot . $fileName;
					JpegSmallWidth($path, 600);
					$suiji = suiji();
					$sql = "INSERT INTO `talkImg`(`id`, `talkId`, `img`, `time`) VALUES ('$suiji','$id','$fileName','$time')";
					mysql_query($sql);
				}
				$json['warn'] = 2 ;
				$json['href'] = root."m/mUser/mAllOrder.php";
			}else{
				$json['warn'] = '未知错误' ;
			}	
		}
	}
}
/**
 * 积分兑换
 */
else if( $get['type'] == 'integralExchange' ){
	$goodsId 		= $post['goodsId'];
	$skid	 		= $post['skid'];	
	$myIntegral 	= Integral::getIntegral($kehu['khid']);
	$canUseTotal 	= Integral::$canUseTotal;
	$needIntegral 	= Integral::getGoodsIntegral($goodsId);
	if( $needIntegral > $canUseTotal ){
		$json['warn'] = '积分不足' ;
	}else{
		$id = 'JL'.date('YmdHis').mt_rand(0,9999);
		$goodsInfo = findOne('goods g,goodsSku sk',"g.id = '$goodsId' AND g.id = sk.goodsId","g.*,sk.name skname");
		
		$address = findOne('address',"id = '{$kehu['address']}'");
		$sql = "INSERT INTO `buyCar`(`id`,`type`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`addressName`, `addressTel`, `regionId`, `addressMx`, `buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','积分订单','{$kehu['khid']}','$goodsId','$skid','{$goodsInfo['name']}','{$goodsInfo['skname']}','{$address['contactName']}','{$address['contactTel']}','{$address['regionId']}','{$address['addressMx']}','1','{$needIntegral}','已付款','$time','$time')";
		
		$bool = mysql_query($sql);
		$sql = "INSERT INTO `integral`(`khid`, `type`, `goodsId`, `changeCode`, `laveCode`, `updateTime`, `time`) VALUES ('{$kehu['khid']}','支出','$goodsId','$needIntegral','$needIntegral','$time','$time')";
		$bool = mysql_query($sql);
		$option = [
			'success' => 2,
			'url' => 'm/mUser/mOrder.php',
			'fail' =>'未知错误'
		];
		$json = redirect($bool,$option);
	}
}
/**
 * 提现
 */
else if( $get['type'] == 'getMoney' ){
	$mon = $post['mon'];
	Income::availableFree($kehu['khid']);
	$available = Income::$available;
	if( $mon != $available ){
		$json['warn'] = '未知错误' ;
	}else{
		$sql = "INSERT INTO `withdraw`(`khid`, `moneny`,`workFlow`, `updateTime`, `time`) VALUES ('{$kehu['khid']}','$available','审核中','$time','$time')";
		$bool = mysql_query($sql);
		if( $bool ){
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 定制商品
 */
else if( $get['type'] == 'customSelf' ){
	$title		= $post['title'];		#title
	$khid		= $post['khid'];		
	$gid		= $post['gid'];
	$skid		= $post['skid'];
	$img 		= $post['imgSet']; 
	$goodsNum	= 1;//$post['num'];
	$goodsData = findOne('goods',"id = '$gid'");
	$goodsSkuData = findOne('goodsSku',"id = '$skid'");
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '未登录' ;
	}else if( !$goodsData || !$goodsSkuData ){
		$json['warn'] = '商品或规格不存在' ;
	}else if( empty($title) ){
		$json['warn'] = '请填写定制文字' ;
	}else if( empty($img) ){
		$json['warn'] = '请上传logo' ;
	}else{
		$id = 'JL'.date('YmdHis').mt_rand(1000,9999);
		$sql = "INSERT INTO `buyCar`(`id`,`type`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','定制订单','{$_SESSION['khid']}','$gid','$skid','{$goodsData['name']}','{$goodsSkuData['name']}','$goodsNum','{$goodsSkuData['price']}','已选定','$time','$time')";
		$bool = mysql_query($sql);
		if( $bool )
		{
			$cid = 'CM'.date('YmdHis').mt_rand(1000,9999);
			#添加定制信息
			$sql = "INSERT INTO `customMade`(`id`, `khid`, `goodsId`, `goodsSkuId`, `title`, `updateTime`, `time`) VALUES ('$cid','{$_SESSION['khid']}','$gid','$skid','$title','$time','$time')";
			$bool = mysql_query($sql);
			#上传定制图片
			foreach ($img as $key => $val)
			{
				fileExists('customImg/'.date('Y-m'));
				$fileName = uploadImgBase64($val, 'img/customImg/'.date('Y-m'));
				$path = ServerRoot . $fileName;
				JpegSmallWidth($path, 600);
				$suiji = suiji();
				$sql = "INSERT INTO `talkImg`(`id`, `talkId`, `img`, `time`) VALUES ('$suiji','$cid','$fileName','$time')";
				$bool = mysql_query($sql);
				if( $bool ){
					$json['warn'] = 2 ;
					$json['href'] = root."m/mEditOrder.php?bid={$id}";
				}
			}
		}else{
			$json['warn'] = '网络繁忙';
		}
	}
	/* if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '未知错误' ;
	}else if( empty($title) ){
		$json['warn'] = '请填写定制文字' ;
	}else if( !findOne('goodsSku',"id = '$skid'") ){
		$json['warn'] = '未知错误' ;
	}else if( !$res || empty($res['logoImg'])){
		$json['warn'] = '请上传logo图片';
	}else{
		$sql = "UPDATE customMade SET title = '$title' WHERE id = '$customId' AND khid = '$khid'";
		$bool = mysql_query($sql);
		$option = [
			'success' => 2,
			'url' => "m/mEditOrder.php",
			'fail' => '未知错误'
		];
		$json = redirect($bool,$option);
		if( $bool ) unset($_SESSION['customMadeId']);
		$id = 'JL'.date('YmdHis').mt_rand(0,9999);
		$goodsData = findOne('goods',"id = '$gid'");
		$goodsSkuData = findOne('goodsSku',"id = '$skid'");
		$sql = "INSERT INTO `buyCar`(`id`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$_SESSION['khid']}','$gid','$skid','{$goodsData['name']}','{$goodsSkuData['name']}','$goodsNum','{$goodsSkuData['price']}','已选定','$time','$time')";
		$bool = mysql_query($sql);
	} */
}
/**
 * 获取优惠劵
 */
else if( $get['type'] == 'getCoupon' ){
	$couId = $post['couId'];
	$khid = $_SESSION['khid'];
	$res = findOne('kehuCoupon',"khid = '$khid' AND couponId = '$couId'");
	if( $res ){
		$json['warn'] = '你已经领取了优惠劵' ;
	}else{
		$sql = "INSERT INTO `kehuCoupon`(`khid`, `couponId`, `status`, `time`) VALUES ('$khid','$couId','未使用','$time')";
		$bool = mysql_query($sql);
		$option = [
			'success' => 2,
			'fail' => '未知错误'
		];
		$json = redirect($bool,$option);
	}
}
/**
 * 支付方式 及 物流选择
 */
else if( $get['type'] == 'setPayType' ){
	$logistiscName 	= $post['logistiscName'];	#物流公司
	$khid 			= $post['khid'];			#khid
	$payType 		= $post['payType'];			#付款方式
	$getType 		= $post['getType'];			#取货方式
	$type 			= $post['type'];			#购买方式（立即购买、购物车）
	$bid 			= $post['bid'];				#buyCar ID
	
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '未知错误1' ;
	}else if( !in_array($payType,['online','bank']) ){
		$json['warn'] = '未知错误2' ;
	}else if( empty($logistiscName) ){
		$json['warn'] = '请选择物流快递' ;	
	}else if( !in_array($getType,['物流到付','物流自提']) ){
		$json['warn'] = '未知错误3' ;
	}else if( !in_array($type,['one','all']) ){
		$json['warn'] = '未知错误4' ;
	}else{
		if( $payType == 'online' ){
			$payType = '在线支付';
		}else if( $payType == 'bank' ){
			$payType = '银行支付';
		}
		/* if( $type == 'one' ){
			$res = findOne('buyCar',"id = '$bid'");
		}else if( $type == 'all' ){
			$res = findOne('buyCar',"khid = '$khid' AND workFlow = '已选定' ORDER BY time DESC");	
		}
		$sql = "UPDATE buyCar SET logisticsName = '$logistiscName',delivery = '$getType',payType = '$payType' WHERE id = '{$res['id']}'";
		$bool = mysql_query($sql);
		if( $bool )
		{
			$json['warn'] = 2;
			$json['info'] = '添加成功' ;
		}else{
			$json['warn'] = '未知错误';
		} */
		$_SESSION['buyCar']['payType'] 			= $payType;			#支付方式
		$_SESSION['buyCar']['logistiscName'] 	= $logistiscName;	#物流公司
		$_SESSION['buyCar']['getType'] 			= $getType;			#取货方式
		$json['warn'] = 2 ;
	}
}
/**
 * 添加发票信息
 */
else if( $get['type'] == 'addInvoice' ){
	$companyName 	= $post['comName'];	#单位
	$taxNum 		= $post['comNum'];	#税号
	$khid 			= $post['khid'];	#khid
	$type 			= $post['type'];	#发票类型
	$buyType 		= $post['buyType'];	#购买方式
//	$bid 			= $post['bid'];		#buyCar id
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '请重新登陆商城' ;
	}else if( !in_array($buyType,['one','all']) ){
		$json['warn'] = '未知错误2';
	}else if( empty($companyName) ){
		$json['warn'] = '请填写发票抬头' ;
	}else if( empty($taxNum)  ){
		$json['warn'] = '请填写发票税号' ;
	}else{
		/* if( $buyType == 'one' ){
			$res = findOne('buyCar',"id = '$bid'");
		}else if( $buyType == 'all' ){
			$res = findOne('buyCar',"khid = '$khid' AND workFlow = '已选定' ORDER BY time DESC");	
		}
		$sql = "UPDATE buyCar SET companyName = '$companyName',taxNum = '$taxNum' WHERE id = '{$res['id']}'";
		$bool = mysql_query($sql);
		if( $bool )
		{
			$json['warn'] = 2;
			$json['info'] = '添加成功' ;
		}else{
			$json['warn'] = '未知错误3';
		} */
		$_SESSION['buyCar']['companyName'] 	= $companyName;	#单位
		$_SESSION['buyCar']['taxNum'] 		= $taxNum;		#税号
        $_SESSION['buyCar']['taxType'] = $type;
		$json['warn'] = 2 ;
	}
}
/**
 * 删除购物车
 */
else if( $get['type'] == 'delMyBuyCar' ){
	$goodsIdArr = $post['id'];		#array buyCarId
	$khid 		= $post['khid'];	#khid
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '未知错误' ;
	}else{
		foreach ($goodsIdArr as $val)
		{
			$buyCar = findOne('buyCar',"id = '$val'");
			$sql = "DELETE FROM `buyCar` WHERE id = '{$val}'";
			$bool = mysql_query($sql);
			if( $bool = 1 ){
				if($buyCar){
					$sql = "UPDATE goodsSku SET number = number + '{$buyCar['buyNumber']}' WHERE id = '{$buyCar['goodsSkuId']}'";
					$bool = mysql_query($sql);
				}
			}
		}
		$option = [
			'success' => 2,
			'fail' => '未知错误'
		];
		$json = redirect($bool,$option);
	}
}
/**
 * 购物车提交 价格检查
 */
else if( $get['type'] == 'subPayForm' ){
//    var_dump($time);
//    print_r($_SESSION);
//    print_r($post);
    //开始事务
    begin();
    //列出有用的数据
    $newAddressId = isset($_SESSION['addressId']) ? $_SESSION['addressId'] : '' ;//检测空，检测默认地址，
    $khid = $_SESSION['khid'];
    //得到所有商品
    $allGoods = findAll('buyCar',"khid = '$khid' AND workFlow = '已选定' ORDER BY time DESC");

    //付款方式:在线支付，银行转账
    $payType = $_SESSION['buyCar']['payType'];  //检测空；
    //发票抬头
    $invoiceTitle = $_SESSION['buyCar']['companyName'];
    //发票税号
    $invoiceNumber = $_SESSION['buyCar']['taxNum'];

    $invoiceType = $_SESSION['buyCar']['taxType'];
    //优惠券id，暂无
//    $couponId = $_SESSION['couponId'];
    //检测支付方式
    if(!empty($payType)){
        if($payType == '在线支付'){
            $prefixTitle = 'w';  //线上
        }else{
            $prefixTitle = 'd';  //线下
        }
    }else{
        returnJsonText('请选择支付方式');
    }
    //检测税
    if(empty($invoiceTitle) || empty($invoiceNumber) || empty($invoiceType)){
        returnJsonText('请填写发票信息');
    }
    //产生一个订单号；
    $orderSN = $prefixTitle.date('YmdHis').substr($khid,-3).rand(1111,9999);
    //拼凑插入order_goods的values;
    $values = [];
    $allGoodsSumPrice = 0;
    //添加列表图像;
    $goodsIDArray = [];
    foreach($allGoods as $key => $val){
        if(!in_array('"'.$val['goodsId'].'"',$goodsIDArray)){
            array_push($goodsIDArray , '"'.$val['goodsId'].'"');
        }
    }
    $goodsidStrr = '('.implode(',',$goodsIDArray).')';
    $goodsData = findAll('goods',"id in {$goodsidStrr}");
    $goodsKeyData = setColtoKey($goodsData,'id');
    foreach($allGoods as $val){
        $values[] = "(NULL,'{$orderSN}','{$val["goodsId"]}','{$val["goodsSkuId"]}','{$val['goodsName']}','{$val['goodsSkuName']}','{$goodsKeyData[$val['goodsId']]['ico']}','{$val["buyNumber"]}','{$val['buyPrice']}','$time')";
        $allGoodsSumPrice+=$val['buyNumber'] * $val['buyPrice'];
    }
    //插入order_goods表中
    $insertOrderGoodsSql = "INSERT INTO `order_goods`(
                                              `id`,
                                              `order_sn`,
                                              `goodsId`,
                                              `goodsSkuId`,
                                              `goodsName`,
                                              `goodsSkuName`,
                                              `goods_icon`,
                                              `buyNumber`,
                                              `buyPrice`,
                                              `addTime`) VALUES ".implode(',',$values);
//    echo $insertOrderGoodsSql;die;
    $res = mysql_query($insertOrderGoodsSql);
    if(!$res){
        rollback();
        returnJsonText('提交订单失败，请稍后重试');
    }

    //计算价格，优惠券，最后支付金额；
    //总价： $allGoodsSumPrice;

    //查找地址；
    if(empty($newAddressId)){
        $defaultAddressId = $_SESSION['addressId'];
        $matchAddress = findOne('address',"id = $defaultAddressId");
    }else{
        $matchAddress = findOne('address'," id = $newAddressId");
    }
    if(empty($matchAddress)){
        rollback();
        returnJsonText('选择收货地址/没有地址请添加');
    }

    //查找详细信息
    $cityData = findOne('region',"id={$matchAddress['regionId']}");
    $receiveGoodsAddress = $cityData['province'].$cityData['city'].$cityData['area'].$matchAddress['addressMx'];
    //插入订单信息;
    //删除购物车的信息；
    //给被推荐人返记录，确定收获30后，由定时任务加钱;
    //插入订单信息
    $insertOrder = "INSERT INTO `order`(
                                          `id`,
                                          `o_type`,   
                                          `order_sn`,
                                          `out_order_sn`,
                                          `pay_type`,
                                          `pay_type_online`,
                                          `pay_khid`,
                                          `target_khid`,
                                          `money`,
                                          `workFlow`,
                                          `extra_info`,
                                          `address_name`,
                                          `address_tel`,
                                          `region_id`,
                                          `address_detail`,
                                          `deli_type`,
                                          `express_name`,
                                          `express_number`,
                                          `tax_type`,
                                          `tax_title`,
                                          `tax_num`,
                                          `ptime`,
                                          `updateTime`,
                                          `ctime`
                                          ) VALUES (
                                            NULL,
                                            '1',
                                            '{$orderSN}',
                                            '',
                                            '{$payType}',
                                            0,
                                            '{$khid}',
                                            '{$khid}',   
                                            '{$allGoodsSumPrice}',
                                            0,
                                            '{$extra_info}',
                                            '{$matchAddress['contactName']}',
                                            '{$matchAddress['contactTel']}',
                                            '{$matchAddress['regionId']}',
                                            '{$receiveGoodsAddress}',
                                             '',
                                             '',
                                             '',
                                             '{$invoiceType}',
                                            '{$invoiceTitle}',
                                            '{$invoiceNumber}',
                                            '1970-01-01 01:01:01',
                                            '{$time}',
                                            '{$time}')";
    $insertRes = mysql_query($insertOrder);
//    var_dump($insertRes);
    if(!$insertRes){
        rollback();
        returnJsonText('生成订单失败');
    }
    //已经插入订单;
//    if($payType == '在线支付'){
//
//    }else{
//
//    }
    $data['warn'] =2;
    $data['data']['total'] 		= $allGoodsSumPrice;					#总价
//    $data['coupon'] 	= 0;					#优惠劵
//    $data['couponId'] 	= $coupon_id;				#优惠劵id
    $data['data']['byId'] 		= $orderSN;	#buyCar Id
    $data['data']['type'] 		= 'all';					#支付类型（立即购买、购物车）【one、all】
    commit();
    returnJson($data);
    





    
    
    
    
    
    die;

    /*
    $total 			= $post['total'];		#总价
	$taxFree 		= $post['taxFree'];		#税费
	$shippingFree 	= $post['shippingFree'];#运费
	$type	 		= $post['type'];		#类型 one all
	$bid 			= $post['bid'];			#立即购买id
	$coupon 		= $post['coupon'];		#优惠价格
	$couponId 		= $post['couponId'];	#优惠劵id
	$userPayType 	= $post['userPayType'];	#支付方式
	


	if( empty($total) || preg_match($CheckPrice,$total) == 0 ){
		$json['warn'] = '请填写总价' ;
	}else if( !in_array($type,['one','all']) ){
		$json['warn'] = '未知错误' ;
	}else if( empty($_SESSION['addressId']) ){
		$json['warn'] = '请填写地址信息' ;
	}else if( empty( $_SESSION['buyCar']['payType'] ) ){
		$json['warn'] = '请选择配送方式' ;
	} else if( empty( $_SESSION['buyCar']['companyName'] ) ){
		$json['warn'] = '请填写发票信息' ;
	} else{
		$buyCarFree		= mBuyCar::getBuyCarFree($_SESSION['khid'],$type,$bid);	#获取购物车总价
		//$couponData 	= GoodsCoupon::getMaxCoupon($_SESSION['khid']);			#获取优惠劵信息
		$orderList      = mBuyCar::orderList($ehu['khid'],$type,$bid);			#购物车列表
		
		#优惠劵信息
		if( !empty($_SESSION['coupon']['couponId']) ){
			$coupon_mon = $_SESSION['coupon']['money'];
			$coupon_id 	= $_SESSION['coupon']['couponId'];
		}else{
			$couponInfo = GoodsCoupon::getCouponMoney();
			$_SESSION['coupon']['money'] 	= $coupon_mon 	= $couponInfo['0']['moeny'];
			$_SESSION['coupon']['couponId'] = $coupon_id 	= $couponInfo['0']['couponId'];
		}
		//$region = findOne('address',"id = '{$kehu['address']}'");
		
		 if( $type == 'one' ){
			$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',regionId = '{$region['regionId']}',addressMx = '{$region['addressMx']}' WHERE id = '$bid'";
			mysql_query($sql);
		}else if( $type == 'all' ){
			$info = findOne('buyCar',"khid = '{$_SESSION['khid']}' AND workFlow = '已选定' ORDER BY time DESC LIMIT 1");
			$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',regionId = '{$region['regionId']}',addressMx = '{$region['addressMx']}' WHERE id = '{$info['id']}'";
			mysql_query($sql);
		}

		if( $total != $buyCarFree['totalPrice'] ){
			$json['warn'] = '价格不一致' ;
		}else if( $coupon != $coupon_mon ){
			$json['warn'] = '优惠劵金额不一致' ;
		}else if( $couponId != $coupon_id ){
			$json['warn'] = '优惠劵ID不一致' ;
		}else if( $taxFree != $orderList['taxFree'] ){
			$json['warn'] = '税费不一致' ;
		}else if( $shippingFree != $orderList['shippingFree'] ){
			$json['warn'] = '运费不一致' ;
		}else {
			if( $userPayType == '银行支付' ){
				
				#插入pay表
				$id = date('YmdHis').mt_rand(10000,99999);
				$money = $total - $coupon_mon + $taxFree + $shippingFree;
				$orderIdGroupJson = $orderList['orderIdGroupJson'];
				
				$sql  = "INSERT INTO `pay`(`id`, `type`, `target`, `targetId`,`purchaserKhid`, `orderType`, `orderIdGroup`, `money`, `workFlow`, `updateTime`, `time`) VALUES ('$id','银行汇款','客户','{$_SESSION['khid']}','{$_SESSION['contacts']['otherKhid']}','购物车','$orderIdGroupJson','$money','未支付','$time','$time')";
				
				$bool = mysql_query($sql);
				if( $bool ){

					$jsonStr = "('".implode("','",json_decode($orderIdGroupJson,true))."')";
					$region = findOne('address',"id = '{$_SESSION['addressId']}'");
					
					#修改购物车信息
					$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',addressMx = '{$region['addressMx']}',logisticsName = '{$_SESSION['buyCar']['logistiscName']}',delivery = '{$_SESSION['buyCar']['getType']}',companyName = '{$_SESSION['buyCar']['companyName']}',taxNum = '{$_SESSION['buyCar']['taxNum']}',kehuCouponId = '{$_SESSION['coupon']['couponId']}',payType = '银行汇款',workFlow = '未支付',updateTime = '$time' WHERE id IN $jsonStr";
					$bool = mysql_query($sql);
					
					#修改优惠劵使用状态
					if( $bool ){
						$sql = "UPDATE kehuCoupon SET status = '已使用' WHERE id = '$coupon_id'";
						$bool = mysql_query($sql);
						if( $bool ){
							$json['warn'] = 2 ;
							$json['href'] = root."m/mUser/mAllOrder.php";
						}else{
							$json['warn'] = '网络繁忙';
						}
					}$jsonStr = "('".implode("','",json_decode($orderIdGroupJson,true))."')";
					$region = findOne('address',"id = '{$_SESSION['addressId']}'");
					
					#修改购物车信息
					$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',addressMx = '{$region['addressMx']}',logisticsName = '{$_SESSION['buyCar']['logistiscName']}',delivery = '{$_SESSION['buyCar']['getType']}',companyName = '{$_SESSION['buyCar']['companyName']}',taxNum = '{$_SESSION['buyCar']['taxNum']}',kehuCouponId = '{$_SESSION['coupon']['couponId']}',payType = '银行汇款',workFlow = '未支付',updateTime = '$time' WHERE id IN $jsonStr";
					$bool = mysql_query($sql);
					
					#修改优惠劵使用状态
					if( $bool ){
						$sql = "UPDATE kehuCoupon SET status = '已使用' WHERE id = '$coupon_id'";
						$bool = mysql_query($sql);
						if( $bool ){
							$json['warn'] = 2 ;
							$json['href'] = root."m/mUser/mAllOrder.php";
						}else{
							$json['warn'] = '网络繁忙';
						}
					}
				}else{
					$json['warn'] = '网络繁忙' ;
				}
				
				#销毁session中的数据
				unset( $_SESSION['contacts'] );		#购货人
				unset( $_SESSION['buyCar'] );		#购物车
				unset( $_SESSION['coupon'] );		#优惠券
				unset( $_SESSION['addressId'] );	#地址
				
			}else if( $userPayType == '在线支付' ){
				#地址信息
				$region = findOne('address',"id = '{$_SESSION['addressId']}'");
				
				if( $type == 'one' ){
					$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',addressMx = '{$region['addressMx']}',regionId = '{$region['regionId']}',logisticsName = '{$_SESSION['buyCar']['logistiscName']}',delivery = '{$_SESSION['buyCar']['getType']}',companyName = '{$_SESSION['buyCar']['companyName']}',taxNum = '{$_SESSION['buyCar']['taxNum']}',kehuCouponId = '{$_SESSION['coupon']['couponId']}',updateTime = '$time' WHERE id = '$bid'";
					
				}else if( $type == 'all' ){
					
					#查找已选定购物车
					$buyCoulum = findAll('buyCar',"khid = '{$_SESSION['khid']}' AND type = '普通订单' AND workFlow = '已选定'",'*','id');
					$jsonStr = $buyCoulum['0']['column'];

					#修改地址信息
					$sql = "UPDATE buyCar SET addressName = '{$region['contactName']}',addressTel = '{$region['contactTel']}',addressMx = '{$region['addressMx']}',regionId = '{$region['regionId']}',logisticsName = '{$_SESSION['buyCar']['logistiscName']}',delivery = '{$_SESSION['buyCar']['getType']}',companyName = '{$_SESSION['buyCar']['companyName']}',taxNum = '{$_SESSION['buyCar']['taxNum']}',kehuCouponId = '{$_SESSION['coupon']['couponId']}',updateTime = '$time' WHERE id IN $jsonStr";
					
				}
				$bool  =mysql_query($sql);
				
				if( $bool ){
					$json['warn'] = 2;
				}else{
					$json['warn'] = '网络繁忙' ;	
				}
				 #修改优惠劵状态
				if( !empty($coupon_id) ){
					
					$sql = "UPDATE kehuCoupon SET status = '已使用' WHERE id = '$coupon_id' AND khid = '{$_SESSION['khid']}'";
					$bool = mysql_query($sql);
					
					if( $bool ){
						$json['warn'] = 2;
					}else{
						$json['warn'] = '网络繁忙' ;	
					}
				}
				$_SESSION['coupon']['couponMon'] = $coupon;
				$data['total'] 		= $total;					#总价
				$data['coupon'] 	= $coupon;					#优惠劵
				$data['couponId'] 	= $coupon_id;				#优惠劵id
				$data['byId'] 		= $buyCarFree['buyCarId'];	#buyCar Id
				$data['type'] 		= $type;					#支付类型（立即购买、购物车）【one、all】
				$json['data'] 		= $data;
			}
		}
	}

	*/
}
/**
 * 判断会员 支付类型
 */
else if( $get['type'] == 'checkVip' ){
	$type = $post['type'];	#类型
	if( !in_array($type,['nor','vip']) ){
		$json['warn'] = '未知错误' ;
	}else if( $kehu['普通会员'] && $type == 'nor' ){
		$json['warn'] = '您已经是普通会员了' ;
	}else if( $kehu['type'] == '高级会员' ){
		$json['warn'] = '您已经是高级会员了' ;
	}else{
		$id = 'JL'.date('YmdHis').mt_rand(1000,9999);
		if( $type == 'nor' ){
			$goodsName = '普通会员';
			/* $info 	= findOne('img',"id = 'myh84324058lV'");
			$price 	= explode('、',$info['text']);
			$money 	= $price['0']; */

			$norPrice 	= explode('、',para('normalMember'));
			$money = $norPrice['0'];
		}else if( $type == 'vip' ){
			$goodsName = '高级会员';
			/* $info 	= findOne('img',"id = 'pah84324212yj'");
			$price 	= explode('、',$info['text']);
			$money 	= $price['0']; */

			$vipPrice 	= explode('、',para('vipMember'));
			$money = $vipPrice['0'];
		}
					
		$sql = "INSERT INTO `buyCar`(`id`, `type`, `khid`,  `goodsName`, `buyPrice`,`workFlow`, `updateTime`, `time`) VALUES ('$id','购买会员','{$_SESSION['khid']}','$goodsName','$money','已选定','$time','$time')";
		$bool = mysql_query($sql);
		if( $bool ){
			$json['warn'] 		= 2;
			$json['orderId'] 	= $id;
			$json['data'] 		= $type ;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 添加常用购货人
 */
else if( $get['type'] == 'addContact' ){
	$otherKhid 		= $post['juliId'];	#购货人khid
	$kehuName 		= $post['name'];	#购货人名字
	$tel 			= $post['tel'];		#购货人电话
	$isCommonly 	= $post['isUsed'];	#是否设置为常用购货人
	$khid 			= $post['khid'];	#khid
	$res = findOne('kehu',"khid = '{$otherKhid}'");
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '未登录' ;
	}else if( empty($otherKhid) ){
		$json['warn'] = '请输入购货人聚礼ID';
	}else if( !$res ){
		$json['warn'] = '您输入的聚礼ID号不存在' ;
	}else if( empty($kehuName) ){
		$json['warn'] = '请填写购货人姓名' ;
	}else{
		if( !empty($tel) && preg_match($CheckTel,$tel) == 0 )
		{
			$json['warn'] = '请填写正确的手机号码' ;
		}else{
			if( $isCommonly == '是' )
			{
				$id = 'JL'.date('YmdHis').mt_rand(1000,9999);
				$sql = "INSERT INTO `contacts`(`id`, `khid`, `otherKhid`, `kehuName`, `tel`, `isCommonly`, `updateTime`, `time`) VALUES ('$id','$khid','$otherKhid','$kehuName','$tel','是','$time','$time')";
				$bool = mysql_query($sql);
				if( $bool ){
					$json['warn'] = 2 ;
					$_SESSION['contacts']['otherKhid'] 	= $otherKhid;
					$_SESSION['contacts']['kehuName'] 	= $kehuName;
					$_SESSION['contacts']['tel'] 		= $tel;
				}else{
					$json['warn'] = '网络繁忙' ;
				}
			}else{
				$_SESSION['contacts']['otherKhid'] 	= $otherKhid;
				$_SESSION['contacts']['kehuName'] 	= $kehuName;
				$_SESSION['contacts']['tel'] 		= $tel;	
				$json['warn'] = 2 ;
			}
		}
	}
}
/**
 * 选择常用购货人
 */
else if( $get['type'] == 'selectContact' ){
	$id = $post['id'];
	if( empty($id) ){
		$json['warn'] = '请选择常用购货人' ;
	}else {
		$res = findOne('contacts',"id = '$id'");
		if( $res ){
			$_SESSION['contacts']['otherKhid'] 	= $res['otherKhid'];
			$_SESSION['contacts']['kehuName'] 	= $res['kehuName'];
			$_SESSION['contacts']['tel'] 		= $res['tel'];
			$json['warn'] = 2;
		}else{
			$json['warn'] = '未知错误' ;
		}
	}
}
/**
 * 使用优惠劵
 */
else if( $get['type'] == 'useCoupon' ){
	$id = $post['id'];	#kehuCouponId
	$res = findOne('kehuCoupon',"id = '$id' AND khid = '{$_SESSION['khid']}'");
	if( $res ){
		$info = findOne('coupon',"id = '{$res['couponId']}'");
		$_SESSION['coupon']['couponId'] = $id;
		$_SESSION['coupon']['money'] = $info['moeny'];
		$json['warn'] = 2 ;
	}
}
/**
 * 确定收货
 */
else if( $get['type'] == 'toSure' ){
	$pid 	= $post['pid'];
	$bid 	= $post['bid'];
	$khid 	= $post['khid'];
	$res = findOne('buyCar',"id = '$bid' AND khid = '$khid'");
	if( $_SESSION['khid'] != $khid ){
		$json['warn'] = '未登录' ;
	}else if( !$res ){
		$json['warn'] = '未知错误' ;
	}else{
		$json = insertIncome($bid,$pid);
		$sql = "UPDATE buyCar SET workFlow = '已收货' WHERE id = '$bid' AND khid = '$khid'";
		$bool = mysql_query($sql);
		if( $bool ){
			$json['warn'] = 2 ;
		}else{
			$json['warn'] = '网络繁忙' ;
		}
		
	}
}
/**
 * 再次订购
 */
else if( $get['type'] == 'buyAgain' ){
	$pid = $post['pid'];
	$res = findOne('pay',"id = '$pid'");
	if( $res ){
		#buyCarId 集合
		$bidJson = "('".implode( "','" , json_decode( $res['orderIdGroup'] , true ) )."')" ;
		
		$sql = "SELECT b.*,k.price,k.retailPrice,k.thePatch,k.endPatch FROM buyCar b,goodsSku k WHERE b.id IN $bidJson AND b.goodsSkuId = k.id";
		$info[] = $sql;
		$result = myQuery($sql);
		$info[] = $result;
		if( $result['0']['sqlRow'] > 0 )
		{
			foreach ($result as $key => $val)
			{
				$id = 'JL'.date("YmdHis").mt_rand(1000,9999);
				if( $val['buyNumber'] == $val['thePatch'] && $val['buyNumber'] == $val['endPatch'] )
				{
					$sql = "INSERT INTO `buyCar`(`id`, `type`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$val['type']}','{$_SESSION['khid']}','{$val['goodsId']}','{$val['goodsSkuId']}','{$val['goodsName']}','{$val['goodsSkuName']}','{$val['buyNumber']}','{$val['price']}','已选定','$time','$time')";
					
					$bool = mysql_query($sql);
				}else if( $val['buyNumber'] >= $val['thePatch'] && $val['buyNumber'] <= $val['endPatch']){
					$sql = "INSERT INTO `buyCar`(`id`, `type`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$val['type']}','{$_SESSION['khid']}','{$val['goodsId']}','{$val['goodsSkuId']}','{$val['goodsName']}','{$val['goodsSkuName']}','{$val['buyNumber']}','{$val['retailPrice']}','已选定','$time','$time')";
					$bool = mysql_query($sql);
				}else{
					$sql = "INSERT INTO `buyCar`(`id`, `type`, `khid`, `goodsId`, `goodsSkuId`, `goodsName`, `goodsSkuName`,`buyNumber`, `buyPrice`, `workFlow`, `updateTime`, `time`) VALUES ('$id','{$val['type']}','{$_SESSION['khid']}','{$val['goodsId']}','{$val['goodsSkuId']}','{$val['goodsName']}','{$val['goodsSkuName']}','{$val['buyNumber']}','{$val['retailPrice']}','已选定','$time','$time')";

					$bool = mysql_query($sql);
				}
				$info[] = $sql;
			}
			$json['info'] = $info ;
			if( $bool ){
				$json['warn'] = 2;
				$json['href'] = root."m/mEditOrder.php?type=all";
			}else{
				$json['warn'] = '网络繁忙' ;
			}
		}
	}else{
		$json['warn'] = '未知订单' ;
	}
}
else if( $get['type'] == 'toGoPay' ){
	$khid = $post['khid'];
	$orderSN = $post['pid'];
	$res = findOne('`order`',"order_sn = '$orderSN' and pay_khid='{$khid}'");
	if( $khid != $_SESSION['khid'] ){
		$json['warn'] = '订单人与支付人不一致，不许支付' ;
	}else if( !$res ){
		$json['warn'] = '未找到该订单' ;
	}else {
		$json['warn'] = 2 ;
		$json['money'] = $res['money'] ;
		returnJson($json);
	}
}
/**
 * 会员领取优惠劵
 */
else if( $get['type'] == 'getMemberCoupon' ){
	$khid = $post['khid'];
	$id = $post['id'];
	if( $_SESSION['khid'] != $khid ){
		$json['warn'] = '未登录' ;
	}else if( empty($id) ){
		$json['warn'] = '优惠劵id为空' ;
	}else {
		$res = findOne('coupon',"goodsId = '会员优惠劵' AND id = '$id' ");
		$kehuCoupon = findOne('kehuCoupon',"couponId = '$id'");
		if( $kehuCoupon ){
			$json['warn'] = '您已经领取过该优惠劵了' ;
		}else{
			if( $res ){
				if( strtotime( $res['endTime'] ) >= time() )
				{
					$sql = "INSERT INTO `kehuCoupon`(`khid`, `couponId`, `status`, `time`) VALUES ('$khid','$id','未使用','$time')";
					$bool = mysql_query($sql);
					if( $bool ){
						$json['warn'] = 2 ;
					}else{
						$json['warn'] = '网络繁忙' ;
					}
				}else{
					$json['warn'] = '该优惠劵已过期' ;
				}
			}else{
				$json['warn'] = '为找到该优惠劵' ;
			}	
		}
	}
}
/**
 * 需求列表页
 */
else if( $get['type'] == 'needMxShow' ){
	$page = $get['page'];
	$size = $get['size'];
	$khid = $get['khid'];
	if( !empty($khid) && $khid != $_SESSION['khid'] ){
		$json['warn'] = '未登录' ;
	}else{
		$json = needMxBuild($page,$size,$kid);
	}
}
/**
 * 测试
 */
else if( $get['type'] == 'test' ){
	echo '呵呵......';
	echo '<pre>';
		print_r($_SESSION);
	echo '</pre>';
	echo '<pre>';
		print_r($_COOKIE);
	echo '</pre>';
}
/**
 * 分润测试
 */
else if( $get['type'] == 'subsun' ){
	$price 			= $post['price'];			#定价
	$cost 			= $post['cost'];			#成本
	$free 			= $post['free'];			#费用
	$shippingFree 	= $post['shippingFree'];	#运费
	
	$json = skuSubRun::getFree($price,$cost,$free,$shippingFree);
	
}
/**
 * 创建文件夹
 */
else if( $get['tyep'] == 'mkdir' ){
	$arr = scandir(ServerRoot.'img');
	$json['warn'] = $arr ;
	mkdir(ServerRoot.'img/talkImg');
	mkdir(ServerRoot.'img/customImg');
	mkdir(ServerRoot.'img/demandImg');
}
/**
 * session 赋值操作
 */
else if( $get['type'] == '77' ){
	$_SESSION['khid'] = 6219991140;
	$_SESSION['adid'] = 'fg35h4';
	$json['warn'] = 2 ;
	
}
/**
 * 
 */
else if( $get['type'] == 'kuaidi' ){
	$ss = kdQuery('474660136448');
	echo '<pre>';
		print_r($ss);
	echo '</pre>';
}
else if( $get['type'] == 'unset' ){
	unset($_SESSION['buyCar']);
	unset($_SESSION['coupon']);
	unset($_SESSION['addressId']);
	unset($_SESSION['contacts']);
}
else if($get['type'] == 'applyBackGoods'){
     $khid = $_SESSION['khid'];
    $res = UserOrder::userApplyBackGoods($post['order_sn'],$khid);
}
/***************返回信息***************************************************/
echo json_encode($json,JSON_UNESCAPED_UNICODE );
?>
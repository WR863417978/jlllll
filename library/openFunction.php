<?php 
/*
*PC端和移动端通用的前端函数库
*/
include dirname(dirname(__FILE__))."/control/ku/configure.php";
//pay('12151513956516','111111111111',10201);
/**
 * buyCar
 * 	加入购物车流程
 * 		未选定 => 已选定 => 
 * 						=> 
 * 	立即购买流程
 * 		未提交 => 
 * 	未选定	加入购物车
 *	未提交	立即购买没有提交订单
 *	已选定	购物车中选定提交
 * @return void
 */

/*
*函数名称：创建支付订单
*微信支付根目录地址：pay/wxpay/pay/wxpay/wxpay.php
*微信扫码支付根目录地址：pay/wxpay/pay/wxpay/wxScan.php，返回warn(提示信息),url（微信扫码支付链接）
*获取二维码地址：pay/wxpay/pay/wxpay/wxScanPng.php?url=您的微信扫码支付链接
*支付宝PC端支付根目录地址：pay/wxpay/pay/alipay/alipayapi.php
*支付宝移动端支付根目录地址：pay/wxpay/pay/alipaywap/alipayapi.php
*函数引入参数：$type(支付类型),$kehu(当前登录客户的基本信息)
*函数引出的参数：$result['warn']（提示信息），$result['money']（本次需要支付的金额/元）必填，$result['orderId']（支付后返回的订单号）必填
*/
function payForm($type,$kehu){
	//赋值
	global $post;

	$time = date("Y-m-d H:i:s");
	$orderType = FormSub($_POST['orderType']);//订单类型，一般从支付表单提交过来
	//$orderIdGroup = json_encode($_POST['orderId']);//需要支付的订单ID号集合
	$orderId = FormSub($_POST['orderId']);//订单类型，一般从支付表单提交过来
	$orderIdGroup = json_encode( explode(',',$post['orderId']) );//需要支付的订单ID号集合
	if($orderType == "测试"){
		$money = 0.01;
	}else if( $orderType == '购物车' ){
		$money = FormSub($_POST['money']);
		if( in_array($post['type'],['one','all']) )
		{
			$buyCarFree		= mBuyCar::getBuyCarFree($_SESSION['khid'],$_POST['type'],$_POST['bid']);	#获取购物车总价
			//$couponData 	= GoodsCoupon::getMaxCoupon($_SESSION['khid']);		#获取优惠劵信息
			$orderList      = mBuyCar::orderList($ehu['khid'],$_POST['type'],$_POST['bid']);		#购物车列表
			if( $post['money'] == $buyCarFree['totalPrice'] && $post['coupon'] == $_SESSION['coupon']['couponMon'] ){
				
				$money = $buyCarFree['totalPrice'] - $post['coupon'] + $orderList['taxFree'] + $orderList['shippingFree'];
				#日志数据
			}
			$logData['Ptype'] 		= $_POST['type'];
			$logData['Pbid'] 		= $_POST['bid'];
			$logData['Pmoney'] 		= $_POST['money'];
			$logData['Pcoupon'] 	= $_POST['coupon'];

			$logData['money'] 			= $buyCarFree['totalPrice'];
			$logData['coupon'] 			= $_SESSION['coupon']['money'];
			$logData['taxFree'] 		= $orderList['taxFree'];
			$logData['shippingFree'] 	= $orderList['shippingFree'];
			$logData['payMoney'] 		= $money;
			test("-- {$_SESSION['khid']} --".serialize($logData));
		}else if( $post['type'] == 'goPay' ){
			$pid = $post['pid'];
			$res = findOne('pay',"id = '$pid'");
			if( $res )
			{
				$money          = $res['money'];
				$orderIdGroup   = $res['orderIdGroup'];
			}else{
				$result['warn'] = "订单不存在";
			}
		}
	}else if( $orderType == '购买会员' ){
		$key = $_POST['key'];
		switch ($key) {
			case 'nor':
				$price 	= explode('、',para('normalMember'));
				$money 	= $price['0'];
				break;
			case 'vip':
				$price 	= explode('、',para('vipMember'));
				$money 	= $price['0'];
				break;
		}
		test(serialize($price));
		test($money);
	}
	$result['money'] = round($money,2);//本次需要支付的总金额，一般通过订单类型和订单号计算而得，不建议直接从前端post过来
	//判断
	if(empty($orderType)){
		$result['warn'] = "订单类型为空";
	}elseif(empty($orderIdGroup)){
		$result['warn'] = "订单ID号为空";
	}elseif(empty($result['money'])){
		$result['warn'] = "支付金额为空";
	}elseif(empty($kehu)){
		$result['warn'] = "您未登录";
	}elseif($result['money'] == 0){
		$result['warn'] = "支付金额为零";
	}else{
		//建立预支付记录
		$result['orderId'] = rand(10,99).time().rand(10,99);
		if( empty($_SESSION['contacts']['otherKhid']) ){
			$purchaserKhid = $_SESSION['khid'];
		}else{
			$purchaserKhid = $_SESSION['contacts']['otherKhid'];
		}
		$bool = mysql_query(" insert into pay (id,type,target,targetId,purchaserKhid,orderType,orderIdGroup,money,workFlow,updateTime,time) 
		values ('$result[orderId]','$type','客户','$kehu[khid]','{$purchaserKhid}','$orderType','$orderIdGroup','$result[money]','未支付','$time','$time')");
		if(!$bool){
			$result['warn'] = "建立预支付记录失败";
		}else{
			#在线支付优惠劵状态修改
			if( in_array($post['type'],['one','all']) ){
				if( !empty($_SESSION['coupon']['couponId']) )
				{	
					$sql = "UPDATE kehuCoupon SET status = '已使用' WHERE id = '{$_SESSION['coupon']['couponId']}' AND khid = '{$_SESSION['khid']}'";
					$bool = mysql_query($sql);
					
					if( $bool ){
						$json['warn'] = 2;
					}
				}
				$ss = "UPDATE kehuCoupon SET status = '已使用' WHERE id = '{$_SESSION['coupon']['couponId']}' AND khid = '{$_SESSION['khid']}'";
				$jsonStr = "('".implode("','",json_decode($orderIdGroup,true))."')";
				$sql = "UPDATE `buyCar` SET workFlow = '未支付' WHERE id IN $jsonStr";
				mysql_query($sql);
			}else if( $post['type'] == 'goPay' ){
                #删除pay表的数据
                $sql = "DELETE FROM pay WHERE id = '$pid'";
                mysql_query($sql);
			}
			#销毁session中的数据
			unset( $_SESSION['contacts'] );		#购货人
			unset( $_SESSION['buyCar'] );		#购物车
			unset( $_SESSION['coupon'] );		#优惠券
			unset( $_SESSION['addressId'] );	#地址
		}
	}
	//返回
	return $result;
}
/********支付回调处理函数********************************************************/
//$PayId为返回的订单号，$money返回的是本次支付的金额（单位为元）
function pay($orderId,$PayId,$money){
	//赋值
	$time = date("Y-m-d H:i:s");
	$pay = query("pay"," id = '$orderId' ");//订单支付记录表
	//判断
	if(empty($orderId)){
		test("充值订单号为空，金额{$money}，交易号：{$PayId}");
	}elseif($pay['id'] != $orderId){
		test("未找到与订单号匹配的支付记录，订单号：{$orderId}，交易号：{$PayId}");
	}elseif($pay['money'] != $money){
		test("返回的交易总金额（{$money}）与订单支付记录表里面的金额（{$pay['money']}）不匹配，订单号：{$orderId}，交易号：{$PayId}");
	}elseif($pay['workFlow'] != "未支付"){
		test("异步返回时，订单不处于“未支付”状态，订单号：{$orderId}，交易号：{$PayId}");
	}else{
		$bool = mysql_query("UPDATE pay SET
		payId = '$PayId',
		workFlow = '已支付',
		updateTime = '$time' WHERE id = '$orderId' ");
		if($bool){
			if($pay['orderType'] == "测试"){
				processOrder($pay['orderIdGroup'],$pay['orderType']);
			}else if( $pay['orderType'] == '购物车' ){
				//变更购物车状态
				if ($bool) {
					processOrder($pay['orderIdGroup'],$pay['orderType']); //更新状态
					unset( $_SESSION['contacts'] );		#购货人
				} else {
					test("订单表更新失败，订单号：{$orderId}，交易号：{$PayId}");
				}
			}else if( $pay['orderType'] == '购买会员' ){
				if ($bool) {
					processOrder($pay['orderIdGroup'],$pay['orderType'],$money); //更新状态
				} else {
					test("订单表更新失败，订单号：{$orderId}，交易号：{$PayId}");
				}
			}else{
				test("未知执行指令，订单号：{$orderId}，交易号：{$PayId}");	
			}
		}else{
			test("预支付记录更新失败，订单号：{$orderId}，交易号：{$PayId}");
		}
	}
}
/**
 * 订单处理函数
 * @param json $orderIdGroup
 * @param str $orderType
 * @return mixed
 */
function processOrder($orderIdGroup,$orderType,$money = NULL)
{
	global $time;
	global $kehu;	
	$orderIdGroup = json_decode($orderIdGroup,true);
	switch ($orderType) {
		case '购物车':
			#查找上级
			if( empty($kehu['shareId']) )
			{
				$res = findOne('kehu',"khid = '{$kehu['shareId']}'");
			}
			foreach ($orderIdGroup as $id) {
				#修改订单状态
				$bool = mysql_query("UPDATE buyCar SET workFlow='已付款',updateTime='$time' WHERE id = '$id'");
				#添加提成信息
				if( $bool )
				{
						
				}
			}
			break;
		case '购买会员':
			/* $norInfo 	= findOne('img',"id = 'myh84324058lV'");
			$norPrice 	= explode('、',$norInfo['text']);
			$norMoney 	= $norPrice['0'];
			
			$vipInfo 	= findOne('img',"id = 'pah84324212yj'");
			$vipPrice 	= explode('、',$vipInfo['text']);
			$vipMoney 	= $vipPrice['0']; */
			
			$norPrice 	= explode('、',para('normalMember'));
			$norMoney 	= $norPrice['0'];

			$vipPrice 	= explode('、',para('vipMember'));
			$vipMoney 	= $vipPrice['0'];
			foreach ($orderIdGroup as $key => $val)
			{
				#修改pay表
				$sql = "UPDATE buyCar SET workFlow = '已付款',updateTime = '$time' WHERE id = '$val' ";
				$res = findOne('buyCar',"id = '$val'");
				mysql_query($sql);
				if( $money == $norMoney ){
					$memberType = '普通会员';
				}else if( $money == $vipMoney ){
					$memberType = '高级会员';
				}
				#修改会员类型
				$sql = "UPDATE kehu SET type = '$memberType',updateTime = '$time' WHERE khid = '{$res['khid']}'";
				$bool = mysql_query($sql);
				if(!$bool){
					test('用户类型 更新失败'.'payId'.$val);
				}
				#添加收入
				$nowkehu = findOne('kehu',"khid = '{$res['khid']}'");
				if( !empty($nowkehu) ){
					$free = para('shareFree');
					$sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderId`, `orderTime`, `sales`, `free`, `time`) VALUES ('推荐','{$res['shareId']}','{$res['khid']}','{$res['name']}','$val','$time','$free','$free','$time')";
					$bool = mysql_query($sql);
					if( !$bool ){
						test('分享提润 插入失败'.'payId'.$val);
					}
				}
			}
			break;
		default:
			break;
	}
}


?>
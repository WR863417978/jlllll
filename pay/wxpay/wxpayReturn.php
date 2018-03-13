<?php
include "../../library/openFunction.php";
//存储微信的回调
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
//将微信回传的xml转换为array
$notify = xmlToArray($xml);
//将notify中的sign变量剔除，并储存到其他变量中
$sign = $notify['sign'];
unset($notify['sign']);
//计算签名
$wxsign = wxsign($notify);
if($sign == $wxsign){//如果签名验证成功
	$return['return_code'] = "SUCCESS";
	if($notify['return_code'] == "FAIL"){
		test("微信支付：return_code为fall,订单号：".$notify['out_trade_no']);
	}elseif($notify['result_code'] == "FAIL"){
		test("微信支付：订单号：".$notify['out_trade_no']."错误信息：".$notify['err_code'].$notify['err_code_des']);
	}elseif($notify['result_code'] == "SUCCESS"){
		$orderId = $notify['out_trade_no'];//微信支付返回的订单号，与pay表的id号保持一致
		$PayId = $notify['transaction_id'];//微信支付交易号
		$money = round($notify['total_fee']/100,2);//客户刚刚用微信已经支付的金额
		pay($orderId,$PayId,$money);
	}else{
		test("微信支付：支付失败，订单号：".$notify['transaction_id'].$notify['err_code'].$notify['err_code_des']."发生未知错误");
	}
}else{
	$return['return_code'] = "FAIL";
	$return['return_msg'] = "签名失败";
	test("微信支付：签名失败：{$xml}");
}
$returnXml = arrayToXml($return);
echo $returnXml;
?>
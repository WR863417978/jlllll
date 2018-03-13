<?php
/*
*微信扫码支付
*开发文档：https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_3
*步骤：在公众号设置处获取AppID和AppSecret，在微信商家后台api安全设置api秘钥（key）
*/
include "../../library/openFunction.php";
$result = payForm("微信扫码支付",$kehu);//建立支付记录
if(!empty($result['warn'])){
	$json['warn'] = $result['warn'];
}else{
	//赋值
	$wx['appid'] = para("wxAppid");//公众账号应用ID
	$wx['mch_id'] = para("wxPayId");//商户号
	$wx['nonce_str'] = suiji();//随机字符串
	$wx['body'] = para("wxPayBody");//商品描述
	$wx['out_trade_no'] = $result['orderId'];
	$wx['total_fee'] = $result['money']*100;//总金额
	$wx['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];//终端IP
	$wx['notify_url'] = root."pay/wxpay/wxpayReturn.php";//通知地址
	$wx['trade_type'] = "NATIVE";//交易类型
	$wx['sign'] = wxsign($wx);
	//生成xml
	$xml = arrayToXml($wx);
	//获取微信扫码支付url
	$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
	$pay = xmlToArray(curl($url,$xml));
	$json['url'] = $pay['code_url'];
	$json['warn'] = 2;
}
/*********返回json数据**************************************************/
echo json_encode($json);
?>
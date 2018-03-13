<?php
//include "../../library/openFunction.php";
include "../../library/mFunction.php";
/*
*微信支付步骤：
*1、进入微信服务号后台，微信支付-开发配置-支付授权目录（精确到文件夹）
*2、进入微信服务号后台，微信支付-商户信息-获取到商户号，并找客户要到六位的微信支付商户平台登录密码，登录此平台，点击左侧的API安全，下载证书，设置api秘钥
*3、apiclient_cert.pem和apiclient_key.pem必须都要有
*/
$ThisUrl = urlencode(para("http_address")."/pay/wxpay/wxpay.php");
$params = array(
    'appid' => para("wxAppid"),
    'mch_id' => para("wxPayId"),
    'device_info' => 'WEB',
    'nonce_str' => rand(10000,99999).suiji()."zz",
    'body' => para("wxPayBody"),
);
$params["openid"] = $kehu['wxOpenid'];
$params['out_trade_no'] = $_POST['orderId'];
$params['total_fee'] = $_POST['money']*100;
$params['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
$params['time_start'] = date('YmdHis');
$params['time_expire'] = date('YmdHis', time() + 1800);
$params['notify_url'] = para("http_address")."/pay/wxpay/wxpayReturn.php";
$params['trade_type'] = 'JSAPI';
ksort($params);
$str = urldecode(http_build_query($params));
$str .= '&key=' . para('wxPayKey') ;
$params['sign'] = strtoupper(md5($str));
$paramsXml = arrayToXmlMy($params);
//以post方式提交xml到对应的接口url，并获得prepay_id
$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
$result = curl($url,$paramsXml);
$prepay = xmlToArray($result);
//使用jsapi调起支付
$wxBridge['appId'] = $params['appid'];
$wxBridge['timeStamp'] = (string)time();//时间戳，数据类型必须为字符串，否则苹果手机报错（调用支付JSAPI缺少参数：timeStamp）
$wxBridge['nonceStr'] = suiji().rand(10000,99999)."aa";
$wxBridge['package'] = "prepay_id=".$prepay['prepay_id'];
$wxBridge['signType'] = "MD5";
$wxBridge['paySign'] = wxsign($wxBridge);
$wxBridgeJson = json_encode($wxBridge);//将所有参数进行json编码
echo head("m");

?>
<?php echo mWarn(); ?>
<script>
$(document).ready(function(){
	callpay();//执行函数
});
//调用微信JS api 支付
function jsApiCall(){
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo $wxBridgeJson; ?>,
		function(res){
        if(res.err_msg == "get_brand_wcpay_request:ok"){
                alert('支付成功');
			 }else{
                alert('支付未成功');
			 }
            setTimeout(function(){
//                location.href = "<?php //echo para("http_address");?>//";
                alert('即将跳到订单详情页');
            },2000);
		}
	)
}
//监听WeixinJSBridgeReady
function callpay(){
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{
		jsApiCall();
	}
}


</script>
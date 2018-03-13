<?php 
$key = '4ULIXbD5JIa5LySelFHF8tqnax8XkPCR';
$params = array(
	"appid" => 'wxce1d8a26bcf7e97d', //微信appid  公众号id
	"payid" => '1440547302',  //微信商户ID
	"nonce_str" => creatStr(13),//随机字符串
	"total_fee" => 100,
	"refund_fee" => 100,
	"out_trade_no" => ,
	"out_refund_no" =>,
);
ksort($params);  //参数名ASCII码从小到大排序（字典序）； 
$str = urldecode(http_build_query($params));   
$str .= '&key=' .$key;
$params['sign'] = strtoupper(md5($str));
$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
$xmlparams = arraytoxml($params);

$ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //证书检查
  if ($useCert == true) {
    // 设置证书
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . '/WxPay/cert/apiclient_cert.pem');
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . '/WxPay/cert/apiclient_key.pem');
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/WxPay/cert/rootca.pem');
  }
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlparams);
  $xml = curl_exec($ch);
  // 返回结果0的时候能只能表明程序是正常返回不一定说明退款成功而已
  if ($xml) {
    curl_close($ch);
    // 把xml转化成数组
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//    var_dump($xmlstring);
    $result['errNum'] = 0;
    $result['info'] = object_to_array($xmlstring);
//    var_dump($result);
    return $result;
  } else {
    $error = curl_errno($ch);
    curl_close($ch);
    // 错误的时候返回错误码。
    $result['errNum'] = $error;
    return $result;
  }
}

//将数组换为XML
function arrayToXml($arr) {
  $xml = "<root>";
  foreach ($arr as $key => $val) {
    if (is_array($val)) {
      $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
    } else {
      $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
    }
  }
  $xml .= "</root>";
  return $xml;
}
//生成随机字符串
function creatStr($length)
{
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz   
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
    for($i=0;$i<$length;$i++)   
    {   
        $key .= $pattern{mt_rand(0,35)};    //生成php随机数   
    }   
    return $key;
}
//obj换为数组
function object_to_array($obj) {
  $obj = (array) $obj;
  foreach ($obj as $k => $v) {
    if (gettype($v) == 'resource') {
      return;
    }
    if (gettype($v) == 'object' || gettype($v) == 'array') {
      $obj[$k] = (array) object_to_array($v);
    }
  }
  return $obj;
}
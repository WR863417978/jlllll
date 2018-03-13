<?php 

class Conf{

	private static $wxkey = '4ULIXbD5JIa5LySelFHF8tqnax8XkPCR';  //微信key
	private static $wxappid = 'wxce1d8a26bcf7e97d';		//微信appid
	private static $wxpayid = '1440547302';  //微信商户ID

	public static function wxRefund($total_fee,$refund_fee,$out_trade_no)
	{
		$params = array(
			"appid" => self::$wxappid, //微信appid  公众号id
			"payid" => self::$wxpayid,  //微信商户ID
			"nonce_str" => self::creatStr(13),//随机字符串
			"total_fee" => $total_fee,
			"refund_fee" => $refund_fee,
			"out_trade_no" => $out_trade_no,
			"out_refund_no" => $out_trade_no,
		);
		ksort($params);  //参数名ASCII码从小到大排序（字典序）；
		$str = urldecode(http_build_query($params));   
		$str .= '&key=' .self::$key;
		$params['sign'] = strtoupper(md5($str));
		$xmlparams = $this->arrayToXml($params);   //数组转换为xML
		$xml = self::curlSend($xmlparams);		//使用证书调用接口  返回xml
		$data = $this->xmlToarray($xml);
		print_r($data);die;
	}
	//调用接口
	public static function curlSend(xmlparams)
	{
		$url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);//证书检查
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLCERT,APP_PATH . '/app/configs'.DIRECTORY_SEPARATOR.'Cert'.DIRECTORY_SEPARATOR.'apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLKEY,APP_PATH . '/app/configs'.DIRECTORY_SEPARATOR.'Cert'.DIRECTORY_SEPARATOR. 'apiclient_key.pem');
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_CAINFO,APP_PATH . '/app/configs'.DIRECTORY_SEPARATOR.'Cert'.DIRECTORY_SEPARATOR. 'rootca.pem');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlparams);
        $data = curl_exec($ch);
        if ($data) { //返回来的是xml格式需要转换成数组再提取值，用来做更新
            return $data;
        } else {
            return false;
        }
	}
	public static function creatStr($length)
	{
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyz   
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
	    for($i=0;$i<$length;$i++)   
	    {   
	        $key .= $pattern{mt_rand(0,35)};    //生成php随机数   
	    }   
	    return $key;
	}
	/**
	 * [arrayToXml 数组转换为XML]
	 * @wangrui
	 * @param     data                   $data [数组]
	 * @return    xml                       
	 */
	public function arrayToXml($data)
	{
		$xml = "<root>";
		foreach ($arr as $key => $val) {
		  if (is_array($val)) {
		    $xml .= "<" . $key . ">" . $this->arrayToXml($val) . "</" . $key . ">";
		  } else {
		    $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
		  }
		}
		$xml .= "</root>";
		return $xml;
	}
	public function xmlToarray($xml)
	{
		libxml_disable_entity_loader(true);
	    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	    $result['errNum'] = 0;
	    $result['info'] = $this ->object_to_array($xmlstring);
	    return $result;
	}
	public function object_to_array($obj) {
	  	$obj = (array) $obj;
	  	foreach ($obj as $k => $v) {
	  	  if (gettype($v) == 'resource') {
	  	    return;
	  	  }
	  	  if (gettype($v) == 'object' || gettype($v) == 'array') {
	  	    $obj[$k] = (array) $this->object_to_array($v);
	  	  }
	  	}
	  	return $obj;
	}
}
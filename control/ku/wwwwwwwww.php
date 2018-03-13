<?php 
// +----------------------------------------------------------------------
// | Description: 微信退款类  
// +----------------------------------------------------------------------
// | Author: wangrui 
// +----------------------------------------------------------------------
class wxRefund{
	private static $wxkey = '4ULIXbD5JIa5LySelFHF8tqnax8XkPCR';  //微信key
	private static $wxappid = 'wxce1d8a26bcf7e97d';		//微信appid
	private static $wxpayid = '1440547302';  //微信商户ID
	/**
	 * [index ]
	 * @wangrui
	 * @param     total_fee,out_trade_no                   
	 * @return    data                       
	 */
	public function index()
	{
		$total_fee = '1.50';
		$out_trade_no = '15151693047840';
		$params = array(
			"appid" => self::$wxappid, //微信appid  公众号id
			"mch_id" => self::$wxpayid,  //微信商户ID
			"nonce_str" => self::creatStr(13),//随机字符串
			"total_fee" => $total_fee*100,
			"refund_fee" => $total_fee*100,
			"out_trade_no" => $out_trade_no,
			"out_refund_no" => $out_trade_no,
		);
		ksort($params);  //参数名ASCII码从小到大排序（字典序）；
		$str = urldecode(http_build_query($params));   
		$str .= '&key=' .self::$wxkey;
		$params['sign'] = strtoupper(md5($str));
		$xmlparams = $this->arrayToXml($params);   //数组转换为xML
		//var_dump($xmlparams);die;
		$xml1 = self::curlSend($xmlparams);  //使用证书调用接口  返回xml
		$data = $this->xmlToarray($xml1);
		echo "<pre>";
		print_r($data);die;
		return $data;
	}
	//调用接口
	public static function curlSend($xmlparams)
	{
		//var_dump($xmlparams);die;
		$url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);//证书检查
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');// 设置证书
		curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . '/cert/apiclient_cert.pem');
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
		curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . '/cert/apiclient_key.pem');
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cert/rootca.pem');
		curl_setopt($ch, CURLOPT_POST, 1);
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlparams);
  		$xml = curl_exec($ch);
        if ($xml) { //返回来的是xml格式需要转换成数组再提取值，用来做更新
            return $xml;
        } else {
            return false;
        }
	}
	public static function creatStr($length)
	{
		$key ='';
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyz   
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
	    for($i=0;$i<$length;$i++)   
	    {   
	        $key .= $pattern{mt_rand(0,$length)};    //生成php随机数   
	    }   
	    return $key;
	}
	/**
	 * [arrayToXml 数组转换为XML]
	 * @wangrui
	 * @param     data                   $data [数组]
	 * @return    xml                       
	 */
	public function arrayToXml($arr)
	{
		$xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                 $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
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
$a = new wxRefund();
$a->index();
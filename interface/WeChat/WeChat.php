<?php
/*
*微信事件接口
*1、进入微信服务号-基本配置-服务器配置
*/
include "../../library/openFunction.php";
//echo $_GET['echostr'];//服务器配置url时返回即可配置成功
/***********自定义菜单**********************************************/
$wxAppid = para("wxAppid");//微信appid
$wxAppSecret = para("wxAppSecret");//微信wxAppSecret
$root = "http://www.yumukeji.com/";
//获取access_token
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$wxAppid}&secret={$wxAppSecret}";
$token = json_decode(curl($url,""),true);
$access_token = $token['access_token'];
//自定义菜单
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$menu['button'][] = array(
   "type" => "view",
   "name" => "关于雨木",
   "url" => $root."m/mList.php?type=about"
);
$menu['button'][] = array(
   "type" => "view",
   "name" => "经典案例",
   "url" => $root."m/mList.php?type=case"
);
$menu['button'][] = array(
   "type" => "view",
   "name" => "产品中心",
   "url" => $root."m/mServe.php"
);
$data = json_encode($menu,JSON_UNESCAPED_UNICODE);
$r = curl($url,$data);
echo head("ad");
echo $r;
?>
<?php
/*
系统环境公共处理异步判断页（不对外开放）
*/
include "../control/ku/configure.php";
/***************管理员登录***************************************************/
if($get['type'] == "adLogin"){
	//赋值
	$tel = $post['ControlTel'];//注册手机号码
	$pas = $post['ControlPasword'];//登录密码
	$prove = $post['prove'];//图形验证码
	//判断
	if(empty($tel)){
	    $json['warn'] = "请输入手机号码";
	}elseif(preg_match($CheckTel,$tel) == 0){
	    $json['warn'] = "手机号码格式有误";
	}elseif(empty($pas)){
	    $json['warn'] = "请输入登录密码";
	}elseif(strlen($pas) < 6 || strlen($pas) > 20){
		$json['warn'] = "登录密码不得低于六位或大于20位";
	}elseif(empty($prove)){
	    $json['warn'] = "请输入验证码";
	}elseif($prove != $_SESSION["yan"]){
	    $json['warn'] = "验证码输入错误";
	}else{
		$pas = md5($pas);//获取登录密码的md5散列
		$admin = query("admin"," adtel = '$tel' ");
		$adLoginNum = para("adLoginNum");//每日登录密码最大的错误次数
		if(empty($admin['adtel'])){
			$json['warn'] = "此手机号码未注册";
		}elseif($admin['loginErrorNum'] >= $adLoginNum and $admin['loginErrorDay'] == $date){
			$json['warn'] = "您今天登录密码输入错误次数已经超过{$adLoginNum}次，不能再登录了";
		}elseif($pas != $admin['adpas']){//登录密码输入错误
		    if($tel == $_SESSION['ForgetPas']['tel'] and $pas == $_SESSION['ForgetPas']['prove']){//如果是点击忘记密码时发送到手机上的短信验证码
				mysql_query(" update admin set 
				adpas = '$pas',
				updateTime = '$time' where adtel = '$tel' ");
				$json['warn'] = 2;
			}else{
				$num = $admin['loginErrorNum'] + 1;
				$remain = $adLoginNum - $num;//今天还能登录的次数
				mysql_query(" update admin set 
				loginErrorNum = '$num',
				loginErrorDay = '$date',
				updateTime = '$time' where adtel = '$tel' ");
				$json['warn'] = "登录密码错误，您今天还可以登录{$remain}次";
			}
		}else{
			$json['warn'] = 2;
		}
		if($json['warn'] == 2){
			//登录密码错误次数清零
			mysql_query(" update admin set 
			loginErrorNum = '0',
			updateTime = '$time' where adtel = '$tel' ");
			//添加登录记录
			$ip = $_SERVER['REMOTE_ADDR'];//登录者当前的IP地址
			$ipSql = mysql_query(" select * from ip where ip = '$ip' ");
			if(mysql_num_rows($ipSql) == 0){
				$finger = 1;
			}else{
				$ipArray = mysql_fetch_array($ipSql);
				if($ipArray['dateNew'] == $date){
					$finger = 2;
				}else{
					$finger = 3;
				}
			}
			if(in_array($finger,array(1,3))){
				//获取ip地址信息
				$url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
				$curl = json_decode(Curl($url,""),true);
				$address = $curl['data']['country']."-".$curl['data']['area']."-".$curl['data']['region']."-".$curl['data']['city']."-".$curl['data']['county']."-".$curl['data']['isp'];
				//存入ip库
				if($finger == 1){
					$ipid = suiji();
					$bool = mysql_query(" insert into ip (id,ip,address,dateNew,updateTime,time) 
					values ('$ipid','$ip','$address','$date','$time','$time') ");
				}elseif($finger == 3){
					$bool = mysql_query(" update ip set 
					dateNew = '$date',
					address = '$address',
					updateTime = '$time' where id = '$ipArray[id]' ");
				}
			}else{
				$address = $ipArray['address'];	
			}
			LogText("管理员登录",$admin['adid'],"{$admin['adname']}登录了管理员后台，ip地址：{$ip}（{$address}）");
			//创建登录session，返回跳转地址
			$_SESSION['adid'] = $admin['adid'];
//			print_r($_SESSION);die;
			$json['href'] = root."control/adIndex.php";
		}
	}
/***************重置密码***************************************************/
}elseif($get['type'] == "adForgetPassword"){
	//赋值
	$type = $post['UserType'];//用户类型
	$tel = $post['ForgetTel'];//注册手机号码
	$session = $_SESSION['ForgetPas'];//防止重复发送短信的session
	$OldTime = $session['time']+60;//最后一次发送验证码的时间不得晚于此时间
	//判断
	if(empty($type)){
	    $json['warn'] = "未知请求类型";
	}elseif(empty($tel)){
		$json['warn'] = "手机号码不能为空";
	}elseif(preg_match($CheckTel,$tel) == 0){
		$json['warn'] = "手机号码格式不正确";
	}elseif($OldTime > time() and $session['tel'] == $tel){
	    $json['warn'] = "发送验证码间隔不能超过一分钟";
	}else{
		if($type == "admin"){
		    $admin = query("admin"," adtel = '$tel' ");
			if(empty($admin)){
			    $json['warn'] = "此号码不是管理员注册手机号码";
			}else{
			    $prove = rand(10000000,99999999);//生成随机六位短信验证码
				$finger = 2;
			}
		/**}elseif($type == "user"){
		    $kehu = query("kehu"," khtel = '$tel' ");
			if($kehu['khtel'] == $tel){
			    $pas = $kehu['khpas'];
				$finger = 2;
			}else{
			    $json['warn'] = "此号码不是客户注册手机号码";
			}**/
		}else{
		    $json['warn'] = "未知请求类型";
		}
	}
	//执行
	if($finger == 2){
		$message = "密码为：{$prove}，请勿泄露给任何人";
		$_SESSION['ForgetPas'] = array("time" => time(),"tel" => $tel,"prove" => md5($prove));
		$json['warn'] = duanxin($tel,$message);
	}
/***************短信验证码***************************************************/
}elseif($get['type'] == "RegisterCheckTel"){
    //赋值
	$tel = $post['tel'];//需要发送短信的手机号码
	$session = $_SESSION['Prove'];//保存到session中的验证信息
	$OldTime = $session['time']+60;//最后一次发送验证码的时间不得晚于此时间
	//判断并执行
	if(empty($tel)){
	    $json['warn'] = "请输入注册手机号码";
	}elseif(preg_match($CheckTel,$tel) == 0){
	    $json['warn'] = "手机号码输入错误";
	}elseif($OldTime > time() and $session['tel'] == $tel){
	    $json['warn'] = "发送验证码间隔不能超过一分钟";
	}else{
		$rand = rand(10000,99999);
		$message = "验证码为：{$rand}，请勿泄露给任何人";
		$_SESSION['Prove'] = array("rand" => $rand,"time" => time(),"tel" => $tel);
		$json['warn'] = duanxin($tel,$message);
	}
/***************异步加载通用文章某段内容***************************************************/
//不能直接使用表现层的文字，因为已经添加段落效果
}elseif(isset($post['ArticleTextId'])){
    $article = query("article"," id = '$post[ArticleTextId]' ");
	$json['word'] = html_entity_decode($article['word']);
/***************根据省份获取下属城市下拉菜单内容值***************************************************/
}elseif(isset($post['ProvincePostCity'])){
    $json['city'] = RepeatOption("region where province = '$post[ProvincePostCity]' ","city","--城市--","");
/***************根据省份和城市获取下属区域下拉菜单***************************************************/
}elseif(isset($post['ProvincePostArea']) and isset($post['CityPostArea'])){
	$json['area'] = IdOption("region where province = '$post[ProvincePostArea]' and city = '$post[CityPostArea]' ","id","area","--区域--","");
/***************通用文章管理-新增或更新文字段落***************************************************/
}elseif($get['type'] == "articleWordEdit"){
	//赋值
	$id = $post['articleTextId'];//段落ID号
	$target = $post['target'];//目标对象
	$targetId = $post['targetId'];//目标对象ID号
	$word = $post['articleText'];//文字段落内容
	//判断
	if(empty($target)){
		$json['warn'] = "目标对象为空";
	}elseif(empty($targetId)){
		$json['warn'] = "目标对象ID号为空";
	}elseif(empty($word)){
		$json['warn'] = "您还没有填写文字呢";
	}elseif(empty($id)){
		$articleLast = query("article"," targetId = '$targetId' order by list desc ");
		$list = $articleLast['list'] + 1;
		$id = suiji();
		$bool = mysql_query("insert into article (id,target,targetId,word,list,updateTime,time) 
		values ('$id','$target','$targetId','$word','$list','$time','$time')");
		if($bool){
		    $_SESSION['warn'] = "新增成功";
			$json['warn'] = 2;
		}else{
			$json['warn'] = "新增失败";
		}
	}else{
		$bool = mysql_query(" update article set 
		word = '$word',
		updateTime = '$time' where id = '$id' ");
		if($bool){
			$_SESSION['warn'] = "更新成功";
			$json['warn'] = 2;
		}else{
			$json['warn'] = "更新失败";
		}
	}
/***************通用文章管理-更新序列号*******************************************/
}elseif($get['type'] == "articleListEdit"){
	//赋值
	$list = $post['articleListText'];//排序号
	$id = $post['artcleListId'];//段落ID号
	//更新序列号
	$bool = mysql_query(" update article set 
	list = '$list',
	updateTime = '$time' where id = '$id' ");
	if($bool){
		$_SESSION['warn'] = "序列号更新成功";
		$json['warn'] = 2;
	}else{
	    $json['warn'] = "更新失败";	
	}
}
/***************返回信息***************************************************/
echo json_encode($json);
?>
<?php
include "adfunction.php";
if($ControlFinger == 2){
    $json['warn'] = $ControlWarn;
/************信息管理-网站图片管理-返回网站参数********************************************/
//当新建图片，即id号为空时还需要从后端传参数的原因是：当客户先点击其他已有图片参数，然后再点击新建图片，需要初始化参数面板。
}elseif($get['type'] == "adGetImgParameter"){
    $id = $post['id'];//图片表ID号
	if(empty($id)){
		$json = array(
			"type" => "",
			"name" => "",
			"url" => "",
			"text" => "",
			"list" => "",
			"geshi" => "",
			"width" => "",
			"height" => "",
			"maxSize" => "",
			"id" => ""
		);
	}else{
		$img = query("img"," id = '$id' ");
		$json = array(
			"type" => $img['type'],
			"name" => $img['name'],
			"url" => $img['url'],
			"text" => $img['text'],
			"list" => $img['list'],
			"geshi" => $img['geshi'],
			"width" => $img['width'],
			"height" => $img['height'],
			"maxSize" => $img['maxSize']/1000,
			"id" => $img['id']
		);
	}
/************信息管理-网站图片管理-新增或更新图像参数********************************************/
}elseif($get['type'] == "adEditImgParameter"){
    //赋值
	$type = $post['AdImgTypeText'];//图片类型
	$name = $post['AdImgName'];//图片名称
	$url = $post['AdImgUrl'];//链接地址
	$text = $post['AdImgText'];//备注
	$list = $post['ImgList'];//排序号
	$geshi = $post['adImgFormat'];//格式
	$width = $post['adImgWidth'];//宽度
	$height = $post['adImgHeight'];//高度
	$adImgMaxSize = $post['adImgMaxSize'];//最大体积（下面要以此判断原始数据是否为空或格式是否符合规范）
	$maxSize = $adImgMaxSize * 1000;//最大体积
	$id = $post['ImgId'];//图片id号
	$img = query("img"," id = '$id' ");
	//判断
	if(!power("adimg","edit")){
	    $json['warn'] = "您没有网站图片编辑权限";
	}elseif(empty($type)){
	    $json['warn'] = "图片分类不能为空";
	}elseif(empty($name)){
	    $json['warn'] = "图片名称不能为空";
	}elseif(Repeat(" img where type = '$type' and name = '$name' ","id",$id)){
	    $json['warn'] = "同一图片类型下存在相同的图片名称";
	}elseif(!empty($list) and preg_match($CheckInteger,$list) == 0){
		$json['warn'] = "排序号必须为正整数";
	}elseif(empty($geshi)){
		$json['warn'] = "图片格式不能为空";
	}elseif($geshi != "JPEG" and $geshi != "PNG"){
		$json['warn'] = "图片格式有误";
	}elseif(empty($width)){
		$json['warn'] = "图片宽度不能为空";
	}elseif(preg_match($CheckInteger,$width) == 0){
		$json['warn'] = "图片宽度必须为正整数";
	}elseif(empty($height)){
		$json['warn'] = "图片高度不能为空";
	}elseif(preg_match($CheckInteger,$height) == 0){
		$json['warn'] = "图片高度必须为正整数";
	}elseif(empty($adImgMaxSize)){
		$json['warn'] = "图片最大体积不能为空";
	}elseif(preg_match($CheckInteger,$adImgMaxSize) == 0){
		$json['warn'] = "图片最大体积必须为正整数";
	}elseif(empty($id)){
		$id = suiji();
		$bool = mysql_query(" insert into img (id,type,name,url,geshi,width,height,maxSize,text,list,del,updateTime,time) 
		values ('$id','$type','$name','$url','$geshi','$width','$height','$maxSize','$text','$list','是','$time','$time') ");
		if($bool){
		    $_SESSION['warn'] = "新增图片成功";
			//添加日志
			LogText("网站图片管理",$Control['adid'],"管理员{$Control['adname']}新增了图片：{$name}");
			$json['warn'] = 2;
		}else{
		    $json['warn'] = "新增图片失败";
		}
	}elseif($img['id'] != $id){
	    $json['warn'] = "图片不存在";
	}else{
		$bool = mysql_query("update img set 
		type = '$type',
		name = '$name',
		url = '$url',
		geshi = '$geshi',
		width = '$width',
		height = '$height',
		maxSize = '$maxSize',
		text = '$text',
		list = '$list',
		updateTime = '$time' where id = '$id' ");
		if($bool){
			$_SESSION['warn'] = "图像参数更新成功";
			//添加日志
			LogText("网站图片管理",$Control['adid'],"管理员{$Control['adname']}修改了图片基本参数，图片名称：{$name}");
			$json['warn'] = 2;
		}else{
			$json['warn'] = "图像参数更新失败";
		}
	}
/************信息管理-网站文字管理-返回基本参数********************************************/
}elseif($get['type'] == "adGetWord"){
    //赋值
	$id = $post['id'];//主键
	$website = query("website"," webid = '$id' ");
	//判断
	if(empty($id)){
	    $json['warn'] = 2;
		$json['title'] = "";
		$json['list'] = "";
		$json['content'] = "";
		$json['id'] = "";
	}elseif($website['webid'] != $id){
	    $json['warn'] = "未找到本内容";
	}else{
	    $json['warn'] = 2;
		$json['title'] = $website['name'];
		$json['list'] = $website['list'];
		$json['content'] = $website['text'];
		$json['id'] = $website['webid'];		
	}
/************信息管理-网站文字管理-新增或更新基本参数********************************************/
}elseif($get['type'] == "adwordEdit"){
    //赋值
	$name = $post['wordName'];//标题
	$text = $post['wordContent'];//文字内容
	$list = $post['wordList'];//排序号
	$id = $post['WordId'];//之前的文字ID号
	//判断
	if(!power("adword","edit")){
	    $json['warn'] = "您没有编辑网站框架文字的权限";
	}elseif(empty($name)){
	    $json['warn'] = "标题不能为空";
	}elseif(Repeat(" website where name = '$name' ","id",$id)){
	    $json['warn'] = "存在相同标题";
	}elseif(empty($list)){
	    $json['warn'] = "排序号不能为空";
	}elseif(preg_match($CheckInteger,$list) == 0){
	   $json['warn'] = "排序号必须为正整数";
	}elseif(empty($text)){
	    $json['warn'] = "内容不能为空";
	}elseif(empty($id)){
	    $id = suiji();
		$bool = mysql_query(" insert into website (webid,name,text,list,del,updateTime,time) 
		values ('$id','$name','$text','$list','是','$time','$time') ");
		if($bool){
		    $_SESSION['warn'] = "新增文字成功";
			//添加日志
			LogText("网站文字管理",$Control['adid'],"管理员{$Control['adname']}新增了文字（{$name}）");
			$json['warn'] = 2;
		}else{
		    $json['warn'] = "新增文字失败";
		}
	}else{
	    $bool = mysql_query(" update website set 
		name = '$name',
		text = '$text',
		list = '$list',
		updateTime = '$time' where webid = '$id' ");
		if($bool){
		    $_SESSION['warn'] = "更新文字成功";
			//添加日志
			LogText("网站文字管理",$Control['adid'],"管理员{$Control['adname']}修改了文字（{$name}）");
			$json['warn'] = 2;
		}else{
		    $json['warn'] = "更新文字失败";
		}
	}
/************信息管理-普通文章管理-根据一级分类返回网站通用内容二级分类********************************************/
}elseif(isset($post['adContentTypeGetClassify'])){
	$json['classify'] = RepeatSelect(" content where type = '$post[adContentTypeGetClassify]' ","classify","classify","select","二级分类");
/************信息管理-普通文章管理-新增或更新基本参数********************************************/
}elseif($get['type'] == "adEditContent"){
	//赋值
	$type = $post['TypeText'];//一级分类
	$classify = $post['ClassifyText'];//二级分类
	$title = $post['adContentTitle'];//标题
	$subTitle = $post['subTitle'];//副标题
	$url = $post['outUrl'];//外部链接
	$summary = $post['summary'];//摘要
	$list = $post['ContentList'];//排序号
	$xian = $post['ContentShow'];//状态（显示/隐藏）
	$id = $post['ContentId'];//id号
	//判断
	if(!power("adContent","edit")){
	    $json['warn'] = "您没有编辑网站通用文章的权限";
	}elseif(empty($type)){
	    $json['warn'] = "请填写内容一级分类";
	}elseif(empty($classify)){
	    $json['warn'] = "请填写内容二级分类";
	}elseif(empty($title)){
	    $json['warn'] = "请定义文章标题";
	}elseif(!empty($list) and preg_match($CheckInteger,$list) == 0){
	   $json['warn'] = "排序号必须为正整数";
	}elseif(empty($xian)){
	    $json['warn'] = "请定义前端状态";
	}elseif(Repeat(" content where type = '$type' and classify = '$classify' and title = '$title' ","id",$id)){
		$json['warn'] = "存在同分类同标题的文章";
	}elseif(empty($id)){
	    $id = suiji();
		$bool = mysql_query(" insert into content (id,type,classify,title,subTitle,outUrl,summary,list,xian,updateTime,time) 
		values ('$id','$type','$classify','$title','$subTitle','$url','$summary','$list','$xian','$time','$time') ");
		if($bool){
		    $_SESSION['warn'] = "文章新增成功";
			$json['warn'] = 2;
			LogText("普通文章管理",$Control['adid'],"管理员{$Control['adname']}新增了一篇文章“{$title}”");
		}else{
		    $json['warn'] = "文章新增失败";
		}
	}else{
		$content = query("content"," id = '$id' ");
		if($content['id'] != $id){
		    $json['warn'] = "未找到这篇文章";
		}else{
			$bool = mysql_query("update content set 
			type = '$type', 
			classify = '$classify', 
			title = '$title',
			subTitle = '$subTitle',
			outUrl = '$url',
			summary = '$summary',
			list = '$list',
			xian = '$xian',
			updateTime = '$time' where id = '$id' ");
			if($bool){
				$_SESSION['warn'] = "文章更新成功";
				$json['warn'] = 2;
				LogText("普通文章管理",$Control['adid'],"管理员{$Control['adname']}更新了文章“{$title}”");
			}else{
				$json['warn'] = "文章更新失败";
			}
		}
	}
	$json['href'] = root."control/info/adContentMx.php?id={$id}";
/************内部管理-员工管理-职位管理-新建或更新职位********************************************/
}elseif($get['type'] == "adDutyEdit"){
    //赋值
	$id = $post['DutyId'];
	$department = $post['DepartmentText'];//所属部门
	$name = $post['DutyName'];//职位名称
	$basePay = $post['BasePay'];//基本工资
	$text = $post['DutyText'];//职位描述
	$xian = $post['DutyShow'];//当前状态（显示/隐藏）
	$list = $post['DutyList'];//排序号
	$power = json_encode($post['power']);//管辖范围
	//判断
	if(!power("admin","editDuty")){
	    $json['warn'] = "您没有编辑职位的权限";
	}elseif(empty($department)){
	    $json['warn'] = "请选择或填写本职位所属部门";
	}elseif(empty($name)){
	    $json['warn'] = "请填写职位名称";
	}elseif(Repeat(" adDuty where name = '$name' ","id",$id)){//最好是不同部门也不要出现相同的职位名称
	    $json['warn'] = "该职位已经存在";
	}elseif(empty($text)){
	    $json['warn'] = "请简要描述该职位";
	}elseif(empty($xian)){
	    $json['warn'] = "请选择职位状态";
	}elseif(empty($list)){
	    $json['warn'] = "请填写排序号";
	}elseif(preg_match($CheckInteger,$list) == 0){
	    $json['warn'] = "排序号必须为正整数";
	}elseif(empty($id)){
	    $id = suiji();
		$bool = mysql_query(" insert into adDuty (id,department,name,basePay,text,xian,list,power,edit,del,updateTime,time) 
		values ('$id','$department','$name','$basePay','$text','$xian','$list','$power','是','是','$time','$time') ");
		if($bool){
		    $_SESSION['warn'] = "职位新增成功";
			LogText("管理员管理",$Control['adid'],"管理员{$Control['adname']}新增了一个职位“{$name}”");
			$json['warn'] = 2;
		}else{
		    $json['warn'] = "职位新增失败";
		}
	}else{
	    $duty = query("adDuty"," id = '$id' ");
		if($duty['id'] != $id){
		    $json['warn'] = "未找到该职位";
		}elseif($duty['edit'] == "否"){//之前的职位名称
		    $json['warn'] = "该职位不能编辑";
		}else{
			$bool = mysql_query(" update adDuty set
			department = '$department',
			name = '$name',
			basePay = '$basePay',
			text = '$text',
			xian = '$xian',
			list = '$list',
			power = '$power',
			updateTime = '$time' where id = '$id' ");
			if($bool){
				$_SESSION['warn'] = "职位更新成功";
				LogText("管理员管理",$Control['adid'],"管理员{$Control['adname']}更新了一个职位“{$name}”");
				$json['warn'] = 2;
				//如果状态为关闭，如果有管理员当前为该职位，则设为无职位
				if($xian == "关闭"){
				    mysql_query(" update admin set duty = '' where duty = '$id' ");
				}
			}else{
				$json['warn'] = "职位更新失败";
			}
		}
	}
	$json['href'] = root."control/Internal/adminDutyMx.php?id=".$id;
/************内部管理-员工管理-根据部门异步加载本部门的职位名称及id号********************************************/
}elseif(isset($post['adDutyDepartmentGetName'])){
    $json['DutyId'] = IdOption("adDuty where department = '$post[adDutyDepartmentGetName]' and xian = '开启' ","id","name","--选择--","");
/************内部管理-员工管理-新建或更新员工基本信息********************************************/
}elseif($get['type'] == "adminEdit"){
    //赋值
	$adDutyId = $post['adDutyId'];//员工职位ID
	$NewDuty = query("adDuty"," id = '$adDutyId' ");	
	$adname = $post['adname'];//员工姓名
	$sex = $post['sex'];//员工性别
	$state = $post['state'];//当前状态
	$adtel = $post['adtel'];//员工登录手机号码
	$ademail = $post['adEmail'];//员工电子邮箱
	$adqq = $post['adQQ'];//员工QQ
	$school = $post['adSchool'];//毕业院校
	$schoolMajor = $post['adSchoolMajor'];//所学专业
	$bankName = $post['adBankName'];//银行名称
	$bankNum = $post['adBankNum'];//银行卡号
	$text = $post['adminText'];//备注
	$schoolEnd = $post['GraduationYear']."-".$post['GraduationMoon']."-".$post['GraduationDay'];//毕业日期
	$entryTime = $post['EntryYear']."-".$post['EntryMoon']."-".$post['EntryDay'];//入职时间
	$quitTime = $post['quitYear']."-".$post['quitMoon']."-".$post['quitDay'];//离职时间
	$adid = $post['adminId'];//本管理员ID
	//判断
	if(!power("admin","editAdmin")){
	    $json['warn'] = "权限不足";
	}elseif(empty($adname)){
	    $json['warn'] = "请输入员工姓名";
	}elseif(Repeat(" admin where adname = '$adname' ","adid",$adid)){
		$json['warn'] = "此姓名已被其他员工使用";
	}elseif(empty($sex)){
	    $json['warn'] = "请选择性别";
	}elseif(empty($state)){
		$json['warn'] = "请选择当前状态";
	}elseif(empty($adtel)){
	    $json['warn'] = "请输入员工用于登录的手机号码";
	}elseif(preg_match($CheckTel,$adtel) == 0){
	    $json['warn'] = "手机号码有误";
	}elseif(Repeat(" admin where adtel = '$adtel' ","adid",$adid)){
		$json['warn'] = "手机号码已被其他员工使用";
	}elseif(empty($ademail)){
	    $json['warn'] = "请输入员工常用电子邮箱";
	}elseif(preg_match($CheckEmail,$ademail) == 0){
	    $json['warn'] = "邮箱格式有误";
	}elseif(Repeat(" admin where ademail = '$ademail' ","adid",$adid)){
		$json['warn'] = "电子邮箱已被其他员工使用";
	}elseif(!empty($adqq) and preg_match($CheckInteger,$adqq) == 0){
	    $json['warn'] = "QQ号码必须为正整数";
	}elseif(!empty($adqq) and Repeat(" admin where adqq = '$adqq' ","adid",$adid)){
		$json['warn'] = "QQ号码已被其他员工使用";
	}elseif(empty($adDutyId)){
	    $json['warn'] = "请选择员工职位";
	}elseif(empty($adid)){
		$adid = suiji();
		$adpas = suiji();
		$bool = mysql_query(" insert into admin (adid,duty,adname,sex,state,adtel,ademail,adqq,adpas,school,schoolMajor,bankName,bankNum,text,schoolEnd,entryTime,quitTime,updateTime,time) 
		values ('$adid','$adDutyId','$adname','$sex','$state','$adtel','$ademail','$adqq','$adpas','$school','$schoolMajor','$bankName','$bankNum','$text','$schoolEnd','$entryTime','$quitTime','$time','$time') ");
		if($bool){
			$_SESSION['warn'] = "新增成功";
			LogText("员工管理",$Control['adid'],"管理员{$Control['adname']}新增了员工“{$name}”的信息");
			$json['warn'] = 2;
		}else{
			$json['warn'] = "新增失败";
		}
	}else{
		$admin = query("admin"," adid = '$adid' ");
		$OldDuty = query("adDuty"," id = '$admin[duty]' ");
		if($admin['adid'] != $adid){
		    $json['warn'] = "未找到该员工";
		}else{
			$bool = mysql_query(" update admin set 
			duty = '$adDutyId',
			adname = '$adname',
			sex = '$sex',
			state = '$state',
			adtel = '$adtel',
			ademail = '$ademail',
			adqq = '$adqq',
			school = '$school',
			schoolMajor = '$schoolMajor',
			bankName = '$bankName',
			bankNum = '$bankNum',
			text = '$text',
			schoolEnd = '$schoolEnd',
			entryTime = '$entryTime',
			quitTime = '$quitTime',
			updateTime = '$time' where adid = '$adid' ");
			if($bool){
				$_SESSION['warn'] = "更新成功";
				LogText("员工管理",$Control['adid'],"管理员{$Control['adname']}更新了员工“{$name}”的信息");
				$json['warn'] = 2;
			}else{
				$json['warn'] = "更新失败";
			}
		}
	}
	$json['href'] = root."control/Internal/adminMx.php?id=".$adid;
/**********财务管理-收支平衡-费用报销*********************/
}elseif($get['type'] == "adProfitApply"){
	//赋值
	$money = $post['money'];//报销金额
	$text = $post['text'];//报销事由
	$pas = $post['pas'];//报销费用的员工的登录密码
	$year = $post['year'];//发生日期-年
	$moon = $post['moon'];//发生日期-月
	$day = $post['day'];//发生日期-日
	$payDate = $year."-".$moon."-".$day;//发生日期
	$adProfitId = $post['adProfitId'];//收支记录ID号（更新时）
	//判断
	if(!power("adProfit","apply")){
		$json['warn'] = "权限不足";
	}elseif(empty($money)){
		$json['warn'] = "请填写报销金额";
	}elseif(preg_match($CheckPrice,$money) == 0){
		$json['warn'] = "报销金额格式有误";
	}elseif(empty($text)){
		$json['warn'] = "请填写报销事由";
	}elseif(empty($year)){
		$json['warn'] = "请填写发生年份";
	}elseif(empty($moon)){
		$json['warn'] = "请填写发生月份";
	}elseif(empty($day)){
		$json['warn'] = "请填写发生日期";
	}elseif(empty($pas)){
		$json['warn'] = "请填写您的登录密码";
	}elseif(md5($pas) != $Control['adpas']){
		$json['warn'] = "登录密码输入有误";
	}elseif(empty($adProfitId)){
		$sqlNum = mysql_num_rows(mysql_query(" select * from profit where adid = '$Control[adid]' and direction = '支出' and money = '$money' and text = '$text' and payDate = '$payDate' and auditing = '审核中' "));
		if($sqlNum > 0){
			$json['warn'] = "请勿重复提交";
		}else{
			$id = suiji();
			$bool = mysql_query(" insert into profit (id,adid,direction,money,text,payDate,auditing,updateTime,time) 
			values ('$id','$Control[adid]','支出','$money','$text','$payDate','审核中','$time','$time') ");
			if($bool){
				$json['id'] = $id;
				$json['warn'] = 2;
				$_SESSION['warn'] = "提交成功";
			}else{
				$json['warn'] = "提交失败";
			}
		}
	}else{
		$profit = query("profit"," id = '$adProfitId' ");
		if(empty($profit['id'])){
			$json['warn'] = "未找到此收支记录";
		}elseif($profit['auditing'] == "已通过"){
			$json['warn'] = "已通过审核的记录不能更改";
		}else{
			$bool = mysql_query(" update profit set 
			money = '$money',
			text = '$text',
			payDate = '$payDate',
			auditing = '审核中',
			updateTime = '$time' where id = '$adProfitId' ");
			if($bool){
				$_SESSION['warn'] = "更新成功";
				$json['warn'] = 2;
			}else{
				$json['warn'] = "更新失败";
			}
		}
	}
/**********财务管理-收支平衡-费用报销审核*********************/
}elseif($get['type'] == "adProfitAuditing"){
	//赋值
	$auditing = $post['auditing'];//状态
	$auditingText = $post['auditingText'];//审核说明
	$pas = $post['pas'];//登录密码
	$adProfitId = $post['adProfitId'];//主键
	$profit = query("profit"," id = '$adProfitId' ");
	//判断
	if(!power("adProfit","auditing")){
		$json['warn'] = "权限不足";
	}elseif(empty($auditing)){
		$json['warn'] = "请选择审核状态";
	}elseif(empty($pas)){
		$json['warn'] = "请填写您的登录密码";
	}elseif(md5($pas) != $Control['adpas']){
		$json['warn'] = "登录密码输入有误";
	}elseif(empty($adProfitId)){
		$json['warn'] = "收支平衡ID号为空";
	}elseif(empty($profit['id'])){
		$json['warn'] = "未找到此记录";
	}else{
		$bool = mysql_query(" update profit set 
		auditing = '$auditing',
		auditingText = '$auditingText',
		updateTime = '$time' where id = '$adProfitId' ");
		if($bool){
			if($profit['direction'] == "收入" and $auditing == "已通过"){
				buyCarProfit($profit['orderId']);
			}
			//返回
			$_SESSION['warn'] = "提交成功";
			$json['warn'] = 2;
		}else{
			$json['warn'] = "提交失败";
		}
	}
/************财务管理-账户管理-变更账户余额********************************************/
}elseif($get['type'] == "accountEdit"){
	//赋值
	$money = $post['money'];//变更金额
	$text = $post['text'];//变更备注
	$pas = $post['password'];//管理员登录密码
	$id = $post['EditMoneyId'];//账户所有者id号
	$type = $post['EditMoneyType'];//账户所有者身份：代理商/商户/客户
	$Aspect = $post['EditMoneyDirection'];//变更方向
	//判断
	if(!power("adAccount","edit")){
		$json['warn'] = "权限不足";
	}elseif(empty($money)){
		$json['warn'] = "变动额度不能为空";
	}elseif(preg_match($CheckPrice,$money) == 0){
		$json['warn'] = "变动额度格式不正确";
	}elseif(empty($text)){
		$json['warn'] = "简要说明不能为空";
	}elseif(empty($pas)){
		$json['warn'] = "管理员登录密码不能为空";
	}elseif(md5($pas) != $Control['adpas']){
		$json['warn'] = "管理员登录密码有误";
	}elseif(empty($id)){
		$json['warn'] = "变更账户的id号为空";
	}elseif(empty($Aspect)){
		$json['warn'] = "变更方向为空";
	}elseif(empty($type)){
		$json['warn'] = "执行指令为空";
	}elseif($type == "adid"){
		$admin = query("admin"," adid = '$id' ");
		if($admin['adid'] != $id){
			$json['warn'] = "员工id号有误";
		}else{
			if($Aspect == "addMoney"){
				$balance = $admin['money'] + $money;
				$direction = "收入";
				$json['warn'] = 2;
			}elseif($Aspect == "cutMoney"){
				$balance = $admin['money'] - $money;
				$direction = "支出";
				if($balance >= 0){
					$json['warn'] = 2;
				}else{
					 $json['warn'] = "员工账户余额不够扣了";
				}
			}
			if($json['warn'] == 2){
				$bool = mysql_query(" update admin set money = '$balance',updateTime = '$time' where adid = '$id' ");
				if($bool){
					RecordMoney($id,"员工现金账户",$direction,$money,$balance,$text);
					LogText("账户管理",$Control['adid'],"管理员{$Control['adname']}将员工（名称：{$admin['adname']}，手机：{$admin['adtel']}，ID：{$id}）的账户余额从”{$admin['money']}“变更为”{$balance}“");
					$_SESSION['warn'] = "员工现金账户{$direction}{$money}";
				}else{
					$json['warn'] = "员工现金账户余额变动失败";
				}
			}
		}
	}else{
		$json['warn'] = "未知执行指令";
	}
/************财务管理-参数管理-根据参数id返回核心参数值********************************************/
}elseif($get['type'] == "parameterShow"){
	$para = query("para"," paid = '$post[id]' ");
	if(empty($para['paid'])){
	    $json = array(
			"name" => "",
			"text" => "",
			"id" => ""
		);	
	}else{
	    $json = array(
			"name" => $para['paName'],
			"text" => $para['paValue'],
			"id" => $para['paid']
		);	
	}
/************财务管理-参数管理-更新核心参数********************************************/
}elseif($get['type'] == "parameterEdit"){
    //赋值
    $paName = $post['name'];//参数名称
    $paValue = $post['text'];//参数值
    $adpas = $post['pas'];//当前管理员登录密码
    $paid = $post['id'];//参数表ID号
	//判断
	if(!power("adParameter","edit")){
	    $json['warn'] = "您没有编辑网站核心参数的权利";
	}elseif(empty($paName)){
		$json['warn'] = "参数名称为空";
	}elseif(empty($paValue)){
		$json['warn'] = "参数值为空";
	}elseif(empty($adpas)){
		$json['warn'] = "请填写管理员登录密码";
	}elseif($Control['adpas'] != md5($adpas)){
		$json['warn'] = "管理员登录密码输入错误";
	}elseif(empty($paid)){
	    $json['warn'] = "参数ID号为空";
	}else{
		$para = query("para"," paid = '$paid' ");
		if($para['paid'] != $paid){
			$json['warn'] = "未找到此记录";
		}elseif($para['edit'] == "否"){
			$json['warn'] = "此参数不可编辑";
		}else{
			$bool = mysql_query(" update para set 
			paName = '$paName',
			paValue = '$paValue',
			updateTime = '$time' where paid = '$paid' ");
			if($bool){
				$_SESSION['warn'] = "更新成功";
				$json['warn'] = 2;
				LogText("参数管理",$Control['adid'],"管理员{$Control['adname']}将核心参数“{$paName}”从“{$para['paValue']}”更新为“{$paValue}”");
			}else{
				$json['warn'] = "更新失败";
			}
		}
	}
/**********订单管理-新增或更新回款记录*********************/
}elseif($get['type'] == "adOrderRecord"){
	//赋值
	$money = $post['money'];//回款金额
	$year = $post['year'];//回款日期-年
	$moon = $post['moon'];//回款日期-月
	$day = $post['day'];//回款日期-日
	$payDate = "{$post['year']}-{$post['moon']}-{$post['day']}";
	$text = $post['text'];//备注
	$id = $post['id'];//主键
	//判断
	if(!power("adOrder","pay")){
		$json['warn'] = "权限不足";
	}elseif(empty($money)){
		$json['warn'] = "请填写回款金额";
	}elseif(preg_match($CheckPrice,$money) == 0){
		$json['warn'] = "回款金额格式有误";
	}elseif(empty($year)){
		$json['warn'] = "请选择回款的年份";
	}elseif(empty($moon)){
		$json['warn'] = "请选择回款的月份";
	}elseif(empty($day)){
		$json['warn'] = "请选择回款的日期";
	}elseif(empty($text)){
		$json['warn'] = "请填写回款说明";
	}elseif(empty($id)){
		$orderId = $post['orderId'];//订单ID号
		$buyCar = query("buyCar"," id = '$orderId' ");
		$repeatNum = mysql_num_rows(mysql_query(" select * from profit where 
		adid = '$Control[adid]' 
		and direction = '收入' 
		and money = '$money' 
		and text = '$text' 
		and projectId = '$buyCar[projectId]' 
		and orderId = '$orderId'
		and payDate = '$payDate'
		and auditing = '审核中'  "));
		if(empty($orderId)){
			$json['warn'] = "订单号为空";
		}elseif(empty($buyCar['id'])){
			$json['warn'] = "未找到此订单";
		}elseif($repeatNum > 0){
			$json['warn'] = "请勿重复提交";
		}else{
			$id = suiji();
			$bool = mysql_query(" insert into profit (id,adid,direction,money,text,projectId,orderId,payDate,auditing,updateTime,time) 
			values ('$id','$Control[adid]','收入','$money','$text','$buyCar[projectId]','$orderId','$payDate','审核中','$time','$time') ");
			if($bool){
				$json['id'] = $id;
				$json['warn'] = 2;
			}else{
				$json['warn'] = "回款记录新增失败";
			}
		}
	}else{
		$profit = query("profit"," id = '$id' ");
		$buyCar = query("buyCar"," id = '$profit[orderId]' ");
		if(empty($profit['id'])){
			$json['warn'] = "未找到此回款记录";
		}else{
			$bool = mysql_query(" update profit set 
			money = '$money',
			text = '$text',
			payDate = '$payDate',
			auditing = '审核中',
			updateTime = '$time' where id = '$id' ");
			if($bool){
				buyCarProfit($buyCar['id']);
				$json['warn'] = 2;
				$_SESSION['warn'] = "更新成功";
			}else{
				$json['warm'] = "更新失败";
			}
		}
	}
/************个人中心-修改管理员注册手机号码********************************************/
}elseif($get['type'] == "adEditTel"){
    //赋值
	$tel = $post['NewTel'];//新手机号码
	$pas = $post['password'];//登录密码
	$prove = $post['Prove'];//短信验证码
	$Repeat = mysql_num_rows(mysql_query(" select * from admin where adtel = '$tel' "));
	//判断
	if(empty($tel)){
	    $json['warn'] = "请输入手机号码";
	}elseif(preg_match($CheckTel,$tel) == 0){
       $json['warn'] = "手机号码格式有误";
	}elseif($tel == $Control['adtel']){
	    $json['warn'] = "新手机号码与之前的手机号码一致";
	}elseif($Repeat > 0){
		$json['warn'] = "手机号码已经被其他用户使用";
	}elseif(empty($pas)){
	    $json['warn'] = "请输入登录密码";
	}elseif(md5($pas) != $Control['adpas']){
	    $json['warn'] = "登录密码不正确";
	}elseif(empty($prove)){
	    $json['warn'] = "请输入验证码";
	}elseif($prove != $_SESSION['Prove']['rand']){
		$json['warn'] = "手机验证码输入错误！";
	}elseif($_SESSION['Prove']['tel'] != $tel){
		$json['warn'] = "请使用接受验证短信的手机号码注册！";
	}else{
		$bool = mysql_query(" update admin set adtel = '$tel',updateTime = '$time' where adid = '$Control[adid]' ");
		if($bool){
		    $_SESSION['warn'] = "注册手机号码更新成功";
			$json['warn'] = 2; 
			//添加日志
			LogText("管理员管理",$Control['adid'],"管理员{$Control['adname']}修改了自己的登录手机号码");
		}else{
		    $json['warn'] = "注册手机号码更新失败";
		}
	}
/************个人中心-修改登录密码********************************************/
}elseif($get['type'] == "adEditPas"){
    //赋值
	$OldPas = $post['pas'];//当前密码
	$NewPas = $post['gxpas'];//新密码
	$NewPasLength = mb_strlen($NewPas,'utf-8');//新密码的长度
	$SurePas = $post['qrpas'];//确认密码
	$prove = $post['Prove'];//短信验证码
	//判断
	if(empty($OldPas)){
        $json['warn'] = "请输入您的原密码";
    }elseif(md5($OldPas) != $Control['adpas']){
		$json['warn'] = "您输入的原密码错误";  
    }elseif(empty($NewPas)){
        $json['warn'] = "请输入您的新密码";
	}elseif($OldPas == $NewPas){
	    $json['warn'] = "新密码不能与旧密码一样";
    }elseif($NewPasLength < 8 or $NewPasLength > 20){
        $json['warn'] = "新密码长度必须大于8位且小于20位";
    }elseif(empty($SurePas)){
        $json['warn'] = "请输入确认密码";
    }elseif($NewPas != $SurePas){
        $json['warn'] = "新密码与确认密码不一致";
    }elseif($OldPas == $NewPas){
	    $json['warn'] = "新密码不能与原密码相同";
	}elseif(empty($prove)){
	    $json['warn'] = "请输入手机验证码";
    }elseif($prove != $_SESSION['Prove']['rand']){
       $json['warn'] = "手机验证码输入错误！";
    }else{
	    $NewPas = md5($NewPas);
		$bool = mysql_query(" update admin set 
		adpas = '$NewPas',
		updateTime = '$time' where adid = '$Control[adid]' ");
		if($bool){
			$json['warn'] = "修改密码成功";
			//添加日志
			LogText("管理员管理",$Control['adid'],"管理员{$Control['adname']}修改了自己的登录密码");
		}else{
			$json['warn'] = "修改密码失败";   
		}
    }
/************一级警告函数之提取警示信息********************************************/
}elseif($get['type'] == "getPasWarn"){
	$json['word'] = website($post['PasWarnWord']);
/************批量处理列表记录（需要管理员登录密码）********************************************/
}elseif(isset($post['PadWarnType'])){
    //赋值
	$type = $post['PadWarnType'];//执行指令
	$pas = $post['Password'];//密码
	$x = 0;
	//判断
	if(empty($type)){
	    $json['warn'] = "执行指令为空";
	}elseif(empty($pas)){
	    $json['warn'] = "请输入管理员登录密码";
	}elseif(md5($pas) != $Control['adpas']){
	    $json['warn'] = "管理员登录密码输入错误";
	}elseif($type == "deleteImg"){
		$Array = $post['ImgList'];
		if(!power("adimg","del")){
			$json['warn'] = "您没有删除网站图片的权限";
		}elseif(empty($Array)){
			$json['warn'] = "您一张图片都没有选择呢";
		}else{
			foreach($Array as $id){
				$img = query("img"," id = '$id'");
				if($img['del'] != "否"){
					//删除图片
					unlink(ServerRoot.$img['src']);
					//删除图片基本参数
					mysql_query("delete from img where id = '$id'");
					//添加记录
					LogText("网站图片管理",$Control['adid'],"管理员{$Control['adname']}删除了图片“{$img['name']}”");
					$x++;
				}
			}
			$_SESSION['warn'] = "删除了{$x}张图片";
			$json['warn'] = 2;
		}
	}elseif($type == "deleteWord"){
		$Array = $post['WordList'];
		if(!power("adword","del")){
			$json['warn'] = "您没有删除网站框架文字的权限";
		}elseif(empty($Array)){
			$json['warn'] = "您一条文字都没有选择呢";
		}else{
			foreach($Array as $id){
				$website = query("website"," webid = '$id'");
				if($website['del'] != "否"){
					//删除文字基本参数
					mysql_query("delete from website where webid = '$id'");
					//添加记录
					LogText("网站文字管理",$Control['adid'],"管理员{$Control['adname']}删除了网站文字内容“{$website['name']}”");
					$x++;
				}
			}
			$_SESSION['warn'] = "删除了{$x}条文字信息";
			$json['warn'] = 2;
		}
	}elseif($type == "deleteArticle"){
		$Array = $post['AdContentList'];
		if(!power("adContent","del")){
			$json['warn'] = "您没有删除网站普通文章的权限";
		}elseif(empty($Array)){
			$json['warn'] = "您一篇文章都没有选择呢";
		}else{
			foreach($Array as $id){
				$content = query("content","id = '$id'");
				//删除列表图像
				unlink(ServerRoot.$content['ico']);
				//删除详细内容
				$ActileSql = mysql_query("select * from article where targetId = '$id'");
				while($Actile = mysql_fetch_array($ActileSql)){
					unlink(ServerRoot.$Actile['img']);
				}
				mysql_query("delete from article where targetId = '$id'");
				//最后删除文章基本参数
				mysql_query("delete from content where id = '$id'");
				//添加记录
				LogText("网站内容管理",$Control['adid'],"管理员{$Control['adname']}删除了网站内容“{$content['title']}”");
				$x++;
			}
			$_SESSION['warn'] = "删除了{$x}篇文章";
			$json['warn'] = 2;
		}
	}elseif($type == "deleteDuty"){
		$Array = $post['DutyList'];
		if(!power("admin","delDuty")){
			$json['warn'] = "权限不足";
		}elseif(empty($Array)){
			$json['warn'] = "您一个职位都没有选择呢";
		}else{
			foreach($Array as $id){
				//查询职位基本参数
				$duty = query("adDuty"," id = '$id'");
				if($duty['del'] != "否"){
					//如果有管理员为该职位，则设为无职位状态
					mysql_query(" update admin set duty = '' where duty = '$id' ");
					//最后删除职位基本参数
					mysql_query("delete from adDuty where id = '$id'");
					//添加记录
					LogText("管理员管理",$Control['adid'],"管理员{$Control['adname']}删除了一个职位（所属部门：{$duty['department']}，职位名称：{$duty['name']}）");
					$x++;
				}
			}
			$_SESSION['warn'] = "删除了{$x}个职位";
			$json['warn'] = 2;
		}
	}elseif($type == "deleteAdmin"){
		$Array = $post['AdminList'];
		if(!power("admin","delAdmin")){
			$json['warn'] = "权限不足";
		}elseif(empty($Array)){
			$json['warn'] = "您一个员工都没有选择呢";
		}else{
			foreach($Array as $id){
				$admin = query("admin"," adid = '$id'");
				$duty = query("adDuty"," id = '$admin[duty]' ");
				if($duty['del'] == "是"){
					//删除员工账户记录
					mysql_query("delete from record where typeid = '$id'");
					//删除员工头像
					unlink(ServerRoot.$admin['touxiang']);
					//删除员工身份证正面
					unlink(ServerRoot.$admin['IDCardFront']);
					//删除员工身份证背面
					unlink(ServerRoot.$admin['IDCardBack']);
					//删除员工毕业证扫描件
					unlink(ServerRoot.$admin['diploma']);
					//删除员工银行卡正面扫描件
					unlink(ServerRoot.$admin['bankIco']);
					//最后删除管理员基本参数
					mysql_query("delete from admin where adid = '$id'");
					//添加记录
					LogText("员工管理",$Control['adid'],"管理员{$Control['adname']}删除了一个员工“{$admin['adname']}”");
					$x++;
				}
			}
			$_SESSION['warn'] = "删除了{$x}个员工";
			$json['warn'] = 2;
		}
	}elseif($type == "fileDelete"){
		$Array = $post['file'];
		$page = getenv("HTTP_REFERER");//传送数据过来的页面名称
		if(strstr($page,"adClientMx.php") !== false){
			if(!power("adClient","delFile")){
				$json['warn'] = "删除客户附件权限不足";
			}
		}elseif(strstr($page,"adOrderMx.php") !== false){
			if(!power("adOrder","delFile")){
				$json['warn'] = "删除订单附件权限不足";
			}
		}else{
			$json['warn'] = "未知页面";
		}
		if(empty($json['warn'])){
			if(empty($Array)){
				$json['warn'] = "您一个附件都没有选择呢";
			}else{
				foreach($Array as $id){
					//查询附件基本信息
					$file = query("file"," id = '$id' ");
					//查询附件宿主的基本信息
					if($file['target'] == "客户"){
						$kehu = query("kehu"," khid = '$file[targetId]' ");
						$nameText = "客户名称：".$kehu['CompanyName'];
						$logType = "客户管理";
					}elseif($file['target'] == "订单"){
						$order = query("buyCar"," id = '$file[targetId]' ");
						$nameText = "订单名称：".$order['name'];
						$logType = "订单管理";
					}else{
						$nameText = "未知附件宿主类型";
						$logType = "异常日志";
					}
					//删除附件
					unlink(ServerRoot.$file['src']);
					//删除附件基本信息
					mysql_query("delete from file where id = '$id'");
					//添加日志
					$text = "
					管理员{$Control['adname']}删除了附件。
					{$nameText}，
					附件名称：{$file['name']}
					";
					LogText($logType,$Control['adid'],$text);
					$x++;
				}
				$_SESSION['warn'] = "删除了{$x}个附件";
				$json['warn'] = 2;
			}
		}
	}else{
	    $json['warn'] = "未知执行指令";
	}
}
/************返回********************************************/
echo json_encode($json);
?>
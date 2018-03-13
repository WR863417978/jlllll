<?php
include "adfunction.php";
ControlRoot();
/********信息管理-日志模糊查询********************************************************/
if($get['type'] == "adSearchLog"){
    //赋值
	$target = $post['target'];//目标对象
	$targetId = $post['targetId'];//目标ID
	$text = $post['text'];//详细说明
	$x = "";
	//串联查询语句
	if(!empty($target)){
	   $x .= " and target = '$target' "; 
	}
	if(!empty($targetId)){
	   $x .= " and targetId = '$targetId' "; 
	}
	if(!empty($text)){
	   $x .= " and text like '%$text%' "; 
	}
	//返回值
	$_SESSION['adLog'] = array("target" => $target,"targetId" => $targetId,"text" => $text,"Sql" => $x);
/********信息管理-网站图片管理-多条件模糊查询********************************************************/
}elseif($get['type'] == "adSearchImg"){
	//赋值
	$id = $post['id'];//图片ID号
	$type = $post['type'];//分类
	$name = $post['name'];//图片名称
	$src = $post['src'];//相对路径
	$del = $post['del'];//可删除
	$x = "where 1=1 ";
	//串接查询语句
	if(!empty($id)){
		$x .= " and id = '$id' ";
	}
	if(!empty($type)){
		$x .= " and type = '$type' ";
	}
	if(!empty($name)){
		$x .= " and name like '%$name%' ";
	}
	if(!empty($src)){
		$x .= " and src like '%$src%' ";
	}
	if(!empty($del)){
		$x .= " and del = '$del' ";
	}
	//返回值
	$_SESSION['adImg'] = array("id" => $id,"type" => $type,"name" => $name,"src" => $src,"del" => $del,"Sql" => $x);
/********信息管理-网站文字管理-多条件模糊查询********************************************************/
}elseif($get['type'] == "adSearchWord"){
	//赋值
	$id = $post['id'];//主键
	$name = $post['name'];//标题
	$content = $post['content'];//内容
	$del = $post['del'];//可删除
	$x = "";
	//串接查询语句
	if(!empty($id)){
		$x .= " and webid = '$id' ";
	}
	if(!empty($name)){
		$x .= " and name like '%$name%' ";
	}
	if(!empty($content)){
		$x .= " and text like '%$content%' ";
	}
	if(!empty($del)){
		$x .= " and del = '$del' ";
	}
	//返回值
	$_SESSION['adWeb'] = array("id" => $id,"name" => $name,"content" => $content,"del" => $del,"Sql" => $x);
/********信息管理-通用文章管理-多条件模糊查询********************************************************/
}elseif($get['type'] == "adSearchContent"){
	//赋值
	$type = $post['adContentType'];//一级分类
	$classify = $post['classify'];//二级分类
	$title = $post['adContentTitle'];//标题
	$xian = $post['adContentShow'];//状态
	$x = "where 1 = 1 ";
	//串接查询语句
	if(!empty($type)){
		$x .= " and type = '$type' ";
	}
	if(!empty($classify)){
		$x .= " and classify = '$classify' ";
	}
	if(!empty($title)){
		$x .= " and title like '%$title%' ";
	}
	if(!empty($xian)){
		$x .= " and xian = '$xian' ";
	}
	//返回值
	$_SESSION['adContent'] = array("type" => $type,"classify" => $classify,"title" => $title,"xian" => $xian,"Sql" => $x);
/********内部管理-员工管理-职位管理-多条件模糊查询********************************************************/
}elseif($get['type'] == "adSearchDuty"){
	//赋值
	$department = $post['adDutyDepartment'];//所属部门
	$name = $post['adDutyName'];//职位名称
	$xian = $post['adDutyShow'];//显示状态
	$power = $post['power'];//权限
	$edit = $post['edit'];//权限
	$del = $post['del'];//权限
	$x = " where 1=1 ";
	//串接查询语句
	if(!empty($department)){
		$x .= " and department = '$department' ";
	}
	if(!empty($name)){
		$x .= " and name like '%$name%' ";
	}
	if(!empty($xian)){
		$x .= " and xian = '$xian' ";
	}
	if(!empty($power)){
		$x .= " and power like '%\"$power\"%' ";
	}
	if(!empty($edit)){
		$x .= " and edit = '$edit' ";
	}
	if(!empty($del)){
		$x .= " and del = '$del' ";
	}
	//返回值
	$_SESSION['adDuty'] = array("department" => $department,"name" => $name,"xian" => $xian,"power" => $power,"edit" => $edit,"del" => $del,"Sql" => $x);
/********内部管理-员工管理-多条件模糊查询********************************************************/
}elseif($get['type'] == "adSearchAdmin"){
	//赋值
	$department = $post['adDutyDepartment'];//所属部门
	$DutyId = $post['adDutyId'];//当前职位
	$name = $post['adName'];//姓名
	$sex = $post['adSex'];//性别
	$tel = $post['adTel'];//手机号码
	$email = $post['adEmail'];//电子邮箱
	$qq = $post['adQQ'];//员工QQ号码
	$x = " where 1=1 ";
	//串接查询语句
	if(empty($department)){
	    $DutyId = "";
	}else{
	    if(empty($DutyId)){
		    $x .= " and duty in ( select id from adDuty where department = '$department' ) ";
		}else{
		    $x .= " and duty = '$DutyId' ";
		}
	}
	if(!empty($name)){
		$x .= " and adname like '%$name%' ";
	}
	if(!empty($sex)){
		$x .= " and sex = '$sex' ";
	}
	if(!empty($tel)){
		$x .= " and adtel like '%$tel%' ";
	}
	if(!empty($email)){
		$x .= " and ademail like '%$email%' ";
	}
	if(!empty($qq)){
		$x .= " and adqq like '%$qq%' ";
	}
	//返回值
	$_SESSION['Admin'] = array("department" => $department,"DutyId" => $DutyId,"name" => $name,"sex" => $sex,"tel" => $tel,"email" => $email,"qq" => $qq,"Sql" => $x);
/**********财务管理-收支平衡-多条件模糊查询************************************/
}elseif($get['type'] == "searchProfit"){
    //赋值
	$direction = $post['adProfitDirection'];//变动方向
	$adid = $post['adid'];//员工ID
	$auditing = $post['auditing'];//审核状态
	$text = $post['adProfitText'];//备注
	$year1 = $post['year1'];
	$moon1 = $post['moon1'];
	$day1 = $post['day1'];
	$year2 = $post['year2'];
	$moon2 = $post['moon2'];
	$day2 = $post['day2'];
	$x = "";
	//串联查询语句
	if(!empty($direction)){
	    $x .= " and direction = '$direction' ";
	}
	if(!empty($adid)){
	    $x .= " and adid = '$adid' ";
	}
	if(!empty($auditing)){
	    $x .= " and auditing = '$auditing' ";
	}
	if(!empty($text)){
	    $x .= " and text like '%$text%' ";
	}
	if(!empty($year1) and !empty($moon1) and !empty($day1) and !empty($year2) and !empty($moon2) and !empty($day2)){
		$DayOne = "{$year1}-{$moon1}-{$day1}";
		$DayTwo = "{$year2}-{$moon2}-{$day2}";
		$x .= " and PayDate >= '$DayOne' and PayDate <= '$DayTwo' ";
	}else{
	    $year1 = $moon1 = $day1 = $year2 = $moon2 = $day2 = $DayOne = $DayTwo = "";
	}
	//返回值
	$_SESSION['adProfit'] = array("direction" => $direction,"adid" => $adid,"auditing" => $auditing,"text" => $text,"DayOne" => $DayOne,"DayTwo" => $DayTwo,"Sql" => $x);
	header("location:".root."control/finance/adProfit.php");
	exit(0);
/********信息管理-网站图片管理-更新图片********************************************************/
}elseif($get['type'] == "adEditImg"){
	//赋值
	$id = $post['ImgId'];
	$img = query("img"," id = '$id' ");
	//判断
	if(!power("adimg","edit")){
	    $_SESSION['warn'] = "您没有网站图片的编辑权限";
	}elseif(empty($id)){
	    $_SESSION['warn'] = "图片id号为空";
	}elseif($img['id'] != $id){
	    $_SESSION['warn'] = "未找到该图片";
	}else{
		$FileName = "UploadImg";//上传图片的表单文件域名称
		$Rule['MaxSize'] = $img['maxSize'];//图像的最大容量
		$Rule['width'] = $img['width'];//图像要求的宽度
		$Rule['height'] = $img['height'];//图像要求的高度
		$Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
		$type['name'] = "更新图像";//《更新图像》或《新增图像》
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from img where id = '$id' ";//查询图片的数据库代码
		$column = "src";//保存图片的数据库列的名称
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/WebsiteImg/{$id}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = " update img set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where id = '$id' ";//保存图片的数据库代码
		$ImgWarn = "框架图像更新成功";//图片保存成功后返回的文字内容
		UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
		//添加记录
		LogText("网站图片管理",$Control['adid'],"管理员{$Control['adname']}更新了图片（名称：{$img['name']}，ID号：{$id}）");
	}
/********信息管理-普通文章管理-更新列表图像********************************************************/
}elseif($get['type'] == "adEditContentIco"){
	//赋值
	$id = $post['ContentId'];
	$content = query("content"," id = '$id' ");
	//判断并执行
	if(!power("adContent","edit")){
	    $_SESSION['warn'] = "您没有编辑网站通用文章的权限";
	}elseif(empty($id)){
	    $_SESSION['warn'] = "请先提交文章基本参数";
	}elseif($content['id'] != $id){
	    $_SESSION['warn'] = "未找到本文章";
	}else{
		$cut['width'] = 400;//裁剪宽度
		$cut['height'] = 300;//裁剪高度
		$FileName = "UploadContentListIco";//上传图片的表单文件域名称
		$cut['type'] = "需要裁剪";//"需要裁剪"或"需要缩放"或空
		$cut['NewWidth'] = "";//缩放的宽度
		$cut['MaxHeight'] = "";//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = "select * from content where id = '$id'";//查询图片的数据库代码
		$column = "ico";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/ContentIco/{$suiji}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = "update content set ico = '$Url[NewImgUrl]',updateTime = '$time' where id = '$id' ";//保存图片的数据库代码
		$ImgWarn = "内容列表图像更新成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
/********内部管理-员工管理-更新身份证正面********************************************************/
}elseif($get['type'] == "adEditAdminIDCardFront"){
    //赋值
	$id = $post['adminId'];//员工ID号
	$admin = query("admin"," adid = '$id' ");
	//判断并执行
	if(empty($id)){
	    $json['warn'] = "员工ID号为空";
	}elseif($admin['adid'] != $id){
	    $json['warn'] = "未找到该员工";
	}else{
		$FileName = "IDCardFront";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = 1000;//缩放的宽度
		$cut['MaxHeight'] = 4000;//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from admin where adid = '$id' ";//查询图片的数据库代码
		$column = "IDCardFront";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/IDCardFront/{$suiji}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = "update admin set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where adid = '$id' ";//保存图片的数据库代码
		$ImgWarn = "员工身份证正面更新成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
/********内部管理-员工管理-更新身份证背面********************************************************/
}elseif($get['type'] == "adEditAdminIDCardBack"){
    //赋值
	$id = $_POST['adminId'];//员工ID号
	$admin = query("admin"," adid = '$id' ");
	//判断并执行
	if(empty($id)){
	    $json['warn'] = "员工ID号为空";
	}elseif($admin['adid'] != $id){
	    $json['warn'] = "未找到该员工";
	}else{
		$FileName = "adIDCardBackUpload";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = 1000;//缩放的宽度
		$cut['MaxHeight'] = 4000;//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from admin where adid = '$id' ";//查询图片的数据库代码
		$column = "IDCardBack";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/IDCardBack/{$suiji}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = "update admin set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where adid = '$id' ";//保存图片的数据库代码
		$ImgWarn = "员工身份证背面更新成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
/********内部管理-员工管理-更新毕业证********************************************************/
}elseif($get['type'] == "adEditAdminDiploma"){
    //赋值
	$id = $_POST['adminId'];//员工ID号
	$admin = query("admin"," adid = '$id' ");
	//判断并执行
	if(empty($id)){
	    $json['warn'] = "员工ID号为空";
	}elseif($admin['adid'] != $id){
	    $json['warn'] = "未找到该员工";
	}else{
		$FileName = "adDiplomaUpload";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = 1000;//缩放的宽度
		$cut['MaxHeight'] = 4000;//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from admin where adid = '$id' ";//查询图片的数据库代码
		$column = "diploma";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/Diploma/{$suiji}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = "update admin set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where adid = '$id' ";//保存图片的数据库代码
		$ImgWarn = "员工毕业证更新成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
/********内部管理-员工管理-更新工资卡********************************************************/
}elseif($get['type'] == "adEditAdminBank"){
    //赋值
	$id = $_POST['adminId'];//员工ID号
	$admin = query("admin"," adid = '$id' ");
	//判断并执行
	if(empty($id)){
	    $json['warn'] = "员工ID号为空";
	}elseif($admin['adid'] != $id){
	    $json['warn'] = "未找到该员工";
	}else{
		$FileName = "adminBankIcoUpload";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = 1000;//缩放的宽度
		$cut['MaxHeight'] = 4000;//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from admin where adid = '$id' ";//查询图片的数据库代码
		$column = "bankIco";//保存图片的数据库列的名称
		$suiji = suiji();
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/BankIco/{$suiji}.jpg";//新图片保存的网站根目录位置
		$NewImgSql = "update admin set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where adid = '$id' ";//保存图片的数据库代码
		$ImgWarn = "员工身份证更新成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
/**********财务管理-收支平衡-更新付款凭证************************************/
}elseif($get['type'] == "adProfitIco"){
    //赋值
	$id = $post['adProfitId'];//收支记录ID号
	$profit = query("profit"," id = '$id' ");
	//判断并执行
	if(empty($id)){
	    $_SESSION['warn'] = "收支记录ID号为空";
	}elseif($profit['id'] != $id){
	    $_SESSION['warn'] = "未找到此收支记录";
	}elseif($profit['auditing'] == "已通过"){
	    $_SESSION['warn'] = "已通过的记录不能变更付款凭证";
	}else{
		$FileName = "adProfitIcoFile";//上传图片的表单文件域名称
		$cut['type'] = "需要缩放";//"需要裁剪"或"需要缩放"或空
		$cut['width'] = "";//裁剪宽度
		$cut['height'] = "";//裁剪高度
		$cut['NewWidth'] = 1000;//缩放的宽度
		$cut['MaxHeight'] = 4000;//缩放后图片的最大高度
		$type['name'] = "更新图像";//"更新图像"or"新增图像"
		$type['num'] = "";//新增图像时限定的图像总数
		$sql = " select * from profit where id = '$id' ";//查询图片的数据库代码
		$column = "ico";//保存图片的数据库列的名称
		$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
		$Url['NewImgUrl'] = "img/ProfitIco/".date("Ym");//新图片保存的网站根目录位置
		//如果对应文件夹不存在，则创建文件夹
		if(!file_exists($Url['root'].$Url['NewImgUrl'])){
			mkdir($Url['root'].$Url['NewImgUrl']);
		}
		$Url['NewImgUrl'] .= "/".suiji().".jpg";
		$NewImgSql = "update profit set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where id = '$id' ";//保存图片的数据库代码
		$ImgWarn = "提交成功";//图片保存成功后返回的文字内容
		UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	}
	header("Location:{$root}control/finance/adProfitMx.php?id={$id}");
	exit(0);
/********个人资料-更新头像********************************************************/
}elseif($get['type'] == "adEditAdminHead"){
	$FileName = "UploadAdHead";//上传图片的表单文件域名称
	$cut['type'] = "需要裁剪";//"需要裁剪"或"需要缩放"或空
	$cut['width'] = 400;//裁剪宽度
	$cut['height'] = 400;//裁剪高度
	$cut['NewWidth'] = "";//缩放的宽度
	$cut['MaxHeight'] = "";//缩放后图片的最大高度
	$type['name'] = "更新图像";//"更新图像"or"新增图像"
	$type['num'] = "";//新增图像时限定的图像总数
	$sql = " select * from admin where adid = '$Control[adid]' ";//查询图片的数据库代码
	$column = "touxiang";//保存图片的数据库列的名称
	$suiji = suiji();
	$Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
	$Url['NewImgUrl'] = "img/AdHead/{$suiji}.jpg";//新图片保存的网站根目录位置
	$NewImgSql = "update admin set {$column} = '$Url[NewImgUrl]',updateTime = '$time' where adid = '$Control[adid]' ";//保存图片的数据库代码
	$ImgWarn = "您的头像更新成功";//图片保存成功后返回的文字内容
	UpdateImg($FileName,$cut,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
	//添加记录
	LogText("管理员资料",$Control['adid'],"管理员{$Control['adname']}更新了自己的头像");
}
/********跳转回刚才的页面********************************************************/
header("Location:".getenv("HTTP_REFERER"));
?>
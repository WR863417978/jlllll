<?php
include "adfunction.php";
ControlRoot();
/*************客户管理-多条件模糊查询***************************/
if($get['type'] == "adSearchClient"){
    //赋值
    $CompanyName = $post['companyName'];//公司名称
    $ContactName = $post['contactName'];//客户名称
    $ContactTel = $post['contactTel'];//联系手机
    $ContactQQ = $post['contactQQ'];//联系QQ
    $ContactWx = $post['contactWx'];//微信号
    $province = $post['province'];//省份
    $city = $post['city'];//城市
    $area = $post['area'];//区域
    $AddressMx = $post['addressMx'];//详细地址
    $WorkFlow = $post['workFlow'];//跟进情况
    $Source = $post['source'];//客户来源
    $Nature = $post['nature'];//公司性质
    $x = " where 1=1 ";
    //串联查询语句
    if(!empty($CompanyName)){
        $x .= " and CompanyName like '%$CompanyName%' ";
    }
    if(!empty($ContactName)){
        $x .= " and ContactName like '%$ContactName%' ";
    }
    if(!empty($ContactTel)){
        $x .= " and ContactTel like '%$ContactTel%' ";
    }
    if(!empty($ContactQQ)){
        $x .= " and ContactQQ like '%$ContactQQ%' ";
    }
    if(!empty($ContactWx)){
        $x .= " and ContactWx like '%$ContactWx%' ";
    }
    if(empty($province)){
        $city = $area = "";
    }else{
        if(empty($city)){
            $x .= " and RegionId in ( select id from region where province = '$province' ) ";
            $area = "";
        }else{
            if(empty($area)){
                $x .= " and RegionId in ( select id from region where province = '$province' and city = '$city' ) ";
            }else{
                $x .= " and RegionId = '$area' ";
            }
        }
    }
    if(!empty($AddressMx)){
        $x .= " and AddressMx like '%$AddressMx%' ";
    }
    if(!empty($WorkFlow)){
        $x .= " and WorkFlow = '$WorkFlow' ";
    }
    if(!empty($Source)){
        $x .= " and Source = '$Source' ";
    }
    if(!empty($Nature)){
        $x .= " and Nature = '$Nature' ";
    }
    //返回
    $_SESSION['adClient'] = array("CompanyName" => $CompanyName,"ContactName" => $ContactName,"ContactTel" => $ContactTel,"ContactQQ" => $ContactQQ,"ContactWx" => $ContactWx,
        "province" => $province,"city" => $city,"area" => $area,"AddressMx" => $AddressMx,"WorkFlow" => $WorkFlow,"Source" => $Source,"Nature" => $Nature,"Sql" => $x);
    /****************附件上传*****************************/
}
elseif($get['type'] == "fileUpload") {
    //赋值
    $fileName = "file";//附件上传域名称
    $target = $post['target'];//目标对象
    $targetId = $post['targetId'];//上传对象ID号
    //判断并执行
    if(empty($target)){
        $_SESSION['warn'] = "目标对象为空";
    }elseif(empty($targetId)){
        $_SESSION['warn'] = "目标对象ID号为空";
    }elseif($target == "客户"){
        if(!power("adClient","newFile")){
            $_SESSION['warn'] = "新增客户附件权限不足";
        }else{
            $kehu = query("kehu", " khid = '$targetId' ");//客户表
            if(empty($kehu['khid'])){
                $_SESSION['warn'] = "未找到此客户";
            }
        }
        $locationUrl = root."control/adClientMx.php?id={$targetId}#fileAnchor";
    }elseif($target == "订单"){
        if(!power("adOrder","newFile")){
            $_SESSION['warn'] = "新增订单附件权限不足";
        }else{
            $order = query("buyCar", " id = '$targetId' ");//订单表
            if(empty($order['id'])){
                $_SESSION['warn'] = "未找到此订单";
            }
        }
        $locationUrl = root."control/adOrderMx.php?id={$targetId}#fileAnchor";
    }else{
        $locationUrl = getenv("HTTP_REFERER");
        $_SESSION['warn'] = "上传对象有误";
    }
    if(empty($_SESSION['warn'])){
        $tmp_name = $_FILES[$fileName]['tmp_name'];//临时文件名
        $name = $_FILES[$fileName]['name'];//附件名称
        $type = $_FILES[$fileName]['type'];//附件类型
        $typeFile = typeFile($type);
        $num = mysql_num_rows(mysql_query(" select * from file where targetId = '$targetId' and name = '$name' "));
        if(!in_array($typeFile,array("img","word","excel"))){
            $_SESSION['warn'] = "仅支持图片、word、excel文件上传";
        }elseif($num > 0){
            $_SESSION['warn'] = "此客户已经存在这个附件了";
        }else{
            $id = suiji();
            $url = "file/".date("Ym");
            if(!file_exists(ServerRoot.$url)){
                mkdir(ServerRoot.$url);
            }
            $suffix = explode('.',$name);//附件后缀
            $src = $url."/".$id.".".$suffix[1];//保存图片的根目录路径
            $bool = mysql_query("insert into file (id,adid,target,targetId,name,type,src,time) 
			values ('$id','$Control[adid]','$target','$targetId','$name','$typeFile','$src','$time')");
            if($bool){
                move_uploaded_file($tmp_name,ServerRoot.$src);
                $_SESSION['warn'] = "附件上传成功";
            }else{
                $_SESSION['warn'] = "附件上传失败";
            }
        }
    }
    header("location:{$locationUrl}");
    exit(0);
    /*************删除跟进记录***************************/
}
elseif($get['type'] == "adFollowDel"){
    //赋值
    $id = $get['id'];
    $follow = query("follow"," id = '$id' ");
    //判断
    if(empty($id)){
        $_SESSION['warn'] = "跟进记录ID号为空";
    }elseif(empty($follow['id'])){
        $_SESSION['warn'] = "未找到此跟进记录";
    }elseif($follow['target'] == "客户"){
        if(!power("adClient","delFollow")){
            $_SESSION['warn'] = "权限不足";
        }else{
            $finger = 2;
        }
    }elseif($follow['target'] == "订单"){
        if(!power("adOrder","delFollow")){
            $_SESSION['warn'] = "权限不足";
        }else{
            $finger = 2;
        }
    }else{
        $_SESSION['warn'] = "未知跟进对象";
    }
    if($finger == 2){
        $bool = mysql_query(" delete from follow where id = '$id' ");
        if($bool){
            $_SESSION['warn'] = "删除成功";
        }else{
            $_SESSION['warn'] = "删除失败";
        }
    }


    /**************商品管理-二级多条件模糊查询****************/
}
elseif($get['type'] == "searchGoodsTwo"){
    //赋值
    $one = $post['goodsOne'];//商品一级分类ID号
    $xian = $post['goodsTypeTwoShow'];//商品二级分类显示状态
    $x = " where 1=1 ";
    //判断
    if(!empty($one)){
        $x .= " and goodsTypeOneId = '$one' ";
    }
    if(!empty($xian)){
        $x .= " and xian = '$xian' ";
    }
    //返回值
    $_SESSION['goodsTwo'] = array("one" => $one,"xian" => $xian,"Sql" => $x);


    /***************商品管理-多条件模糊查询****************/
}
elseif($get['type'] == "adSearchGoods"){
    //赋值
    $name = $post['name'];//商品名称
    $goodsOneId = $post['goodsOne'];//一级分类
    $xian = $post['SearchShow'];//显示状态
    $x = " where 1=1";
    //串联查询语句
    if(!empty($name)){
        $x .= "and name like '%$name%' ";
    }
    if(!empty($goodsOneId)){
        $x .= " and goodsOneId = '$goodsOneId' ";
    }
    if(!empty($xian)){
        $x .= " and xian = '$xian' ";
    }
//返回值
    $_SESSION['SearchGoods'] = array("name"=>$name,"goodsOneId"=>$goodsOneId,"xian"=>$xian,"Sql" => $x);
    /********************商品列表图添加*******************/
}
elseif($get['type'] == "goodsIco"){
    //赋值
    $id = $post['GoodsId'];//商品id
    //判断
    $goods = query("goods"," id = '$id' ");
    if(!power("adGoods","edit")){
        $_SESSION['warn'] = "无权限";
    }elseif(empty($id)){
        $_SESSION['warn'] = "请先提交商品基本资料";
    }elseif($goods['id'] != $id){
        $_SESSION['warn'] = "未找到本商品";
    }else{
        $FileName = "GoodsIcoUpload";//上传图片的表单文件域名称
        $ImgName = $_FILES[$FileName]["tmp_name"];
        $size       = $_FILES["$FileName"]["size"];
        $ImgSize    = getimagesize($ImgName);
        $ImgWidth = $ImgSize[0];
        $ImgHeight = $ImgSize[1];
        $Rule['MaxSize'] = $size;//图像的最大容量
        $Rule['width'] = $ImgWidth;//图像要求的宽度
        $Rule['height'] = $ImgHeight;//图像要求的高度
        $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
        $type['name'] = "更新图像";//《更新图像》或《新增图像》
        $type['num'] = "";//新增图像时限定的图像总数
        $sql = " select * from goods where id = '$id' ";//查询图片的数据库代码
        $column = "ico";//保存图片的数据库列的名称
        $Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
        $Url['NewImgUrl'] = "img/goodsIco/{$id}.jpg";//新图片保存的网站根目录位置
        $NewImgSql = " update goods set {$column} = '$Url[NewImgUrl]',UpdateTime = '$time' where id = '$id' ";//保存图片的数据库代码
        $ImgWarn = "商品列表图像更新成功";//图片保存成功后返回的文字内容
        UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
    }


    /**********************新增窗厨图************************/
}
elseif($get['type'] == "goodsWin"){
    //赋值
    $files = $_FILES['GoodsWinUpload']['name'];
    $imgMove = $_FILES['GoodsWinUpload']['tmp_name'];
    $goodsId = $post['GoodsId'];
    //判断
    $goods = query("goods"," id = '$goodsId' ");
    if(!power("adGoods","edit")){
        $_SESSION['warn'] = "无权限";
    }elseif(empty($goodsId)){
        $_SESSION['warn'] = "请先提交商品基本资料";
    }elseif($goods['id'] != $goodsId){
        $_SESSION['warn'] = "未找到本商品";
    }else{
        $i = 0;
        //允许上传的格式
        $img_type = array('jpg','jpeg');
        //循环 （$_FILES是一个三维数组）
        for($i;$i<count($files);$i++){
            $file_type = substr($files[$i],strrpos($files[$i],'.')+1);
            if(!in_array($file_type,$img_type) || empty($files)) {
                $_SESSION['warn'] = "不是图片或格式不对！";
            }else{
                $id             = suiji();
                $fileName       = "{$id}$files[$i]";//图片重命名
                $Url['root']        = "../../";//为图片处理页相对于网站根目录的级差，如差一级及标注为（../）
                $Url['NewImgUrl']   = "img/goodsWin/{$fileName}";//新图片保存的网站根目录位置
                JpegSmallWidth($Url['NewImgUrl'],950);
                $bool = mysql_query("INSERT INTO goodsWin (id,goodsId,src,time) VALUE ('$id','$goods[id]','$Url[NewImgUrl]','$time') ");
                //保存图片到服务器
                move_uploaded_file($imgMove[$i],$Url['root'].$Url['NewImgUrl']);
                //返回信息
                if($bool){
                    $_SESSION['warn'] = "图片上传成功";
                }else{
                    $_SESSION['warn'] = "上传失败";
                }
            }
        }
    }
    /***************************删除橱窗图***************************/
}
elseif(!empty($get['GoodsWinDelete'])){
    $id = $_GET['GoodsWinDelete'];
    $win = query("goodsWin"," id = '$id' ");
    unlink(ServerRoot.$win['src']);
    mysql_query("delete from goodsWin where id = '$id'");
    $_SESSION['warn'] = "商品橱窗图像删除成功";

    /********************订单管理-订单模糊查询********************/
}
elseif($get['type'] == "adSearchOrder"){
    //赋值
    $order_sn = $post['order_sn'];//订单号
    $rstime = $post['rstime'];//开始时间
    $rdtime = $post['rdtime'];//结束时间
    $o_type = $post['o_type'];//订单类型
    $workFlow = $post['workFlow'];//支付状态
    $pay_type = $post['pay_type'];//支付方式
    $x = "";
    //串联查询语句
    if(!empty($order_sn)){
        $x .= " and order_sn like '%$order_sn%'";
    }
    if(!empty($rstime) && !empty($rdtime)){
        $starTime = strtotime($rstime);
        $endTime = strtotime($rdtime);
        $x .= " and UNIX_TIMESTAMP(ctime) >= '$starTime' and UNIX_TIMESTAMP(ctime) <= '$endTime'";
    }
    if(!empty($o_type)){
        $x .= " and o_type = '$o_type'";
    }
    if(!empty($workFlow)){
        $x .= " and workFlow = $workFlow-1";
    }
    if(!empty($pay_type)){
        $x .= " and pay_type = '$pay_type'";
    }
    $_SESSION['SearchOrder'] = Array(
        "order_sn"=>$order_sn,
        "o_type" => $o_type,
        "workFlow" => $workFlow,
        "rstime" => $rstime,
        "rdtime" => $rdtime,
        "Sql"=>$x,
        "pay_type"=>$pay_type,
    );
    /******************供应商管理-模糊查询*****************/
}
elseif($get['type'] == "singleOrder"){
    $orderType = $_GET['ordertype'];
    $orderTypeMap = [
        'singleNoPay' => '0' ,//代付款
        'singleToSend' => '1',//待发货
        'singleHadSend' => '2',//待收货
        'singleWaitTalk' => '4',//待评价
        'singleTradeSuccess' => '5', //已完成
        'singleApplyBackMoney' => '6',//申请仅退款,
        'singleApplyBackGoods' => '8',//申请退货,
        'singleAgreeBackGoods' =>'9', //退货中的,
        'singleHaveBackMoney' =>'7'//已退款
    ];
    if($orderType == 'singleAll'){
        $sql = '';
    }else{
        $sql = "and workFlow='{$orderTypeMap[$orderType]}'";
    }

    $_SESSION['SearchOrder'] = Array(
        "Sql"=>$sql,
    );

    header("Location:/control/adOrder.php?ordertype=".$orderType);

    die;




}
elseif($get['type'] == "adSearchSupplier"){
    //赋值
    $adname = $post['companyName'];//供应商名称
    $prname = $post['contactName'];//商品名称
    $contactTel = $post['contactTel'];//供应商联系电话
    $status = $post['status'];
    //判断
    $x = " where adDuty.department='供应商' ";
    if(!empty($adname)){
        $x .=" and adname like '%$adname%'";
    }
    if(!empty($prname)){
        $x .=" and contactName like '%$prname%'";
    }
    if(!empty($contactTel)){
        $x .=" and contactTel like '%$contactTel%'";
    }
    if(!empty($status)){
        $x .= "and status = '$status'";
    }
    $_SESSION['adSupplier'] = Array(
        "adname" => $adname,
        "contactName" => $prname,
        "contactTel" => $contactTel,
        "status" => $status,
        "Sql" => $x
    );
    /*****************客户管理-店铺LOGO********************/
}
elseif($get['type'] == "shopLogo"){
    //赋值
    $id = $post['kehuId'];//客户ID
    $check = query("kehu","khid='$id'");
    if($check['khid'] != $id){
        $_SESSION['warn'] = "未找到此客户";
    }else{
        $userId = $check['khid'];

        $FileName   = "shopLogoUpload";//上传图片的表单文件域名称
        $ImgName    = $_FILES[$FileName]["tmp_name"];
        $size       = $_FILES["$FileName"]["size"];
        $ImgSize    = getimagesize($ImgName);
        $ImgWidth   = $ImgSize[0];
        $ImgHeight  = $ImgSize[1];
        $Rule['MaxSize'] = $size;//图像的最大容量
        $Rule['width'] = $ImgWidth;//图像要求的宽度
        $Rule['height'] = $ImgHeight;//图像要求的高度
        $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
        $type['name'] = "更新图像";//《更新图像》或《新增图像》
        $type['num'] = 1;//新增图像时限定的图像总数
        $sql = " SELECT * FROM kehu WHERE khid = '$userId' ";//查询图片的数据库代码
        $column = "shopImg";//保存图片的数据库列的名称
        $Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
        $suiji = suiji();
        $Url['NewImgUrl'] = "img/shopImgs/{$suiji}.jpg";//新图片保存的网站根目录位置
        $NewImgSql = " UPDATE kehu SET shopImg='$Url[NewImgUrl]' WHERE khid = '$check[khid]'";//保存图片的数据库代码
        $ImgWarn = "店铺LOGO更新成功";//图片保存成功后返回的文字内容
        UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
    }
    /********************邀请码申诉模糊查询*******************/
}
elseif($get['type'] == "adCodeHelpSerach"){
    //赋值
    $shareName = $post['shareName'];//邀请人姓名
    $explainName = $post['explainName'];//申请人姓名
    $status = $post['status'];//申请状态
    $x = " where 1=1";
    if(!!empty($shareName)){
        $x .=" and shareName ='$shareName'";
    }
    if(!empty($explainName)){
        $x .=" and explainName ='$explainName'";
    }
    if(!empty($status)){
        $x .=" and status ='$status'";
    }
    $_SESSION['SerachCodeHelp'] = array(
        'shareName' =>$shareName,
        'explainName' =>$explainName,
        'status' =>$status,
        'Sql' =>$x
    );
    /****************优惠券管理***************/
}
elseif($get['type'] == "adSearchcoupon"){
    //赋值
    $money = $post['money'];//金额
    $year = $post['StartYear'];
    $moon = $post['StartMoon'];
    $day = $post['StartDay'];
    //串联查询条件
    $x = " where 1=1";
    if(!empty($money)){
        $x .=" AND moeny='$money'";
    }
    $_SESSION['adSearchcoupon'] = array(
        'money' => $money,
        'Sql'   => $x

    );
    /*****************需求管理-模糊查询****************/
}
elseif($get['type'] == "adSerachDemand"){
    //赋值
    $theme = $post['theme'];//需求主题
    $giftType = $post['giftType'];//礼品分类
    $status = $post['status'];//需求状态
    //串联查询
    $x = " where 1=1";
    if(!empty($theme)){
        $x .=" and theme like '%$theme%'";
    }
    if(!empty($giftType)){
        $x .=" and giftType like '%$giftType%'";
    }
    if(!empty($status)){
        $x .=" and status = '$status'";
    }
    $_SESSION['SerachDemand'] = array(
        'theme' => $theme,
        'giftType' => $giftType,
        'status' => $status,
        'Sql' => $x,
    );
    /*******************商品管理添加素材******************/
}
elseif($get['type'] == "goodsMaterial"){
    //赋值
    $id = $post['aid'];//article表id
    $GoodsId = $post['GoodsId'];//商品ID
    $check = query("goods","id='$GoodsId'");
    if(!power("adGoods","edit")){
        $_SESSION['warn'] = "无权限";
    }elseif($check['id'] != $GoodsId){
        $_SESSION['warn'] = "未找到此商品";
        header("Location:".getenv("HTTP_REFERER"));
        exit;
    }else{
        $targetId = $check['id'];
        $FileName = "GoodsMaterialUpload";//上传图片的表单文件域名称
        $ImgName = $_FILES[$FileName]["tmp_name"];
        $size = $_FILES["$FileName"]["size"];
        $ImgSize = getimagesize($ImgName);
        $ImgWidth = $ImgSize[0];
        $ImgHeight = $ImgSize[1];
        $Rule['MaxSize'] = $size;//图像的最大容量
        $Rule['width'] = $ImgWidth;//图像要求的宽度
        $Rule['height'] = $ImgHeight;//图像要求的高度
        $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
        $type['name'] = "新增图像";//《更新图像》或《新增图像》
        $type['num'] = 1;//新增图像时限定的图像总数
        $sql = " SELECT * FROM article  1=1 ";//查询图片的数据库代码
        $column = "shopImg";//保存图片的数据库列的名称
        $Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
        $suiji = suiji();
        $Url['NewImgUrl'] = "img/goodsWin/{$suiji}.jpg";//新图片保存的网站根目录位置
        $NewImgSql = " INSERT INTO article (id,target,targetId,img,updateTime,time) VALUE ('$suiji','商品素材','$targetId','$Url[NewImgUrl]','$time','$time')";//保存图片的数据库代码
        $ImgWarn = "素材图片添加成功";//图片保存成功后返回的文字内容
        UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
    }
    /**********分享管理-模糊查询***********/
}
elseif($get['type'] == "adSearchShare"){
    //赋值
    $shareName = $post['shareName'];//分享人姓名
    $x = " AND 1=1";
    if(!empty($shareName)){
        $x .= " AND name = '$shareName'";
    }
    $_SESSION['adShare'] = array(
        'name' => $shareName,
        'Sql'  => $x,
    );
    /***********商品管理-视频封面************/
}
elseif($get['type'] == "goodsPoster"){
    //赋值
    $GoodsId = $post['GoodsId'];//商品ID
    $check = query("goods","id='$GoodsId'");
    if(!power("adGoods","edit")){
        $_SESSION['warn'] = "无权限";
    }elseif($check['id'] != $GoodsId){
        $_SESSION['warn'] = "未找到此商品";
        header("Location:".getenv("HTTP_REFERER"));
        exit;
    }else{
        $goodsId = $check['id'];
        $FileName = "GoodsPosterUpload";//上传图片的表单文件域名称
        $ImgName = $_FILES[$FileName]["tmp_name"];
        $size = $_FILES["$FileName"]["size"];
        $ImgSize = getimagesize($ImgName);
        $ImgWidth = $ImgSize[0];
        $ImgHeight = $ImgSize[1];
        $Rule['MaxSize'] = $size;//图像的最大容量
        $Rule['width'] = $ImgWidth;//图像要求的宽度
        $Rule['height'] = $ImgHeight;//图像要求的高度
        $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
        $type['name'] = "更新图像";//《更新图像》或《新增图像》
        $type['num'] = 1;//新增图像时限定的图像总数
        $sql = " SELECT * FROM goods WHERE id='$goodsId'";//查询图片的数据库代码
        $column = "poster";//保存图片的数据库列的名称
        $Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
        $suiji = suiji();
        $Url['NewImgUrl'] = "img/goodsIco/{$suiji}.jpg";//新图片保存的网站根目录位置
        $NewImgSql = " UPDATE goods SET poster='$Url[NewImgUrl]',updateTime='$time' WHERE id='$goodsId'";//保存图片的数据库代码
        $ImgWarn = "商品视频封面图片上传成功";//图片保存成功后返回的文字内容
        UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
    }
    /**********商品素材-添加**********/
}
elseif($get['type'] == "MaterialImg"){
    $files = $_FILES['ImgArticle']['name'];
    $imgMove = $_FILES['ImgArticle']['tmp_name'];
    $i = 0;
    //允许上传的格式
    $img_type = array('jpg','jpeg');
    //循环 （$_FILES是一个三维数组）
    for($i;$i<count($files);$i++) {
        //print_r($imgMove[$i]);
        //截取上传图片的格式
        $file_type = substr($files[$i],strrpos($files[$i],'.')+1);
        if(!in_array($file_type,$img_type) || empty($files)) {
            $_SESSION['warn'] = "不是图片或格式不对！";
        }else{
            $target      = "宣传素材";//目标对象
            $targetId    = $post['TargetIdOne'];//商品id
            $list         = mysql_fetch_assoc(mysql_query("select max(list) from article where targetId='$targetId'"));
            //查询最大的list加上+1
            $NewList        = $list['max(list)']+1;
            $id             = suiji();
            $fileName       = "{$id}.$files[$i]";//图片重命名
            $Url['root']        = "../../";//为图片处理页相对于网站根目录的级差，如差一级及标注为（../）
            $Url['NewImgUrl']   = "img/goodsWin/{$fileName}";//新图片保存的网站根目录位置
            JpegSmallWidth($Url['NewImgUrl'],200);
            //将保存地址存入数据库
            print_r("INSERT INTO article (id,target,targetId,img,list,updateTime,time) VALUE ('$id','$target','$targetId','$Url[NewImgUrl]','$NewList','$time','$time')");
            $bool = mysql_query("INSERT INTO article (id,target,targetId,img,list,updateTime,time) VALUE ('$id','$target','$targetId','$Url[NewImgUrl]','$NewList','$time','$time')");
            //保存图片到服务器
            move_uploaded_file($imgMove[$i],$Url['root'].$Url['NewImgUrl']);
            //返回信息
            if($bool){
                $_SESSION['warn'] = "素材图片添加成功";
            }else{
                $_SESSION['warn'] = "上传失败";
            }
        }
    }
    /************规格图片************/
}
elseif($get['type'] == "goodsSku"){
    //赋值
    $sukId  = $post['sukId'];//规格id
    $suk = query("goodsSku","id='$sukId'");
    if(!power("adGoods","edit")){
        $_SESSION['warn'] = "无权限";
    }elseif(empty($suk) || $suk['id'] != $sukId){
        $_SESSION['warn'] = "请先上传规格资料";
    }else{
        $sukId = $suk['id'];
        $FileName = "sukUpload";//上传图片的表单文件域名称
        $ImgName = $_FILES[$FileName]["tmp_name"];
        $size = $_FILES["$FileName"]["size"];
        $ImgSize = getimagesize($ImgName);
        $ImgWidth = $ImgSize[0];
        $ImgHeight = $ImgSize[1];
        $Rule['MaxSize'] = $size;//图像的最大容量
        $Rule['width'] = $ImgWidth;//图像要求的宽度
        $Rule['height'] = $ImgHeight;//图像要求的高度
        $Rule['MaxHeight'] = "";//当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
        $type['name'] = "更新图像";//《更新图像》或《新增图像》
        $type['num'] = 1;//新增图像时限定的图像总数
        $sql = " SELECT * FROM goodsSku WHERE id='$suk[id]'";//查询图片的数据库代码
        $column = "img";//保存图片的数据库列的名称
        $Url['root'] = "../../";//图片处理页相对于网站根目录的级差，如差一级及标注为（../）
        $suiji = suiji();
        $Url['NewImgUrl'] = "img/skuImg/{$suiji}.jpg";//新图片保存的网站根目录位置
        $NewImgSql = " UPDATE goodsSku SET img='$Url[NewImgUrl]',updateTime='$time' WHERE id='$suk[id]'";//保存图片的数据库代码
        $ImgWarn = "商品规格图片上传成功";//图片保存成功后返回的文字内容
        UpdateCheckImg($FileName,$Rule,$type,$sql,$column,$Url,$NewImgSql,$ImgWarn);
    }
}

/********跳转回刚才的页面********************************************************/
header("Location:".getenv("HTTP_REFERER"));
?>
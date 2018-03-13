<?php
include "adfunction.php";
if ($ControlFinger == 2) {
    $json['warn'] = $ControlWarn;
    /*********客户管理-新增或更新客户*********************************/
}
elseif ($get['type'] == "adClientEdit") {
    //赋值
    $id           = $post['adClientId']; //客户id
    $kuhuname     = $post['kuhuname']; //姓名
    $tel          = $post['tel']; //联系电话
    $email        = $post['email']; //邮箱
    $viptype      = $post['viptype']; //会员类型
    $shopName     = $post['shopName']; //店铺名称
    $IdCard       = $post['IdCard']; //身份证号
    $bankName     = $post['bankName']; //银行名称
    $bankNum      = $post['bankNum']; //银行卡号
    $zipCode      = $post['zipCode']; //邮政编码
    $bankUserName = $post['bankUserName']; //持卡人姓名
    //详细地址
    $province    = $post['province']; //省份
    $city        = $post['city']; //城市
    $area        = $post['area']; //区域
    $khAddressMx = $post['khAddressMx']; //填写详细地址
    //默认地址
    $provinces = $post['provinces'];
    $citys     = $post['citys'];
    $areas     = $post['areas'];

    if(!power("adClient","edit")){
        $json['warn'] = "无权限";
    }elseif (empty($kuhuname)) {
        $json['warn'] = "请填写真实姓名";
    } elseif (preg_match($CheckTel, $tel) == 0) {
        $json['warn'] = "请填写正确的联系方式";
    } elseif (preg_match($CheckEmail, $email) == 0) {
        $json['warn'] = "请填写正确的邮箱格式";
    } elseif (empty($shopName)) {
        $json['warn'] = "请填写店铺名称";
    } elseif (empty($IdCard) || !isCreditNo($IdCard)) {
        $json['warn'] = "请填写正确身份证号";
    } elseif (empty($bankName)) {
        $json['warn'] = "请填写银行名称";
    } elseif (empty($bankNum) || preg_match($CheckInteger, $bankNum) == 0) {
        $json['warn'] = "请填写正确的银行卡号";
    } elseif (empty($zipCode) || funcZip($zipCode) != true) {
        $json['warn'] = "请填写正确的邮政编码";
    } elseif (empty($bankUserName)) {
        $json['warn'] = "请填写持卡人姓名";
    } elseif (empty($province) || empty($city) || empty($area)) {
        $json['warn'] = "请选择所属区域";
    } elseif (empty($khAddressMx)) {
        $json['warn'] = "请填写详细地址";
    } elseif (empty($provinces) || empty($citys) || empty($areas)) {
        $json['warn'] = "请选择默认地址";
    } else {
        if (!empty($id)) {
//更新客户
            $check = query("kehu", "khid=$id");
            if ($check['khid'] != $id) {
                $json['warn'] = "未找到此客户";
            } else {
                $bool = mysql_query("update kehu set name='$kuhuname',tel='$tel',email='$email',shopName='$shopName',IdCard='$IdCard',bankName='$bankName',bankUserName='$bankUserName',regionId='$area',addressMx='$khAddressMx',zipCode='$zipCode',address='$areas',type='$viptype',updateTime='$time' WHERE khid='$id'");
                if ($bool) {
                    $_SESSION['warn'] = "客户基本信息更新成功";
                    $json['warn']     = 2;
                    $json['href']     = root . "control/adClientMx.php?id={$id}";
                } else {
                    $json['warn'] = "客户基本信息更新失败";
                }
            }
        } else {
            $json['warn'] = "客户基本信息更新失败1";
        }
    }
    /************批量处理列表记录（需要管理员登录密码）********************************************/
}
elseif (isset($post['PadWarnType'])) {
    //赋值
    $type = $post['PadWarnType']; //执行指令
    $pas  = $post['Password']; //密码
    $x    = 0;
    //判断
    if (empty($type)) {
        $json['warn'] = "执行指令为空";
    } elseif (empty($pas)) {
        $json['warn'] = "请输入管理员登录密码";
    } elseif (md5($pas) != $Control['adpas']) {
        $json['warn'] = "管理员登录密码输入错误";
        //删除文字
    } elseif ($type == "deleteWord") {
        $Array = $post['WordList'];
        if (!power("adword", "del")) {
            $json['warn'] = "您没有删除网站框架文字的权限";
        } elseif (empty($Array)) {
            $json['warn'] = "您一条文字都没有选择呢";
        } else {
            foreach ($Array as $id) {
                $website = query("website", " webid = '$id'");
                if ($website['del'] != "否") {
                    //删除文字基本参数
                    mysql_query("delete from website where webid = '$id'");
                    //添加记录
                    LogText("网站文字管理", $Control['adid'], "管理员{$Control['adname']}删除了网站文字内容“{$website['name']}”");
                    $x++;
                }
            }
            $_SESSION['warn'] = "删除了{$x}条文字信息";
            $json['warn']     = 2;
        }
        //删除提现数据
    } elseif ($type == "deleteWithdraw"){
        $cname = ",";
        $idArray = $post['WithdrawList'];//id 数组
        if(!power("adadWithdraw", "del")){
            $json['warn'] = "您没有删除提现数据的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query("DELETE FROM withdraw WHERE id='$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("提现管理", $Control['adid'], "管理员{$Control['adname']}成功删除了提现数据（共{$x}条）（提现id：{$cname}）");
        }
        //删除需求
    }elseif($type == "deleteDemand"){
        $cname = ",";
        $idArray = $post['DemandList'];//id 数组
        if(!power("adDemand", "del")){
            $json['warn'] = "您没有删除需求数据的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query("DELETE FROM demand WHERE id='$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("需求管理", $Control['adid'], "管理员{$Control['adname']}成功删除了需求数据（共{$x}条）（需求id：{$cname}）");
        }
        //删除优惠券
    }elseif($type == "deleteCoupon"){
        $cname = ",";
        $idArray = $post['couponList'];//id 数组
        if(!power("adCoupon", "del")){
            $json['warn'] = "您没有删除优惠券的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query("DELETE FROM coupon WHERE id='$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("优惠券管理", $Control['adid'], "管理员{$Control['adname']}成功删除了优惠券（共{$x}条）（优惠券id：{$cname}）");
        }
        //删除申请码
    }elseif($type == "deleteCode"){
        $cname = ",";
        $idArray = $post['CodeList'];//id 数组
        if(!power("adCodeHelp", "del")){
            $json['warn'] = "您没有删除优惠券的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query("DELETE FROM codeExplain WHERE id='$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("申请码管理", $Control['adid'], "管理员{$Control['adname']}成功删除了申请码（共{$x}条）（申请码id：{$cname}）");
        }
        //删除供应商
    }elseif($type == "deleteSupplier"){
        $cname = ",";
        $idArray = $post['SupplierList'];//id 数组
        if(!power("adSupplier", "del")){
            $json['warn'] = "您没有删除供应商的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query("DELETE FROM admin WHERE adid='$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("供应商管理", $Control['adid'], "管理员{$Control['adname']}成功删除了供应商（共{$x}条）（供应商id：{$cname}）");
        }
        //删除商品
    }elseif($type == "deleteGoods"){
        $cname = ",";
        $idArray = $post['goodsList'];//id 数组
        if(!power("adGoods", "del")){
            $json['warn'] = "您没有删除商品的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $goods = query("goods", " id = '$val' ");
                if(!empty($goods)){
                    mysql_query(" delete from goodsSku where goodsId = '{$goods['id']}' ");
                    //删除商品橱窗图
                    mysql_query("delete from goodsWin where goodsId = '{$goods['id']}' ");
                    //删除商品基本参数
                    mysql_query(" delete from goods where id = '{$goods['id']}' ");
                    //删除商品优惠券
                    mysql_query("delete from coupon where goodsId = '{$goods['id']}' ");
                    $cname .= $goods['name'];
                    $json['warn'] = 2;
                    $x++;

                }else{
                    $json['warn'] = "未找到该商品";
                }
            }
            LogText("商品管理", $Control['adid'], "管理员{$Control['adname']}成功删除了商品（共{$x}条）（商品名称：{$cname}）");
        }
        //删除一级分类
    }elseif($type == "deleteGoodsOne"){
        $cname = ",";
        $idArray = $post['GoodsOneList'];//id 数组
        if(!power("adGoods", "del")){
            $json['warn'] = "您没有删除一级分类的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach ($Array as $id) {
                $goodsTypeOne    = query("goodsOne", " id = '$id' ");
                $goodsTypeTwoNum = mysql_num_rows(mysql_query(" select * from goodsTwo where goodsTypeOneId = '$id' "));
                if ($goodsTypeTwoNum != 0) {
                    if (empty($warn)) {
                        $a = "";
                    } else {
                        $a = "，";
                    }
                    $warn .= $a . "“{$goodsTypeOne['name']}”";
                } else {
                    mysql_query("delete from goodsOne where id = '$id'");
                    //添加日志
                    LogText("商品一级分类管理", $Control['adid'], "管理员{$Control['adname']}删除了商品一级分类（{$goodsTypeOne['name']}）");
                    $x++;
                }
            }
            if (!empty($warn)) {
                $wa = "如下一级分类旗下存在二级分类：" . $warn;
            }
            $_SESSION['warn'] = "删除了{$x}个商品一级分类。" . $wa;
            $json['warn']     = 2;
        }
        //删除二级分类
    }elseif($type == "deleteGoodsTwo"){
        $cname = ",";
        $idArray = $post['adGoodsTypeTwoList'];//id 数组
        if(!power("adGoods", "del")){
            $json['warn'] = "您没有删除二级分类的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query(" delete from goodsTwo where id = '$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("商品管理-二级分类", $Control['adid'], "管理员{$Control['adname']}成功删除了二级分类（共{$x}条）（分类id：{$val}）");
        }
        //删除客户
    }elseif($type == "deleteClient"){
        $cname = ",";
        $idArray = $post['ClientList'];//id 数组
        if(!power("adGoods", "del")){
            $json['warn'] = "您没有删除客户的权限的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $bool = mysql_query(" delete from kehu where khid = '$val'");
                if($bool){
                    $_SESSION['warn'] = "删除成功";
                    $json['warn'] = 2;
                    $x++;
                    $cname .= $val;
                }else{
                    $json['warn'] = "删除失败";
                }
            }
            LogText("商品管理-二级分类", $Control['adid'], "管理员{$Control['adname']}成功删除了二级分类（共{$x}条）（分类id：{$val}）");
        }
        //批量提现-通过
    }elseif($type == "upateDraws"){
        $z = 0;
        $t = 0;
        $s = 0;
        $idArray = $post['WithdrawList'];//id 数组
        if(!power("adWithdraw", "edit")){
            $json['warn'] = "您没有修改提现状态的权限";
        }elseif(empty($idArray)){
            $json['warn'] = "未选中对象";
        }else{
            foreach($idArray as $val){
                $check = query("withdraw","id='$val'");
                if($check['workFlow'] == "已支付"){
                    $z++;
                    $json['warn'] = "已支付提现申请，不能再次审核";

                }else{
                    $bool = mysql_query("UPDATE withdraw SET workFlow='已通过',actionId='$Control[adid]',updateTime='$time' WHERE id='$val'");
                    if($bool){
                        $_SESSION['warn'] = "批量通过审核成功";
                        $json['warn'] = 2;
                        $t++;
                    }else{
                        $s++;
                        $json['warn'] = "批量通过审核失败";
                    }
                }
            }
            LogText("提现管理-批量通过审核", $Control['adid'], "管理员{$Control['adname']}批准通过提现申请（共{$t}条）（已支付状态共{$z}条，不能修改状态）（失败共{$s}条）");
        }
    }else {
        $json['warn'] = "未知执行指令";
    }
    /********************商品管理-一级分类新增 、更新*********************/
}
elseif ($get['type'] == "adGoodsOneMx") {
    //赋值
    $goodOneId = $post['goodsOneId']; //一级分类id
    $list      = $post['list']; //序号
    $name      = $post['name']; //分类名称
    $xian      = $post['xian']; //显示状态
    //判断
    if(!power("adGoods","edit")){
        $json['warn'] = "无权限";
    }elseif (empty($name)) {
        $json['warn'] = "分类名称不能为空";
    } elseif (empty($list)) {
        $json['warn'] = "请输入排序号";
    } elseif (preg_match($CheckInteger, $list) == 0) {
        $json['warn'] = "排序号必须是正整数";
    } elseif (empty($xian)) {
        $json['warn'] = "请选择前端状态";
    } elseif (empty($goodOneId)) {
//新增
        if (Repeat(" goodsOne where name = '$name' ")) {
            $json['warn'] = "一级分类名称存在重复";
        }else{
            $id   = suiji();
            $bool = mysql_query("insert into goodsOne (id,name,list,xian,updateTime,time)
VALUE ('$id','$name','$list','$xian','$time','$time')");
            if ($bool) {
                $_SESSION['warn'] = "商品一级分类新增成功";
                $json['href']     = root . "control/adGoodsOneMx.php?id={$id}";
                $json['warn']     = 2;
            } else {
                $_SESSION['warn'] = "商品一级分类新增失败";
                $json['warn']     = 0;
            }
        }
    } else {
//更新
        $bool = mysql_query("update goodsOne set
          list = '$list',
          name = '$name',
          xian = '$xian',
          updateTime = '$time'where id ='$goodOneId'
        ");
        if ($bool) {
            $_SESSION['warn'] = "商品一级分类更新成功";
            $json['warn']     = 2;
            $json['href']     = root . "control/adGoodsOneMx.php?id={$goodOneId}";
        } else {
            $json['warn'] = "一级分类更新失败";
        }

    }
    /********************商品管理-二级分类新增 、更新*********************/
}
elseif ($get['type'] == "GoodsTwoMx") {
    //赋值
    $id        = $post['goodsTwoId'];
    $goodOneId = $post['goodsOne']; //一级分类id
    $name      = $post['name']; //二级分类名称
    $list      = $post['list']; //排序
    $xian      = $post['xian']; // 前端显示状态
    if(!power("adGoods","edit")){
        $json['warn'] = "无权限";
    }elseif (empty($goodOneId)) {
        $json['warn'] = "请选择一级分类";
    } elseif (empty($name)) {
        $json['warn'] = "请填写二级分类名称";
    } elseif (empty($list)) {
        $json['warn'] = "请填写序号";
    } elseif (empty($xian)) {
        $json['warn'] = "请选择前端状态";
    } elseif (empty($id)) {
//增加
        if (Repeat(" goodsTwo where name = '$name' ")) {
            $json['warn'] = "二级分类名称存在重复";
        }else{
            $id   = suiji(); //创建二级分类id
            $bool = mysql_query("insert into goodsTwo (id,goodsTypeOneId,name,list,xian,updateTime,time)
VALUE ('$id','$goodOneId','$name','$list','$xian','$time','$time')");
            if ($bool) {
                $_SESSION['warn'] = "商品二级分类新增成功";
                $json['href']     = root . "control/adGoodsTwoMx.php?id=".$id;
                $json['warn']     = 2;
            } else {
                $_SESSION['warn'] = "商品二级分类新增失败";
                $json['warn']     = 0;
            }
        }
    } else {

        $bool = mysql_query("update goodsTwo set
          goodsTypeOneId ='$goodOneId',
          list = '$list',
          name = '$name',
          xian = '$xian',
          updateTime = '$time' where id ='$id'
        ");
        if ($bool) {
            $_SESSION['warn'] = "商品二级分类更新成功";
            $json['warn']     = 2;
            $json['href']     = root . "control/adGoodsTwoMx.php?id=" . $id;
        } else {
            $json['warn'] = "二级分类更新失败";
        }

    }
    /******************一级分类调用二级*******************/
}
elseif ($get['type'] == "queryOne") {
    $one         = $post['goodsTypeOneIdGetTwoId'];
    $json['two'] = IdOption(" goodsTwo where goodsTypeOneId = '$one' and xian = '显示' order by list ", "id", "name", "--二级分类--", "");

    /**************************商品管理-商品基本资料添加-更新**************************/
}
elseif ($get['type'] == "upGoods") {
    //赋值
    $id             = $post['goodsid']; //商品id
    $goodsName      = $post['goodsName']; //商品名称
    $goodsTypeOneId = $post['goodsOneId']; //一级分类id
    $goodsTypeTwoId = $post['goodsTypeTwoId']; //二级分类id
    $summary        = $post['summary']; //商品摘要
    $promotion      = $post['promotion']; //促销信息
    $parameter      = $post['parameter']; //产品详细参数
    $price          = $post['price']; //零售价
    //$VipPrice       = $post['VipPrice']; //会员零售价
    $priceMarket    = $post['priceMarket']; //批发价
    //$VipPriceMarket = $post['VipPriceMarket']; //会员批发价
    $customMade     = $post['customMade']; //是否定制
    $isIndex        = $post['isIndex']; //是否首页推荐
    //$scareBuying = $post['scareBuying'];//是否为抢购
    //$sellingToday = $post['sellingToday'];//是否为热销商品
    //$publicGood = $post['publicGood'];//是否为公益
    $list = $post['GoodsList']; //商品排序
    $xian = $post['GoodsShow']; //前端显示状态
    $taxPoint = $post['taxPoint'];//发票税点
    $recommendArea  = $post['recommendArea'];
    //判断
    if(!power("adGoods","edit")){
        $json['warn'] = "您没有修改商品信息的权限";
    }elseif (empty($goodsName)) {
        $json['warn'] = "请填写商品名称";
    } elseif (empty($goodsTypeOneId)) {
        $json['warn'] = "请选择一级分类";
    } elseif (empty($summary)) {
        $json['warn'] = "请填写商品摘要";
    } elseif (empty($parameter)) {
        $json['warn'] = "请填写商品详细信息";
//    } elseif (empty($price)) {
//        $json['warn'] = "请填写商品零售价";
//    } elseif (preg_match($CheckPrice, $price) == 0) {
//        $json['warn'] = "商品单价格式不正确";
//    } elseif (empty($priceMarket)) {
//        $json['warn'] = "请填写商品批发价";
//    } elseif (preg_match($CheckPrice, $priceMarket) == 0) {
//        $json['warn'] = "商品批发价格式不正确";
    } elseif (empty($list)) {
        $json['warn'] = "请填写商品排序";
    } elseif (preg_match($CheckInteger, $list) == 0) {
        $json['warn'] = "排序号必须为正整数";
    } elseif (empty($id)) {
        //采货员细分权限
        if(!power("adGoods","xian")){
            $otherxian = "隐藏";
        }else{
            $otherxian = $xian;
        }
        //添加商品
        //新增三个字段，会员零售价，会员批发价，物流方式（sql语句内还未添加相应字段）
        $suiji = suiji(); //生成商品id
        $bool  = mysql_query(" insert into goods (id,name,goodsOneId,goodsTwoId,summary,promotion,parameter,price,priceMarket,list,xian,time,updateTime,customMade,isIndex,recommendArea,taxPoint)
        values ('$suiji','$goodsName','$goodsTypeOneId','$goodsTypeTwoId','$summary','$promotion','$parameter','$price','$priceMarket','$list','$otherxian','$time','$time','$customMade','$isIndex','$recommendArea','$taxPoint') ");
        if ($bool) {
            $_SESSION['warn'] = "新建商品成功";
            $json['warn']     = 2;
            $json['href']     = "{$adroot}adGoodsMx.php?id={$suiji}";
        } else {
            $json['warn'] = "新建商品失败";
        }
    } else {
        if(!power("adGoods","xian")){
            $otherxian = "隐藏";
        }else{
            $otherxian = $xian;
        }
        //商品更新
        $goods = query("goods", " id = '$id' ");
        if ($goods['id'] != $id) {
            $json['warn'] = "未找到该商品";
        } else {
            $bool = mysql_query(" update goods set
            goodsOneId = '$goodsTypeOneId',
            goodsTwoId = '$goodsTypeTwoId',
            name = '$goodsName',
            summary = '$summary',
            promotion = '$promotion',
            parameter = '$parameter',
            price = '$price',
            priceMarket = '$priceMarket',
            list = '$list',
            xian = '$otherxian',
            customMade = '$customMade',
            recommendArea = '$recommendArea',
            taxPoint = '$taxPoint',
            isIndex = '$isIndex',
            UpdateTime = '$time' where id = '$id'");
            if ($bool) {
                $_SESSION['warn'] = "商品更新成功";
                $json['warn']     = 2;
                $json['href']     = "{$adroot}adGoodsMx.php?id={$id}";
            } else {
                $json['warn'] = "商品更新失败";
            }
        }
    }

//商品规格编辑
}
elseif ($get['type'] == "updateSkuone") {
    //编辑商品规格弹出窗显示该商品的商品规格
    $id      = $post['skuId']; //规格id
    $goodsku = query("goodsSku", "id = '$id'");

    $json['warn'] = array(
        'skuNum'        => $goodsku['skuNum'],
        'twoName'        => $goodsku['twoName'],
        'goodsId'       => $goodsku['goodsId'],
        'name'          => $goodsku['name'],
        'number'        => $goodsku['number'],
        'price'         => $goodsku['price'],
        'retailPrice'   => $goodsku['retailPrice'],
        'thePatch'      => $goodsku['thePatch'],
        'integral'      => $goodsku['integral'],
        'shippingPlace' => $goodsku['shippingPlace'],
        'clerk'         => $goodsku['clerk'],
        'skuSeat'       => $goodsku['skuSeat'],
        'factory'       => $goodsku['factory'],
        'type'          => $goodsku['type'],
        'cost'          => $goodsku['cost'],
        'free'          => $goodsku['free'],
        'shippingFree'  => $goodsku['shippingFree'],
        'endPatch'      => $goodsku['endPatch'],
        'profit'        => $goodsku['profit'],
        'grossProfit'   => $goodsku['grossProfit'],
        'pricing'       => $goodsku['pricing'],
        'corresponding' => $goodsku['corresponding'],
        'type'          => $goodsku['type'],
        'weight'        => $goodsku['weight'],
        'skuUnit'       => $goodsku['skuUnit']
    );
    /***********************商品管理-规格添加，修改***********************/
}
elseif ($get['type'] == "updateSku") {
    //赋值
    $sid           = $post['specId']; //规格id
    $goodsid       = $post['GoodsId']; //商品id
    $specName      = $post['specName']; //规格名称
    $twoName       = $post['twoName']; //二级规格名称
    $skuNum        = $post['skuNum']; //货号
    $price         = $post['price']; //零售价
    $retailPrice    = $post['retailPrice']; //批发价
    $thePatch       = $post['thePatch']; //起批量
    $endPatch       = $post['endPatch']; //截止起批量
    $number         = $post['number']; //库存
    $profit         = $post['profit'];//利润
    $integral       = $post['integral']; //所需兑换积分
    $skuSeat        = $post['skuSeat']; //发货地
    $shippingPlace  = $post['shippingPlace']; //发货信息
    $clerk          = $Control['adname']; //采货员
    $factory       = $post['factory']; //厂家信息
    $typeprice     = $post['typeprice'];//定制价格
    $cost          = $post['cost'];//成本价
    $free          = $post['free'];//手续费
    $shippingFree  = $post['shippingFree'];//运费
    $weight         = $post['weight'];//重量
    $skuUnit        = $post['skuUnit'];//单位
    //2017.12.28添加字段（财务权限）
    $pricing       = $post['pricing'];//定价
    $grossProfit   = $post['grossProfit'];//对应毛利率
    $corresponding  = $post['corresponding'];//拨比毛利率
    $goods         = query("goods", "id='$goodsid'");
    $skuType = query("goodsSku","goodsId='$goods[id]' and type='默认'");
    if(!power("adGoods","edit")){
        $json['warn'] = "您没有修改商品信息的权限";
    }elseif (empty($specName)) {
        $json['warn'] = "请填写规格名称";
    } elseif (empty($skuNum)) {
        $json['warn'] = "请填写货号";
    }elseif(empty($twoName)){
        $json['warn'] = "请填写二级规格名称";
    }elseif (empty($typeprice)) {
        $json['warn'] = "请选择规格类型";
    } elseif (preg_match($CheckPrice,$price) == 0) {
        $json['warn'] = "请填写零售价";
    } elseif (preg_match($CheckPrice,$retailPrice) == 0) {
        $json['warn'] = "请填写批发价";
    } elseif (preg_match($CheckPrice,$cost) == 0) {
        $json['warn'] = "请填写成本价";
    } elseif (preg_match($CheckPrice,$free) == 0) {
        $json['warn'] = "请填写手续费";
    } elseif (empty($weight)) {
        $json['warn'] = "请填写规格重量";
    } elseif (empty($thePatch)) {
        $json['warn'] = "请填写起批量";
    } elseif(empty($endPatch)){
        $json['warn'] = "请填写截止起批量";
    }elseif($typeprice == "分类价格" && $thePatch > $endPatch){
        $json['warn'] = "起批量不能大于截止起批量";
    }elseif(empty($profit)){
        $json['warn'] = "请填写利润";
    } elseif (empty($skuSeat)) {
        $json['warn'] = "请填写发货地";
    } elseif (empty($shippingPlace)) {
        $json['warn'] = "请填写发货信息";
    }elseif(empty($skuUnit)){
        $json['warn'] = "请填写数量单位";
    } else {
        if (empty($goods)) {
            $json['warn'] = "请先添加商品信息";
        }else {
            //添加
            if (empty($sid)){
                $id   = suiji();
                $bool = mysql_query("INSERT INTO goodsSku (id,goodsId,name,skuNum,price,retailPrice,thePatch,number,integral,shippingPlace,clerk,factory,updateTime,time,skuSeat,type,cost,free,shippingFree,twoName,endPatch,profit,grossProfit,pricing,corresponding,weight,skuUnit) VALUE ('$id','$goodsid','$specName','$skuNum','$price','$retailPrice','$thePatch','$number','$integral','$shippingPlace','$clerk','$factory','$time','$time','$skuSeat','$typeprice','$cost','$free','$shippingFree','$twoName','$endPatch','$profit','$grossProfit','$pricing','$corresponding','$weight','$skuUnit')");
                if ($bool) {
                    $_SESSION['warn'] = "商品规格添加成功";
                    $json['warn']     = 2;
                    $json['href']     = root."control/adGoodsMx.php?id={$goodsid}";
                } else {
                    $json['warn'] = "商品规格添加失败";
                }
            } else {
//修改
                $check = query("goodsSku", "id='$sid'");
                if ($check['id'] != $sid) {
                    $json['warn'] = "未找到此规格";
                } else {
                    $bool = mysql_query("UPDATE goodsSku SET name='$specName',skuNum='$skuNum',price='$price',retailPrice='$retailPrice',thePatch='$thePatch',number='$number',integral='$integral',shippingPlace='$shippingPlace',clerk='$clerk',factory='$factory',skuSeat='$skuSeat',type='$typeprice',cost='$cost',free='$free',shippingFree='$shippingFree',twoName='$twoName',endPatch='$endPatch',profit='$profit',pricing='$pricing',grossProfit='$grossProfit',corresponding='$corresponding',weight='$weight',skuUnit='$skuUnit' WHERE id = '$sid'");
                }
                if ($bool) {
                    $_SESSION['warn'] = "商品规格修改成功";
                    $json['warn']     = 2;
                    $json['href']     = root . "control/adGoodsMx.php?id={$goodsid}";
                } else {
                    $json['warn'] = "商品规格修改失败";
                }
            }
        }
    }
    /************删除商品规格**************/
}
elseif ($get['type'] == "deleteSpecId") {
    $deleteSpecId = $post['deleteSpecId']; //商品规格id
    $num          = mysql_fetch_array(mysql_query("select number from goodsSku where id = '$deleteSpecId'"));
    if(!power("adGoods","del")){
        $json['warn'] = "您没有修改商品信息的权限";
    }elseif ($num['number'] > 0) {
        $json['warn'] = "该商品规格下还有库存";
    } else {
        mysql_query("delete  from goodsSku where id = '$deleteSpecId' ");
        $_SESSION['warn'] = "商品规格删除成功";
        $json['warn']     = 2;
    }
    /**********查看物流信息****************/
}
elseif($get['type'] == "lookOrder"){
    $id = $post['id'];
    $order = query("buyCar","id='$id'");
    $json['warn'] = array(
        'id' => $order['id'],
        'logisticsNum'  => $order['logisticsNum'],
        'logisticsName' => $order['logisticsName'],
        'workFlow'      => $order['workFlow'],
    );
    /****************订单管理-备注添加-修改***************/
}
elseif ($get['type'] == "OrderText") {
    //赋值
    $id           = $post['orderId']; //订单id
    $logisticsNum = $post['logisticsNum'];//物流单号
    $logisticsName = $post['logisticsName'];//物流公司
    $workFlow         = $post['workFlow']; //备注
    $buyCar = query("buyCar", "id = '$id' ");
    if ($buyCar['id'] != $id) {
        $json['warn'] = "未找到此订单";
    }elseif(empty($workFlow)){
        $json['warn'] = "请选择订单状态";
    } else {
        $bool = mysql_query("update buyCar set workFlow='$workFlow',logisticsNum='$logisticsNum',logisticsName='$logisticsName',updateTime='$time' where id='$id'");
        if ($bool) {
            $_SESSION['warn'] = "订单信息修改成功";
            $json['warn'] = 2;
            $json['href'] = root."control/adOrder.php";
        } else {
            $json['warn'] = "订单信息修改失败";
        }
    }
    /****************订单管理-添加视频地址***************/
}
elseif ($get['type'] == "goodsVideo") {
    $id           = $post['goodsId']; //商品id
    $videoAddress = $post['videoAddress']; //视频地址
    $goods        = query("goods", "id = '$id' ");
    if ($goods['id'] != $id || empty($goods)) {
        $json['warn'] = "请先添加商品资料";
    } else {
        $bool = mysql_query("update goods set videoUrl='$videoAddress',updateTime='$time' where id='$id'");
        if ($bool) {
            $_SESSION['warn'] = "商品视频地址添加或修改成功";
            $json['warn']     = 2;
            $json['href']     = root . "control/adGoodsTwoMx.php?id={$id}";
        } else {
            $json['warn'] = "商品视频地址添加或修改失败";
        }
    }
    /*****************邀请码申述管理*****************/
}
elseif ($get['type'] == "codeHelp") {
    //赋值
    $codeId   = $post['codeId']; //申述表id
    $status   = $post['status']; //审核状态
    $actionId = $post['actionId']; //审批人id
    //判断
    $check = query("codeExplain", "id='$codeId'");
    if ($check['id'] != $codeId) {
        $_SESSION['warn'] = "未找到此申请";
        $json['warn']     = 2;
        $json['href']     = root . "control/adCodeHelp.php";
    } elseif (empty($status)) {
        $json['warn'] = "请选择审核状态";
    } else {
        $bool = mysql_query("UPDATE codeExplain SET status='$status',actionId='$actionId',updateTime='$time' WHERE id='$codeId'");
        if ($bool) {
            $_SESSION['warn'] = "审核成功";
            $json['warn']     = 2;
            $json['href']     = root . "control/adCodeHelp.php";
        } else {
            $json['warn'] = "审核失败";
        }
    }
}
elseif ($get['type'] == "coupon") {
    //赋值
    $couponId    = $post['couponId']; //优惠券id
    $goodsId     = $post['goodsId'];//所属商品id
    $moeny       = $post['moeny']; //金额
    $amountMoeny = $post['amountMoeny']; //满足条件
    $StartYear   = $post['StartYear']; //开始年份
    $StartMoon   = $post['StartMoon']; //月份
    $StartDay    = $post['StartDay']; //日期
    $endYear     = $post['endYear']; //结束年份
    $endMoon     = $post['endMoon']; //结束月份
    $endDay      = $post['endDay']; //结束日期
    $num         = $post['num']; //优惠券数量
    //判断
    if (empty($moeny)) {
        $json['warn'] = "请填写优惠金额";
    } elseif (empty($amountMoeny)) {
        $json['warn'] = "请填写满足条件金额";
    } elseif (empty($StartYear)) {
        $json['warn'] = "请填写开始年份";
    } elseif (empty($StartMoon)) {
        $json['warn'] = "请填写开始月份";
    } elseif (empty($StartDay)) {
        $json['warn'] = "请填写开始日期";
    } elseif (empty($endYear)) {
        $json['warn'] = "请填写结束年份";
    } elseif (empty($endMoon)) {
        $json['warn'] = "请填写结束月份";
    } elseif (empty($endDay)) {
        $json['warn'] = "请填写结束日期";
    } elseif (empty($num)) {
        $json['warn'] = "请填写优惠券数量";
    } else {
        $starTime = $StartYear.'-' . $StartMoon.'-' . $StartDay;
        $endTime  = $endYear.'-' . $endMoon.'-' . $endDay;
        if (empty($couponId)) {
            //新建优惠券
            $id = suiji();
            $bool = mysql_query("INSERT  INTO coupon (id,moeny,amountMoeny,starTime,endTime,num,time,goodsId) VALUE ('$id','$moeny','$amountMoeny','$starTime','$endTime','$num','$time','$goodsId')");
            if ($bool) {
                $_SESSION['warn'] = "优惠券新增成功";
                $json['warn'] = 2;
                $json['href'] =  root . "control/adCouponMx.php?id={$id}";
            }else{
                $json['warn'] = "优惠券新增失败";
            }
        } else {
            //修改优惠券
            $check = query("coupon","id='$couponId'");
            if($check['id'] != $couponId){
                $_SESSION['warn'] = "未找到此优惠券的信息";
                $json['warn'] = 2;
                $json['href'] =  root . "control/adCoupon.php";
            }else{
                $bool = mysql_query("UPDATE coupon SET moeny='$moeny',amountMoeny='$amountMoeny',starTime='$starTime',endTime='$endTime',num='$num',goodsId='$goodsId' WHERE id = '$couponId'");
                if ($bool) {
                    $_SESSION['warn'] = "优惠券更新成功";
                    $json['warn'] = 2;
                    $json['href'] =  root . "control/adCouponMx.php?id={$couponId}";
                }else{
                    $json['warn'] = "优惠券更新失败";
                }
            }
        }
    }
    /****************商品管理-评论显示*****************/
}
elseif($get['type'] == "talkmx"){
    //赋值
    $id = $post['id'];
    $talk = query("talk","id='$id'");
    $json['warn'] = array(
        'word' => $talk['word'],
        'xian' => $talk['xian'],
        'grade' => $talk['grade'],
    );
    $sql = "SELECT * FROM talkImg WHERE talkId='$id'";
    $res = mysql_query($sql);
    $num = mysql_num_rows($res);
    if($num > 0){
        while ($val = mysql_fetch_assoc($res)){
            $json['talkimg'] .=ProveImgShow("$root$val[img]","暂无图片")."<span class='deleteimg' onclick='delImg($val[id])'>×</span>";
        }
    }else{
        $json['talkimg'] .= "<span>暂无图片</span>";
    }
    /****************商品管理-评论修改*****************/
}
elseif($get['type'] == "updateTalk"){
    //赋值
    $goodsid = $post['goodsid'];//商品id
    $id = $post['talkId'];//评论id
    $grade = $post['grade'];//评分
    $word = $post['word'];//评论内容
    $xian = $post['xian'];//显示
    if(!power("talk","edit")){
        $json['warn'] = "您没有编辑评论的权限";
    }elseif(empty($grade) || preg_match($CheckInteger, $grade) == 0 || $grade > 5){
        $json['warn'] = "评分格式不正确";
    }elseif(empty($word)){
        $json['warn'] = "评论内容不能为空";
    }else{
        $bool = mysql_query("UPDATE talk SET word='$word',grade='$grade',xian='$xian' WHERE id='$id'");
        if($bool){
            $_SESSION['warn'] = "评论修改成功";
            $json['warn'] = 2;
            $json['href'] = root."control/adGoodsMx.php?id={$goodsid}";
        }else{
            $json['warn'] = "商品评论修改失败";
        }
    }
    /****************删除评论图片**************/
}
elseif($get['type'] == "DeleteImg"){
    $imId = $post['imId'];
    if(!power("talk","del") || !power("adGoods","del")){
        $json['warn'] = "您没有编辑评论的权限";
    }elseif(!empty($imId)){
        $booltalk=mysql_query("DELETE FROM `talkImg` WHERE id = '$imId'");
        $boolWin = mysql_query("DELETE FROM `goodsWin` WHERE id = '$imId'");
        if($booltalk || $boolWin){
            $json['warn'] = "图片删除成功";
        }else{
            $json['warn'] = "图片删除失败";
        }
    }else{
        $json['warn'] = "未知错误";
    }
    /*****************发放优惠券****************/
}
elseif($get['type'] == "CouponSend"){
    //赋值
    $kehuid     = $post['kehuid'];//客户id
    $couponid   = $post['couponid'];//优惠券id
    $checkCou   = query("coupon","id='$couponid'");
    $checkKehu  = query("kehu","khid='$kehuid'");
    $checkcunzai = query("kehuCoupon","khid='$kehuid' AND couponId='$couponid'");
    $json['cunzai'] = $checkcunzai;
    if(!power("adCoupon","edit")){
        $json['warn'] = "无权限";
    }elseif($checkCou['id'] != $couponid){
        $json['warn'] = "未找到该优惠信息";
    }elseif($checkCou['num'] == 0){
        $json['warn'] = "优惠券数量不足";
    }elseif($checkKehu['khid'] != $kehuid || empty($checkKehu['name'])){
        $json['warn'] = "该客户不存在或者该客户未实名制";
    }elseif(!empty($checkcunzai)){
        $json['warn'] = "该客户已拥有该优惠券";
    }else{
        $bool = mysql_query("INSERT  INTO kehuCoupon (khid,couponId,status,time) VALUE ('$kehuid','$couponid','未使用','$time')");
        if($bool){
            $num = $checkCou['num']-1;
            $bool = mysql_query("UPDATE coupon SET num = '$num' WHERE id='$couponid'");
            if($bool){
                $_SESSION['warn'] = "发放优惠券成功";
                $json['warn'] = 2;
                $json['href'] = root."control/adCouponMx.php?id={$couponid}";
            }else{
                $json['warn'] = "优惠券发放失败";
            }
        }else{
            $json['warn'] = "优惠券发放失败";
        }
    }
    /****************商品管理-编辑素材****************/
}
elseif($get['type'] == "updateMaterial"){
    //赋值
    $aid            = $post['articleId'];//articleid
    $articGoodsid   = $post['articGoodsid'];//商品id
    $articword      = $post['articword'];//宣传文字
    $imgarray       = $post['imgSet'];//宣传图片数组
    $list           = $post['alist'];//序号
    $check = query("goods","id='$articGoodsid'");
    if(!power("adGoods","edit")){
        $json['warn'] = "无权限";
    }elseif(empty($check)){
        $json['warn'] = "请先添加商品基本信息";
    }elseif($check['id'] != $articGoodsid){
        $json['warn'] = "未找到此商品信息";
    }elseif(empty($articword) && empty($imgarray)){
        $json['warn'] = "请填写宣传文字或者图片";
    }else{
        if(empty($aid)){
            if(!empty($imgarray)){
                foreach ($imgarray as $img){
                    $target = "宣传素材";
                    $fileName = ImagesUpload($img, 'img/goodsWin');
                    $path = ServerRoot . $fileName;
                    JpegSmallWidth($path, 200);
                    $id = suiji();
                    $bool = mysql_query("INSERT INTO article (id,target,targetId,word,img,list,updateTime,time) VALUE ('$id','$target','$articGoodsid','$articword','$fileName','$list','$time','$time')");
                    if($bool){
                        $_SESSION['warn'] = "宣传素材添加成功";
                        $json['warn'] = 2;
                        $json['href'] = root."control/adGoodsMx.php?id={$articGoodsid}";
                    }else{
                        $json['warn'] = "宣传素材添加失败";
                    }
                }
            }else{
                $target = "宣传素材";
                $id = suiji();
                $bool = mysql_query("INSERT INTO article (id,target,targetId,word,list,updateTime,time) VALUE ('$id','$target','$articGoodsid','$articword','$list','$time','$time')");
                $json['sql1'] = "INSERT INTO article (id,target,targetId,word,list,updateTime,time) VALUE ('$id','$target','$articGoodsid','$articword','$list','$time','$time')";
                if($bool){
                    $_SESSION['warn'] = "宣传素材添加成功";
                    $json['warn'] = 2;
                    $json['href'] = root."control/adGoodsMx.php?id={$articGoodsid}";
                }else{
                    $json['warn'] = "宣传素材添加失败2";
                }
            }
        }else{
            $check = query("article","id='$aid'");
            if(empty($check)){
                $json['warn'] = "未找到该素材的信息";
            }else{
                if(empty($img)){//无图片修改
                    $bool = mysql_query("UPDATE article SET word='$articword',list='$list',updateTime='$time' WHERE id='$aid'");
                    if($bool){
                        $_SESSION['warn'] = "宣传素材修改成功";
                        $json['warn'] = 2;
                        $json['href'] = root."control/adGoodsMx.php?id={$articGoodsid}";
                    }else{
                        $json['warn'] = "宣传素材修改失败";
                    }
                }else{//有图片修改
                    foreach ($imgarray as $img){
                        $fileName = ImagesUpload($img,'img/goodsWin');
                        $path = ServerRoot . $fileName;
                        JpegSmallWidth($path, 200);
                        $bool = mysql_query("UPDATE article SET word='$articword',img='$fileName',list='$list',updateTime='$time' WHERE id='$aid'");
                        if($bool){
                            $_SESSION['warn'] = "宣传素材修改成功";
                            $json['warn'] = 2;
                            $json['href'] = root."control/adGoodsMx.php?id={$articGoodsid}";
                        }else{
                            $json['warn'] = "宣传素材修改失败";
                        }
                    }
                }
            }
        }
    }
    /**********商品管理-弹出素材详情**********/
}
elseif($get['type'] == "articleMx"){
    $id     = $post['id'];//articId

    $res = query("article","id='$id'");
    $json['aimg'] = ProveImgShow("$res[img]","暂无图片");
    $json['warn'] = array(
        'id' =>$res['id'],
        'aword' => $res['word'],
        'alist' => $res['list'],
    );
    /**************需要管理-接单***************/
}
elseif($get['type'] == "adDemandSub"){
    //赋值
    $status     = $post['status'];//需求状态
    $actionId   = $post['actionId'];//操作人员
    $demandId   = $post['demandId'];//需求id
    //判断
    $check = query("demand","id='$demandId'");
    if(!power("adDemand","edit")){
        $json['warn'] = "无权限";
    }elseif($check['id'] != $demandId){
        $_SESSION['warn'] = "未找到改需求";
        $json['warn'] = 2;
        $json['href'] = root."control/adDemand.php";
    }else{
        if($check['status'] != "已发布" && $check['actionId'] != $Control['adid']){//判断是否有别的人员操作
            $_SESSION['warn'] = "该需求不是您的项目";
            $json['warn'] = 2;
            $json['href'] = root."control/adDemand.php";
        }elseif($check['status'] == "不能接"){
            $_SESSION['warn'] = "该需求已经不能接";
            $json['warn'] = 2;
            $json['href'] = root."control/adDemand.php";
        }else{
            $bool = mysql_query("UPDATE demand SET status='$status',actionId='$actionId' WHERE id='$demandId'");
            if($bool && $status=="在商洽"){
                $_SESSION['warn'] = "争取合作，继续努力";
                $json['warn'] = 2;
                $json['href'] = root."control/adDemandMx.php?id={$demandId}";
            }elseif($bool && $status=="已合作"){
                $_SESSION['warn'] = "需求接单成功";
                $json['warn'] = 2;
                $json['href'] = root."control/adDemandMx.php?id={$demandId}";
            }else{
                $json['warn'] = "需求操作失败";
            }
        }
    }
    /*****************删除宣传素材******************/
}
elseif($get['type'] == "DeleteGoodsArticle"){
    //赋值
    $id = $post['arId'];
    if(!power("adGoods","del")){
        $json['warn'] = "无权限";
    }elseif(empty($id)){
        $json['warn'] = "未知错误";
    }else{
        $check = query("article","id='$id'");
        if($check['id'] != $id){
            $json['warn'] = "未找到该素材";
        }else{
            $bool = mysql_query("DELETE FROM article WHERE id='$check[id]'");
            if($bool){
                $json['warn'] = "该素材删除成功";
            }else{
                $json['warn'] = "该素材删除失败";
            }
        }
    }
    /*****************提现管理****************/
}
elseif($get['type'] == "Withdraw"){
    //赋值
    $id         = $post['withId'];//提现id
    $actionId   = $post['actionId'];//审核人
    $workFlow   = $post['workFlow'];//状态
    $check = query("withdraw","id='$id'");
    //判断
    if(!power("adWithdraw","edit")){
        $json['warn'] = "无权限";
    }elseif($check['id'] != $id){
        $json['warn'] = "未找到该申请信息";
    } else{
        if(empty($check['actionId'])){
            if($check['workFlow'] == "已支付"){
                $json['warn'] = "该申请已经支付，无需重复审核";
            }else{
                $bool = mysql_query("UPDATE withdraw SET workFlow='$workFlow' WHERE id='$id'");
                if($bool){
                    $_SESSION['warn'] = "审核成功";
                    $json['warn'] = 2;
                    $json['href'] = root."control/adWithdrawMx.php?id={$id}";
                }else{
                    $json['warn'] = "审核失败";
                }
            }
        }else{
            $json['warn'] = "该提现申请已经有工作人员操作";
        }
    }
    /********商品管理-计算利率******/
}
elseif($get['type'] == "imputedPrice"){
    $price 			= $post['pricing'];		#定价
    $cost 			= $post['cost'];			#成本
    $free 			= $post['free'];			#手续费
    $shippingFree 	= $post['shippingFree'];	#运费
    $json = skuSubRun::getFree($price,$cost,$free,$shippingFree);
    /***************供应商管理-添加，更新***************/
}
elseif($get['type'] == "supplierAdd"){
    //赋值
    $supplierId  = $post['supplierId'];//供应商id
    $adname       = $post['sname'];//供应商姓名
    $duty         = $post['dutyId'];//职位id
    $sex          = $post['sex'];//性别
    $adtel        = $post['tel'];//联系电话
    $ademail      = $post['email'];//电子邮箱
    $adqq         = $post['qq'];//联系QQ
    $bankNum      = $post['bankId'];//银行卡号
    $bankName     = $post['bankName'];//银行卡名称
    //判断
    if(!power("adSupplier","edit")){
        $json['warn'] = "无此操作权限";
    }elseif(empty($adname)){
        $json['warn'] = "请填写供应商真实姓名";
    }elseif(empty($adtel) || preg_match($CheckTel,$adtel) == 0){
        $json['warn'] = "请填写正确的联系方式";
    }else{
        //新增供应商
        if(empty($supplierId)){
            $id = suiji();
            $pas = 123456;
            $adpas = md5($pas);
            $bool = mysql_query("INSERT INTO admin (`adid`,`duty`,`adname`,`sex`,`adtel`,`ademail`,`adqq`,`bankName`,`bankNum`,`updateTime`,`time`,`adpas`) VALUE ('$id','$duty','$adname','$sex','$adtel','$ademail','$adqq','$bankName','$bankNum','$time','$time','$adpas')");
            if($bool){
                $_SESSION['warn'] = "供应商新增成功";
                $json['warn'] = 2;
                $json['href'] = root."control/adSupplierMx.php?id={$id}";
            }else{
                $json['warn'] = "供应商新增失败";
            }
            //更新供应商
        }else{
            $check = query("admin","adid='$supplierId'");
            if($check['adid'] != $supplierId){
                $json['warn'] = "未找到供应商信息";
            }else{
                $bool = mysql_query("UPDATE admin SET duty='$duty',adname='$adname',sex='$sex',adtel='$adtel',ademail='$ademail',adqq='$adqq',bankName='$bankName',bankNum='$bankNum',updateTime='$time' WHERE adid='$check[adid]'");
                if($bool){
                    $_SESSION['warn'] = "供应商更新成功";
                    $json['warn'] = 2;
                    $json['href'] = root."control/adSupplierMx.php?id={$check['adid']}";
                }else{
                    $json['warn'] = "供应商更新失败";
                }
            }
        }
    }
    /*******商品管理-规格-设置规格默认********/
}
elseif($get['type'] == "defaultData"){
    //赋值
    $id = $post['sid'];//规格id
    //判断
    $check = query("goodsSku","id='$id'");
    if(!power("adGoods","edit")){
        $json['warn'] = "无权限";
    }elseif(empty($check) || $check['id'] != $id){
        $json['warn'] = "未找到该规格信息";
    }else{
        mysql_query("UPDATE goodsSku SET defaultData='',updateTime='$time' WHERE goodsId='$check[goodsId]'");
        $bool = mysql_query("UPDATE goodsSku SET defaultData='默认',updateTime='$time' WHERE id='$check[id]'");
        if($bool){
            $json['warn'] = "设置默认规格成功";
        }else{
            $json['warn'] = "设置默认规格失败";
        }
    }
}
elseif($get['type'] == 'getExpressDataAdmin'){
    if(empty($post['express_number'])){
        returnJsonText('缺少运单号');
    }
    $expressNumber = $post['express_number'];
    $res = kdQuery($expressNumber);
    //$json['express_html'] = '{"express_html":"\r\n<li class=\'first\'>\r\n<i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-26 08:28:00<\/span>\r\n                    <span class=\'txt\'>[新乡市] 已签收,感谢使用顺丰,期待再次为您服务<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-26 07:44:00<\/span>\r\n                    <span class=\'txt\'>[新乡市] 快件交给贺贝贝，正在派送途中（联系电话：17539577469）<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-26 07:30:00<\/span>\r\n                    <span class=\'txt\'>[新乡市] 快件到达 【新乡市牧野区建北小区营业点】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-25 16:17:00<\/span>\r\n                    <span class=\'txt\'>[新乡市] 快件在【新乡关堤集散中心】装车，已发往 【新乡市牧野区建北小区营业点】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-25 15:59:00<\/span>\r\n                    <span class=\'txt\'>[新乡市] 快件到达 【新乡关堤集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-25 13:33:00<\/span>\r\n                    <span class=\'txt\'>[郑州市] 快件在【郑州圃田集散中心】装车，已发往 【新乡关堤集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-25 11:45:00<\/span>\r\n                    <span class=\'txt\'>[郑州市] 快件到达 【郑州圃田集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-24 18:05:00<\/span>\r\n                    <span class=\'txt\'>[杭州市] 快件在【杭州上城集散中心】装车，已发往下一站<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-24 14:15:00<\/span>\r\n                    <span class=\'txt\'>[杭州市] 快件到达 【杭州上城集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-24 06:44:00<\/span>\r\n                    <span class=\'txt\'>[杭州市] 快件在【杭州瓜沥集散中心】装车，已发往 【杭州上城集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-24 03:38:00<\/span>\r\n                    <span class=\'txt\'>[杭州市] 快件到达 【杭州瓜沥集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-23 00:39:00<\/span>\r\n                    <span class=\'txt\'>[台州市] 快件在【台州临海中转场】装车，已发往 【杭州上城集散中心】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-22 23:40:00<\/span>\r\n                    <span class=\'txt\'>[台州市] 快件到达 【台州临海中转场】<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-21 20:06:00<\/span>\r\n                    <span class=\'txt\'>[台州市] 快件在【台州黄岩新前营业部】装车，已发往下一站<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-21 20:06:00<\/span>\r\n                    <span class=\'txt\'>[台州市] 顺丰速运 已收取快件<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-21 13:45:23<\/span>\r\n                    <span class=\'txt\'>[温州市] 卖家发货<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-21 13:45:23<\/span>\r\n                    <span class=\'txt\'>您的包裹已出库<\/span>\r\n                <\/li>\r\n                <li>\r\n                    <i class=\'node-icon\'><\/i>\r\n                    <span class=\'time\'>2018-02-19 14:30:00<\/span>\r\n                    <span class=\'txt\'>您的订单开始处理<\/span>\r\n                <\/li>\"}';
    $json['html'] = $res;
    returnJson($json);
}
elseif ($get['type'] == 'writeOrderExpressData'){
    $expressName = $post['expressName'];
    $expressNumber = $post['expressNumber'];
    $orderSN= $post['orderSN'];
    $sql = "update `order` set express_name='{$expressName}',express_number='{$expressNumber}',workFlow='2' where order_sn='{$orderSN}'";
    $res = mysql_query($sql);
    $json['code'] = 0;
    returnJson($json);
}
/********返回**************************************/
echo json_encode($json);
?>
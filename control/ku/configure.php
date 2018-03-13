<?php
//常量
define("root", "/"); //网站根目录
define("ServerRoot", "E:\\juli\\"); //本网站的服务器根目录（不能使用$_SERVER ['DOCUMENT_ROOT']的原因：测试环境往往在非根目录下运行）
//define("version", "1.1.2"); //版本号，主要解决js和css的缓存问题
define("version", mt_rand(10000, 99999)); //版本号，主要解决js和css的缓存问题
//变量
$root = root;
//基本配置
$conf = array(
    "ServerName"   => "localhost", //mysql服务器名称
    "UserName"     => "root", //mysql服务器登录账号
    "password"     => "", //mysql服务器登录密码
    "DatabaseName" => "juli", //目前使用的数据库的名称
    "SmsName"      => "18581286862", //短信账户名（一般为注册手机号码）
    "SmsPwd"       => "3AE17D34D81367185C57BEA28772", //pwd码
    "SmsSign"      => "雨木科技", //签名后缀
    "SqlUrl"       => "E:\\xampp\\MySQL\\data\\juli", //当前运行的数据库相对于网站根目录的相对路径，用于数据库备份
);
//权限划分
$powerAll = array(
    "首页管理"  =>array(
        "adspecial"=>array(
            "name"  => "专题管理",
            "see"   => "查询",
            "edit"  => "编辑",
            "del"   => "删除",
        ),
        "adupnew"=>array(
            "name"  => "上新专区",
            "see"   => "查询",
            "edit"  => "编辑",
            "del"   => "删除",
        ),
    ),
    "信息管理"  => array(
        "adlog"     => array(
            "name"   => "日志管理",
            "seeAll" => "查询所有",
        ),
        "adimg"     => array(
            "name" => "网站图片管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
        "adword"    => array(
            "name" => "网站文字管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
        "adContent" => array(
            "name" => "普通文章管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "内部管理"  => array(
        "admin"    => array(
            "name"      => "员工管理",
            "seeDuty"   => "查询职位",
            "editDuty"  => "编辑职位",
            "delDuty"   => "删除职位",
            "seeAdmin"  => "查询员工",
            "editAdmin" => "编辑员工",
            "delAdmin"  => "删除员工",
        ),
        "adProfit" => array(
            "name"     => "收支平衡",
            "seeAll"   => "查询所有",
            "edit"     => "编辑",
            "del"      => "删除",
            "apply"    => "费用报销",
            "auditing" => "费用报销审核",
        ),
        "adSystem" => array(
            "name" => "管理制度",
            "see"  => "查询",
        ),
    ),
    "财务管理"  => array(
        "adParameter" => array(
            "name" => "参数管理",
            "see"  => "查询",
            "edit" => "编辑",
        ),
        "adAccount"   => array(
            "name" => "账户管理",
            "see"  => "查询",
            "edit" => "编辑",
        ),
    ),
    "商品管理"  => array(
        "adGoods" => array(
            "name"       => "商品管理",
            "see"        => "查询",
            "edit"       => "编辑",
            "del"        => "删除",
            "xian"       => "上，下架",
            "editProfit" => "利率编辑",
        ),
    ),
    "评论管理"  => array(
        "talk" => array(
            "name" => "评论管理",
            "see"  => "查看",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "供应商管理" => array(
        "adSupplier" => array(
            "name" => "供应商管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "客户管理"  => array(
        "adClient" => array(
            "name" => "客户管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "订单管理"  => array(
        "adOrder" => array(
            "name" => "订单管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "需求管理"  => array(
        "adDemand" => array(
            "name" => "需求管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "优惠券管理" => array(
        "adCoupon" => array(
            "name" => "优惠券管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "邀请码管理" => array(
        "adCodeHelp" => array(
            "name" => "邀请码管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "提现管理"  => array(
        "adWithdraw" => array(
            "name" => "提现管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),
    "分享管理"  => array(
        "adShare" => array(
            "name" => "分享管理",
            "see"  => "查询",
            "edit" => "编辑",
            "del"  => "删除",
        ),
    ),

);
//引用
include ServerRoot . "control/ku/ku.php"; //核心函数库
include ServerRoot . "control/ku/extend.php"; //扩展函数库

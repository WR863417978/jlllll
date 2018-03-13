<?php
error_reporting(E_ALL&~E_NOTICE&~E_DEPRECATED);//关闭错误报告
/**********初始化数据库********************************************************/
$con = mysql_connect($conf['ServerName'], $conf['UserName'], $conf['password']);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($conf['DatabaseName'], $con);
mysql_query("set names 'utf8'");
session_start();
//调整格林威治时间为北京时间
date_default_timezone_set('Etc/GMT-8');
//获取当前日期时间
$time = date("Y-m-d H:i:s");
$date = date("Y-m-d");
//如果域名地址中没有www,则跳转到首页（解决jquery异步处理时无法跨域的问题）
$ThisUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//if (strstr($_SERVER['HTTP_HOST'], "www") == false) {
//    if (!empty($_SERVER["QUERY_STRING"])) {
//        $get = "?" . $_SERVER["QUERY_STRING"];
//    }
//    header("Location:http://www.{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}" . $get);
//    exit(0);
//} else {
//    $ThisUrl = "http://" . $ThisUrl;
//}
/**********处理所有GET和POST参数********************************************************/
foreach ($_POST as $key => $value) {
    $post[$key] = FormSubArray($value);
}
foreach ($_GET as $key => $value) {
    $get[$key] = FormSubArray($value);
}
/**********正则表达式********************************************************/
$CheckTel = "/^0?(13[0-9]|15[012356789]|18[0123456789]|14[57]|17[0367])[0-9]{8}$/";//手机号码正则表达式
$CheckEmail = "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/";//邮箱正则表达式
$CheckInteger = "/^\d*$/";//正整数正则表达式
$CheckPrice = "/^[0-9]+(.[0-9]{1,2})?$/";//价格正则表达式
$CheckString = "/^[a-zA-Z0-9\u4E00-\u9FA5]+$/";//非法字符正则表达式
/**********检查登录状态********************************************************/
//管理员登录状态
if (empty($_SESSION['adid'])) {
    $ControlFinger = 2;
    $ControlWarn = "您未登录";
} else {
    $Control = query("admin", " adid = '$_SESSION[adid]' ");
    $adDuty = query("adDuty", " id = '$Control[duty]' ");
    $adPower = json_decode($adDuty['power'], true);
    if ($Control['adid'] != $_SESSION['adid']) {
        $ControlFinger = 2;
        $ControlWarn = "未找到您的登录账号";
    } else {
        $ControlFinger = 1;
    }
}

//客户登录状态
if (isset($_SESSION['khid']) and $_SESSION['khid'] != "") {
    $kehu = query("kehu", ' khid = '.$_SESSION["khid"].' ');
    if ($kehu['khid'] == $_SESSION['khid']) {
        $KehuFinger = 1;

    } else {
        $KehuFinger = 2;
    }
} else {
    $KehuFinger = 2;
}
/**********权限函数********************************************************/
//$power：需要的权限名称，如果是多个权限的任意一个皆可开启此页面，则权限名称之间用英文逗号隔开
//页面权限
function powerPage($power)
{
    $adPower = array_keys($GLOBALS['adPower']);
    if (empty($power)) {//如果本页面不需要特殊权限就能打开，则设$power为空
        return true;
    } elseif (count(array_intersect($adPower, explode(",", $power))) > 0) {
        return true;
    } else {
        return false;
    }
}

//细分权限
function power($page, $power)
{
    return in_array($power, $GLOBALS['adPower'][$page]);
}

//管理员权限跳转函数
function ControlRoot($power)
{
    if ($GLOBALS['ControlFinger'] == 2) {
        $_SESSION['warn'] = $GLOBALS['ControlWarn'];
        header("Location:" . root . "control/login.php");
        exit(0);
    } elseif (!powerPage($power)) {
        $_SESSION['warn'] = "权限不足";
        header("Location:" . root . "control/adpersonal.php");
        exit(0);
    }
}

//客户权限跳转函数
function UserRoot($url)
{
    if ($GLOBALS['KehuFinger'] == 2) {
        $_SESSION['warn'] = "您未登录";
        if (empty($url)) {
            if (isMobile()) {
                header("location:" . root . "m/mUser/mUsLogin.php");
            } else {
                header("location:" . root . "user/usLogin.php");
            }
        } else {
            header("location:{$url}");
        }
        exit(0);
    }
}

/**********注销登录********************************************************/
//注销管理员登录
if ($get['Delete'] == "admin") {
    unset($_SESSION['adid']);
    $_SESSION['warn'] = "您已经退出管理员登录状态";
    header("Location:{$root}control/login.php");
    exit(0);
}
/**********返回随机数********************************************************/
function suiji()
{
    $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $x = strlen($c) - 1;
    for ($i = 1; $i <= 3; $i++) {
        $rand = rand(1, $x);
        $suiji .= substr($c, $rand, 1);
    }
    $suiji .= time() - 1426408044;
    for ($i = 1; $i <= 2; $i++) {
        $rand = rand(1, $x);
        $suiji .= substr($c, $rand, 1);
    }
    return $suiji;
}

/**********输出数据库表内容********************************************************/
function query($name, $where)
{
    $sql = "select * from {$name} where {$where}";
//    echo $sql;die;
    $data = mysql_query($sql);
    if($data){
        $query = mysql_fetch_array($data);
        return $query;
    }else{
        return false;
    }

}

/**********输出图片********************************************************/
function img($id)
{
    $img = query("img", " id = '$id' ");
    return root . $img['src'] . "?t=" . strtotime($img['updateTime']);
}

//输出图片超链接地址
function imgurl($id)
{
    $img = query("img", " id = '$id' ");
    return $img['url'];
}

//输出图片备注
function imgtext($id)
{
    $img = query("img", " id = '$id' ");
    return $img['text'];
}

/**********输出网站自定义文字********************************************************/
function website($id)
{
    $website = query("website", " webid = '$id' ");
    return $website['text'];
}

/**********输出网站核心参数********************************************************/
function para($id)
{
    $para = query("para", " paid = '$id' ");
    return $para['paValue'];
}

/**********列表图像替换********************************************************/
function ListImg($img)
{
    if ($img == "") {
        $url = img("IXZ49933118lV");
    } else {
        $url = root . $img;
    }
    return $url;
}

/**********头像输出********************************************************/
function HeadImg($sex, $img)
{
    if ($img == "") {
        if ($sex == "男") {
            $HeadImg = img("replaceMan");
        } elseif ($sex == "女") {
            $HeadImg = img("replaceWoman");
        } else {
            $HeadImg = img("replaceHead");
        }
    } else {
        $HeadImg = root . $img;
    }
    return $HeadImg;
}

function adHead($admin)
{
    //if(!empty($admin['touxiang'])){
    $img = HeadImg($admin['sex'], $admin['touxiang']);
    //}else{
    //	$img = HeadImg($admin['sex'],$admin['wxIco']);
    //}
    return $img;
}

/**********给当前菜单加效果********************************************************/
function menu($name, $class)
{
    if (strstr($_SERVER['PHP_SELF'], $name) !== false) {
        return $class;
    }
}

/**********GET地址菜单效果********************************************************/
function MenuGet($get, $name, $class)
{
    if ($_GET[$get] == $name) {
        return $class;
    }
}

/**********所属区域********************************************************/
function Region($id)
{
    if (empty($id)) {
        return "未设置";
    } else {
        $Region = query("region", " id = '$id' ");
        if ($Region['id'] != $id) {
            return "未找到";
        } else {
            return $Region['province'] . "-" . $Region['city'] . "-" . $Region['area'];
        }
    }
}

/**********重复查询（更新数据库表记录的时候使用）********************************************************/
function Repeat($where, $IdName, $IdValue)
{
    if (!empty($IdValue)) {
        $and = " and {$IdName} != '$IdValue' ";
    }
    $num = mysql_num_rows(mysql_query(" select * from {$where} {$and} "));
    if ($num == 0) {
        return false;
    } else {
        return true;
    }
}

/**********输出多选框********************************************************/
function checkbox($name, $array, $CheckArray)
{
    $result = "";
    //如果用此数组中所有的值组成的数组完全等于此数组，则说明此数组为索引数组，反之为关联数组
    if (array_values($array) === $array) {
        foreach ($array as $key) {
            if (in_array($key, $CheckArray)) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $result .= "&nbsp;&nbsp;<label><input name=\"{$name}[]\" type=\"checkbox\" value=\"{$key}\" {$checked}>&nbsp;{$key}</label>";
        }
    } else {
        foreach ($array as $key => $text) {
            if (in_array($key, $CheckArray)) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $result .= "&nbsp;&nbsp;<label><input name=\"{$name}[]\" type=\"checkbox\" value=\"{$key}\" {$checked}>&nbsp;{$text}</label>";
        }
    }
    return $result;
}

/**********输出单选框********************************************************/
function radio($name, $array, $Value)
{
    $result = "";
    //如果用此数组中所有的值组成的数组完全等于此数组，则说明此数组为索引数组，反之为关联数组
    if (array_values($array) === $array) {
        foreach ($array as $key) {
            if ($key == $Value) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $result .= "&nbsp;&nbsp;<label><input name='{$name}' type='radio' value='{$key}' {$checked}>&nbsp;{$key}</label>";
        }
    } else {
        foreach ($array as $key => $text) {
            if ($key == $Value) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $result .= "&nbsp;&nbsp;<label><input name='{$name}' type='radio' value='{$key}' {$checked} >&nbsp;{$text}</label>";
        }
    }
    return $result;
}

/**********option打印函数********************************************************/
function option($title, $option, $value)
{
    if (empty($title)) {
        $result = "";
    } else {
        $result = "<option value=''>{$title}</option>";
    }
    //如果用此数组中所有的值组成的数组完全等于此数组，则说明此数组为索引数组，反之为关联数组
    if (array_values($option) === $option) {
        foreach ($option as $key) {
            if ($key == $value) {
                $selected = " selected='selected' ";
            } else {
                $selected = "";
            }
            $result .= "<option value='{$key}' {$selected}>{$key}</option>";
        }
    } else {
        foreach ($option as $key => $text) {
            if ($key == $value) {
                $selected = " selected='selected' ";
            } else {
                $selected = "";
            }
            $result .= "<option value='{$key}' {$selected}>{$text}</option>";
        }
    }
    return $result;
}

/**********下拉菜单完整打印函数********************************************************/
function select($name, $class, $title, $option, $value)
{
    $result = "
	<select name='{$name}' class='{$class}'  style=\"display: inline-block;padding: 4px;\">
	" . option($title, $option, $value) . "
	</select>
	";
    //返回一个完整的下拉菜单
    return $result;
}

/**********超链接菜单********************************************************/
function UrlSelect($SqlForm, $SelectName, $class, $SqlUrl, $SqlId, $SqlColumn, $title)
{
    $SearchSql = mysql_query("select * from {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $SqlUrl . $Search[$SqlId];
        $option[$key] = $Search[$SqlColumn];
    }
    return select($SelectName, $class, $title, $option);
}

/**********消除重复数据菜单********************************************************/
function RepeatSelect($SqlForm, $SqlColumn, $SelectName, $class, $title, $value)
{
    $SearchSql = mysql_query("SELECT DISTINCT {$SqlColumn} FROM {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $Search[$SqlColumn];
        $option[$key] = $key;
    }
    return select($SelectName, $class, $title, $option, $value);
}

//只打印option，一般用于关联菜单
function RepeatOption($SqlForm, $SqlColumn, $title, $value)
{
    $SearchSql = mysql_query("SELECT DISTINCT {$SqlColumn} FROM {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $Search[$SqlColumn];
        $option[$key] = $key;
    }
    return option($title, $option, $value);
}

/**********vlaue为ID号的菜单********************************************************/
function IDSelect($SqlForm, $SelectName, $class, $SqlId, $SqlColumn, $title, $value)
{
    $SearchSql = mysql_query("select * from {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $Search[$SqlId];
        $option[$key] = $Search[$SqlColumn];
    }
    return select($SelectName, $class, $title, $option, $value);
}

function IdOption($SqlForm, $SqlId, $SqlColumn, $title, $value)
{
    $SearchSql = mysql_query("select * from {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $Search[$SqlId];
        $option[$key] = $Search[$SqlColumn];
    }
    return option($title, $option, $value);
}

/**********数据库筛选菜单********************************************************/
function SqlSelect($SqlForm, $SelectName, $class, $SqlColumn, $title)
{
    $SearchSql = mysql_query("select * from {$SqlForm}");
    while ($Search = mysql_fetch_array($SearchSql)) {
        $key = $Search[$SqlColumn];
        $option[$key] = $key;
    }
    return select($SelectName, $class, $title, $option);
}

/**********输出月日时下拉列表********************************************************/
function year($name, $class, $type, $value)
{
    if ($type == "new") {
        $m = 2010;
    } else {
        $m = 1960;
    }
    for ($n = $m; $n <= 2020; $n++) {
        $key = sprintf("%02d", $n);
        $option[$key] = $key . "年";
    }
    return select($name, $class, "-年份-", $option, ValueTime($value, "Y"));
}

function moon($name, $class, $value)
{
    for ($n = 1; $n <= 12; $n++) {
        $key = sprintf("%02d", $n);
        $option[$key] = $key . "月";
    }
    return select($name, $class, "-月份-", $option, ValueTime($value, "m"));
}

function day($name, $class, $value)
{
    for ($n = 1; $n <= 31; $n++) {
        $key = sprintf("%02d", $n);
        $option[$key] = $key . "日";
    }
    return select($name, $class, "-日期-", $option, ValueTime($value, "d"));
}

function hour($name, $class, $value)
{
    for ($n = 1; $n <= 23; $n++) {
        $key = sprintf("%02d", $n);
        $option[$key] = $key . "点";
    }
    return select($name, $class, "-小时-", $option, ValueTime($value, "H"));
}

function minute($name, $class, $value)
{
    for ($n = 1; $n <= 59; $n++) {
        $key = sprintf("%02d", $n);
        $option[$key] = $key . "分";
    }
    return select($name, $class, "-分钟-", $option, ValueTime($value, "i"));
}

/**********返回时间中的某段（年、月、日、时、分、秒）********************************************************/
function ValueTime($value, $form)
{
    if ($value == "0000-00-00" || $value == "0000-00-00 00:00:00" || $value == "") {
    } else {
        $t = date($form, strtotime($value));
        return $t;
    }
}

/**********未设置函数********************************************************/
function kong($word)
{
    if ($word == "") {
        $value = "未设置";
    } else {
        $value = $word;
    }
    return $value;
}

/**********字数限制********************************************************/
function zishu($word, $num)
{
    if (mb_strlen($word, 'utf8') > $num) {
        $dot = "...";
    } else {
        $dot = "";
    }
    return mb_substr($word, 0, $num, "utf-8") . $dot;
}

/**********表单提交数据整理和防sql注入********************************************************/
function FormSub($data)
{
    $data = trim($data);//消除两边的空格
    $data = htmlentities($data, ENT_QUOTES, "utf-8");//字符转换为 HTML 实体。
    $data = addslashes($data);//对单引号（'）双引号（"）反斜杠（\）NULL进行转义
    return $data;
}

function FormSubArray($data)
{
    $result = "";
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = FormSubArray($value);
            } else {
                $result[$key] = FormSub($value);
            }
        }
    } else {
        $result = FormSub($data);
    }
    return $result;
}

/**********内容格式化********************************************************/
function neirong($article, $class = null)
{
    if (empty($class)) {
        $tag = "";
    } else {
        $tag = " class='{$class}'";
    }
    $replace = array('/\n/', '/【/', '/】/', '/《/', '/》/');
    $array = array("</p><p{$tag}>", "<span class='ArticleTitle'>", "</span>", "<span class='Articlename'>", "</span>");
    $contens = preg_replace($replace, $array, $article);
    return "<p{$tag}>{$contens}</p>";
}

/**********打印文章明细********************************************************/
function ArticleMx($id)
{
    $article = "";
    $sql = mysql_query(" select img,word from article where TargetId = '$id' order by list ");
    while ($array = mysql_fetch_array($sql)) {
        if (empty($array['img'])) {
            $article .= neirong($array['word']);
        } else {
            $article .= "<img src='" . root . $array['img'] . "'>";
        }
    }
    return $article;
}

/**********记录本次消费********************************************************/
function RecordMoney($typeid, $type, $direction, $money, $balance, $text)
{
    mysql_query("insert into record (typeid,type,direction,money,balance,text,time) values ('$typeid','$type','$direction','$money','$balance','$text','$GLOBALS[time]')");
}

/**********添加日志********************************************************/
function LogText($target, $targetId, $text)
{
    mysql_query("insert into log (target,targetId,text,time) values ('$target','$targetId','$text','$GLOBALS[time]')");
}

/**********测试记录函数********************************************************/
function test($text)
{
    $time = date("Y-m-d H:i:s");
    mysql_query(" insert into test (text,time) values ('$text','$time') ");
}

/**********发送验证短信********************************************************/
function duanxin($Mphone, $message)
{
    $name = $GLOBALS['conf']['SmsName'];
    $pwd = $GLOBALS['conf']['SmsPwd'];
    $sign = $GLOBALS['conf']['SmsSign'];
    $gateway = "http://web.cr6868.com/asmx/smsservice.aspx?name={$name}&pwd={$pwd}&content={$message}&mobile={$Mphone}&sign={$sign}&type=pt";
    $result = file_get_contents($gateway);
    if ($result) {
        LogText("短信接口", $Mphone, $message);
        return "发送成功";
    } else {
        return "发送失败";
    }
}

/**********缩略图展示********************************************************/
function ProveImgShow($img)
{
    if ($img == "") {
        return "<img class='smallImg imgHover' src='" . root . "img/images/EmptyImg.jpg'>";
    } else {
        if (strstr($img, "http") !== false) {
            $r = "";
        } else {
            $r = root;
        }
        return "<a target='_blank' href='{$r}{$img}' title='点击查看大图'><img class='smallImg imgHover' src='{$r}{$img}'></a>";
    }
}

/**********分页********************************************************/
function paging($sql, $order, $PageNum)
{
    $query = mysql_query($sql);
    $GLOBALS['num'] = mysql_num_rows($query); //总条数
    $GLOBALS['AllPage'] = ceil($GLOBALS['num'] / $PageNum); //总页数
    $GLOBALS['page'] = empty($_GET['page']) ? 1 : $_GET['page']; //当前页
    //修正当前页
    if ($_GET['page'] > 1 and $_GET['page'] <= $GLOBALS['AllPage']) {
        $GLOBALS['page'] = $_GET['page'];
    } else {
        $GLOBALS['page'] = 1;
    }
    $first = ($GLOBALS['page'] - 1) * $PageNum; //当前页面开始位置
    $GLOBALS['query'] = mysql_query($sql . $order . " limit $first , $PageNum");
}

//$url为当前页面地址，$n为要显示的页数
function fenye($url, $n, $class=null)
{
    $urldata = parse_url($url);
    $queryString = $urldata['query'];
    parse_str($queryString,$queryData);
    unset($queryData['page']);
    if(!empty($queryData)){
        $url = substr($url,0,strpos($url,'?')).'?'.http_build_query($queryData);
    }else{
        $url = substr($url,0,strpos($url,'?'));
    }

    if (strstr($url, "?") == false) {
        $b = "?";
    } else {
        $b = "&";
    }
    $AllPage = $GLOBALS['AllPage'];
    $page = $GLOBALS['page'];
    $ps = $page <= 1 ? 1 : $page - 1;//上一页
    $px = $page >= $AllPage ? $AllPage : $page + 1;//下一页
    //判断要显示的第一个页码
    $y = ceil($n / 2);//尽量让当前页高亮居中，$y为居中时左右的页码数量
    if ($page <= $y) {//如果当前页小于需要显示的页码数量的一半，则从第一页开始显示
        $p = 1;
    } elseif (($AllPage - $page) <= $y) {//如果总页数减去当前页小于需要显示的页码数量的一半，则将最后几页显示出来
        $p = $AllPage - $n + 1;
    } else {
        $p = $page - $y;//如果当前页两边都有充足的页面，则居中显示
    }
    for ($x = 0; $x < $n; $x++) {
        $z = $p + $x;
        if ($z > 0 and $z <= $AllPage) {//$z可能小于零或大于总页数
            if ($z == $page) {
                $c = " class='Current' ";
            } else {
                $c = "";
            }
            $PageWord .= "<a {$c} href='{$url}{$b}page={$z}'>{$z}</a>\n";
        }
    }
    if (empty($class)) {
        $c = "page";
    } else {
        $c = $class;
    }
    $return = "
	<div class='{$c}'>
		<a href='{$url}'>第一页</a>
		<a href='{$url}{$b}page={$ps}'>上一页</a>
		{$PageWord}
		<a href='{$url}{$b}page={$px}'>下一页</a>
		<a href='{$url}{$b}page={$AllPage}'>最后一页</a>
		<select onChange='location.replace(this.options[this.selectedIndex].value)'>
	";
    //分页下拉菜单
    if ($AllPage > 0) {
        $x = 1;
        while ($x <= $AllPage) {
            if ($page == $x) {
                $selected = " selected='selected' ";
            } else {
                $selected = "";
            }
            $return .= "<option value='{$url}{$b}page={$x}' {$selected}>第{$x}页</option>";
            $x++;
        }
    } else {
        $return .= "<option>第1页</option>";
    }
    $return .= "
		</select>
	</div>
	";
    return $return;
}

/**********网站头部********************************************************/
function head($type, $title=null)
{
    if ($type == "ad") {
        $css = "
		<link rel='stylesheet' type='text/css' href='" . root . "control/ku/css.css?v=" . version . "'>
		<link rel='stylesheet' type='text/css' href='" . root . "control/ku/extend.css?v=" . version . "'>
		<link rel='stylesheet' type='text/css' href='" . root . "control/ku/layer/css/layui.css?v=" . version . "'>
		";
        $js = "
		<script type='text/javascript' charset='UTF-8' src='" . root . "control/ku/js.js?v=" . version . "'></script>
		<script type='text/javascript' charset='UTF-8' src='" . root . "control/ku/extend.js?v=" . version . "'></script>
		<script type='text/javascript' charset='UTF-8' src='" . root . "control/ku/layer/layui.js?v=" . version . "'></script>
		";
    } elseif ($type == "pc") {
        $css = "<link rel='stylesheet' type='text/css' href='" . root . "library/pc.css?v=" . version . "'>";
        $js = "<script type='text/javascript' charset='UTF-8' src='" . root . "library/pc.js?v=" . version . "'></script>";
    } elseif ($type == "m") {
        $css = "
		<link rel='stylesheet' type='text/css' href='" . root . "library/m.css?v=" . version . "'>
		<link rel='stylesheet' type='text/css' href='" . root . "library/banner.css?v=" . version . "'>
		";
        $js = "
		<script type='text/javascript' charset='UTF-8' src='" . root . "library/m.js?v=" . version . "'></script>
		<script type='text/javascript' charset='UTF-8' src='" . root . "library/banner.js?v=" . version . "'></script>
		";
        $meta = "
		<meta name='viewport' content='width=device-width,initial-scale=1,user-scalable=no'>
		<meta name='apple-mobile-web-app-capable' content='yes'/>
		<meta name='apple-mobile-web-app-status-bar-style' content='black'/>
		<meta name='format-detection' content='telephone=no,email=no,adress=no'/>
		";
    }
    if (empty($title)) {
        $title = website("title");
    }
    return "
	<!DOCTYPE html>
	<html>
	<head>
		<title>{$title}</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<meta name='keywords' content='" . website("keywords") . "'>
		<meta name='description' content='" . website("description") . "'>
		{$meta}{$css}
		<script type='text/javascript' src='" . root . "library/jquery-1.11.2.min.js'></script>
		<script type='text/javascript' src='" . root . "library/lib.js'></script>
		{$js}
		<link rel='Bookmark' type='image/x-icon'  href='" . root . "favicon.ico'/>  
		<link rel='icon' type='image/x-icon' href='" . root . "favicon.ico' />  
		<link rel='shortcut icon' type='image/x-icon' href='" . root . "favicon.ico' />  
		<link rel='apple-touch-icon' href='" . root . "favicon.ico'>
	</head>
	<body>
	";
    //删除每次请求，保存数据库文件，改用crontab；

}

/**********警示弹出层********************************************************/
function warn()
{
    if (!empty($_SESSION['warn'])) {
        $warn = $_SESSION['warn'];//接收session中的提示信息
        unset($_SESSION['warn']);
    } elseif (!empty($GLOBALS['warn'])) {//接收全局变量中的提示信息
        $warn = $GLOBALS['warn'];
    }
    if (!empty($warn)) {
        $html = "<script>$(function(){warn('{$warn}')})</script>";
    }
    $html .= "
	<div class='hide' id='warn'>
	    <div class='dibian' style='z-index:100'></div>
		<div class='win' style=' width:300px;height:160px; margin:-80px 0 0 -150px;z-index:101;'>
			<p class='winTitle'>温馨提示<span onclick=\"$('#warn').hide()\" class='winClose'>×</span></p>
			<div id='warnWord'>无</div>
			<div id='warnSure' onclick=\"$('#warn').hide()\">确定</div>
			<div id='warnCancel' onclick=\"$('#warn').hide()\">取消</div>
		</div>
	</div>
	";
    return $html;
}

/**********图像处理函数********************************************************/
/*
函数目的：上传图像（可以更新图像、新增图像、裁剪图像、缩放图像）
变量解释：
$FileName为上传图片的表单文件域名称
$cut['type']为《需要裁剪》或《需要缩放》或空，$cut['width']为裁剪的宽度，$cut['height']为裁剪的高度，$cut['NewWidth']为缩放的宽度，$cut['MaxHeight']为缩放后图片的最大高度。
$type['name']为《更新图像》或《新增图像》,$type['num']为新增图像时限定的图像总数,$sql为查询图片的数据库代码,$column为保存图片的数据库列的名称。
$Url['root']为图片处理页相对于网站根目录的级差，如差一级及标注为（../），$Url['NewImgUrl']新图片保存的网站根目录位置
$NewImgSql为保存图片的数据库代码,$ImgWarn为图片保存成功后返回的文字内容
*/
function UpdateImg($FileName, $cut, $type, $sql, $column, $Url, $NewImgSql, $ImgWarn)
{
    if (isset($_FILES[$FileName])) {
        $img = ImgType($_FILES[$FileName]["type"], $_FILES[$FileName]["tmp_name"]);
        $height = imagesy($img);
        $width = imagesx($img);
        if (empty($_FILES[$FileName]['tmp_name'])) {
            $_SESSION['warn'] = "请上传图像！";
        } elseif ($img == "") {
            $_SESSION['warn'] = "不是图片或格式不对！";
        } elseif ($cut['type'] == "需要缩放" and $cut['NewWidth'] * $height / $width > $cut['MaxHeight']) {
            $_SESSION['warn'] = "图像缩放后高度仍然超过{$cut['MaxHeight']}像素！";
        } else {
            /*******裁剪图像*******************************/
            if ($cut['type'] == "需要裁剪") {
                //新建一个真彩色图像
                $NewImg = imagecreatetruecolor($cut['width'], $cut['height']);
                //放置图像
                $OldRatio = $width / $height;
                $NewRatio = $cut['width'] / $cut['height'];
                if ($OldRatio == $NewRatio) {//宽高比一致
                    imagecopyresampled($NewImg, $img, 0, 0, 0, 0, $cut['width'], $cut['height'], $width, $height);
                } elseif ($OldRatio > $NewRatio) {//上传的图像过宽
                    $NewWidth = $height * $cut['width'] / $cut['height'];
                    $CutLeft = ($width - $NewWidth) / 2;
                    imagecopyresampled($NewImg, $img, 0, 0, $CutLeft, 0, $cut['width'], $cut['height'], $NewWidth, $height);
                } elseif ($OldRatio < $NewRatio) {//上传的图像过高
                    $NewHeight = $width * $cut['height'] / $cut['width'];
                    $CutTop = ($height - $NewHeight) / 2;
                    imagecopyresampled($NewImg, $img, 0, 0, 0, $CutTop, $cut['width'], $cut['height'], $width, $NewHeight);
                }
            } else {
                $NewImg = $img;
            }
            /*******判断图像处理方式*******************************/
            if ($type['name'] == "新增图像") {
                if (mysql_num_rows(mysql_query($sql)) < $type['num']) {
                    $ImgFinger = 1;
                } else {
                    $_SESSION['warn'] = "最多只能上传{$type['num']}张图像！";
                    $ImgFinger = 2;
                }
            } elseif ($type['name'] == "更新图像") {
                if (mysql_num_rows(mysql_query($sql)) == 1) {
                    $Result = mysql_fetch_array(mysql_query($sql));
                    //如果以前上传过图像，则删除旧的图像
                    if ($Result[$column] != "") {
                        unlink($Url['root'] . $Result[$column]);
                    }
                    $ImgFinger = 1;
                } else {
                    $ImgFinger = 2;
                }
            } else {
                $ImgFinger = 2;
            }
            /*******处理图像*******************************/
            if ($ImgFinger == 1) {
                //保存图片到服务器
                imagejpeg($NewImg, $Url['root'] . $Url['NewImgUrl']);
                //将保存地址存入数据库
                mysql_query($NewImgSql);
                // 释放内存
                imagedestroy($img);
                imagedestroy($NewImg);
                //如果图像过大，则适当缩放图像
                if ($cut['type'] == "需要缩放" and $width > $cut['NewWidth']) {
                    JpegSmallWidth($Url['root'] . $Url['NewImgUrl'], $cut['NewWidth']);
                }
                //返回信息
                $_SESSION['warn'] = $ImgWarn;
            }
        }
    }
}

/*
函数目的：上传图像-不压缩，不裁剪。可以限制图片的宽度、高度、最大高度和最大体积。判断无误后直接存入服务器（可以更新图像、新增图像）
变量解释：
$FileName为上传图片的表单文件域名称
$Rule['MaxSize']为图像的最大容量，$Rule['width']为图像要求的宽度，$Rule['height']为图像要求的高度，$Rule['MaxHeight']是当图像要求的高度为空时，判断图片要求最高的高度（超高图片切片时需要）
$type['name']为《更新图像》或《新增图像》,$type['num']为新增图像时限定的图像总数,$sql为查询图片的数据库代码,$column为保存图片的数据库列的名称,
$Url['root']为图片处理页相对于网站根目录的级差，如差一级及标注为（../），$Url['NewImgUrl']新图片保存的网站根目录位置
$NewImgSql为保存图片的数据库代码,$ImgWarn为图片保存成功后返回的文字内容
*/
function UpdateCheckImg($FileName, $Rule, $type, $sql, $column, $Url, $NewImgSql, $ImgWarn)
{
    if (isset($_FILES[$FileName])) {
        $ImgName = $_FILES[$FileName]["tmp_name"];
        $ImgType = $_FILES[$FileName]["type"];
        $ImgSize = getimagesize($ImgName);
        $ImgWidth = $ImgSize[0];
        $ImgHeight = $ImgSize[1];
        //修正高度判断
        if ($Rule['height'] == "") {
            if ($Rule['MaxHeight'] == "") {
                $_SESSION['warn'] = "未设定高度！";
                $RuleFinger = 1;
            } else {
                if ($ImgHeight < $Rule['MaxHeight']) {
                    $RuleFinger = 2;
                } else {
                    $_SESSION['warn'] = "图片超高，建议切图上传。";
                    $RuleFinger = 3;
                }
            }
        } else {
            if ($ImgHeight == $Rule['height']) {
                $RuleFinger = 2;
            } else {
                $_SESSION['warn'] = "图片高度不等于{$Rule['height']}像素！";
                $RuleFinger = 4;
            }
        }
        if (empty($ImgName)) {
            $_SESSION['warn'] = "请上传图像！";
        } elseif ($ImgType == "image/jpeg" || $ImgType == "image/pjpeg" || $ImgType == "image/png" || $ImgType == "image/x-png" || $ImgType == "image/gif") {
            if ($_FILES[$FileName]["size"] > $Rule['MaxSize']) {
                $MaxSizeKb = $Rule['MaxSize'] / 1000;
                $_SESSION['warn'] = "图片大小不能超过{$MaxSizeKb}KB！";
            } elseif ($ImgWidth != $Rule['width']) {
                $_SESSION['warn'] = "图片宽度不等于{$Rule['width']}像素！";
            } elseif ($RuleFinger == 2) {
                /*******判断图像处理方式*******************************/
                if ($type['name'] == "新增图像") {
                    if (mysql_num_rows(mysql_query($sql)) < $type['num']) {
                        $ImgFinger = 1;
                    } else {
                        $_SESSION['warn'] = "最多只能上传{$type['num']}张图像！";
                        $ImgFinger = 2;
                    }
                } elseif ($type['name'] == "更新图像") {
                    if (mysql_num_rows(mysql_query($sql)) == 1) {
                        $Result = mysql_fetch_array(mysql_query($sql));
                        //如果以前上传过图像，则删除旧的图像
                        if ($Result[$column] != "") {
                            unlink($Url['root'] . $Result[$column]);
                        }
                        $ImgFinger = 1;
                    } else {
                        $_SESSION['warn'] = "数据库未查到记录";
                        $ImgFinger = 2;
                    }
                } else {
                    $_SESSION['warn'] = "未知图像处理方式";
                    $ImgFinger = 2;
                }
                /*******处理图像*******************************/
                if ($ImgFinger == 1) {
                    //保存图片到服务器
                    move_uploaded_file($ImgName, $Url['root'] . $Url['NewImgUrl']);
                    //将保存地址存入数据库
                    mysql_query($NewImgSql);
                    //返回信息
                    $_SESSION['warn'] = $ImgWarn;
                }
            }
        } else {
            $_SESSION['warn'] = "不是图片或格式不对！";
        }
    }
}

/**********判断上传的图像格式，并根据图像的地址和格式创建新图像到内存中********************************************************/
function ImgType($type, $name)
{
    if ($type == "image/jpeg" || $type == "image/pjpeg") {
        $img = imagecreatefromjpeg($name);
    } elseif ($type == "image/png" || $type == "image/x-png") {
        $img = imagecreatefrompng($name);
    } elseif ($type == "image/gif") {
        $img = imagecreatefromgif($name);
    } else {
        $img = "";
    }
    return $img;
}

/**********获取图片地址并根据指定宽度强制缩放JPEG图像********************************************************/
//$ImgUrl为图片地址，$NewWidth为指定宽度
function JpegSmallWidth($ImgUrl, $NewWidth)
{
    $img = imagecreatefromjpeg($ImgUrl);
    $height = imagesy($img);
    $width = imagesx($img);
    $NewHeight = $NewWidth * ($height / $width);
    //创建一个新的图像
    $SmallImg = imagecreatetruecolor($NewWidth, $NewHeight);
    //将原图像缩放并放入新图像中
    imagecopyresampled($SmallImg, $img, 0, 0, 0, 0, $NewWidth, $NewHeight, $width, $height);
    //保存图像至原图像地址
    imagejpeg($SmallImg, $ImgUrl);
    // 释放内存
    imagedestroy($SmallImg);
}

/**********图文混排的文章编辑********************************************************/
//变量解释：$Target为文章对象，$TargetName为文章对象的表名称，$TargetId为当前文章主人的id号,$imgurl为图片的子文件夹名称，$ImgMaxWidth为图片的最大宽度（超过此宽度则会缩放为此宽度）
function article($Target, $TargetId, $imgurl, $ImgMaxWidth)
{
    $html = "";
    $ArticleSql = mysql_query(" select * from article where Target = '$Target' and TargetId = '$TargetId' order by list ");
    if (mysql_num_rows($ArticleSql) == 0) {
        $html .= "<div class='kuang'>没有任何内容</div>";
    } else {
        while ($array = mysql_fetch_array($ArticleSql)) {
            if (empty($array['img'])) {
                $content = "<div ArticleWordContentId='{$array['id']}' class='articleMx'><p>" . neirong($array['word']) . "</p></div>";
                $istype = "word";
            } else {
                $content = "<div class='center'><img src='" . root . "{$array['img']}'></div>";
                $istype = "img";
            }
            $html .= "
			<a name='{$array['id']}'>
			<div class='kuang relative TextIndent'>
				<div title='点击更新序列号' articleEditList='{$array['list']}' isid='{$array['id']}' class='articleList articleControl'>{$array['list']}</div>
				<div articleEditType='{$istype}' isid='{$array['id']}' class='articleEdit articleControl'>编辑这段</div>
				<a href='" . root . "library/libPost.php?articleDelete={$array['id']}'><div class='articleDelete articleControl'>X</div></a>
				{$content}
			</div>
			</a>
			";
        }
    }
    $html .= "
	<!--窗口浮标开始-->
	<div id='addArticleWordButton'>
		<img src='" . root . "img/images/ArticleAddWord.png'>
		<p>添加一段文字</p>
	</div>
	<div id='addArticleImgButton'>
		<img src='" . root . "img/images/ArticleAddImg.png'>
		<p>添加一张图片</p>
	</div>
	<!--窗口浮标结束-->
	<!--文字编辑弹出层开始-->
	<div class='hide' id='articleWordEdit'>
		<div class='dibian'></div>
		<div class='win' style='width:600px; height:354px; margin:-172px 0 0 -300px;'>
			<p class='winTitle'>文字编辑器<span class='winClose' onClick=\"$('#articleWordEdit').hide()\">×</span></p>
			<form name='articleWordForm'>
				<textarea name='articleText' class='textarea' style='width:590px; height:260px; border:0;'></textarea>
				<input name='articleTextId' type='hidden'>
				<input name='target' type='hidden' value='{$Target}'>
				<input name='targetId' type='hidden' value='{$TargetId}'>
			</form>
			<p class='winFooter'><input type='button' class='button' value='提交文字' onclick=\"Sub('articleWordForm','" . root . "library/libData.php?type=articleWordEdit')\"></p>
		</div>
	</div>
	<!--文字编辑弹出层结束-->
	<!--更新序列号弹出层开始-->
	<div class='hide' id='articleListEdit'>
		<div class='dibian'></div>
		<div class='win' style='width:300px; height:127px; margin:-63px 0 0 -150px;'>
			<p class='winTitle'>更新段落序列号<span class='winClose' onClick=\"$('#articleListEdit').hide()\">×</span></p>
			<div class='padding'>
			<form name='articleListForm'>
			段落序列号：<input name='articleListText' type='text' class='text textPrice'>
			<input name='artcleListId' type='hidden'>
			</form>
			</div>
			<p class='winFooter'><input type='button' class='button' onclick=\"Sub('articleListForm','" . root . "library/libData.php?type=articleListEdit')\" value='更新段落序列号'></p>
		</div>
	</div>
	<!--更新序列号弹出层结束-->
	<!--隐藏表单开始-->
	<div class='hide'>
	<form name='articleImgForm' method='post' action='" . root . "library/libPost.php?type=articleImgEdit' enctype='multipart/form-data'>
		<input name='articleImg' type='file' onchange='document.articleImgForm.submit()' />
		<input name='artcleImgId' type='hidden'>
		<input name='Target' type='hidden' value='{$Target}'>
		<input name='TargetId' type='hidden' value='{$TargetId}'>
		<input name='imgurl' type='hidden' value='{$imgurl}'>
		<input name='ImgMaxWidth' type='hidden' value='{$ImgMaxWidth}'>
	</form>
	</div>
	<!--隐藏表单结束-->
	<script>
	$(document).ready(function(){
		//新增文字段落
		$('#addArticleWordButton').click(function(){
			$('#articleWordEdit').show();
			document.articleWordForm.articleText.value = '';
			document.articleWordForm.articleTextId.value = '';
		});
		//新增图片
		$('#addArticleImgButton').click(function(){
			document.articleImgForm.articleImg.click();
			document.articleImgForm.artcleImgId.value = '';
		});
		//编辑已有段落
		$('[articleEditType]').click(function(){
			if($(this).attr('articleEditType') == 'word'){
				$('#articleWordEdit').show();
				var articleId = $(this).attr('isid');
				document.articleWordForm.articleTextId.value= articleId;
				$.post('" . root . "library/libData.php',{ArticleTextId:articleId},function(data){
					document.articleWordForm.articleText.value = data.word;
				},'json');
			}else{
				document.articleImgForm.articleImg.click();
				document.articleImgForm.artcleImgId.value= $(this).attr('isid');
			}
		});
		//弹出序列号编辑层
		$('[articleEditList]').click(function(){
			$('#articleListEdit').show();
			document.articleListForm.articleListText.value = $(this).attr('articleEditList'); 
			document.articleListForm.artcleListId.value = $(this).attr('isid'); 
		});
	});
	</script>
	";
    return $html;
}

/**********删除本文件的同时检查本文件夹是否为空文件夹，如果是，则删除********************************************************/
function FileDelete($url)
{
    unlink($url);
    $folder = dirname($url);
    if (EmptyFolder($folder)) {
        rmdir($folder);
    }
}

/**********判断是否为空文件夹********************************************************/
//如果返回true，则为空文件夹
function EmptyFolder($dir)
{
    if ($handle = opendir($dir)) {
        while (($item = readdir($handle)) !== false) {
            if ($item != "." && $item != "..") {
                return false;
            }
        }
    }
    return true;
}

/**********判断客户设备函数********************************************************/
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if (
            (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
        ) {
            return true;
        }
    }
    return false;
}

/**************判断是否为微信浏览器*************************/
function isWeixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], "MicroMessenger") !== false) {
        return true;
    }
    return false;
}

/**********服务器之间数据交换********************************************************/
function curl($url, $data)
{
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);//设置超时（秒）
    curl_setopt($ch, CURLOPT_URL, $url);//需要获取的URL地址
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_HEADER, FALSE);//启用时会将头文件的信息作为数据流输出。（因为是模拟post信息，所以不需要输出头文件）
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验2
    curl_setopt($ch, CURLOPT_POST, true);//启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//全部数据使用HTTP协议中的"POST"操作来发送。
    $Result = curl_exec($ch);//执行一个cURL会话
    print_r($Result);
    //返回结果
    if ($Result) {
        return $Result;
    } else {
        return "curl出错:" . curl_errno($ch);
    }
    curl_close($ch);//关闭一个cURL会话并且释放所有资源。cURL句柄ch 也会被释放。
}

/*********微信自动登录注册函数**************************/
/*开发文档：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455784140&token=&lang=zh_CN
*1、修改授权回调域名：开发 - 接口权限 - 网页服务 - 网页授权 - 网页授权获取用户基本信息，如www.yumukeji.com
*$ThisUrl为当前页面路径
*/
function wxLogin($ThisUrl)
{
    $appId = para("wxAppid");
    $appSecret = para("wxAppSecret");
    $time = date("Y-m-d H:i:s");
    $shareId = $_GET['shareId'];//分享人ID号
    if (empty($_GET['code'])) {
        //获取code
        $ThisUrl = urlencode($ThisUrl);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$ThisUrl}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header("Location:{$url}");
        exit(0);
    } else {
        //获取openid
        $token = json_decode(file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$appSecret}&code={$_GET['code']}&grant_type=authorization_code"), true);
        $openid = $token['openid'];
        //自动登录注册
        if (empty($openid)) {
            $warn = "empty openid";
        } else {
            //获取用户基本信息
            $umes_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$openid}&lang=zh_CN";
            $user = json_decode(file_get_contents($umes_url), true);
            //用户昵称
            $nickname = $user['nickname'];
            //用户性别
            if ($user['sex'] == 1) {
                $sex = "男";
            } else if ($user['sex'] == 2) {
                $sex = "女";
            } else if ($user['sex'] == 0) {
                $sex = "未知";
            }
            //用户头像
            $ico = $user['headimgurl'];
            //用户地址
            $address = $user['country'] . $user['province'] . $user['city'];
            //获取用户基本信息链接   access_token用于获取用户基本信息
            $subtoken = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}"), true);
            $subscribeuser = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$subtoken['access_token']}&openid={$openid}&lang=zh_CN"), true);
            $subscribe = $subscribeuser['subscribe'];
            if ($subscribe == 0) {
                $subscribe = "未关注";
            } elseif ($subscribe == 1) {
                $subscribe = "已关注";
            }
            //通过openid找对应的客户是否注册
            $kehu = query("kehu", " wxOpenid = '$openid' ");
            //如果查询不到说明没有注册
            if (empty($kehu['khid'])) {
                $khid = rand(1000000000, 9999999999);
                while (mysql_num_rows(mysql_query(" select * from kehu where khid = '$khid' ")) > 0) {
                    $khid = rand(1000000000, 9999999999);
                }
                $bool = mysql_query(" insert into kehu (khid,wxOpenid,wxSex,wxNickName,wxAddress,wxIco,wxFollow,shareId,updateTime,time) 
				values ('$khid','$openid','$sex','$nickname','$address','$ico','$subscribe','$shareId','$time','$time') ");
                if ($bool) {
                    $_SESSION['khid'] = $khid;
                    header("location:" . $ThisUrl);
                    exit(0);
                } else {
                    $warn = "insert kehu is error";
                }
                //查询到了，说明客户注册则为客户登录并随着客户资料的变动而更新
            } else {
                $bool = mysql_query(" update kehu set 
				wxSex = '$sex',
				wxNickName = '$nickname',
				wxAddress = '$address',
				wxIco = '$ico',
				wxFollow = '$subscribe',
				updateTime = '$time' where khid = '$kehu[khid]' ");
                if ($bool) {
                    $warn = " update kehu is ok ";
                    $_SESSION['khid'] = $kehu['khid'];
                    header("location:" . $ThisUrl);
                    exit(0);
                } else {
                    $warn = " update kehu is error ";
                }
            }
        }
    }
    return $warn;
}

/*********签名函数**************************/
function wxsign($parameter)
{
    $key = para("wxPayKey");//key不参与字典排序
    ksort($parameter);//按照键名对数组升序排序，为数组值保留原来的键。
    //拼接url
    $buff = "";
    foreach ($parameter as $k => $v) {
        $buff .= "{$k}={$v}&";
    }
    $buff .= "key={$key}";
    $sign = strtoupper(MD5($buff));
    return $sign;
}

/*********array转xml**************************/
function arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if (is_numeric($val)) {
            $xml .= "<{$key}>{$val}</{$key}>";
        } else {
            $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
        }
    }
    $xml .= "</xml>";
    return $xml;
}

function arrayToXmlMy($arr)
{
    $xml = "<xml>";
    /*$xml = '<?xml version="1.0" encoding="UTF-8"?>';*/
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

/*********将XML转为array**************************/
function xmlToArray($xml)
{
    $array = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);//把 XML 字符串载入对象中,LIBXML_NOCDATA为附加的Libxml参数。意为把 CDATA 设置为文本节点。
    $array = json_encode($array);//对除resource（资源类型，保存了到外部资源的一个引用）类型之外的任何数据类型进行JSON编码
    $array = json_decode($array, true);//接受一个 JSON 格式的字符串并且把它转换为 PHP 变量，当该参数为 TRUE 时，将返回 array 而非 object 。
    return $array;
}

/*********通过微信openid获取客户信息**************************/
//必须要关注公众号才能使用此函数获得客户信息
function wxData($openid)
{
    $appid = para("wxAppid");
    $secret = para("wxAppSecret");
    $json = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}");
    $token = json_decode($json, true);
    $UserJson = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token['access_token']}&openid={$openid}&lang=zh_CN");
    $user = json_decode($UserJson, true);
    if ($user['subscribe'] == 1) {
        if ($user['sex'] == 1) {
            $data['sex'] = "男";
        } else if ($user['sex'] == 2) {
            $data['sex'] = "女";
        }
        $data['nickname'] = $user['nickname'];
        $data['ico'] = $user['headimgurl'];//头像地址
        $data['address'] = $user['country'] . $user['province'] . $user['city'];
    } else {
        $data['warn'] = "未关注公众号";
    }
    return $data;
}

/**********文件类型识别********************************************************/
//$typeNow为当前文件类型
//注fileType为php自带函数
function typeFile($typeNow)
{
    $type = array(
        "img" => array(
            "image/jpeg",
            "image/png",
            "image/gif"
        ),
        "word" => array(
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/msword"
        ),
        "excel" => array(
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.ms-excel"
        )
    );
    foreach ($type as $key => $value) {
        if (in_array($typeNow, $value)) {
            return $key;
        }
    }
    return false;
}

?>
<?php
/*
*移动端函数库
*/
include (dirname(__FILE__))."/openFunction.php";
/*********移动端使用微信自动登录函数***********************/
//需要上线后打开;
$ThisUrl = 'http://'.$ThisUrl;
//if(isWeixin()){
//    if($KehuFinger == 2){
//        wxLoginUp($ThisUrl);
//    }
//    //根据用户
//    $kehuData = query("kehu"," khid='{$_SESSION['khid']}' ");
//    if( (empty($kehuData['tel']) || empty($kehuData['password']))&& strpos($ThisUrl,'mRegister') == FALSE){
//        header("Location: http://ju.yanfaguanjia.com/m/mRegister.php");
//    }
//}else{
//    echo head('m');
//    die('请在微信中打开');
//}


function wxLoginUp($ThisUrl)
{
    $appId = para("wxAppid");
    $appSecret = para("wxAppSecret");
    $time = date("Y-m-d H:i:s");
    $shareId = $_GET['shareId'];//分享人ID号
    if(empty($_GET['code'])){
        //获取code
        $ThisUrl = urlencode($ThisUrl);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri=$ThisUrl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header("Location:{$url}");
        exit(0);
    }else{
        //获取openid
        $token=json_decode(file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$appSecret}&code={$_GET['code']}&grant_type=authorization_code"),true);
        $openid = $token['openid'];
        //自动登录注册
        if(empty($openid)){
            $warn = "授权登录失败，请重新授权!";
        }else{
            //获取用户基本信息
            $umes_url="https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$openid}&lang=zh_CN";
            $user=json_decode(file_get_contents($umes_url),true);
            //用户昵称
            $nickname = $user['nickname'];
            //用户性别
            if($user['sex'] == 1){
                $sex = "男";
            }else if($user['sex'] == 2){
                $sex = "女";
            }else if($user['sex'] == 0){
                $sex = "未知";
            }
            //用户头像
            $ico = $user['headimgurl'];
            //用户地址
            $address = $user['country'].$user['province'].$user['city'];
            //获取用户基本信息链接   access_token用于获取用户基本信息
            $subtoken=json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}"),true);
            $subscribeuser=json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$subtoken['access_token']}&openid={$openid}&lang=zh_CN"),true);
            $subscribe = $subscribeuser['subscribe'];
            if($subscribe == 0){
                $subscribe = "未关注";
            }elseif($subscribe == 1){
                $subscribe = "已关注";
            }
            //通过openid找对应的客户是否注册
                $kehu = query("kehu"," wxOpenid = '$openid' ");
            //如果查询不到说明没有注册
            if(empty($kehu['khid'])){
                $khid = rand(1000000000,9999999999);
                while(mysql_num_rows(mysql_query("SELECT * FROM kehu WHERE khid = '$khid' ")) > 0){
                    $khid = rand(1000000000,9999999999);
                }
                $bool = mysql_query("INSERT INTO kehu (khid,wxOpenid,wxSex,wxNickName,wxAddress,wxIco,wxFollow,shareId,updateTime,time) 
                VALUES ('$khid','$openid','$sex','$nickname','$address','$ico','$subscribe','$shareId','$time','$time') ");
                if($bool){
                    if( !empty($shareId) )
                    {
                        $shareFree = para('shareFree');
                        $sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderTime`, `sales`, `free`, `time`) VALUES ('推荐','$shareId','$khid','$nickname','$time','$shareFree','$shareFree','$time')";
                        mysql_query($sql);
                    }
                    $_SESSION['khid'] = $khid;
                    header("location:".$ThisUrl);
                    exit(0);
                }else{
                    $warn = "insert kehu is error";
                }
            //查询到了，说明客户注册则为客户登录并随着客户资料的变动而更新
            }else{
                $bool = mysql_query("UPDATE kehu SET 
                wxSex = '$sex',
                wxNickName = '$nickname',
                wxAddress = '$address',
                wxIco = '$ico',
                wxFollow = '$subscribe',
                updateTime = '$time' WHERE khid = '$kehu[khid]' ");
                if($bool){
                    $warn = " update kehu is ok ";
                    $_SESSION['khid'] = $kehu['khid'];
                    header("location:".$ThisUrl);
                    exit(0);
                }else{
                    $warn = " update kehu is error ";
                }
            }
        }
    }
    return $warn;
}
/**
 * 分享接口
 */
function share($title,$imgurl,$desc)
{
    $appid      = para('wxAppid');
    //token
    if( $_SESSION['access_token']['expires_in'] > time() )
    {
        $access_token = $_SESSION['access_token']['access_token'];
    }else{
        $appsecret  = para('wxAppSecret');
        $url        = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $tokenArr    = json_decode(curl($url),true);
        $access_token = $_SESSION['access_token']['access_token'] = $tokenArr['access_token'];
        $_SESSION['access_token']['expires_in'] = time() + 7000;    
    }
    //ticket
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $ticketArr = json_decode(curl($url),true);
        echo '<pre>';
            print_r($ticketArr);
        echo '</pre>';
    if( $_SESSION['ticket']['expires_in'] > time() ){
        $ticket = $_SESSION['ticket']['ticket'];
    }else{
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $ticketArr = json_decode(curl($url),true);
        echo '<pre>';
            print_r($ticketArr);
        echo '</pre>';
        if( $itckteArr['errcode'] == 0 ){
            $ticket = $_SESSION['ticket']['ticket'] = $ticketArr['ticket'];
            $_SESSION['ticket']['expires_in'] = time() + 7000;    
        }
    }
    echo '<pre>';
        print_r($_SESSION);
    echo '</pre>';
    $nonceStr = suiji();
    $timestamp = time();
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $signature="{$noncestr}&jsapi_ticket={$ticket}timestamp={$timestamp}&url={$url}";
    $signature = sha1($signature);
    $str = "
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '".$appid."', // 必填，公众号的唯一标识
            timestamp: {$timestamp}, // 必填，生成签名的时间戳
            nonceStr: '$nonceStr', // 必填，生成签名的随机串
            signature: '$signature',// 必填，签名，见附录1
            jsApiList: [
                'onMenuShareTimeline','onMenuShareAppMessage'
            ]
        });
        wx.ready(function(){
            wx.onMenuShareAppMessage({
                title: '$title', // 分享标题
                desc: '$desc', // 分享描述
                link: '', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '$imgUrl', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            });
            wx.onMenuShareTimeline({
                title: '$title', // 分享标题
                link: '', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '$imgUrl', // 分享图标
            });
        });
    </script>";
    return $str;
}

/*********底部***********************/
function mFooter(){
    $html = "
            <div class='footer mui-fixed'>
                <ul class='mui-dis-flex'>
                    <li> <a href='".root."m/mIndex.php'> <span class='mindex'><img  src='".imgt('RhM87872111Oi')."'/></span><p>首页</p></a> </li>
                    <li> <a href='".root."m/mGoodsClass.php'> <span class='mclassy'><img  src='".imgt('Orm87872391uM')."'/></span><p>分类</p></a> </li>
                    <li> <a href='".root."m/mNeed.php'> <span class='mcar'><img src='".imgt('yik87872420ou')."'/></span><p>需求</p></a> </li>
                    <li> <a href='".root."m/mUser/mBuyCar.php'> <span class='mcar'><img  src='".imgt('kQX87872439tR')."'/></span><p>订货单</p></a> </li>
                    <li> <a href='".root."m/mUser/mUser.php'> <span class='musercenter'><img  src='".imgt('xMd87872458PK')."'/></span><p>我的</p></a> </li>
                </ul>
            </div>
        </body>
        </html>
    ";
    return $html;
}

/**************滚动至底部显示我是有底线的**************/
function footerLine(){
$html = "
<div class='footer-line' style='display:none'>
    <p>我是有底线的</p>
</div>

<script type='text/javascript'>
//判断整个文档到底部
$(window).scroll(function(){
    //滚动条所在位置的高度
    totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
    //当前文档高度   小于或等于   滚动条所在位置高度  则是页面底部
    if(($(document).height()) <= totalheight) {
        //页面到达底部
        $('.footer-line').css('display','block');
    }
});
</script>
";
return $html;
}



/*********个人中心底部***********************/
function mUfooter(){
    $html = "
            <div class='footer mui-fixed'>
                <ul class='mui-dis-flex'>
                    <li> <a href='".root."m/mIndex.php'> <span class='mindex'>&#xe606;</span><p>首页</p></a> </li>
                    <li> <a href='".root."m/mGoodsClass.php'> <span class='mclassy'>&#xe6ae;</span><p>分类</p></a> </li>
                    <li> <a href='".root."m/mNeed.php'> <span class='mcar'>&#xe641;</span><p>需求</p></a> </li>
                    <li> <a href='".root."m/mUser/mOrder.php'> <span class='mcar'>&#xe63a;</span><p>订货单</p></a> </li>
                    <li> <a href='".root."m/mUser/mUser.php'> <span class='musercenter'>&#xe602;</span><p>我的</p></a> </li>
                </ul>
            </div>
        </body>
        </html>
    ";
    return $html;   
}
/**
 * 移动端弹出层
 * @return void
 */
function mWarn()
{
    if (isset($_SESSION['warn']) and !empty($_SESSION['warn'])) {
        $GLOBALS['warn'] = $_SESSION['warn']; //使用全局变量的原因：$warn可能从函数外部传入
        unset($_SESSION['warn']);
    }
    if (!empty($GLOBALS['warn'])) {
        $show = "mwarn('{$GLOBALS['warn']}');";
    }
    $html .= "
    <div id='cover'>
        <div id='cover_con'>
            <p id='coverP'>空</p>
            <div>
                <button id='coverSure'>确 认</button>
                <button id='coverCancel'>取 消</button>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function(){
        {$show}
        $('#coverSure,#coverCancel').click(function(){
            $('#cover').hide();
        });
    });
    function mwarn(word){
        $('#cover').show();
        $('#coverP').html(word);
    }
    </script>";
    return $html;
}
/**
 * 环信 微信渠道集成
 *
 * @return void
 */
function easemobBuild($jpgUrl)
{
    global $ThisUrl;
    global $kehu;    
    $html = "
    <script>
        window.easemobim = window.easemobim || {};
        easemobim.config = {
            configId: '".para('hxConfigId')."',
            hide: true,
            autoConnect: true,
            visitor: {
                trueName: '{$kehu['name']}',
                phone: '{$kehu['tel']}',
                userNickname: '{$kehu['wxNickName']}',
                description: '推荐人ID：{$kehu['shareId']}',
                email: '{$kehu['email']}'
            },
            //聊天窗口加载成功回调
            onready: function () {
                easemobim.sendExt({
                    ext:{
                        \"imageName\": \"mallImage3.png\",
                        \"type\": \"custom\",
                        \"msgtype\": {
                            \"track\":{
                                \"title\":\"我正在看：\",
                                \"price\":\"¥: 235.00\",
                                \"desc\":\"女装小香风气质蕾丝假两件短袖\",
                                \"img_url\":\"{$jpgUrl}\",
                                \"item_url\":\"{$ThisUrl}\"
                            }
                        }
                    }
                });
            },
        };
    </script>
    <script src='//kefu.easemob.com/webim/easemob.js'></script>";
    return $html;
}
/**
 * 分类轮播图
 * @return void
 */
function goodsClassBanner()
{
    $res = findAll('img',"type = '分类轮播图' ORDER BY list");
    foreach ($res as $val)
    {
        $classImg .= "
        <div class='swiper-slide'>
            <a href='{$val['url']}'><img src='".root."{$val['src']}'></a>
        </div>";
    }
    return $classImg;
}
/**
 * ico图标
 * @return void
 */
function icoImgBuild()
{
    $res = findAll('img',"type = '首页ico图片' ORDER BY list");
    foreach ($res as $key => $val)
    {
        $icoImg .= "<li><a href='{$val['url']}'><span class='strategy'><i><img src='".root."{$val['src']}' /></i></span><p>{$val['name']}</p></a></li>";
    }
    return $icoImg;
}
/**
 * 会员轮播图片
 * @return void
 */
function memberBannerBuild()
{
    $res = findAll('img',"type = '会员轮播图' ORDER BY list");
    if( $res ){
        foreach ($res as $key => $val)
        {
            $html .= "
            <div class='swiper-slide'>
                <a href=''><img src='".root."{$val['src']}'/></a>
            </div>";
        }
    }
    return $html;
}
/**
 * 判断是否未注册用户
 * @author r7
 * @return boolean
 */
function isRegister()
{
    if(!empty($_SESSION['khid']))
    {
        $info = findOne('kehu',"khid = '{$_SESSION['khid']}'",'tel');
    }
    if( empty($info['tel']) )
    {
        header("location:".root."m/mRegister.php");
        die();
    }else{
        return '';
    }
}
/**
 * 购物车地址生成
 */
function buyCarAddressShow()
{
    global $kehu;
    if( !empty($_SESSION['addressId']) ){
        $res = findOne('address',"id = '{$_SESSION['addressId']}'");
        $region = findOne('region',"id = '{$res['regionId']}'");
    }else{
        if( !empty($kehu['address']) ){
            $res = findOne('address',"id = '{$kehu['address']}'");
            $region = findOne('region',"id = '{$res['regionId']}'");
        }
        $_SESSION['addressId'] = $kehu['address'];
    }

    $dataArr['name'] = $res['contactName'];
    $dataArr['tel'] = $res['contactTel'];
    $dataArr['region'] = $region['province'].$region['city'].$region['area'].$res['addressMx'];
    return $dataArr;
}
//$self = $_SERVER['PHP_SELF'];   #当前的网页地址
//if( strpos($self,'mIndex.php') !== false || strpos($self,'mRegister.php') !== false || strpos($self,'test.php') !== false ){
//
//}else{
//    //test();
//    //isRegister();
//}
function isRegisterUp($kehu)
{
    /* if( empty($kehu['type']) || ( empty($kehu['type']) && empty($kehu['tel']) ) ){
        header("location:".root."m/mRegister.php");
        die();
    } */
    if( empty($kehu['type']) && empty($kehu['tel']) ){
        header("location:".root."m/mRegister.php");
        die();
    }
}

?>
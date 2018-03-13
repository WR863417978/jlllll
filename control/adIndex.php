<?php
include "ku/adfunction.php";
ControlRoot();
$index = "
<li menuOne='index'>
    <img src='" . root . "img/images/triangleRight.png'>
    首页管理
</li>
";
$special = "
<li menuParent='index' menuTwo='special' class='" . menu("special.php", "frameLeftHover") . "' iframeHref='" . root . "control/index/special.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>专题管理</span>
</li>
";
$upnew = "
<li menuParent='index' menuTwo='upnew' class='" . menu("upnew.php", "frameLeftHover") . "' iframeHref='" . root . "control/index/upnew.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>上新专区</span>
</li>
";
if (powerPage("adspecial")) {
   // echo 1;die;
    $index .= $special;
}
if (powerPage("adupnew")) {
   // echo 1;die;
    $index .= $upnew;
}

$info = "
<li menuOne='info'>
    <img src='" . root . "img/images/triangleRight.png'>
    信息管理
</li>
";
$log = "
<li menuParent='info' menuTwo='adlog' class='" . menu("adlog.php", "frameLeftHover") . "' iframeHref='" . root . "control/info/adlog.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>日志管理</span>
</li>
";
$img = "
<li menuParent='info' menuTwo='adimg' class='" . menu("adimg.php", "frameLeftHover") . "' iframeHref='" . root . "control/info/adimg.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>图片管理</span>
</li>
";
$word = "
<li menuParent='info' menuTwo='adword' class='" . menu("adword.php", "frameLeftHover") . "' iframeHref='" . root . "control/info/adword.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>文字管理</span>
</li>
";
$article = "
<li menuParent='info' menuTwo='adContent' class='" . menu("adContent.php", "frameLeftHover") . "' iframeHref='" . root . "control/info/adContent.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>文章管理</span>
</li>
";
$info .= $log;
if (powerPage("adimg")) {
    $info .= $img;
}
if (powerPage("adword")) {
    $info .= $word;
}
if (powerPage("adContent")) {
    $info .= $article;
}
$internal = "
<li menuOne='internal'>
    <img src='" . root . "img/images/triangleRight.png'>
    内部管理
</li>
";
$staff = "
<li menuParent='internal' menuTwo='admin' class='" . menu("admin.php", "frameLeftHover") . "' iframeHref='" . root . "control/Internal/admin.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>员工管理</span>
</li>
";
$system = "
<li menuParent='internal' menuTwo='adSystem' class='" . menu("adSystem.php", "frameLeftHover") . "' iframeHref='" . root . "control/Internal/adSystem.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>管理制度</span>
</li>
";
if (powerPage("admin")) {
    $internal .= $staff;
}
if (powerPage("adSystem")) {
    $internal .= $system;
}
$finance = "
<li menuOne='finance'>
    <img src='" . root . "img/images/triangleRight.png'>
    财务管理
</li>
";
$profit = "
<li menuParent='finance' menuTwo='adProfit' class='" . menu("adProfit.php", "frameLeftHover") . "' iframeHref='" . root . "control/finance/adProfit.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>收支平衡</span>
</li>
";
$parameter = "
<li menuParent='finance' menuTwo='adParameter' class='" . menu("adParameter.php", "frameLeftHover") . "' iframeHref='" . root . "control/finance/adParameter.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>参数管理</span>
</li>
";
$account = "
<li menuParent='finance' menuTwo='adAccount' class='" . menu("adAccount.php", "frameLeftHover") . "' iframeHref='" . root . "control/finance/adAccount.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>账户管理</span>
</li>
";
$adCoupon = "
<li menuParent='finance' menuTwo='adAccount' class='" . menu("adCoupon.php", "frameLeftHover") . "' iframeHref='" . root . "control/adCoupon.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>优惠券管理</span>
</li>
";
$adWithdraw = "
<li menuParent='finance' menuTwo='adAccount' class='" . menu("adWithdraw.php", "frameLeftHover") . "' iframeHref='" . root . "control/adWithdraw.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>提现管理</span>
</li>
";
$adShare = "
<li menuParent='finance' menuTwo='adAccount' class='" . menu("adShare.php", "frameLeftHover") . "' iframeHref='" . root . "control/adShare.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>分享管理</span>
</li>
";
if (powerPage("adProfit")) {
    $finance .= $profit;
}
if (powerPage("adParameter")) {
    $finance .= $parameter;
}
if (powerPage("adAccount")) {
    $finance .= $account;
}
if (powerPage("adWithdraw")) {
    $finance .= $adWithdraw;
}
if (powerPage("adShare")) {
    $finance .= $adShare;
}
if (powerPage("adCoupon")) {
    $finance .= $adCoupon;
}

$client = "
<li menuOne='client'>
    <img src='" . root . "img/images/triangleRight.png'>
    客户管理
</li>
<li menuParent='client' menuTwo='adAccount' class='" . menu("adClient.php", "frameLeftHover") . "' iframeHref='" . root . "control/adClient.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>客户管理</span>
</li>
";

$adGoods = "
<li menuOne='goods'>
    <img src='" . root . "img/images/triangleRight.png'>
    商品管理
</li>
<li menuParent='goods' menuTwo='adAccount' class='" . menu("adGoods.php", "frameLeftHover") . "' iframeHref='" . root . "control/adGoods.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>商品管理</span>
</li>
<li menuParent='goods' menuTwo='adAccount' class='" . menu("adMemberGoods.php", "frameLeftHover") . "' iframeHref='" . root . "control/adMemberGoods.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>会员商品</span>
</li>
";
$adDemand = "
<li menuOne='demand'>
    <img src='" . root . "img/images/triangleRight.png'>
    需求管理
</li>
<li menuParent='demand' menuTwo='adAccount' class='" . menu("adDemand.php", "frameLeftHover") . "' iframeHref='" . root . "control/adDemand.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>需求管理</span>
</li>
";

$adSupplier = "
<li menuOne='supplier'>
    <img src='" . root . "img/images/triangleRight.png'>
    供应商管理
</li>
<li menuParent='supplier' menuTwo='adAccount' class='" . menu("adSupplier.php", "frameLeftHover") . "' iframeHref='" . root . "control/adSupplier.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>供应商管理</span>
</li>
";

$order = "
<li menuOne='order'>
    <img src='" . root . "img/images/triangleRight.png'>
    订单管理
</li>
<li menuParent='order' menuTwo='adAccount' class='" . menu("adOrder.php", "frameLeftHover") . "' iframeHref='" . root . "control/adOrder.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>订单管理</span>
</li>
";
$adCodeHelp = "
<li menuOne='code'>
    <img src='" . root . "img/images/triangleRight.png'>
    申请码管理
</li>
<li menuParent='code' menuTwo='adAccount' class='" . menu("adCodeHelp.php", "frameLeftHover") . "' iframeHref='" . root . "control/adCodeHelp.php'>
    <img src='" . root . "img/images/arrow.png'>
    <span>申请码管理</span>
</li>
";

//导航权限
$menu = $info;
//print_r($index);die;
if (powerPage("adspecial")) {
    $menu .= $index;
}
if (powerPage("admin,adSystem")) {
    $menu .= $internal;
}
if (powerPage("adParameter,adAccount")) {
    $menu .= $finance;
}
if (powerPage("adClient")) {
    $menu .= $client;
}
if (powerPage("adOrder")) {
    $menu .= $order;
}
if (powerPage("adGoods")) {
    $menu .= $adGoods;
}

if (powerPage("adDemand")) {
    $menu .= $adDemand;
}

if (powerPage("adSupplier")) {
    $menu .= $adSupplier;
}

if (powerPage("adCodeHelp")) {
    $menu .= $adCodeHelp;
}

echo head("ad");
?>
<div class="frameTop">
    <img src="<?php echo img("logoConrol"); ?>">
    <a href="<?php echo root . "control/login.php?Delete=admin"; ?>">
        <img src="<?php echo root . "img/images/out.png"; ?>">
        退出
    </a>
</div>
<div class="iframeTitle">
    <ul>
        <li class="iframeTitleNow">
            <div iframeMenuHref="<?php echo root . "control/control/adpersonal.php"; ?>">我的桌面</div>
        </li>
    </ul>
</div>
<div class="frameLeft">
    <ul><?php echo $menu; ?></ul>
</div>
<div id="frameRight">
    <iframe scrolling="yes" frameborder="0" class="iframe" iframeName="我的桌面" src="<?php echo root . "control/adpersonal.php"; ?>"></iframe>
</div>
<script>
    $(function(){
        /****左侧菜单点击事件~一级菜单*******************************/
        $("[menuOne]").click(function(){
            //二级菜单滑动
            $("[menuParent="+ $(this).attr("menuOne") +"]").slideToggle();
            //判断箭头
            var img = $(this).children("img").attr("src");
            if(img == "<?php echo root;?>img/images/triangleRight.png"){
                var newImg = "<?php echo root;?>img/images/triangleDown.png";
            }else{
                var newImg = "<?php echo root;?>img/images/triangleRight.png";
            }
            $(this).children("img").attr("src",newImg);
        });
        /****点击左侧导航**********************************/
        $("[iframeHref]").click(function(){
            //赋值
            var url = $(this).attr("iframeHref");
            var name = $(this).children("span").html();
            var titleName = $("[iframeMenuHref]:contains("+name+")").html();
            //检查此页卡是否存在
            if(titleName){
                iframeTitle(titleName);
            }else{
                //添加iframe
                $("[iframeName]").css("display","none");
                $("#frameRight").append("<iframe scrolling='yes' frameborder='0' class='iframe' iframeName='"+name+"' src='" + url + "'></iframe>");
                //添加顶部页卡
                $(".iframeTitle > ul > li").removeClass("iframeTitleNow");
                $(".iframeTitle > ul").append("<li class='iframeTitleNow'><div iframeMenuHref='" + url + "'>" + name + "</div><div class='iframeClose'>×</div></li>");
            }
            //高亮显示左侧当前导航
            $("[iframeHref]").removeClass("frameLeftHover");
            $(this).addClass("frameLeftHover");
        });
        /****点击顶部导航**********************************/
        $(document).on("click","[iframeMenuHref]",function(){
            var name = $(this).html();
            iframeTitle(name);
        });
        /****关闭页卡*************************************/
        $(document).on("click",".iframeClose",function(){
            var name = $(this).prev().html();
            $("[iframeName=" + name + "]").remove();//删除iframe
            $(this).parent().remove();//删除顶部页卡
            var has = $(".iframeTitle > ul > li").hasClass("iframeTitleNow");//检查是否存在高亮的顶部页卡
            if(has){
            }else{
                $("[iframeName=我的桌面]").css("display","block");//高亮我的桌面顶部页卡
                $("[iframeMenuHref]:contains(我的桌面)").parent().addClass("iframeTitleNow");//显示我的桌面iframe
            }
        });
    });
    //页卡切换函数
    function iframeTitle(name){
        //iframe切换
        $("[iframeName]").css("display","none");
        $("[iframeName=" + name + "]").css("display","block");
        //顶部页卡切换
        $(".iframeTitle > ul > li").removeClass("iframeTitleNow");
        $("[iframeMenuHref]:contains("+name+")").parent().addClass("iframeTitleNow");
    }
</script>
<?php echo warn(); ?>
</body>
</html>
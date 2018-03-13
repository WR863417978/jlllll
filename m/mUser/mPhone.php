<?php
include "../../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mInfo.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">手机号</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-编辑信息-手机号-->
<div class="container">
    <div class="mui-pt45 mui-mbottom60">
    <form name="userInfo">
    <dl class="header-search">
        <dd><p>请输入手机号并验证</p></dd>
           <dt class="mui-dis-flex" style=" position:  relative;"><i style=" position:  absolute;">+86</i>
                <input style=" padding-left:15%;" type="search" class="header-stext" value="" maxlength="15" name="tel">
                <input id="verifyBtn-register" type="button" value="获取验证码"/>
           </dt>
           <dt class="mui-dis-flex"><i>验证码</i><input type="search" class="header-stext" name='verify' value="" maxlength="15" placeholder="请输验证码"></dt>
        </dl>
    </form>
        <input type="button" class="addPassenger_btn" value="完 成"/>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
})
/**************获取验证码*****************/
var countdown=60;
$("#verifyBtn-register").click(function(){
    var tel = $("[name='userInfo'] [name='tel']").val();
	if( $.trim(tel).length == 11 ){
        settime(this);
        getVerify(tel);
    }else{
        mwarn('请正确填写手机号码');
    }
});
//发送验证码
function getVerify(tel){
    $.post(root + 'library/mData.php?type=getVerify',{tel:tel}, function(data) {
        if(data.warn == 2){
           
        }else{
            mwarn(data.warn);
        }
    }, "json");
}
function settime(obj) {
    if (countdown == 0) {
        obj.removeAttribute("disabled");
        obj.value="获取验证码";
        $(obj).css("background","#e32b2b !important");
        countdown = 60;
        return;
    } else {
        obj.setAttribute("disabled", true);
        obj.value="(" + countdown + ")秒后重发";
        $(obj).css({"background":"#ccc","color":"#fff"});
        countdown--;
    }
setTimeout(function() {
    settime(obj) }
    ,1000)
}
$('.addPassenger_btn').on('click',function(){
    console.log('1111');
    $.post(root+"library/mData.php?type=bindUserTel",$("[name='userInfo']").serialize(),function(data){
        if(data.warn == 2){
            if(data.href){
                location.href = data.href;
            }
        }else{
            mwarn(data.warn);
        }
    },'json');
});
</script>
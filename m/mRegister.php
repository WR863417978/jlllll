<?php
include "../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" onclick='windowBack();' class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">注册</p>
		</div>
		<a href="javascript:;" class="header-btn "><span class="musercenter user-ico"></span></a> 
	</div>
</div>
<!--//-->
<div class="container mui-pt45">
	<div class="login">
    <form name="UserRegister">
        <ul>
			<li><span>手机</span><input name='userTel' type="text" placeholder="请输入手机号" /></li>
			<li><span>验证码</span><input name='verify' type="text" placeholder="请输入验证码" /><input id="verifyBtn-register" type="button" value="获取验证码"/></li>
			<li><span>用户ID</span><input name='userId' type="text" placeholder="此处为系统默认，不可更改" value='<?php echo $kehu['khid'];?>' readonly=readonly/></li>
			<li><span>推荐码</span><input name='shareId' type="text" placeholder="此处为系统默认，不可更改" value='<?php echo $kehu['shareId'];?>' readonly=readonly/></li>
			<li><span>密码</span><input name='Pwd' type="password" placeholder="" /></li>
			<li><span>确认密码</span><input name='rePwd' type="password" placeholder="" /></li>
		</ul>
		<p class="pro"><label class="mui-dis-flex"><input name='isSure' type="checkbox" /><span>确认即为同意</span><i>聚礼优选用户使用协议</i></label></p>
		<input type="button" class="addPassenger_btn" value="确 认"/>
    </form>
	</div>
</div>
<?php echo mWarn();?>
<script>
	/**************获取验证码*****************/
var countdown=60; 
$("#verifyBtn-register").click(function(){
    var tel = $("[name='UserRegister'] [name='userTel']").val();
	if( $.trim(tel).length == 11 ){
        settime(this);
        getVerify(tel);
    }else{
        mwarn('请正确填写手机号码');
    }
})
function settime(obj) { 
    if (countdown == 0) { 
        obj.removeAttribute("disabled");    
        obj.value="获取验证码"; 
        $(obj).css("background-color","#e32b2b !important");
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
function getVerify(tel){
    $.post('<?php echo root;?>library/mData.php?type=getVerify',{tel:tel}, function(data) {
        if(data.warn == 2){
            
        }else{
            mwarn(data.warn);
        }
    }, "json");
}
$(function(){
    $('.addPassenger_btn').on('click',function(){
        var isSure = '';
        if( $("[name='UserRegister'] [name='isSure']").is(':checked') ){
            isSure = 'yes';
        }else{
            isSure = 'no';
        }
        $.post('<?php echo root;?>library/mData.php?type=userRegister&isSure='+isSure,$("[name='UserRegister']").serialize(), function(data) {
            if(data.warn == 2){
                if(data.href){
                    location.href = data.href;
                }
            }else{
                mwarn(data.warn);
            }
        }, "json");
    });
});
</script>
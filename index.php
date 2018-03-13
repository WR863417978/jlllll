<?php
include "library/pcFunction.php";
$_SESSION['khid'] = "gRJ67246843Rk";
echo head("pc").pcHeader();
?>
<style>
.divTest{ width:200px; height:200px; line-height:200px; text-align:center; color:#FFF; background-color:#9C0; cursor:pointer; float:left; margin:10px 10px 10px 10px;}
</style>
<div class="column">
    <div class="divTest" id="warnId">弹出层测试</div>
    <div class="divTest" id="wxScan">微信扫码支付测试</div>
    <img class="divTest" id="wxScanImg">
</div>
<!--隐藏域开始-->
<div class="hide">
<form name="wxpay" method="post" action="<?php echo root."pay/wxpay/wxpay.php";?>">
<input name="orderType" type="text" value="测试">
<input name="orderId" type="hidden" value="orderIdOne">
</form>
</div>
<!--隐藏域结束-->
<script>
$(function(){
    //弹出层测试
	$("#warnId").click(function(){
	    warn("您好，世界！");	
	});	
	//微信扫码支付测试
	$("#wxScan").click(function(){
		$.post(root+"pay/wxpay/wxScan.php",$("[name=wxpay]").serialize(),function(data){
			if(data.warn == 2){
				$("#wxScanImg").attr("src",root+"pay/wxpay/wxScanPng.php?url="+data.url);
			}else{
				warn(data.warn);
			}
		},"json");
	});
});
</script>
<?php echo pcWarn().pcFooter();?>
<?php
include "../../control/ku/configure.php";
echo head("ad");
?>
<img id="imgscan" src="http://www.yumukeji.com/project/demo/img/AdHead/STs66705998zS.jpg">
<input id="buttonScan" type="button" value="xian">
<!--
<form name="test" method="post" action="<?php echo root."pay/wxpay/wxpay.php";?>">
<input name="orderType" type="text" value="测试">
<input name="orderId[]" type="checkbox" value="testid1">
<input name="orderId[]" type="checkbox" value="testid2">
<input name="orderId[]" type="checkbox" value="testid3">
<input name="money" type="text" value="0.01">
<input type="submit" value="提交">
</form>
-->
<script>
$(function(){
	$("#buttonScan").click(function(){
		$.post(root+"pay/wxpay/wxScan.php",{"test":"yes"},function(data){
			if(data.warn == 2){
				$("#imgscan").attr("src",root+"pay/wxpay/wxScanPng.php?url="+data.url);
			}else{
				alert(data.warn);
			}
		},"json");
	});
});
</script>
<?php echo warn();?>

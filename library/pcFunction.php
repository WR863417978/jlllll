<?php 
/*
*PC端专用函数库，用于存放仅用于PC端的函数
*/
include dirname(__FILE__)."/openFunction.php";
/********如果在本函数库检查到客户使用的是移动端浏览器，则跳转至移动端*****************************/
if(isMobile()){
  header("location:{$root}m/mindex.php");
  exit(0);
}
/********PC端头部*****************************/
function pcHeader(){
    $html = "
	<div>
		
	</div>
	";
	return $html;	
}
/********PC端底部*****************************/
function pcFooter(){
    $html = "
			<div>
				
			</div>
		</body>
	</html>
	";
	return $html;	
}
/********PC端提示信息弹出层*****************************/
function pcWarn(){
	if(!empty($_SESSION['warn'])){
		$warn = $_SESSION['warn'];//接收session中的提示信息
		unset($_SESSION['warn']);
	}elseif(!empty($GLOBALS['warn'])){//接收全局变量中的提示信息
		$warn = $GLOBALS['warn'];
	}
	if(!empty($warn)){
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
?>
<?php
include "../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="<?php echo root."m/mIndex.php";?>" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">注册成功</p>
		</div>
		<a href="<?php echo root."m/mNeed.php";?>" class="header-btn header-login">我要发布</a>
	</div>
</div>
<!--//-->
<div class="container mui-pt45">
	<div class="succeed">
		<p><span class="succeed-ico">&#xe627;</span></p>
		<p>恭喜您 ！注册成功</p>
		<p>
			<a href='mMember.php'>成为会员享受更多权益</a>
            <a href="mIndex.php">先看看</a>
		</p>
	</div>
</div>
<?php
include "../library/mFunction.php";
echo head('m');
$title = findOne('content',"id = 'GrX85962388OP'");
$sql = "SELECT * FROM article WHERE targetId = 'GrX85962388OP' ORDER BY list";
$article = myQuery($sql);
foreach ($article as $key => $val) {
    $html .= "<p>{$val['word']}</p>";
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text"><?php echo $title['title'];?></p>
		</div>
		<a href="#" class="header-btn header-login"></a>
	</div>
</div>
<!--//-->
<div class="container mui-pt45 mui-mbottom60">
	<div class="potocol">
    <dl>
			<dt><?php echo $title['subTitle'];?></dt>
			<dd>
                <?php echo $html;?>
				<!-- <h3>一、【协议的范围】</h3>
				<p>
				　　1.1【协议适用主体范围】<br/>
				　　本协议是您与腾讯之间关于您使用本服务所订立的协议。<br/>
				　　1.2【本服务内容】<br/>
				　　	本协议视为《腾讯服务协议》（链接地址：http://www.qq.com/contract.shtml，若链接地址变更的，则以变更后的链接地址所对应的内容为准；其他链接地址变更的情形，均适用前述约定。）、《QQ号码规则》（链接地址：http://zc.qq.com/chs/agreement1_chs.html）的补充协议，是其不可分割的组成部分，与其构成统一整体。本协议与上述内容存在冲突的，以本协议为准。<br/>
				　　本协议内容同时包括腾讯可能不断发布的关于本服务的相关协议、业务规则等内容。上述内容一经正式发布，即为本协议不可分割的组成部分，您同样应当遵守。
				</p> -->
			</dd>
		</dl>
		<!-- <dl>
			<dt>请认真阅读并理解以下内容，其中以加粗方式显著标识的文字，请着重阅读</dt>
			<dd>
				<h3>一、【协议的范围】</h3>
				<p>
				　　1.1【协议适用主体范围】<br/>
				　　本协议是您与腾讯之间关于您使用本服务所订立的协议。<br/>
				　　1.2【本服务内容】<br/>
				　　	本协议视为《腾讯服务协议》（链接地址：http://www.qq.com/contract.shtml，若链接地址变更的，则以变更后的链接地址所对应的内容为准；其他链接地址变更的情形，均适用前述约定。）、《QQ号码规则》（链接地址：http://zc.qq.com/chs/agreement1_chs.html）的补充协议，是其不可分割的组成部分，与其构成统一整体。本协议与上述内容存在冲突的，以本协议为准。<br/>
				　　本协议内容同时包括腾讯可能不断发布的关于本服务的相关协议、业务规则等内容。上述内容一经正式发布，即为本协议不可分割的组成部分，您同样应当遵守。
				</p>
			</dd>
			<dd>
				<h3>一、【协议的范围】</h3>
				<p>
				　　1.1【协议适用主体范围】<br/>
				　　本协议是您与腾讯之间关于您使用本服务所订立的协议。<br/>
				　　1.2【本服务内容】<br/>
				　　	本协议视为《腾讯服务协议》（链接地址：http://www.qq.com/contract.shtml，若链接地址变更的，则以变更后的链接地址所对应的内容为准；其他链接地址变更的情形，均适用前述约定。）、《QQ号码规则》（链接地址：http://zc.qq.com/chs/agreement1_chs.html）的补充协议，是其不可分割的组成部分，与其构成统一整体。本协议与上述内容存在冲突的，以本协议为准。<br/>
				　　本协议内容同时包括腾讯可能不断发布的关于本服务的相关协议、业务规则等内容。上述内容一经正式发布，即为本协议不可分割的组成部分，您同样应当遵守。
				</p>
			</dd>
		</dl> -->
		<input type="button" class="addPassenger_btn" value="我已阅读并同意"/>
	</div>
</div>
</body>
<script>
    $('.addPassenger_btn').click(function(){
        window.history.back(-1); 
    });
</script>
</html>
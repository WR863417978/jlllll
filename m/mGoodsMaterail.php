<?php
include "../library/mFunction.php";
echo head('m');
$gid = $get['gid'];
$res = findOne('goods',"id = '$gid'");
$material = findOne('article',"target = '宣传素材' AND targetId = '{$gid}'");
if( $res['ismaterial'] == '是' || $material )
{
	$data = findAll("article","targetId = '$gid' AND target = '宣传素材'");
	
    foreach ($data as $val) {
        if( !empty( $val['img'] ) )
        {
            $html .= "<img src=\"".root."{$val['img']}\"/>";   
        }else{
            $word .= "<li>{$val['word']}</li>";
        }
    }
}else{
	header('location:'.root."m/mIndex.php");
    exit();
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">商品素材</p>
		</div>
		<a href="#" class="header-btn header-login"></a>
	</div>
</div>
<!--//-->
<div class="container mui-pt45">
	<dl class="made">
		<dt class="mui-dis-flex">
            <?php echo $html;?>
			<!-- <img src="<?php echo img('wOZ84129241GJ');?>" />
			<img src="<?php echo img('wOZ84129241GJ');?>" />
			<img src="<?php echo img('wOZ84129241GJ');?>" />
			<img src="<?php echo img('wOZ84129241GJ');?>" />
			<img src="<?php echo img('wOZ84129241GJ');?>" />
			<img src="<?php echo img('wOZ84129241GJ');?>" /> -->
		</dt>
		<dd><span>宣传文字</span></dd>
		<dd>
			<ul>
                <?php echo $word;?>
				<!-- <li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li>
				<li>杯子特别美 ，快来买吧</li> -->
			</ul>
		</dd>
	</dl>
</div>

<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
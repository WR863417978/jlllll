<?php
include "../library/mFunction.php";
echo head('m');
//$needMx = mNeedMxShow();
$thisUrl = root.'m/mNeed.php';
$kid = $get['kid'];
$needList = needMxBuild(0,10,$kid);
empty($kid) ? $btn = "<a href='".root."m/mNeed.php?kid={$kehu['khid']}' name='myNeed' class='header-btn header-login'>我的发布</a>" : $btn = "<a href='".root."m/mNeedMx.php?kid={$kehu['khid']}' name='sendNeed' class='header-btn header-login'>发布需求</a>";
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting">
		<a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">发布需求</p>
		</div>
		<?php echo $btn;?>
		<!-- <a href="javascript:;" name='sendNeed' class="header-btn header-login">发布需求</a> -->
	</div>
</div>
<!--//-->
<div class="container mui-pt45 mui-mbottom60">
	<div class="post">
		<div class="appraise" id='goodsEval'>
			<div class='content-drop'>
				<!-- 评价 -->
				<?php echo $needList['html'];?>
			</div>
		</div>
		<!-- <a>
			<dl>
				<dt>
					<h3>需求名称需求名称需求名称</h3>
					<label><span>发布者：张三</span><span>发布时间：2017:11：07</span></label>
				</dt>
				<dd>
					<p>我公司需订购一批水杯我公司需订购一批水杯我公司需订购一批水杯我公司需订购一批水杯我公司需订购一批水杯我公司需订购一批水杯</p>
					<label class="mui-dis-flex">
						<span class="flex1"><i>&#xe652;</i>剩余5天2小时30分</span>
						<span class="post-btn">已发布</span>
					</label>
				</dd>
			</dl>
		</a> -->
	</div>
</div>
<!--底部-->
<?php echo mFooter();?>
<!--//-->
<script>
$(function(){
	changeNav();
	//发布需求
	$("[name='sendNeed']").on('click',function(){
		var tel = '<?php echo $kehu['tel'];?>';
		if( $.trim(tel).length == 0 ){
			location.href = root + 'm/mRegister.php';	
		}else{
			location.href = root + 'm/mNeedMx.php';	
		}
	});
});
$(function(){
	// 页数
    var page = 0;
    // 每页展示10个
    var size = 10;
    //goodsId
    var kid = '<?php echo $kid;?>';
    // dropload
    $('#goodsEval').dropload({
        scrollArea : window,
        loadDownFn : function(me){
            page++;
            // 拼接HTML
            var result = '';
            $.ajax({
                type: 'POST',
                url: root+'library/mData.php?type=needMxShow&page='+page+'&size='+size+'&khid='+kid,
                dataType: 'json',
                success: function(data){
                    var arrLen = data.data.length;
                    $('.dropload-down').show();
                    if(arrLen > 0){
                        resutl = data.html;
                    // 如果没有数据
                    }else{
                        console.log(22222);
                        // 锁定
                        me.lock();
                        // 无数据
                        me.noData();
                    }
                    // 为了测试，延迟1秒加载
                    setTimeout(function(){
                        // 插入数据到页面，放到最后面
                        $('.content-drop').append(data.html);
                        // 每次数据插入，必须重置
                        me.resetload();
                    },1000);
                },
                error: function(xhr, type){
                    alert('Ajax error!');
                    // 即使加载出错，也得重置
                    me.resetload();
                }
            });
        }
    });
})
</script>
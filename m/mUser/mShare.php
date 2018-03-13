<?php
include "../../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mUser.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的分享</p>
        </div>
        <a href="javascript:;" class="header-btn share-more">&#xe615;</a>
        <p class="share-more-box">
            <span><a href="<?php echo root;?>m/mUser/mCode.php">我的二维码</a></span>
            <span><a href="<?php echo root;?>m/mMemberSucced.php?type=shareNum">我的推荐码</a></span>
        </p>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".share-more").on("click",function(){
        $(".share-more-box").fadeToggle();
    });
});
</script>
<!--//-->
<!--分享-->
<div class="container">
    <div class="share mui-pt45 mui-mbottom60">
        <?php echo mShareShow();?>
    </div>
</div>
<!--//-->
<p class="share-note-img"><img src="<?php echo img('Owc88029825BN');?>" /></p>
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
     $(".share-note").on("click",function(){
        $(".share-note-img").show();
    });
})
</script>
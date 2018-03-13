<?php
include "../../library/mFunction.php";
echo head('m');
if(empty($kehu['shareId'])){
    $readonly = '';
}else{
    $readonly = "readonly='readonly'";
}
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mInfo.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">推荐码</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-编辑信息-推荐码-->
<div class="container">
    <div class="mui-pt45 mui-mbottom60">
        <dl class="header-search">
            <dt class="mui-dis-flex">
                <i>推荐码</i>
                <input type="search" class="header-stext" value="<?php echo $kehu['shareId'];?>" maxlength="15" placeholder="请输入邀请者的推荐码" <?php echo $readonly;?>>
            </dt>
            <dd class="invite">
                <h4>说明</h4>
                <ul>
                    <li>1.请输入邀请者的唯一ID</li>
                    <li>2.若为扫描推荐二维码，本页面自动默认</li>
                    <li>3.朋友圈点击链接，本页面自动默认</li>
                    <li>4.对默认有异议的，可点击<a href="<?php echo root; ?>m/mUser/mCodeHelp.php"><span class="user-btn">申诉</span></a></li>
                </ul>
            </dd>
        </dl>
        <input type="button" class="addPassenger_btn" value="保 存"/>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
})
</script>
<?php
include "../../library/mFunction.php";
echo head('m');
$htmlBuild = mShareMoreShow($get['type']);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mShare.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的分享</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--分享-->
<div class="container">
    <div class="share mui-pt45 mui-mbottom60">
        <dl>
        <?php echo $htmlBuild;?>
            <!-- <dt class="mui-dis-flex"><label class="flex1">非会员</label><span class="more">&#xe62e;</span></dt>
            <dd>
                <ul class="mui-dis-flex">
                    <li>张三</li>
                    <li>0001</li>
                    <li>时间：2017-11-08</li>
                </ul>
                <ul class="mui-dis-flex">
                    <li>张三</li>
                    <li>0001</li>
                    <li>时间：2017-11-08</li>
                </ul>
                <ul class="mui-dis-flex">
                    <li>张三</li>
                    <li>0001</li>
                    <li>时间：2017-11-08</li>
                </ul>
            </dd> -->
        </dl>
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
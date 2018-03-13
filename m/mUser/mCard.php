<?php
include "../../library/mFunction.php";
echo head('m');
$bankArr = para('bankName');
$bankArr = explode('，',$bankArr);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root;?>m/mUser/mInfo.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">银行卡绑定</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-编辑信息-推荐码-->
<div class="container">
    <div class="mui-pt45 mui-mbottom60">
        <form name="UserCardEdit">
        <dl class="header-search">
            <dd><p>要与填写的身份证信息一致</p></dd>
           <dt class="mui-dis-flex"><i>持卡人</i><input name='userName' type="search" class="header-stext" value="<?php echo $kehu['bankUserName'];?>" maxlength="15" placeholder="请输入持卡人姓名"></dt>
           <dt class="mui-dis-flex"><i>卡号</i><input name='cardNum' type="search" class="header-stext" value="<?php echo $kehu['bankNum'];?>" maxlength="20" placeholder="请输入持卡人银行卡号"></dt>
           <dt class="mui-dis-flex"><i>开户行</i>
            <select class="select-down" name='bank'>
                <?php echo option('--开户银行--',$bankArr,$kehu['bankName']);?>
            </select>
            <input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
           </dt>
        </dl>
        </form>
        <input type="button" class="addPassenger_btn" value="保 存"/>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
$(function(){
    changeNav();
    $('.addPassenger_btn').on('click',function(){
        $.post(root+"library/mData.php?type=editUserCardInfo",$("[name='UserCardEdit']").serialize(),function(data){
            if(data.warn == 2){
                mwarn('编辑成功');
            }else{
                mwarn(data.warn);
            }
        },"json");
    });
})
</script>
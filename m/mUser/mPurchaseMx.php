<?php
include "../../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:windowBack();" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">更改购货人</p>
        </div>
        <a href="javascript:;" class="header-btn"></a> 
    </div>
</div>
<!--//-->
<!-- 一个店铺 -->
<div class="container mui-ptopsmaple mb180 add-address edit-info  edit-info2">
    <!--更改购货人-->
    <dl class="address-lists">
        <dt><a href='<?php echo root.'m/mUser/mPurchase.php'?>' class="mui-dis-flex"><label class="flex1"><i class="return-people">&#xe64e;</i><span>更改购货人</span> </label></a></dt>
    </dl>
        <p class="note-txt">
            <label><i class="light">&#xe60e;</i>请确认您为他人购物，业绩等计算在该ID下</label>
            <em>请清楚填写购物人的聚礼ID及姓名，<span class="red">*</span>为必填项目</em>
        </p>
    <!--//-->
        <form name="subForm">
            <ul>
                <li><label><input name='juliId' type="text" placeholder="聚礼ID"/><i class="red">*</i><i class="return-people">&#xe64e;</i><span>请选择常用购货人</span></label></li>
                <li><label><input name='name' type="text" placeholder="姓名"/><i class="red">*</i></label></li>
                <li><label><input name='tel' type="text" placeholder="手机号"/></label></li>
                <li>
                    <label>
                        <div class="choice">
                            <label class="sex"><em>保存为常用购货人</em><input type="radio" name="isUsed" value="是"><i></i></label>
                        </div>
                    </label>
                </li>
            </ul>
            <input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
        </form>
    <input name='subBtn' type="button" class="addPassenger_btn" value="确认提交"/>
</div>
</body>
<script>
$(function(){
    $("[name='subBtn']").click(function(){
        $.post(root+"library/mData.php?type=addContact",$("[name='subForm']").serialize(),function(data){
            if(data.warn == 2){
                window.history.back(-1);
            }else{
                mwarn(data.warn);
            }
        },'json'); 
    });
});
</script>
</html>
<?php echo mWarn();?>
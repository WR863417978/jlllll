<?php
include "../../library/mFunction.php";
echo head('m');
?>
<!--头部-->
<div class="header header-fixed">
  <div class="nesting"> <a href="#" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
    <div class="align-content">
      <p class="align-text">售后/投诉</p>
    </div>
    <a href="#" class="header-btn"></a> 
  </div>
</div>
<!--//-->
<!--会员中心-编辑信息-推荐码-->
<div class="container">
  <div class="mui-pt45 mui-mbottom60">
    <dl class="header-search">
      <dd><p class="note-txt">
            <label><i class="light">&#xe60e;</i>亲，无论什么原因造成你的不便，我们都感到抱歉！我们会尽快处理。</label>
        </p></dd>
       <dt class="mui-dis-flex"><i>订单号</i><input type="search" class="header-stext" value="" maxlength="15" placeholder="请填写需要售后的订单编号"></dt>
       <dt class="mui-dis-flex"><i>联系人电话</i><input type="search" class="header-stext" value="" maxlength="15" placeholder="请填写手机号码"></dt>
       <dt ><i>售后原因描述</i><br>
        <textarea class="sugeest-box">请详细阐述售后的原因，已便于尽快处理</textarea>
       </dt>
    </dl>
    <input type="button" class="addPassenger_btn" value="完成并提交"/>
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
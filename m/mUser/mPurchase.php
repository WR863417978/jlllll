<?php
include "../../library/mFunction.php";
echo head('m');
$res = findAll('contacts',"khid = '{$kehu['khid']}' ORDER BY time DESC");
if( $res ){
    foreach ($res as $val) {
        $html .="
        <li name='selectContacts' data-conid='{$val['id']}'>
			<a href='javascript:;' class='mui-dis-flex'>
				<span class='flex1'>{$val['kehuName']} ：</span>
				<label>ID:{$val['otherKhid']}</label>
			</a>
		</li>";
    }
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:windowBack();" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">购货人管理</p>
		</div>
		<a href="javascript:;" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<style>
.address-lists dt{position: relative;}
.add-people{position: absolute;top: 4px;right: 10px;border: 1px solid red;padding: 2px 10px;height: 30px;line-height: 28px;border-radius: 3px;color: red;}
</style>
<!-- 一个店铺 -->
<div class="container mui-ptopsmaple mb180">
	<!--更改购货人-->
	<dl class="address-lists">
		<dt>
			<a class="mui-dis-flex"><label class="flex1"><i class="return-people">&#xe64e;</i><span>常用购货人</span> </label></a>
			<span class="add-people">新增购货人</span>
		</dt>
	</dl>
	<!--//-->
	<ul class="mui-mtop10 user-wrap-style1">
		<?php echo $html;?>
        <!-- <li>
			<a href="<?php echo root;?>mPurchase.php" class="mui-dis-flex">
				<span class="flex1">张三 ：</span>
				<label>23457894654</label>
			</a>
		</li> -->
	</ul>
</div>
</body>
<script>
$(function(){
    //添加购货人跳转
    $('.add-people').click(function(){
        location.href = root + "m/mUser/mPurchaseMx.php";  
    });
    //选择常用购货人
    $("[name='selectContacts']").click(function(){
        var key = $(this).data('conid');
        $.post(root+"library/mData.php?type=selectContact",{id:key},function(data){
            if(data.warn == 2){
                location.href = root + "m/mUser/mBuyCar.php";
            }else{
                mwarn(data.warn);
            }
        },'json');
    });
});
</script>
</html>
<?php
include "../../library/mFunction.php";
echo head('m');
$id = $get['id'];
$data = findOne('address',"id = '$id'");
if($data){
    $Region = findOne('region',"id = '{$data['regionId']}'");    
}
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">地址详情</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<div class="container">
	<div class="add-address edit-info mui-pt45">
		<form name="UserAddress">
			<ul>
				<!--<li><span>收货人</span><label><input type="text"  placeholder="请输入收货人真实姓名" /></label></li>
				<li><span>手机号码</span><label><input type="text" placeholder="请输入收货人手机号码"/></label></li>-->
                <li><span>姓名</span><label><input type="text" name='userName' placeholder="请填写联系人" value='<?php echo $data['contactName'];?>'/></label></li>
                <li><span>电话</span><label><input type="text" name='userTel' placeholder="请填写手机号码" value='<?php echo $data['contactTel'];?>'/></label></li>
				<li><span>选择地区</span>
					<div>
						<!-- <select class="select-xia w60">
							<option>省</option>
						</select>
						<select class="select-xia w60">
							<option>市</option>
						</select>
						<select class="select-xia w60">
							<option>区</option>
						</select> -->

                        <?php echo RepeatSelect("region","province","province","select-xia w60","--省份--",$Region['province']);?>
                        <select name="city" class="select-xia w60">
                        <?php echo RepeatOption(" region WHERE province = '{$Region['province']}' ","city","--城市--",$Region['city']);?>
                        </select>
                        <select name="area" class="select-xia w60">
                        <?php echo IdOption(" region WHERE province = '{$Region['province']}' and city = '{$Region['city']}' ","id","area","--区域--",$Region['id']);?>
                        </select>
					</div>
				</li>
                <input type="hidden" name="addressId" value='<?php echo $id;?>'>
				<li><span>详细地址</span>
					<textarea name='addressMx' rows="5" cols="40" placeholder='详细到街道门牌信息'><?php echo $data['addressMx'];?></textarea>
				</li>
				<li><span>邮政编码</span><label><input type="text" name='userZipCode' placeholder="请填写邮编" value='<?php echo $data['zipCode'] == 0 ? '' : $data['zipCode'];?>'/></label></li>
				<li>
					<label>
						<div class="choice">
			                <label class="sex"><em>默认地址</em><input type="radio" name="defaultAddress" value="是"><i></i></label>
				        </div>
			        </label>
		        </li>
			</ul>
		</form>
	</div>
	<input type="button" class="addPassenger_btn" value="保 存"/>
</div>
<?php echo mWarn();?>
</body>
<script>
$(function(){
    var root = "<?php echo root;?>";
    region("UserAddress","province","city","area",root);
    $('.addPassenger_btn').click(function(){
        $.post("<?php echo root;?>"+"library/mData.php?type=editUserAddress",$("[name='UserAddress']").serialize(),function(data){
            if(data.warn == 2){
//                if(data.href){
//                    location.href = data.href
//                }
                window.history.back(-1);
            }else{
                mwarn(data.warn);
            }
        },'json');
    });
});
</script>
</html>
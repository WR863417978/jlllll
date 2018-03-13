<?php
include "../../library/mFunction.php";
echo head('m');
function addressShow($kehu)
{
    $khid = $kehu['khid'];
    //$dataArr = findAll('address a,region r',"a.khid = '$khid' AND a.regionId = r.id ORDER BY time",'a.*,r.province,r.city,r.area');
    $sql = "SELECT a.*,r.province,r.city,r.area FROM address a,region r WHERE a.khid = '$khid' AND a.regionId = r.id ORDER BY time";
	$dataArr = myQuery($sql);
    $addressArr = [];   #地址数组
    if($dataArr[0]['sqlRow'] > 0)
    {
        foreach ($dataArr as $val)
        {
            if( $kehu['address'] == $val['id'] )
            {
                $addressHeadStr = addressBuild($val,'checked');
            }else{
                $addressStr .= addressBuild($val);
            }
        }
    }else{
        return $addresHtml = '';
    }
    $addresHtml = $addressHeadStr.$addressStr;
    return $addresHtml;
}
function addressBuild($val,$checked = NULL)
{
    $str = "<dl>
        <dt><img src='".img('kFq84156316pg')."'/></dt>
        <dd>
            <div class='choice'>
                <label class='sex mui-dis-flex'>
                    <p>
                        <span>{$val['contactName']} {$val['contactTel']}</span><br />
                        <em>{$val['province']}{$val['city']}{$val['area']}{$val['addressMx']}</em>
                    </p>
                    <em>
                        <input type='radio' name='address' value='{$val['id']}'>
                        <i></i>
                    </em>
                </label>
            </div>
        </dd>
        <dd class='mui-dis-flex'>
        	<label><input name='defaultAddress' type='radio' {$checked} value='{$val['id']}'/><span>默认地址</span></label>
            <label class='delete-btn mui-dis-flex' data-editid='{$val['id']}'><i>&#xe64f;</i><span>删除</span></label>
            <label class='edit-btn mui-dis-flex' data-editid='{$val['id']}'><i>&#xe679;</i><span>编辑</span></label>
        </dd>
    </dl>";
    return $str;
}
$html = addressShow($kehu);
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">地址管理</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<!-- 一个店铺 -->
<div class="container mui-ptopsmaple mb180">
	<div class="address-box">
		<div>
			<?php echo $html;?>
		</div>
	</div>
	<a href="<?php echo root;?>m/mUser/mAddressMx.php"><input type="button" class="addPassenger_btn" value="添 加"/></a>
</div>
<?php echo mWarn();?>
</body>
<script>
$(function(){
	window.onpageshow = function(event){
	if (event.persisted) {
		window.location.reload();
		}
	}
	//回退
	$('.header-return').click(function(){
        window.history.back(-1);
    });
	//编辑
	$('.edit-btn').on('click',function(){
		var id = $(this).data('editid');
		location.href = "<?php echo root;?>" + 'm/mUser/mAddressMx.php?id=' + id;
	});
	//默认地址
	$("[name='defaultAddress']").on('click',function(){
		var id = $(this).val();
		$.post("<?php echo root;?>"+"library/mData.php?type=addressChoice",{id:id},function(data){
			if(data.warn == 2){
				location.reload();
			}else{
				mwarn(data.warn);
			}
		},'json');
	});
	//删除
	$('.delete-btn').on('click',function(){
		var id = $(this).data('editid');
		$.post("<?php echo root;?>"+"library/mData.php?type=delUserAddress",{id:id},function(data){
			if(data.warn == 2){
				location.reload();
			}else{
				warn(data.warn);
			}
		},'json');
	});
	//选择地址
	$("[name='address']").click(function(){
		var id = $(this).val();
		$.post("<?php echo root;?>"+"library/mData.php?type=buyCarAddressChoice",{id:id},function(data){
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
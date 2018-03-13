<?php
include "../library/mFunction.php";
echo head('m');
$gid 	= $get['gid'];      #goodsId
$skid 	= $get['skid'];     #skId

/* if( empty($_SESSION['customMadeId']) ){
	$customId = $_SESSION['customMadeId']  = "JL".date("YmdHi").mt_rand(1000,9999);
}else{
	$customId = $_SESSION['customMadeId'];
}
$res = findOne('customMade',"id = '$customId'");
if( $res ){
	$imgHtml = "<img src='".root."{$res['logoImg']}'/>";
}else{
	$imgHtml = "
	<img src='".img('gsW84129141Qg')."'/>
	<label onclick=\"$('[name=madeImgUpload]').click();\"><span>点击浏览</span></br><em>需访问你的手机相册</em></label>";
} */
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">商品定制</p>
		</div>
		<a href="javascript:;" class="header-btn header-login"></a>
	</div>
</div>
<!--//-->

<div class="container mui-pt45">
	<dl class="made">
		<dd><img src="<?php echo img('bDh84129637RF');?>"/></dd>
		<dd><img src="<?php echo img('bDh84129637RF');?>"/></dd>
		<form name="customMade">
			<dd>
				<span>定制描述</span>
				<textarea name='title' placeholder="定制文字"></textarea>
				<input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
				<input type="hidden" name="gid" value='<?php echo $gid;?>'>
				<input type="hidden" name="skid" value='<?php echo $skid;?>'>
			</dd>
			<dd>
				<span>上传logo</span>
				<p class="mui-dis-flex">
				<?php //echo $imgHtml;?>
					<img src="<?php echo img('gsW84129141Qg');?>" />
					<label name='imUpload' id='imgClick'><span>点击浏览</span></br><em>需访问你的手机相册</em></label>
				</p>
                <div id='logo_area' style='display:inline'></div>
			</dd>
			<div id='areaShow'></div>
			<dt>
				<input type="button" class="addPassenger_btn" value="完成并提交"/>
			</dt>
			<input type="file" name="img" multiple='multiple'>
		</form>
	</dl>
</div>
<!--//-->
<!--选择定制规格-->

<div class="cover" style="display: block;">
    <div class="made-cover-con">
        <div class="sku-pro">
            <div>
                <div class="sku-img">
                    <p><img src="" id='goodsSkuImg'></p>
                    <div class="goods-specs">
                        <p><?php //echo $goodsData['name'];?> dongdong大卖场</p>
                        <ul class="mui-dis-flex">
                            <li><span>零售价 : </span><i name='sPrice'>￥<?php echo $fistSkuProfit['price'];?></i></li>
                            <li><span>批发价 : </span><i name='sRetailPrice'>￥<?php echo $fistSkuProfit['retailPrice'];?></i></li>
                            <li><span>利润 : </span><i name='sProfit'>￥<?php echo $fistSkuProfit['profit'];?></i></li>
                        </ul>
                    </div>
                </div>
                <div class="sku-pro-info">
                    <dl>
                        <dt>
                            <ul class="mui-dis-flex">
                                <?php// echo $goodsSkuTypeOne;#一级规格?>
                                <li class='goodsSku' data-skuid='{$val['id']}' data-price='{$val['0']['price']}' data-retailprice='{$val['0']['retailPrice']}' data-profit='{$val['0']['profit']}' data-skuimg='{$sku_img}'>买一送一</li>
                            </ul>
                        </dt>
                        <dd>
                           <input type="button" class="addPassenger_btn1 close" value="确认"/>
                        </dd>
                    </dl>
                </div>
                <!-- <div class="sku-closed"></div> -->
            </div>
        </div>
    </div>
</div>
<!--//-->


<!-- 隐藏层 begin -->
<div class='hide'>
    <form name="madeImgForm" action="<?php echo $root;?>library/mPost.php?type=customMade" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
        <input name="madeImgUpload" type="file" onchange="$('[name=madeImgForm]').submit();">
		<input name="khid" type="hidden" value="<?php echo $kehu['khid']; #khid?>">
        <input name="customId" type="hidden" value="<?php echo $customId; #customId?>">
        <input name="goodsId" type="hidden" value="<?php echo $gid;          #goodsId?>">
        <input name="skid" type="hidden" value="<?php echo $skid;          #skid?>">
    </form>
</div>
<!-- 隐藏层 end -->
<!--底部-->
<?php echo mFooter(),mWarn(); ?>
<!--//-->
<script>
	/* $("[name='title']").val( getCookie('customMadeTitle') );
	$("[name='title']").blur(function(){
		var title = $(this).val();
		console.log(title);
		setCookie('customMadeTitle',title);
	}); */
	/*选择规格弹窗*/
	$(function(){
		$(".close").on("click",function(){
			$(".cover").hide();
		});
	});
	$('.addPassenger_btn').on('click',function(){
		var title = $("[name='title']").val();
        var imgNum = $('.seeImg').length;
        if( $.trim(title).length == 0 ){
            mwarn('定制文字不能为空');
        }else if( imgNum > 6 ){
            mwarn('做多只能上传6张图片');
        }else{
            $.post(root+"library/mData.php?type=customSelf",$("[name='customMade']").serialize(),function(data){
                if(data.warn == 2){
                    if( data.href ){
                        location.href = data.href;
                    }
                }else{
                    mwarn(data.warn);
                }
            },'json');          
        }
	});
	//上传
$.fn.extend({
    /*
     **@name 隐藏form表单
     **@author hushiyong
     */
    imgUpload: function(name,aera,statua){
        if ( typeof(FileReader) === 'undefined' ){
            alert("抱歉，你的浏览器不支持 FileReader，请使用现代浏览器操作！");
            $(this).attr('disabled','disabled');
        }else{
            var _this = this;
            var form=$('[name="'+name+'"]');
            $(this).on('click',function(){
                form.find('[type="file"]').click();
            });
            form.on('change','[type="file"]',function(){
                var file = this.files;
                console.log(file.length);
                if(statua){
                    for (var i=0;i<file.length;i++){
                        //这里我们判断下类型如果不是图片就返回 去掉就可以上传任意文件
                        if(!/image\/\w+/.test(file[i].type)){
                            alert(file[i]['name']+"不是图像类型");
                            return false;
                        }
                        var reader = new FileReader();
                        reader.readAsDataURL(file[i]);
                        reader.onload = function(e){
                            var obj = "<div class='seeImg'>"
                                +"<textarea class='hide' name='imgSet[]'>"+this.result+"</textarea>"
                                +"<img style='width: 100px;'   src='"+this.result+"'/>"
                                +"<span class='delimg' onclick='$(this).parent().remove();'>x</span>"
                                +"</div>";
                            $('#'+aera+'').append(obj);
                        }
                    }
                }else{
                    if(!/image\/\w+/.test(file[0].type)){
                        alert(file[0]['name']+"不是图像类型");
                        return false;
                    }
                    var reader = new FileReader();
                    reader.readAsDataURL(file[0]);
                    reader.onload = function(e){
                        var obj = "<div class='seeImg'>"
                            +"<textarea class='hide' name='imgSetLogo'>"+this.result+"</textarea>"
                            +"<img style='width: 100px;'   src='"+this.result+"'/>"
                            +"<span class='delimg' onclick='$(this).parent().remove();'>x</span>"
                            +"</div>";
                        $('#'+aera+'').empty().append(obj);
                    }
                }
            });
        }
        return this;
    }
});
//$('#upload').imgUpload("imgMxForm");
//$("[type='file']").imgUpload('imgMxForm','show',true);
//$("[name='imUpload']").imgUpload('customMade','areaShow',true);
$("#imgClick").imgUpload('customMade','logo_area',true);
</script>
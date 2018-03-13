<?php
include "../library/mFunction.php";
echo head('m');
$id = $get['id'];
if( empty($id) ){
    $uploadHtml = "<li><img id='imgClick' style=\"width: 60px;height: 60px;\" src='".img('VCp85710136Sx')."'/></li>";
    $sureBtn = "<p class='pro'><label class='mui-dis-flex'><span>客户在7天内联系您 剩余时间可以在我的发布中查询</span></label></p>
    <input type='button' class='addPassenger_btn' value='确认发布'/>";
}else{
    $res = findOne('demand',"id = '$id' AND khid = '{$_SESSION['khid']}'");
    if( !$res ){
        header("Location:".getenv("HTTP_REFERER"));   
    }else{
        $imgRes = findAll('talkImg',"talkId = '$id' ORDER BY time");
        //echo "SELECT * FROM talkImg WHERE talkId = '$id' ORDER BY time";
        if( $imgRes )
        {
            foreach ($imgRes as $key => $val)
            {
                $imgHtml .= "<div class='seeImg'><img src='".root."{$val['img']}'></div>";        
            }
        }
    }
}
?>
<style>
.logo_area{}
.seeImg{display: inline-block;position: relative;margin-right: 10px;margin-top: 10px;}
.delimg{position: absolute;top: -10px;right: -10px;text-align: center;font-size: 16px;background: #44444480;color: #fff;border-radius: 50%;width: 20px !important;height: 20px;line-height: 20px;}
</style>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:history.back(-1);" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">发布需求</p>
		</div>
		<a href="mNeed.php" class="header-btn ">我的发布</a> 
	</div>
</div>
<!--//-->
<div class="container mui-pt45">
	<div class="login">
		<!--<dl>
			<dt><span>需求主题</span><input type="text" placeholder="最多可编辑20个字" /></dt>
		</dl>-->
		<form name="needForm">
			<ul class="post-title">
				<li><span>需求主题</span>
					<textarea placeholder="最多可编辑20个字" name='project'><?php echo $res['theme'];?></textarea>
				</li>
			</ul>
			<ul>
				<li><span>礼品类别</span>
					<select style="width: 80%;padding-left: 30%;    height: 40px;background-size: 25px;"  class="select-down" name='goodsType'>
						<?php echo option('--类型--',explode('，',para('giftType')),$res['giftType']);?>
					</select>
				</li>
				<li><span>采购数量</span><input type="text" name='num' placeholder="请输入采购数量" value='<?php echo $res['num']?>'/></li>
				<li><span>截止时间</span><input style="width: 80%;text-align: center;" type="date" name='endtime' placeholder="" value='<?php echo $res['endTime'];?>'/></li>
				<li><span>联系电话</span><input type="text" name='tel' placeholder="请输入联系方式" value='<?php echo $res['tel'];?>'/></li>
			</ul>
			<ul>
				<li><span>详细描述</span>
					<textarea placeholder="请尽量详细的描述你的需求" name='textInfo'><?php echo $res['text'];?></textarea>
				</li>
                <?php echo $uploadHtml;?>
			</ul>
			<div id='logo_area' style='display:inline'><?php echo $imgHtml;?></div>
			<input type="file" name="img" multiple='multiple' style='display:none;'>
			<input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
		</form>
		<?php echo $sureBtn;?>
	</div>
</div>
<?php echo mWarn();?>
<script>
	/**************获取验证码*****************/
var countdown=60; 
$("#verifyBtn-register").click(function(){
	settime(this);
});
function settime(obj) { 
    if (countdown == 0) { 
        obj.removeAttribute("disabled");    
        obj.value="获取验证码"; 
        $(obj).css("background","#e32b2b !important");
        countdown = 60; 
        return;
    } else { 
        obj.setAttribute("disabled", true); 
        obj.value="(" + countdown + ")秒后重发"; 
        $(obj).css({"background":"#ccc","color":"#fff"});
        countdown--; 
    } 
	setTimeout(function(){ 
		settime(obj);
	},1000);
}
$(function(){
	//发布
	$('.addPassenger_btn').on('click',function(){
		var imgNum = $('.seeImg').length;
		if( imgNum > 6 ){
			mwarn('最多只能上传6张图片');
		}else{
			$.post(root+"library/mData.php?type=addNeed",$("[name='needForm']").serialize(),function(data){
				if(data.warn == 2){
					location.href = data.href;
				}else{
					mwarn(data.warn);
				}
			},'json');	
		}
	});
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
$("#imgClick").imgUpload('needForm','logo_area',true);
</script>
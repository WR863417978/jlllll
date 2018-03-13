<?php
include "../../library/mFunction.php";
echo head('m');
$gid    = $get['gid'];#goodsId
$buyId  = $get['bid'];#buyCarId
if( !empty($gid) ){
    $info = findOne('goods',"id = '$gid'");
    $goods_src = root.$info['ico'];
}
?>
<style>
.logo_area{}
.seeImg{display: inline-block;position: relative;margin-right: 10px;margin-top: 10px;}
.delimg{position: absolute;top: -10px;right: -10px;text-align: center;font-size: 16px;background: #44444480;color: #fff;border-radius: 50%;width: 20px !important;height: 20px;line-height: 20px;}
</style>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick='windowBack();'><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">评价</p>
        </div>
        <a href="javascript:;" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--订单管理-->
<div class="container">
    <div class="mui-mbottom60 order-list mui-ptopsmaple">
        <div class="order">
            <div class="order-lists">
                <!--<h2 class="mui-dis-flex"><span class="flex1">订单号：12345678798</span></h2>-->
                <dl>
                    <dd><img src='<?php echo $goods_src;?>'></dd>
                    <dd class="info">
                        <p><?php echo $info['name'];?></p>
                        <!-- <p><span>单价：￥136.00</span><span>数量：3 </span> <span>待付款</span></p> -->
                    </dd>
                    <dd></dd>
                </dl>
            </div>
        </div>
        <div class="evaluation">
            <!-- <p class="evaluation-sf">
                <img src="img/star.png" border="0">
                <img src="img/star.png" border="0">
                <img src="img/star.png" border="0">
                <img src="img/star.png" border="0">
                <img src="img/star.png" border="0">
             </p>-->
            <form name="talkForm">
                <p>
                    <textarea placeholder='发表一下您对商品的意见与评价' name='text'></textarea>
                </p>
                <input type="hidden" name="goodsId" value='<?php echo $gid;?>'>
                <input type="hidden" name="buyId" value="<?php echo $buyId;#buyCarId?>">                
                <input type="hidden" name="userId" value='<?php echo $kehu['khid'];?>'>                
                <p class="goods-show">
                    <img src='<?php echo img('laX84134464tg');?>' class="uploadImg" id='imgClick'>
                </p>
                <div id='logo_area' class='logo_area' style='display:inline'></div>
                <input type="file" name="imgFile"  multiple='multiple' style='display:none'>
            </form>
            <input type="button" class="addPassenger_btn" value="发表评论"/>
        </div>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
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
                                +"<img style='width: 100px;height:100px'   src='"+this.result+"'/>"
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
                            +"<img style='width: 120px;'   src='"+this.result+"'/>"
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

$("#imgClick").imgUpload('talkForm','logo_area',true);

    $("[name='text']").blur(function(){
        var content = $(this).val();
        if( $.trim(content).length <= 10 ){
            mwarn('评价内容不小于10个字');
        }
    });
    $('.addPassenger_btn').on('click',function(){
        var num = $('.seeImg').length;
        var content = $("[name='text']").val();
        if( $.trim(content).length <= 10 ){
            mwarn('评价内容不小于10个字');
        }else if( num > 6 ){
            mwarn('最多只能上传6张图片');   
        }else{
            $.post(root+"library/mData.php?type=addTalk",$("[name='talkForm']").serialize(),function(data){
                if(data.warn == 2){
                    window.history.back(-1);
                }else{
                    mwarn(data.warn);
                }
            },'json');    
        }
    });
})
</script>
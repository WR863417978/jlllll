<?php
include "../../library/mFunction.php";
echo head('m');

?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="javascript:;" onclick='windowBack();' class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">我的二维码</p>
        </div>
         <a href="javascript:;" class="header-btn share-more"></a>
    </div>
</div>
<!--//-->
<div class="container mui-pt45">
    <div align="center" style="display: none;"> 
        <img src="<?php echo img('PmY88022900gr');?>" style="width:100px;"> 
        <img src="<?php echo mUserQrcode();?>" style="width:100px;"> 
    </div>
    <div id="imgBox" align="center">
      <input type="hidden" value="" onclick="hecheng()">
    </div>
    <input type="button" class="addPassenger_btn" value="长按保存图片"/>
</div>
<!-- 合成图片 -->
<script>
$(function(){
    draw(function(){
        document.getElementById('imgBox').innerHTML='<p style="padding:10px 0"></p><img src="'+base64[0]+'">';
    }); 
});
/*function hecheng(){
    draw(function(){
        document.getElementById('imgBox').innerHTML='<p style="padding:10px 0"></p><img src="'+base64[0]+'">';
    })  
}*/
var data=["<?php echo img('PmY88022900gr');?>","<?php echo mUserQrcode();?>"],base64=[];
function draw(fn){
    var c=document.createElement('canvas'),
        ctx=c.getContext('2d'),
        len=data.length;
    c.width=290;
    c.height=290;
    ctx.rect(0,0,c.width,c.height);
    ctx.fillStyle='#ccc';
    ctx.fill();
    function drawing(n){
        if(n<len){
            var img=new Image;
            //img.crossOrigin = 'Anonymous'; //解决跨域
            img.src=data[n];
            img.onload=function(){
                /*ctx.drawImage(img,0,0,100,200);*/
                if(n == 1){
                    /*ctx.rotate(10*Math.PI/180);*/
                    ctx.drawImage(img,200,200, 85,85);
                }else{
                    ctx.drawImage(img,0,0, 290,290);
                }
                drawing(n+1);//递归
            }
        }else{
            //保存生成作品图片
            base64.push(c.toDataURL("image/jpeg",0.8));
            fn();
        }
    }
    drawing(0);
}
</script>
<!-- // -->

<!--底部-->
<?php echo mFooter(); ?>
<!--//-->
<script>
    $('.header-login').click(function(){
        location.href = root + "m/mMemberSucced.php?type=shareNum";
    });
</script>
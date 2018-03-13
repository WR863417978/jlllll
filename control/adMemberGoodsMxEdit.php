<?php
//商品详细页
include "ku/adfunction.php";
include "ku/newfunction.php";
$id = $_GET['id'];
$onion = array(
    "修改会员商品" => root."control/adMemberGoodsMxEdit.php?id=$id"
);

$data = findsql('member_goods',"id = '$id'");
if(isset($_POST['dosubmit'])){
	$id = $_POST['id'];
	$data = array();
	$data['goods_name'] = $_POST['goodsname'];
	$data['goods_img'] = $_POST['imgpath'];
	$data['goods_money']= $_POST['goods_money'];
	$data['content'] = $_POST['content'];
	$data['addtime'] = time();
	$re = updatesql('member_goods',$data,"id = '$id'");
	if($re){
		alert('修改成功','adMemberGoods.php');		
	}else{
		alert('修改失败');
	}
}

echo head("ad").adheader($onion);
?>
<link rel="stylesheet" href="<?php echo root."library/layer.css";?>" />
    <div class="minHeight">
        <!--商品资料开始-->
        <div class="kuang">
            <form name="GoodsForm" action="" method="post" onsubmit="return isok();" >
                <table class="tableRight">
                    <tr>
                        <td><span class="red">*</span>&nbsp;商品名称：</td>
                        <td>
                            <input name="goodsname" isname="商品名称" type="text" class="text isok" value="<?php echo $data['goods_name'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;缩略图：</td>
                        <td>
                        	<input type="file" id="upimg" name="file" />
                            <input  style="display:none;" isname="缩略图" name="imgpath" id="imgpath" type="text" class="text isok" value="<?php echo $data['goods_img'];?>"/>
                        </td>
                    </tr>
                    <tr id="imgblock">
                        <td></td>
                        <td>
                            <img src="<?php echo $data['goods_img'];?>" id="imgshow" style="width:200px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;价格：</td>
                        <td>
                            <input name="goods_money" type="number" isname="价格" class="text isok" value="<?php echo $data['goods_money'];?>"></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>详情：</td>
                        <td>
                           <script style="width:800px;height:300px;" isname="详情" class="isok" id="container" name="content" type="text/plain"><?php echo $data['content'];?></script>
                        </td>
                    </tr>
                   
                    <tr>
                    	<td><input type="hidden" name="id" value="<?php echo $data['id']; ?>"/></td>
                        <td><input type="submit" name="dosubmit" class="button" value="保存" ></td>
                    </tr>
                    
                </table>
            </form>
        </div>
        <script src="<?php echo root."library/ueditor/ueditor.config.js";?>"></script>
        <script src="<?php echo root."library/ueditor/ueditor.all.min.js";?>"></script>
		<script src="<?php echo root."library/ajaxfileupload.js";?>"></script>
		<script src="<?php echo root."library/layer.js";?>"></script>
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>
<script>
	function isok(){
		var is = 1;
		var val,scriptval,isname;
		$(".isok").each(function(){
			val = $(this).val();
			scriptval = $(this).html();
			if(val == '' && scriptval == ''){
				is = 2;
				isname = $(this).attr('isname');
				return false;
			}
		})
		
		if(is == 2){
			layer.alert(isname+'不可为空');
			return false;
		}
		
	}
</script>
<script>
	$(function(){
            $("body").on("change","#upimg",function(){
                $.ajaxFileUpload({  
                    url : "./adUpFile.php",   //submit to UploadFileServlet  
                    secureuri : false,  
                    dataType : 'text',    //返回的文本类型
                    fileElementId : 'upimg',     //file 的   ID
                    success : function(data) { 
                    	console.log(data);
                        $("#imgpath").val('/'+data);     //把返回的路径放到要提交的input中    data是返回的路劲
                        $("#imgshow").attr("src",'/'+data);   //显示图片
                        $("#imgblock").show();
                    },  
                });
            })
        })
</script>
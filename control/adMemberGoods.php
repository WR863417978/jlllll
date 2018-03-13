<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 11:06
 */
//会员商品管理列表页\
include "ku/adfunction.php";
include "ku/newfunction.php";
$num = 10; // 每页显示条数
if(isset($_GET['page'])){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$nowpage = ($page-1) * $num; 
$limit = "$nowpage,$num";
$data = seletesql('member_goods','1=1','id desc',$limit);
$count = countsql('member_goods');
//搜索
if(isset($_GET['keyword'])){
	$keyword = $_GET['keyword'];
	$data = seletesql('member_goods','1=1 and goods_name like "%'.$keyword.'%"','id desc',$limit);
	$count = countsql('member_goods',"goods_name like '%$keyword%'");
}

$newpage = newpage($count,$num,$page,"adMemberGoods.php");


//删除
if(isset($_POST['del']) && $_POST['del'] == 'del'){
	$id = $_POST['id'];
	$del = delsql('member_goods', "id = '$id'");
	if($del == 1){
		echo 'success';die;
	}else{
		echo 'full';die;
	}
}
//多条删除
if(isset($_POST['alldel']) && $_POST['alldel'] == 'alldel'){
	if(substr($_POST['idstr'],-1) == ','){
		$idstr = substr( $_POST['idstr'],0,-1);
	}
	
	$del = delsql('member_goods', "id in ($idstr)");
	if($del == 1){
		echo 'success';die;
	}else{
		echo 'full';die;
	}
}


$onion = array(
    "会员商品" => root."control/adMemberGoods.php"
);
echo head("ad").adheader($onion);
?>
<link rel="stylesheet" href="<?php echo root."library/layer.css";?>" />
    <div class="minHeight">
        <div class="search">
            <form name="Search" action="adMemberGoods.php" method="get">
                商品名称：<input name="keyword" type="text" class="text textPrice" value="<?php echo $_GET['keyword'];?>">
                <input type="submit" value="模糊查询">
            	<a href="adMemberGoods.php" style="padding:3px 6px;" class="spanButton">全部数据</a>
            </form>
        </div>
        <div class="search">
            <span onclick="$('[name=GoodsForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
            <span onclick="$('[name=GoodsForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
            <!--<a href="<?php echo root."control/adGoodsOne.php";?>"><span class="spanButton">一级商品分类</span></a>
            <a href="<?php echo root."control/adGoodsTwo.php";?>"><span class="spanButton">二级商品分类</span></a>-->
            <a href="<?php echo root."control/adMemberGoodsMx.php";?>"><span class="spanButton">新建商品</span></a>
            <span onclick="alldelete();" class="spanButton">删除所选</span>
            <span class="smallWord floatRight">
			共找到<?php echo $count;?>条数据&nbsp;&nbsp;
            第<?php echo $page;?>页/
            共<?php echo ceil($count/$num);?>页
		</span>
        </div>
        <!--查询结束-->
        <!--列表开始-->
        <form name="GoodsForm">
            <table class="tableMany">
                <tr>
                    <td></td>
                    <td>ID</td>
                    <td>商品名称</td>
                    <td>价格</td>
                    <td>缩略图</td>
                    <td>添加时间</td>
                    <td style="width:54px;">操作</td>
                </tr>
                <?php if($data){ ?>
                <?php foreach($data as $v){ ?>
                <tr>
                	<td><input name='goodsList[]' type='checkbox' value='<?php echo $v['id']; ?>'/></td>
                	<td><?php echo $v['id']; ?></td>
                	<td><?php echo $v['goods_name']?></td>
                	<td><?php echo $v['goods_money']?></td>
                	<td><img src="<?php echo $v['goods_img']; ?>" style='width:120px;max-height:200px;'/></td>
                	<td><?php echo date('Y-m-d H:i:s',$v['addtime'])?></td>
                	<td>
                		<a href='adMemberGoodsMxEdit.php?id=<?php echo $v['id']; ?>'><span class='spanButton'>修改</span></a>&nbsp;&nbsp;<a onclick="delalert(<?php echo $v['id'];?>);"><span class='spanButton'>删除</span></a>
            		</td>
                </tr>
                <?php } }else{ ?>
                	<tr><td colspan='12' style="text-align: center;">没有找到您要的商品</td></tr>
                <?php } ?>
            </table>
        </form>
        <?php if($data){ ?>
        <div class='page'>
			<?php echo $newpage; ?>
		</div>
		<?php } ?>
        <!--列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/addata.php").warn().adfooter();?>
	<script src="<?php echo root."library/layer.js";?>"></script>
<script>
	//删除多条数据
	function alldelete(){
		var idstr='';
		$("input[type='checkbox']:checked").each(function(){
			idstr += $(this).val()+',';
		});
		layer.confirm('删除不可恢复，确认删除多条数据?', function(index){
			$.post('adMemberGoods.php',{idstr:idstr,alldel:'alldel'},function(data){
				console.log(data);
				if(data == 'success'){
			  		layer.open({
					  content: '删除成功',
					  yes: function(index, layero){
					  	location.reload();
					    //do something
					    layer.close(index); //如果设定了yes回调，需进行手工关闭
					  }
					}); 
			  		
			  	}else if(data == 'full'){
			  		layer.alert('删除失败');
			  	}
			})
		  	layer.close(index);
		}); 
	}
	//删除一条数据
	function delalert(id){
		layer.confirm('删除不可恢复，确认操作?', function(index){
		  //do something
		  $.post('adMemberGoods.php',{id:id,del:'del'},function(data){
		  	if(data == 'success'){
		  		layer.open({
				  content: '删除成功',
				  yes: function(index, layero){
				  	location.reload();
				    //do something
				    layer.close(index); //如果设定了yes回调，需进行手工关闭
				  }
				}); 
		  		
		  	}else if(data == 'full'){
		  		layer.alert('删除失败');
		  	}
		  })
		  
		  layer.close(index);
		}); 
	}
</script>
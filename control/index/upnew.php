<?php
//上新专区页面
include "../ku/adfunction.php";
ControlRoot('adupnew');
$list = "";
$ThisUrl = $adroot."adOrder.php?".$_SERVER['QUERY_STRING'];
$sql= "select g.id,g.name,gs.name as gname,g.price,g.xian,g.ico,g.summary,up.isShow,up.newid from upnew as up left join goods as g on up.goodsId = g.id left join goodsone as gs on g.goodsOneId = gs.id";
paging($sql," order by g.id",5);
if($num == 0){
    $list .= "<tr><td colspan='13'>一个订单都没有</td></tr>";
}else{
    while($array = mysql_fetch_assoc($query)){
        $list .= "
            <tr>
            	  <td><input type='checkbox'></td>
                  <td>{$array['id']}</td>
                  <td>{$array['name']}</td>
                  <td>{$array['gname']}</td>
                  <td>{$array['price']}</td>
                  <td>{$array['xian']}</td>
                  <td>{$array['isShow']}</td>
                  <td><a target='_blank' href='{$root}{$array['ico']}' title='点击查看大图'><img class='smallImg imgHover' src='{$root}{$array['ico']}' alt='暂无图片'></a></td>
                  <td class='summary'>".zishu(kong($array['summary']),20)."</td>
                  <td><a href='#' class='gengxin' isShow={$array[isShow]} id={$array[newid]}>更新</a></td>
            </tr>";
    }
}

$onion = array(
    "首页管理" => "",
	"上新专区" => root."control/index/special.php",
	$title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<style>
    .search2{
        margin-top:15px;
        margin-bottom:-10px;
        border-left:1px solid #CAD9EA;
        border-top:1px solid #CAD9EA;
        border-right:1px solid #CAD9EA;
    }
    .order_list {
        float: left;
        display:inline-block;
    }

    .search2 .order_list li{
        float:left;
        display:inline-block;
        padding:5px;
        line-height: 30px;
        width:80px;
        text-align: center;
    }
    .layui-form-item{
        display: inline-block;
    }
    .nav-order-on{
        background: #EBF2F8;
    }



</style>
<!--<script type="text/javascript" src="ku/js/jquery-1.4.2.min.js"></script>-->
<script type="text/javascript">
    $(function(){
        $(".gengxin").click(function(){
            var isShow = $(this).attr('isShow');
            var id = $(this).attr("id");
            var message = '';
            if(isShow == '显示'){
                message = '隐藏' ;
            }else if(isShow == '隐藏'){
                message = '显示' ;
            }
            var status='';
            status = confirm("你确定要"+message+"么？");
            if(status){
                $.post('../ku/updateUpnew.php?type=update',{"message":message,"id":id},function(data){
                    alert(data.status);
                },'json')
            }
        });
    });
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#begin_time'
            , type: 'datetime'
            , range: false
        });
        laydate.render({
            elem: '#end_time'
            , type: 'datetime'
            , range: false
        });
    });
</script>
<div class="search">
            <a href="adspecial.php"><span class="spanButton">新增商品</span></a>
        </div>
    <div class="minHeight">
        <div>
            <!-- 列表开始-->
            <form name="OrderForm">
                <table class="tableMany" style="text-align: center">
                    <tr>
                    	<td><a href='adspecial.php'></a></td>
                        <td>Id</td>
                        <td>商品名称</td>
                        <td>所属分类</td>
                        <td>价格</td>
                        <td>商品是否显示</td>
                        <td>专区是否显示</td>
                        <td>商品列表图</td>
                        <td>摘要</td>
                        <td>操作</td>
                    </tr>
                    <?php echo $list;?>
                </table>
            </form>
        </div>
        <div>
		    <span class="pageTop">
		        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
		        第<?php echo $page;?>页/
		        共<?php echo $AllPage;?>页
		    </span>
        </div>
        <?php echo fenye($ThisUrl,5);?>
        <!--订单列表结束-->
    </div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
<?php
//专题管理页面
include "../ku/adfunction.php";
ControlRoot('adspecial');
$list = "";
$ThisUrl = $adroot."adOrder.php?".$_SERVER['QUERY_STRING'];
$sql= "select * from special";
paging($sql," order by spid",5);
if($num == 0){
    $list .= "<tr><td colspan='13'>一个订单都没有</td></tr>";
}else{
    while($array = mysql_fetch_assoc($query)){
        $list .= "
            <tr>
            	  <td><input type='checkbox'></td>
                  <td>{$array['spid']}</td>
                  <td>{$array['showPage']}</td>
                  <td>{$array['specialName']}</td>
                  <td>{$array['isShow']}</td>
                  <td>{$array['createTime']}</td>
                  <td>{$array['updateTime']}</td>
                  <td><a href='upspecial.php?id={$array[spid]}'>更新</a></td>
            </tr>";
    }
}

$onion = array(
    "首页管理" => "",
	"专题管理" => root."control/index/special.php",
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
            <a href="adspecial.php"><span class="spanButton">新增专题</span></a>
        </div>
    <div class="minHeight">
        <div>
            <!-- 列表开始-->
            <form name="OrderForm">
                <table class="tableMany" style="text-align: center">
                    <tr>
                    	<td><a href='adspecial.php'>新增专题</a></td>
                        <td>Id</td>
                        <td>显示条数</td>
                        <td>专题名称</td>
                        <td>是否显示</td>
                        <td>创建时间</td>
                        <td>更新时间</td>
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
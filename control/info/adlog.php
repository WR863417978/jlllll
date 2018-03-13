<?php 
include "../ku/adfunction.php";
ControlRoot();
$ThisUrl = root."control/info/adlog.php";
if(power("adlog","seeAll")){
    $where = "";
}else{
    $where = " and targetId = '$Control[adid]' ";
}
$onion = array(
    "信息管理" => root."control/info/info.php",
	"日志管理" => $ThisUrl
);
$sql = "select * from log where 1=1 ".$where.$_SESSION['adLog']['Sql'];
paging($sql," order by id desc",100);
echo head("ad").adheader($onion);
?>
<div class="search">
    <form name="Search" action="<?php echo root."control/ku/post.php?type=adSearchLog";?>" method="post">
    <?php echo RepeatSelect("log where 1=1 {$where}","target","target","select","--选择分类--",$_SESSION['adLog']['target']);?>
    目标ID：<input name="targetId" type="text" class="text textPrice" value="<?php echo $_SESSION['adLog']['targetId'];?>">
    详细说明：<input name="text" type="text" class="text textPrice" value="<?php echo $_SESSION['adLog']['text'];?>">
    <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <span class="pageTop">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<table class="tableMany">
    <tr>
        <td>目标对象</td>
        <td>目标ID</td>
        <td>详细说明</td>
        <td>记录时间</td>
    </tr>
    <?php
    if($num > 0){
        while($array = mysql_fetch_array($query)){
            echo "
            <tr>
                <td>{$array['target']}</td>
                <td>{$array['targetId']}</td>
                <td>{$array['text']}</td>
                <td>{$array['time']}</td>
            </tr>
            ";
        } 
    }else{
        echo "<tr><td colspan='4'>一条记录都没有</td></tr>";
    }
    ?>
</table>
<?php echo fenye($ThisUrl,7).warn().adfooter();?>
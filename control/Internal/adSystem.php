<?php
include "../ku/adfunction.php";
ControlRoot("adSystem");
$ThisUrl = root."control/Internal/adSystem.php";
if(empty($_GET['id'])){
    $System = query("content"," type = '内部资料' and classify = '公司制度' and xian = '显示' order by list ");
	if(empty($System)){
		$_SESSION['warn'] = "一条管理制度都没有";
		header("location:{$root}control/Internal/adInternal.php");
	}else{
		header("location:{$ThisUrl}?id={$System['id']}");
	}
	exit(0);
}
//左侧导航
$menu = "";
$Sql = mysql_query(" select * from content where type = '内部资料' and classify = '公司制度' and xian = '显示' order by list ");
while($array = mysql_fetch_array($Sql)){
	$menu .= "<a href='{$ThisUrl}?id={$array['id']}'><li class='".MenuGet("id",$array['id'],"syMenuHover")."'>{$array['title']}</li></a>";
}
//本章基本参数
$content = query("content"," id = '$_GET[id]' ");
$onion = array(
    "内部管理" => root."control/Internal/adInternal.php",
	"管理制度" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<div class="column minHeight">
    <!--左侧导航开始-->
    <div class="syMenu">
        <ul><?php echo $menu;?></ul>
    </div>
    <!--左侧导航结束-->
    <!--右侧内容开始-->
    <div class="syRight">
		<h2><?php echo $content['title'];?></h2>
		<?php echo ArticleMx($_GET['id']);?>
    </div>
    <!--右侧内容结束-->
</div>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
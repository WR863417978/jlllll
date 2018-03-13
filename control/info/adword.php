<?php 
include "../ku/adfunction.php";
ControlRoot("adword");
$ThisUrl = root."control/info/adword.php";
//修正列表
$sql = "select * from website where 1=1 ".$_SESSION['adWeb']['Sql'];
paging($sql," order by list ",100);
$word = "";
if($num > 0){
	while($array = mysql_fetch_array($query)){
	    $word .= "
		<tr>
		    <td><input name='WordList[]' type='checkbox' value='{$array['webid']}'/></td>
			<td>{$array['webid']}</td>
		    <td title='{$array['name']}'>".zishu(kong($array['name']),20)."</td>
		    <td title='{$array['webnr']}'>".zishu(kong(htmlspecialchars($array['text'])),20)."</td>
		    <td>{$array['list']}</td>
		    <td>{$array['del']}</td>
			<td>{$array['updateTime']}</td>
			<td><span EditWord='{$array['webid']}' class='spanButton'>编辑</span></td>
		</tr>
		";
	}
}else{
	$word = "<tr><td colspan='8'>一条内容都没有</td></tr>";
}
$onion = array(
    "信息管理" => root."control/info/info.php",
	"网站文字管理" => $ThisUrl
);
echo head("ad").adheader($onion);
?>
<!--查询开始-->
<div class="search">
    <form name="search" action="<?php echo root."control/ku/post.php?type=adSearchWord";?>" method="post">
        ID号：<input name="id" type="text" class="text textPrice" value="<?php echo $_SESSION['adWeb']['id'];?>">
        标题：<input name="name" type="text" class="text textPrice" value="<?php echo $_SESSION['adWeb']['name'];?>">
        内容：<input name="content" type="text" class="text textPrice" value="<?php echo $_SESSION['adWeb']['content'];?>">
        <?php echo select("del","select textPrice","--可删除--",array("是","否"),$_SESSION['adWeb']['del']);?>
        <input type="submit" value="模糊查询">
    </form>
</div>
<div class="search">
    <span onclick="$('[name=AdWebsiteForm] [type=checkbox]').prop('checked',true);" class="spanButton">选择全部</span>
    <span onclick="$('[name=AdWebsiteForm] [type=checkbox]').prop('checked',false);" class="spanButton">取消选择</span>
    <span EditWord="" class="spanButton">新增文字</span>
    <span onclick="EditList('AdWebsiteForm','deleteWord')" class="spanButton">删除所选</span>
    <span class="pageTop">
        共找到<?php echo $num;?>条数据&nbsp;&nbsp;
        第<?php echo $page;?>页/
        共<?php echo $AllPage;?>页
    </span>
</div>
<!--查询结束-->
<form name="AdWebsiteForm">
<table class="tableMany">
    <tr>
        <td></td>
        <td>id号</td>
        <td>标题</td>
        <td>内容</td>
        <td>排序号</td>
        <td>可删除</td>
        <td>更新时间</td>
        <td></td>
    </tr>
    <?php echo $word;?>
</table>
</form>
<?php echo fenye($ThisUrl,7);?>
<!--编辑弹出层开始-->
<div class="hide" id="adwordEdit">
    <div class="dibian"></div>
    <div class="win" style=" height:324px; width:758px; margin: -167px 0px 0px -379px;">
      <p class="winTitle">编辑文字<span onclick="$('#adwordEdit').hide()" class="winClose">×</span></p>
      <form name="WordForm">
      <table class="tableRight">
          <tr>
              <td>ID号：</td>
              <td id="wordId"></td>
          </tr>
          <tr>
              <td><span class="red">*</span>&nbsp;标题：</td>
              <td><input name="wordName" type="text" class="text"></td>
          </tr>
          <tr>
              <td><span class="red">*</span>&nbsp;排序号：</td>
              <td><input name="wordList" type="text" class="text textPrice"></td>
          </tr>
          <tr>
              <td><span class="red">*</span>&nbsp;内容：</td>
              <td><textarea name="wordContent" class="textarea"></textarea></td>
          </tr>
          <tr>
              <td><input name="WordId" type="hidden"></td>
              <td><input onclick="Sub('WordForm','<?php echo root."control/ku/data.php?type=adwordEdit";?>')" type="button" class="button" value="确认提交"></td>
          </tr>
      </table>
      </form>
    </div>
</div>
<!--编辑弹出层结束-->
<script>
$(document).ready(function(){
	//弹出编辑层
	$("[EditWord]").click(function(){
		$("#adwordEdit").fadeIn();
		$.post(root + "control/ku/data.php?type=adGetWord",{id:$(this).attr("EditWord")},function(data){
			if(data.warn == 2){
				document.WordForm.wordName.value = data.title;
				document.WordForm.wordList.value = data.list;
				document.WordForm.wordContent.value = data.content;
				document.WordForm.WordId.value = data.id;
				if(data.id == ""){
				    $("#wordId").html("未设置");
				}else{
				    $("#wordId").html(data.id);
				}
			}else{
			    warn(data.warn);
			}
		},"json");
	});
});
</script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter();?>
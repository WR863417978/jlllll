<?php
//sql 语句    添加
function addsql($table,$data){
	foreach($data as $key => $v){
		$strkey .= '`'.$key."`,";
		$strval .= "'".$v."',";
	}
	if(substr($strkey,-1) == ','){
		$strkey = substr($strkey,0,-1);
	}
	if(substr($strval,-1) == ','){
		$strval = substr($strval,0,-1);
	}
	$sql = "INSERT INTO `$table` ($strkey) VALUES ($strval)";
	if(mysql_query($sql)){
		return mysql_insert_id();
	}else{
		echo "sql错误：".mysql_error();die;
	}
}

//sql 语句  修改
function updatesql($table,$data,$where){
	if($where){
		foreach($data as $key => $v){
			$upstr .= "$key = '$v',";
		}
		if(substr($upstr,-1) == ','){
			$upstr = substr($upstr,0,-1);
		}
		$sql = "UPDATE $table SET $upstr WHERE $where";
		if(mysql_query($sql)){
			return 1;
		}else{
			echo "sql错误：".mysql_error();die;
		}
	}else{
		echo '修改请规定条件';die;
	}
}

// sql 语句  查询多条
function seletesql($table,$where="1=1",$order="id desc",$limit="0,10",$field='*'){
	$sql = "select $field from `$table` where $where order by $order limit $limit";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$data[] = $row;
	}
//	return $sql;
	if($res){
		return $data;
	}else{
		echo "sql错误：".mysql_error();die;
	}
	
}
//sql 语句  查询一条
function findsql($table,$where="1=1",$field='*',$echosql=null){
	$sql = "select $field from `$table` where $where limit 1";
	if($echosql != null){
		echo $sql;die;
	}
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);
	if($res){
		return $row;
	}else{
		echo "sql错误：".mysql_error();die;
	}
}

//sql 语句  查询数量
function countsql($table,$where="1=1"){
	$sql = "select count(*) from `$table` where $where";
	$res = mysql_query($sql);
	$data = mysql_fetch_assoc($res);
	return $data['count(*)'];
}

//sql 语句  删除
function delsql($table,$where){
	if($where && $where != '1=1'){
		$sql = "delete from `$table` where $where";
		if(mysql_query($sql)){
			return 1;
		}else{
			echo "sql错误：".mysql_error();die;
		}
	}else{
		echo '删除请规定条件';die;
	}
	
}

function newpage($count,$num,$page,$url){
	$zpage = ceil($count/$num);   //总页数
	$lastpage = $page-1;  //上一页
	$nextpage = $page+1;  //下一页
	
	if(isset($_GET['keyword'])){
		$getkey = $_GET['keyword'];
		$keystr = "&keyword=$getkey";
	}else{
		$keystr = '';
	}
	
	
	
	if($page <= 2){
		$startpage = 1;
	}else{
		if($page+2 >= $zpage){
			$startpage = $zpage-4;
		}else{
			$startpage = $page - 2;
		}
		
	}
	
	if($page+2 >= $zpage){
		$endpage = $zpage;
	}else{
		if($page-2 <= 1){
			$endpage = 5;
		}else{
			$endpage = $page +2;
		}
		
	}
	
	for($i=$startpage;$i<=$endpage;$i++){
		if($i == $page){
			$html .= "<a style='border:1px solid #5eb599;color:#5eb599;' href='$url?page=$i$keystr'>$i</a>\n";
		}else{
			$html .= "<a href='$url?page=$i$keystr'>$i</a>\n";
		}
	}
	
	if($lastpage < 1){
		$ret['last'] = "<a style='border:1px solid #ccc;color:#ccc;'>上一页</a>\n";
	}else{
		$ret['last'] = "<a href='$url?page=$lastpage$keystr'>上一页</a>\n";
	}
	
	$ret['html'] = $html;
	
	if($nextpage > $zpage){
		$ret['next'] = "<a style='border:1px solid #ccc;color:#ccc;'>下一页</a>\n";
	}else{
		$ret['next'] = "<a href='$url?page=$nextpage$keystr'>下一页</a>\n";
	}
	
	$ret['frist'] = "<a href='$url?page=1$keystr'>第一页</a>\n";
	$ret['one'] = "<a href='$url?page=$zpage$keystr'>最后一页</a>";
	
	$str = $ret['frist'].$ret['last'].$ret['html'].$ret['next'].$ret['one'];
	return $str;
	
}

function alert($message,$url=''){
	if($url == ''){
		echo "<script>alert('$message');</script>";
	}else{
		echo "<script>alert('$message');window.location.href='$url'</script>";
	}
	
}
?>
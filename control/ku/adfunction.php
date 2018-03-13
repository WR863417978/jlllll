<?php
include dirname(__FILE__) . "/configure.php";



/***********************列表菜单************************/
function adlist($img, $url, $title, $word){
    return "
    <div class='kuang'>
        <div class='list' style='background-image:url(" . root . "img/adimg/{$img})'></div>
        <a href='" . root . "control/{$url}'>
        <h2>{$title}</h2>
        <p>{$word}</p>
        </a>
        <div class='clear'></div>
    </div>
    ";
}
/***********************头部************************/
function adheader($onion){
	$url = "<a target='_blank' href='".root."'>".website("name")."</a>";
	foreach($onion as $key => $value){
		$url .= "&nbsp;>&nbsp;<a href='{$value}'>{$key}</a>";
	}
	$html = "
	<div class='onion'>{$url}</div>
	<div class='body'>
	";
	return $html;
}
/***********************底部************************/
function adfooter(){
	$html = "
			</div>
		</body>
	</html>
	";	
	return $html;
}
/***********************警示函数************************/
/*
本函数不仅可以用户列表页、还可以用于明细页表单的附加密码警示
javascript的EditList函数带表单名称的作用是让同一个页面多个表单都可以使用本函数
注：表单中不用input type='hidden'而用type='text'且class来隐藏文本框的原因是本表单为单一文本框，客户按enter时会自动提交。
 */
function PasWarn($PostUrl)
{
    $div = "
    <div class='hide' id='PasWarn'>
        <div class='dibian'></div>
        <div class='win' style=' height:236px; width:500px; margin:-163px 0 0 -250px;'>
            <p class='winTitle'>一级警告<span class='winClose' onclick=\"$('#PasWarn').hide()\">×</span></p>
            <form name='PasForm'>
            <table class='tableRight'>
                <tr>
                    <td style='width:100px;height:100px;'>警告信息：</td>
                    <td id='PasWarnWord'></td>
                </tr>
                <tr>
                    <td>登录密码：</td>
                    <td><input name='Password' type='password' class='text short'></td>
                </tr>
                <tr>
                    <td>
                    <input name='FormName' type='text' class='hide'>
                    <input name='PadWarnType' type='text' class='hide'>
                    </td>
                    <td><input type='button' class='button' value='确认提交' onclick=\"Sub('PasForm,' + document.PasForm.FormName.value,'{$PostUrl}')\"></td>
                </tr>
            </table>
            </form>
        </div>
    </div>
    <script>
    function EditList(FormName,type){
        document.PasForm.FormName.value = FormName;
        document.PasForm.PadWarnType.value = type;
        $('#PasWarn').fadeIn();
        $.post('" . root . "control/ku/data.php?type=getPasWarn',{PasWarnWord:type},function(data){
            $('#PasWarnWord').html(data.word);
        },'json');
    }
    </script>
    ";
    return $div;
}
/***********************跟进************************/
function follow($target, $targetId)
{
    $follow = "";
    $Sql    = mysql_query(" select * from follow where targetId = '$targetId' order by time desc ");
    if (mysql_num_rows($Sql) == 0) {
        $follow = "暂无跟进";
    } else {
        if (($target == "客户" and power("adClient", "delFollow")) or ($target == "订单" and power("adOrder", "delFollow"))) {
            $finger = 2;
        }
        while ($array = mysql_fetch_array($Sql)) {
            if ($finger == 2) {
                $d = "<a href='" . root . "control/ku/adpost.php?type=adFollowDel&id={$array['id']}'><div class='followDel'>×</div></a>";
            } else {
                $d = "";
            }
            $admin = query("admin", " adid = '$array[adid]' ");
            if (mb_strlen($array['text'], "UTF8") < 68) {
                $name      = "";
                $titleText = $array['text'];
                $divText   = "";
            } else {
                $name      = $admin['adname'];
                $titleText = "";
                $divText   = "
                <div class='followText'>
                    " . neirong($array['text']) . "
                </div>
                ";
            }
            $follow .= "
            <div class='followTitle'>
                <img src='" . adHead($admin) . "'>
                {$name}
                {$titleText}
                <span>{$array['time']}</span>&nbsp;
                {$d}
            </div>
            " . $divText;
        }
    }
    $html = "
    <div class='follow'>{$follow}</div>
    <div class='kuang'>
        <form name='followForm'>
        <textarea name='text' class='followTextarea' placeholder='新增跟进记录'></textarea>
        <input name='target' type='hidden' value='{$target}'>
        <input name='targetId' type='hidden' value='{$targetId}'>
        <input type='button' class='button' value='提交' onclick=\"Sub('followForm',root+'control/ku/addata.php?type=adFollow')\">
        </form>
    </div>
    ";
    return $html;
}
/***********************附件上传************************/
function fileUpload($type, $id)
{
    //上传附件列表
    if (($type == "客户" and power("adClient", "newFile")) or ($type == "订单" and power("adOrder", "newFile"))) {
        $newFile = "<span class='spanButton' onclick=\"document.fileUpForm.file.click()\">新增</span>";
    }
    if (($type == "客户" and power("adClient", "delFile")) or ($type == "订单" and power("adOrder", "delFile"))) {
        $delFile = "&nbsp;<span class='spanButton' onclick=\"EditList('fileForm','fileDelete')\">删除</span>";
    }
    $fileSql = mysql_query(" select * from file where target = '$type' and targetId = '$id' order by time desc ");
    $fileTr  = "";
    if (mysql_num_rows($fileSql) == 0) {
        $fileTr = "<tr><td colspan='6'>一个附件都没有</td></tr>";
    } else {
        while ($array = mysql_fetch_array($fileSql)) {
            $admin = query("admin", " adid = '$array[adid]' ");
            if (in_array($array['type'], array("word", "excel"))) {
                $fileShow   = "<a target='_blank' href='https://view.officeapps.live.com/op/view.aspx?src=" . root . "{$array['src']}'><span class='spanButton'>预览</span></a>";
                $downButton = "下载";
            } else {
                $downButton = "预览";
                $fileShow   = "";
            }
            $fileTr .= "
            <tr>
                <td><input name='file[]' type='checkbox' value='{$array['id']}'></td>
                <td>{$admin['adname']}</td>
                <td>{$array['name']}</td>
                <td>{$array['type']}</td>
                <td>{$array['time']}</td>
                <td>
                    <a target='_blank' href='" . root . "{$array['src']}'><span class='spanButton'>{$downButton}</span></a>
                    {$fileShow}
                </td>
            </tr>
            ";
        }
    }
    $fileTable = "
    <a name='fileAnchor'></a>
    <form name='fileForm'>
    <table class='tableMany'>
        <tr>
            <td></td>
            <td>上传者</td>
            <td>附件名称</td>
            <td>类型</td>
            <td>上传时间</td>
            <td>{$newFile}{$delFile}</td>
        </tr>
        {$fileTr}
    </table>
    </form>
    <div class='hide'>
    <form name='fileUpForm' method='post' enctype='multipart/form-data' action='" . root . "control/ku/adpost.php?type=fileUpload'>
    <input name='file' type='file' onchange='document.fileUpForm.submit()'>
    <input name='target' type='hidden' value='{$type}'>
    <input name='targetId' type='hidden' value='{$id}'>
    </form>
    </div>
    ";
    return $fileTable;
}
function showOrder($array)
{
    if($array['o_type']==1){
        $array['o_type']='购买订单';
    }else{
         $array['o_type']='积分订单';
    }
    if($array['pay_type_online']==0){
        $array['pay_type_online']='未支付';
    }else if($array['pay_type_online']==1){
        $array['pay_type_online']='线下转账';
    }else if($array['pay_type_online']==2){
        $array['pay_type_online']='微信';
    }else if($array['pay_type_online']==3){
        $array['pay_type_online']='支付宝';
    }else if($array['pay_type_online']==4){
        $array['pay_type_online']='银联';
    }
    if($array['workFlow']==0){
        $array['workFlow']='待支付';
    }else if($array['workFlow']==1){
        $array['workFlow']='待发货';
    }else if($array['workFlow']==2){
        $array['workFlow']='待收货';
    }else if($array['workFlow']==3){
        $array['workFlow']='已收货';
    }else if($array['workFlow']==4){
        $array['workFlow']='待评价';
    }else if($array['workFlow']==5){
        $array['workFlow']='已完成';
    }else if($array['workFlow']==6){
        $array['workFlow']='已申请退款';
    }else if($array['workFlow']==7){
        $array['workFlow']='已退款';
    }else if($array['workFlow']==8){
        $array['workFlow']='申请退货';
    }else if($array['workFlow']==9){
        $array['workFlow']='同意退货';
    }
    return $array;
}
function newPdo()
{
    $pdo = new PDO('mysql:host='.$GLOBALS['conf']['ServerName'].';dbname='.$GLOBALS['conf']['DatabaseName'], $GLOBALS['conf']['UserName'], $GLOBALS['conf']['password'] );
    $pdo->query('set names utf8');
    return $pdo;
}
function getSelect()
{
    $arr = array('1','待支付','待发货','待收货','已收货','待评价','已完成','已申请退款','已退款','申请退货','同意退货');
    return $arr;
}
function getSelecttype()
{
    $arr = array('1','购买订单','积分订单');
    return $arr;
}
function showWorkFlow($array)
{
    if($array['workFlow']==0){
        $a='待支付';
    }else if($array['workFlow']==1){
        $a='待发货';
    }else if($array['workFlow']==2){
        $a='待收货';
    }else if($array['workFlow']==3){
        $a='已收货';
    }else if($array['workFlow']==4){
        $a='待评价';
    }else if($array['workFlow']==5){
        $a='已完成';
    }else if($array['workFlow']==6){
        $a='已申请退款';
    }else if($array['workFlow']==7){
        $a='已退款';
    }else if($array['workFlow']==8){
        $a='申请退货';
    }else if($array['workFlow']==9){
        $a='同意退货';
    }
    return $a;
}
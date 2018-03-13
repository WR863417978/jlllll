<?php
header('content-type:text/html; charset=utf8');
include "adfunction.php";
$type = $_GET['type'];
$id = $_POST['ClientList'];
if(!empty($id)){
    switch ($type == "excelOut") {
        case 'adClient': //导出客户信息
            ControlRoot("adClient");
            $id = $_POST['ClientList'];
            $title  = "客户信息表";
            $sql    = "SELECT * FROM kehu WHERE khid='".implode("' OR khid='",array_merge($id))."'";
            $result = mysql_query($sql);
            $nums   = mysql_num_rows($result);
            if ($nums > 0) {
                while ($array = mysql_fetch_assoc($result)) {
                    $quyu = query("region","id='$array[regionId]'");
                    $num = mysql_num_rows(mysql_query("SELECT * FROM income WHERE khid='$array[khid]'"));
                    $trStr .= "
                    <tr align=center>
                        <td style='vnd.ms-excel.numberformat:@'>{$array['name']}</td>  
                        <td style='vnd.ms-excel.numberformat:@'>{$array['tel']}</td>
                        <td>{$array['IdCard']}</td>
                        <td>{$quyu['province']}{$quyu['city']}{$quyu['area']}{$sharename['addressMx']}</td>
                        <td>{$array['bankNum']}</td>
                        <td>{$array['bankName']}</td>
                        <td>{$num}</td>             
                        <td>{$array['type']}</td>
                    </tr>";
                }
            } else {
                $trStr = "<tr class='center'><td colspan='8'>未找到您选择的客户信息</td></tr>";
            }
            $body = "<table>
            <tr align=center>
                <th>客户姓名</th>
                <th>手机号码</th>
                <th>身份证号</th>
                <th>常用收货地址</th>
                <th>银行卡号</th>
                <th>开户行</th>
                <th>分享数</th>
                <th>会员类别</th>
            </tr>
            {$trStr}
        </table>";
            break;

        default:
            exit();
            break;
    }
//输出结果
    header("Content-Type: application/vnd.ms-excel"); //charset必须跟你将要输出的内容的编码一致,否则乱码
    header("Content-Disposition: attachment; filename={$title}.xls");
    $head = "<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title>{$title}导出</title>
</head>";
    echo $head . $body;
}else{
    $_SESSION['warn'] = "您未选择服务单";
    header("Location:".getenv("HTTP_REFERER"));
}
?>
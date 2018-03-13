<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>专题区</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../library/css/thematic_area.css">
</head>
<?php
    include "../library/mFunction.php";
    $pdo = new PDO('mysql:host='.$GLOBALS['conf']['ServerName'].';dbname='.$GLOBALS['conf']['DatabaseName'], $GLOBALS['conf']['UserName'], $GLOBALS['conf']['password'] );
    $pdo->query('set names utf8');
    $id = $_GET['id'];
    $sql = "select * from goods as g left join special as s on g.recommendArea = s.spid where s.spid = $id";
    $a = $pdo->query($sql);
    $data = $a->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="thematic-area">
        <!-- 页头 -->
        <div class="order-header">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="title">专题区</span>
            <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
        </div>
        <!-- 内容 -->
        <div class="thematic-title">
            <?php echo $data[0]['specialName']?>
        </div>
        <!-- 产品列表 -->
        <div class="pro-items">
            <?php foreach($data as $k=>$v){?>
                <div class="produce">
                    <div class="img">
                        <a href="<?php echo root;?>m/mGoodsMx.php?gid=<?php echo $v['id']?>">
                        <img class='smallImg imgHover' src="<?php echo root,$v['ico']?>" alt='暂无图片'></a>
                    </div>
                    <p><?php echo $v['name']?></p>
                    <p class="price"><?php echo $v['price']?></p>
                </div>
            <?php }?>
        </div>

    </div>
</body>

</html>
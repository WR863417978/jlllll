<?php
/**
 * 邮编正则
 */
$CheckZipCode = "/^0?\d{5,6}$/";
/**
 * 判断是否为合法的身份证号码
 * @param  [string]  $vStr 身份证号码
 * @return boolean
 */
function isCreditNo($vStr){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
    if ($vLength == 18)
    {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    }else{
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18)
    {
        $vSum = 0;
        for ($i = 17 ; $i >= 0 ; $i--)
        {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
        if($vSum % 11 != 1) return false;
    }
    return true;
}
/**
 * 银行卡验证
 * @param str $no
 * @return void
 */
function bankVerify($no)
{
    $arr_no = str_split($no);
    $last_n = $arr_no[count($arr_no)-1];
    krsort($arr_no);
    $i = 1;
    $total = 0;
    foreach ($arr_no as $n){
        if($i%2==0){
            $ix = $n*2;
            if($ix>=10){
                $nx = 1 + ($ix % 10);
                $total += $nx;
            }else{
                $total += $ix;
            }
        }else{
            $total += $n;
        }
        $i++;
    }
    $total -= $last_n;
    $x = 10 - ($total % 10);
    if($x == $last_n){
        return TRUE;
    }else{
        return FALSE;
    }
}
/**
 * 邮编正则
 */
function funcZip($str)//邮编正则表达试
{
    return (preg_match("/^[0-9][0-9]{5}$/",$str))?true:false;
}
/**
 * 查询一条记录
 * @author: R7
 * @param  str $table 表名
 * @param  str $where 条件语句
 * @param  str $field 字段
 * @return mixed        数组|FALSE
 */
function findOne($table,$where,$field=NULL)
{
    if(empty($field))
    {
        $field = '*';
    }
    $sql = "SELECT $field FROM $table WHERE $where";
    $res = mysql_query($sql);
    if($res)
    {
        $result = mysql_fetch_assoc($res);
        return $result;
    }else{
        return FALSE;
    }
}
/**
 * 查询多条语句
 * @param  str $table    表名
 * @param  str $where    条件语句
 * @param  str $hasWhere 是否有WHERE关键字(主要用于不需要WHERE条件与的查询)
 * @param  str $column   返回指定字段的集合  eg: id IN (1,2,3)
 * @param  str $field    字段
 * @return mixed         数组|FALSE
 */
function findAll($table,$where,$field = NULL,$column = NULL,$hasWhere='yes')
{
    if(empty($field))
    {
        $field = '*';
    }
    if($hasWhere == 'no')
    {#主要用于不需要where条件句的查询(查询所有的数据)
        $hasWhere = '';
    }else{
        $hasWhere = 'WHERE';
    }
    $sql = "SELECT $field FROM $table $hasWhere $where";
    $res = mysql_query($sql);
    if($res)
    {
        $result = [];
        $columnStr = '';
        while($row = mysql_fetch_assoc($res))
        {
            $result[] = $row;
            if(!empty($column)){
                $columnStr .= "'".$row[$column]."',";
            }
        }
        if(!empty($column)){
            $columnStr = trim($columnStr,',');
            $result[0]['column'] = '('.$columnStr.')';
        }
        return $result;
    }else{
        return FALSE;
    }
}
/**
 * MySQL 执行语句(有结果集)
 * @Author: R7
 * @param  str $sql SQL语句
 * @return arr $result  二维数组,$arr[0]['sqlRow'] = '结果集条数'
 */
function myQuery($sql)
{
    $res = mysql_query($sql);
    if($res)
    {
        $result = [];
        $num = mysql_num_rows($res);

        while( $row = mysql_fetch_assoc($res) )
        {
            $result[] = $row;
        }
        if($num >1 )
        {
            $result[0]['sqlRow'] = $num;
            return $result;
        }else{
            $result[0]['sqlRow'] = $num;
            return $result;
        }
    }else{
        return FALSE;
    }
}

/*
 * 开始事务
 */
function begin(){
    mysql_query('begin');
}
function rollback(){
    mysql_query('rollback');
}
function commit(){
    mysql_query('commit');
}
/**
 * 空数据占位符
 * @author r7 <email@email.com>
 * @param mixed $data
 * @param str $info
 * @return void
 */
function emptyData($data,$info)
{
    if( empty($data) ){
        return $info;
    }else{
        return $data;
    }
}
/**
 * json赋值及重定向
 * @author r7
 * @param bool $bool
 * @param array $option
 * @param str $url
 * @return array
 */
function redirect($bool,array $option)
{
    $json = [];
    if( $bool ){
        $json['warn'] = $option['success'];
        $option['session'] ? $_SESSION['warn'] = $option['session'] : '' ;
        $option['url'] ? $json['href'] = root.$option['url'] : "";
    }else{
        $json['warn'] = $option['fail'] ;
    }
    return $json;
}
/**
 * 地址生成
 * @author r7
 * @return void
 */
function myRegion($regionId)
{
    $info = findOne('region',"id = $regionId");
    return $info['province'].$info['city'].$info['area'];
}
/**
 * 动态变化选定效果
 */
function myMenuGet($get,$name,$class){
    if( is_array($name) )
    {
        if( in_array($_GET[$get],$name) ) return $class;
    }else{
        if($_GET[$get]==$name) return $class;
    }
}
/**
 * 创建文件
 * @param str $file
 */
function fileExists($file)
{
    if( !file_exists(ServerRoot.$file) ){
        mkdir(ServerRoot.$file);
    }
}

function uploadImgBase64($base64_url,$name) {
    $base64_body = substr(strstr($base64_url, ','), 1);
    $data = base64_decode($base64_body);
    $path = "../";//网站跟目录跳的级数
    $fileName = "$name/". suiji() . ".jpg";
    file_put_contents($path.$fileName, $data);//名字请用相对路径
    $imgsize = getimagesize($path . $fileName);
    return $fileName;
}
function ImagesUpload($base64_url,$name) {
    $base64_body = substr(strstr($base64_url, ','), 1);
    $data = base64_decode($base64_body);
    $path = "../../";//网站跟目录跳的级数
    $fileName = "$name/". suiji() . ".jpg";
    file_put_contents($path.$fileName, $data);//名字请用相对路径
    $imgsize = getimagesize($path . $fileName);
    return $fileName;
}
//=======================移动端函数===========================
/**
 * 移动端首页一级分类展示
 * @author r7 <email@email.com>
 * @return void
 */
function navListShow($oid = NULL)
{
    $navData = findAll('goodsOne',"xian = '显示' ORDER BY list");
    $navList = [];
    foreach ($navData as $key => $val) {
        /* $navList .= "<li><a href='{$root}m/mIndex.php?oid={$val['id']}'>{$val['name']}</a></li>"; */
        if($key <= 5 )
        {
            if( (empty($oid) && $key == 0) || $val['id'] == $oid )
            {
                $class = 'nav-meun-on';
            }else{
                $class = '';
            }
            $navList['0'] .= "<li class='{$class}'><a href='".root."m/mIndex.php?oid={$val['id']}'>{$val['name']}</a></li>";
            //$navList['1'] .= "<dd><a href='".root."m/mIndex.php?oid={$val['id']}'>{$val['name']}</a></dd>";
        }
        $navList['1'] .= "<dd><a href='".root."m/mGoodsList.php?oid={$val['id']}'>{$val['name']}</a></dd>";
        $navList['2'] .= "<li class='{$class}'><a href='".root."m/mGoodsList.php?oid={$val['id']}'>{$val['name']}</a></li>";
    }
    return $navList;
}
/**
 * 首页banner图
 * @return mixed
 */
function indexBanner()
{
    $res = findAll('img',"type = '首页banner图' ORDER BY list");
    $html = '';
    if( $res )
    {
        foreach ($res as $key => $val)
        {
            $html .= "
            <div class='swiper-slide'>
                <a href='{$val['url']}'><img src='".root."{$val['src']}?t=".strtotime($val['updateTime'])."'></a>
            </div>";
        }
    }
    return $html;
}
/**
 * 移动端首页广告专区
 *      未注册用户含有广告   注册用户不含广告
 * @author r7 <email@email.com>
 * @return str
 */
function advShow()
{
    global $kehu;
    if( !empty($kehu['tel']) )
    {
        #注册
        $advHtml = '';
    }else{
        #未注册
        $res = findAll('img',"type = '广告专区（小图）' ORDER BY list");
        if( $res )
        {
            foreach ($res as $key => $val) {
                $centerHtml .="
                <li>
                    <a href='{$val['url']}'>
                        <img src='".root."{$val['src']}?t=".strtotime($val['updateTime'])."'>
                    </a>
                </li>";
            }
        }
        $info = findOne('img',"id = 'Ewn86808395Lm'");
        $advHtml = "
        <li class='team-lists-left'>
            <a href='{$info['url']}'>
                <img src='".root."{$info['src']}?t=".strtotime($val['updateTime'])."'>
            </a>
        </li>
        <div class='team-lists-right'>
            <ul class='mui-dis-flex'>
                {$centerHtml}
            </ul>
        </div>";
    }
    return $advHtml;
}
/**
 * 首页专区图片
 * @return mixed
 */
function topicImg()
{
    $res = findAll('img',"type = '首页专题' ORDER by list");
    $html = '';
    if( $res ){
        foreach ($res as $key => $val) {
            $html .= "<dd><a href='{$val['url']}'><img src='".root."{$val['src']}?t=".strtotime($val['updateTime'])."'/></a></dd>";
        }
    }
    return $html;
}
/**
 * 判断是否登录
 * @return void
 */
function findKehu()
{
    $info = findOne('kehu',"khid = '{$_SESSION['khid']}'");
    if($info){
        return TRUE;
    }else{
        return FALSE;
    }
}
/**
 * 专区推荐商品
 * @author r7 <email@email.com>
 * @param mixed $oid
 * @return void
 */
function areaShow($oneId = NULL)
{
    global $kehu;
    $sql = "select * from special where isShow = '显示'";
    $pdo = newPdo1();
    $a = $pdo->query($sql);
    $select = $a->fetchAll(PDO::FETCH_ASSOC);
    foreach($select as $k=>$val){
        $sql = "select * from goods where recommendArea = '$val[spid]' order by agio desc limit $val[showPage]";
        $b = $pdo->query($sql);
        $res = $b->fetchAll(PDO::FETCH_ASSOC);
        $centerStr = '';
            foreach ($res as $v)
            {
                if( !empty($kehu['type']) ) {
                    $info = findOne('goodsSku',"goodsId = '{$v['id']}' AND defaultData = '默认'");
                    $priceHtml = "<em class='text-price'>零售:￥".floatval($info['price'])."</em><em class='text-sale'>批发:￥".floatval($info['retailPrice'])."</em>";
                }else if( empty($kehu['tel']) ){
                    $priceHtml = "<em class='text-price'>注册后查看价格</em>";
                }else if( !empty($kehu['tel']) ){
                    $info = findOne('goodsSku',"goodsId = '{$v['id']}' AND defaultData = '默认'");
                    $priceHtml = "<em class='text-price'>零售:￥".floatval($info['price'])."</em>";
                }
                $centerStr .= "
                    <li>
                        <a href='".root."m/mGoodsMx.php?gid={$v['id']}'>
                            <img src='".root."{$v['ico']}?t=".strtotime($v['updateTime'])."'>
                            <p class='nameSpc'>{$v['name']}</p>
                            <p class='textSale'>
                                {$priceHtml}
                            </p>
                        </a>
                    </li>";
            }
            $html .= "
            <div class='key_title'><a href='".root."m/specialarea.php?id={$val['spid']}'>{$val['specialName']}</a></div>
            <ul class='product-lists mui-dis-flex'>
                {$centerStr}
            </ul>";
    }
    //$recommendArr = explode( '、' , para('recommendArea' ) );    #专区数据
    //$indexNum = para('IndexAreaNum');
//foreach ($recommendArr as $val)
    //{
        //$sql = "SELECT * FROM goods WHERE recommendArea = '$val' AND isIndex = '是' AND xian = '显示' ORDER BY list LIMIT $indexNum";
//res = myQuery($sql);
       // if( $res['0']['sqlRow'] > 0 )
        //{
            
       // }
    //}
    return $html;
}
/**
 * 用户评价分页
 * @author r7 <email@email.com>
 * @param int $page
 * @param str $goodsId
 * @param int $goodsId
 * @return mixed
 */
function goodsEvalBuild($goodsId,$page,$size)
{
    $dataArr = [];
    $sql = "SELECT t.*,k.wxIco,k.wxNickName FROM talk t,kehu k WHERE targetId = '$goodsId' AND t.khid = k.khid ORDER BY id DESC LIMIT ".($page * $size).",$size";
    $info = myQuery($sql);
    //$json['sql'] = $sql;
    //var_dump($info);
    if($info['0']['sqlRow'] > 0)
    {
        foreach ($info as $key => $val) {
            $html .= "
                <dl>
                    <dt class='mui-dis-flex'>
                        <label class='flex1'>
                            <img src='{$val['wxIco']}'>
                            <span>".substr_cut($val['wxNickName'])."</span>
                            <em class='evaluation-sf'>
                                ".goodsGradeShow($val['grade'])."
                            </em>
                        </label>
                        <span>".date('Y-m-d',strtotime($val['time']))."</span>
                    </dt>
                    <dd>
                        <p>{$val['word']}</p>
                    </dd>
                    <dd>
                        ".goodsEvalImg($val['id'])."
                    </dd>
                </dl>";
        }
    }
    $dataArr['html'] = $html;
    $dataArr['data']['length'] = $info['0']['sqlRow'];
    return $dataArr;
}
/**
 * 需求列表
 * @param integer $page  起始页
 * @param integer $size  偏移量
 * @param str $khid khid
 * @return void
 */
function needMxBuild($page,$size,$khid = NULL)
{
    global $kehu;
    if( empty($khid) ){
        $sql = "SELECT * FROM demand ORDER BY time DESC LIMIT ".($page * $size).",$size";
    }else{
        $sql = "SELECT * FROM demand WHERE khid = '$khid' ORDER BY time DESC LIMIT ".($page * $size).",$size";
    }
    $res = myQuery($sql);
    if( $res['0']['sqlRow'] > 0 )
    {
        foreach ($res as $key => $val)
        {
            $d = $h = $m = 0;#初始化
            $endTime = strtotime($val['time']) + 7 * 24 * 3600;
            $diffTime = $endTime - time();
            if( $endTime <= time() )
            {
                $status = "已合作";
            }else{
                $status = $val['status'];
                $d = floor( $diffTime / ( 24 * 3600 ) );     #天数
                $h = $diffTime / 3600 % 24;                  #小时
                $m = $diffTime / 60 % 60;                    #分钟
            }
            $html .= "
            <a href='".root."m/mNeedMx.php?id={$val['id']}'>
                <dl>
                    <dt>
                        <h3>{$val['theme']}</h3>
                        <label><span>发布者：{$kehu['name']}</span><span>发布时间：{$val['time']}</span></label>
                    </dt>
                    <dd>
                        <p>{$val['text']}</p>
                        <label class='mui-dis-flex'>
                            <span class='flex1'><i>&#xe652;</i>剩余{$d}天{$h}小时{$m}分</span>
                            <span class='post-btn'>{$status}</span>
                        </label>
                    </dd>
                </dl>
            </a>";
        }
    }
    $dataArr['html'] = $html;
    $dataArr['data']['length'] = $res['0']['sqlRow'];
    $dataArr['sql'] = $sql;
    return $dataArr;
}
/**
 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
 * @param string $user_name 姓名
 * @return string 格式化后的姓名
 */
function substr_cut($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr   = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr    = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}
/**
 * 星级评价展示
 * @author r7
 * @param str $grade 评分
 * @return mixed
 * JZM84230568mN 好评
 * mxj84132490nv 差评
 */
function goodsGradeShow($grade)
{
    $badStar = 5 - $grade;
    for ($i = 1; $i <= $grade; $i++) {
        $img .= "<img src='".imgt('JZM84230568mN')."'>";
    }
    for ($j = 1; $j <= $badStar; $j++) {
        $img .= "<img src='".imgt('mxj84132490nv')."'>";
    }
    if($grade == 1){
        $eval = "差评";
    }else if($grade > 1 && $grade <= 4){
        $eval = "中评";
    }else if($grade == 5){
        $eval = "好评";
    }
    $img .= $eval;
    return $img;
}
/**
 * 商品用户评价图片展示
 * @author r7
 * @param str    $talkId
 * @return mixed
 */
function goodsEvalImg($talkId)
{
    $imgStr = '';
    $img = findAll('talkImg',"talkId = '$talkId' ORDER BY time");
    if($img)
    {
        foreach ($img as $key => $val) {
            $imgStr .="<img src='".root."{$val['img']}'>";
        }
    }
    return $imgStr;
}
/**
 * 需求列表页展示
 * @author r7
 * @return str
 */
function mNeedMxShow()
{
    global $kehu;
    $res = findAll('demand',"khid = '{$kehu['khid']}' ORDER BY time DESC");
    if( !$res ) return '';
    foreach ($res as $key => $val) {
        $d = $h = $m = 0;#初始化
        $endTime = strtotime($val['time']) + 7 * 24 * 3600;
        $diffTime = $endTime - time();
        if( $endTime <= time() )
        {
            $status = "已合作";
        }else{
            $status = $val['status'];
            $d = floor( $diffTime / ( 24 * 3600 ) );     #天数
            $h = $diffTime / 3600 % 24;                  #小时
            $m = $diffTime / 60 % 60;                    #分钟
        }
        $html .= "
        <a href='".root."m/mNeedMx.php?id={$val['id']}'>
            <dl>
                <dt>
                    <h3>{$val['theme']}</h3>
                    <label><span>发布者：{$kehu['name']}</span><span>发布时间：{$val['time']}</span></label>
                </dt>
                <dd>
                    <p>{$val['text']}</p>
                    <label class='mui-dis-flex'>
                        <span class='flex1'><i>&#xe652;</i>剩余{$d}天{$h}小时{$m}分</span>
                        <span class='post-btn'>{$status}</span>
                    </label>
                </dd>
            </dl>
        </a>";
    }
    return $html;
}
/**
 * 分享页面展示
 * @author r7
 * @return str
 */
function mShareShow()
{
    global $kehu;
    $sql = "SELECT * FROM kehu WHERE shareId = '{$kehu['khid']}'";
    $res = myQuery($sql);
    if( $res[0]['sqlRow'] > 0 )
    {
        $dataArr = [];
        foreach ($res as $val) {
            switch ($val['type']) {
                case '普通会员':
                    $key = 'com';
                    break;
                case '高级会员':
                    $key = 'high';
                    break;
                default:
                    $key = 'non';
                    break;
            }
            $name = $val['name'] ? $val['name'] : $val['wxNickName'];
            $dataArr[$key]['ul'] .= "
            <ul class='mui-dis-flex'>
                <li>{$name}</li>
                <li>{$val['khid']}</li>
                <li>时间：".date("Y-m-d",strtotime($val['time']))."</li>
            </ul>";
        }
        $html = "
        <dl>
        <a href='".root."m/mUser/mShareMore.php?type=non'><dt class='mui-dis-flex'><label class='flex1'>非会员</label><span class='more'>&#xe62e;</span></dt></a>
            <dd>
                ".emptyData($dataArr['non']['ul'],"<img class='share-note' src='".imgt('aLW88032373Sa')."'>")."
            </dd>
        </dl>
        <dl>
        <a href='".root."m/mUser/mShareMore.php?type=com'><dt class='mui-dis-flex'><label class='flex1'>普通会员</label><span class='more'>&#xe62e;</span></dt></a>
            <dd>
                ".emptyData($dataArr['com']['ul'],"<img class='share-note' src='".imgt('aLW88032373Sa')."'>")."
            </dd>
        </dl>
        <dl>
        <a href='".root."m/mUser/mShareMore.php?type=high'><dt class='mui-dis-flex'><label class='flex1'>高级会员</label><span class='more'>&#xe62e;</span></dt></a>
            <dd>
                ".emptyData($dataArr['high']['ul'],"<img class='share-note' src='".imgt('aLW88032373Sa')."'>")."
            </dd>
        </dl>";
    }else{
        $html = "<img class='share-note' src='".imgt('aLW88032373Sa')."'>";
    }
    return $html;
}
/**
 * 分享明细
 * @author r7
 * @param str   $get_type
 * @return str
 */
function mShareMoreShow($get_type)
{
    global $kehu;
    if( $get_type == 'non' ){
        $type = '非会员';
        $typeStr = '';
    }else if( $get_type == 'com' ){
        $typeStr = $type = '普通会员';
    }else if( $get_type == 'high' ){
        $typeStr = $type = '高级会员';
    }
    $dataArr = findAll('kehu',"shareId = '{$kehu['khid']}' AND type = '{$typeStr}' ORDER BY time DESC");
    if( $dataArr )
    {
        foreach ($dataArr as $key => $val) {
            $name = $val['name'] ? $val['name'] : $val['wxNickName'];
            $str .= "
            <dd>
                <ul class='mui-dis-flex'>
                    <li>{$name}</li>
                    <li>{$val['khid']}</li>
                    <li>时间：".date('Y-m-d',strtotime($val['time']))."</li>
                </ul>
            </dd>";
        }
    }else{
        return $html = "暂无成员";
    }
    return $html = "
        <dt class='mui-dis-flex'><label class='flex1'>{$type}</label><span class='more'>&#xe62e;</span></dt>
            <dd>
            {$str} 
            </dd>
        </dl>";
}
/**
 * 个人信息编辑--用户类型
 * @author r7
 * @param [type] $type
 * @return void
 */
function kehuTypeBuild($type)
{
    if(empty($type)){
        $str = '非会员';
        $toBeMember = "
        <p class='upgrade toBeMember' data-key='nor'>
            <a><img src='".imgt('FOF87885852Uf')."'/></a>
        </p>";
    }else if( $type == '普通会员' ){
        $str = $type;
        $toBeMember = "
        <p class='upgrade toBeMember' data-key='vip'>
            <a><img src='".imgt('FOF87885852Uf')."'/></a>
        </p>";
    }else if( $type == '高级会员' ){
        $str = $type;
        $toBeMember = "";
    }
    $dataArr['type'] = $str;
    $dataArr['toBeMember'] = $toBeMember;
    return $dataArr;
}
/**
 * 图文详情
 * @author r7
 * @param [type] $id
 * @return void
 */
function myArticleMx($id,$target = NULL){
    $targetStr = '';
    if( !empty($target) )
    {
        $targetStr = "target = '{$target}' AND ";
    }
    $article = [];
    $sql = mysql_query("SELECT img,word FROM article WHERE {$targetStr} TargetId = '$id' ORDER BY list");
    while($array = mysql_fetch_array($sql)){
        if(empty($array['img'])){
            $str = neirong($array['word']);
            $article['word']        .= $str;
            $article['orderList']   .= $str;
        }else{
            $str = "<img src='".root.$array['img']."'>";
            $article['img']         .= $str;
            $article['orderList']   .= $str;
        }
    }
    return $article;

}
/**
 * 个人中心我的二维码
 * @author r7
 * @return mixed
 */
function mUserQrcode()
{
    global $kehu;
    $url  = root . "m/mIndex.php?shareId={$kehu['khid']}";
    $src  = root . "pay/wxpay/wxScanPng.php?url=".urlencode($url);
    return $src;
}













#=============================================================================================#
/**
 * 发送验证码函数
 * @param  int $tel     接收的手机号
 * @return string          状态
 */
function sendSMS($tel)
{
    global $CheckTel; //正则表达式
    $accessKeyId     = para("aliAccessKeyId"); //阿里AK
    $accessKeySecret = para("aliAccessKeySecret"); //阿里Secret
    $signName        = para("aliSignName"); //短信签名
    $templateCode    = para("aliTemplateCode"); //短信签名模板
    $session         = $_SESSION['Prove'];
    $OldTime         = $session['time'] + 60;
    //判断并执行
    if (empty($tel)) {
        return "请输入注册手机号码";
    } elseif (preg_match($CheckTel, $tel) == 0) {
        return "手机号码输入错误";
    } elseif ($OldTime > time() and $session['tel'] == $tel) {
        return "发送验证码间隔不能超过一分钟";
    } else {
        $rand              = 1234;//mt_rand(100000, 999999);
        $_SESSION['Prove'] = array("rand" => $rand, "time" => time(), "tel" => $tel);
        $content           = array(
            'code' => $rand,
        );
        //return '发送成功';
        $info = alisms($accessKeyId, $accessKeySecret, $signName, $templateCode, $tel, $content);
        echo '<pre>';
            print_r($info);
        echo '</pre>';
        if ($info->Message == 'OK') {
            return "发送成功";
        } else {
            return "发送失败";
            $code = $info->Code;
        }
    }
}
/**
 * 短信主体函数
 * @param  int    $phone           电话号码
 * @param  string $AccessKeyId     AccessKeyId密钥
 * @param  string $accessKeySecret AccessKeySecret密匙
 * @param  string $SignName        短信签名
 * @param  string $TemplateCode    模版id
 * @param  array  $content   内容
 * @return result
 */
function alisms($accessKeyId, $accessKeySecret, $signName, $templateCode, $phone, $content)
{
    date_default_timezone_set("GMT"); //设置时区
    $apiParams["PhoneNumbers"]     = $phone; //手机号
    $apiParams["SignName"]         = $signName; //签名
    $apiParams["TemplateCode"]     = $templateCode; //短信模版id
    $apiParams["TemplateParam"]    = json_encode($content, true); //模版内容
    $apiParams["AccessKeyId"]      = $accessKeyId; //key
    $apiParams["RegionId"]         = "cn-hangzhou"; //固定参数
    $apiParams["Format"]           = "json"; //返回数据类型,支持xml,json
    $apiParams["SignatureMethod"]  = "HMAC-SHA1"; //固定参数
    $apiParams["SignatureVersion"] = "1.0"; //固定参数
    $apiParams["SignatureNonce"]   = uniqid(); //用于请求的防重放攻击，每次请求唯一
    $apiParams["Timestamp"]        = date('Y-m-d\TH:i:s\Z'); //格式为：yyyy-MM-dd’T’HH:mm:ss’Z’；时区为：GMT
    $apiParams["Action"]           = 'SendSms'; //api命名 固定子
    $apiParams["Version"]          = '2017-05-25'; //api版本 固定值
    $domain                        = 'dysmsapi.aliyuncs.com';
    $apiParams["Signature"]        = computeSignature($apiParams, $accessKeySecret); //最终生成的签名结果值
    $requestUrl                    = "http://" . $domain . "/?";
    foreach ($apiParams as $apiParamKey => $apiParamValue) {
        $requestUrl .= "$apiParamKey=" . urlencode($apiParamValue) . "&";
    }
    return curls(substr($requestUrl, 0, -1));
}

function computeSignature($parameters, $accessKeySecret)
{
    ksort($parameters);
    $canonicalizedQueryString = '';
    foreach ($parameters as $key => $value) {
        $canonicalizedQueryString .= '&' . percentEncode($key) . '=' . percentEncode($value);
    }
    $stringToSign = 'GET&%2F&' . percentencode(substr($canonicalizedQueryString, 1));
    $signature    = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . "&", true));

    return $signature;
}

function percentEncode($str)
{
    $res = urlencode($str);
    $res = preg_replace('/\+/', '%20', $res);
    $res = preg_replace('/\*/', '%2A', $res);
    $res = preg_replace('/%7E/', '~', $res);
    return $res;
}
/**
 * Curl 函数
 * @param  string $url 链接
 * @return json
 */
function curls($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $httpResponse = curl_exec($ch);
    if ($httpResponse) {
        return json_decode($httpResponse);
    } else {
        return json_decode(curl_error($ch));
    }
    curl_close($ch);
}
/**
 * 商品浏览痕迹
 * @author r7
 * @param str $gid 商品id
 */
function insertCookie($gid)
{
    $num = para('browseTracesNum'); #浏览痕迹保存条数
    if( empty($gid) ) return '';
    $res = findOne('goods',"id = '$gid'");
    if( !$res ) return '';
    if( empty($_COOKIE['browseTraces']) )
    {
        $arr = [$gid];
        $browseTraces = serialize($arr);
    }else{
        $browseTraces = $_COOKIE['browseTraces'];
        $browseTraces = unserialize($browseTraces);#arr
        $totalNum = count($browseTraces);
        if( in_array($gid,$browseTraces) )
        {//销毁存在的id
            $key = array_search($gid,$browseTraces);
            unset($browseTraces[$key]);
        }
        if( $totalNum == $num  ){
            unset($browseTraces[$num - 1]);
        }
        $newArr = [$gid];
        $browseTraces = serialize(array_merge($newArr,$browseTraces));//重新将数组进行排序
    }
    setcookie('browseTraces',$browseTraces,time() + 24*3600*10,'/');
}
/**
 * php console.log out put
 * @param [type] $content
 * @return void
 */
function console_log($content)
{
    $content = json_encode($content,JSON_UNESCAPED_UNICODE);
    /* echo "
        <script>
            console.log('$content');
        </script>"; */
}
/**
 * 商品会员类
 * @author r7
 */
class Goods
{
    public static $defaultTypeId = '';      #默认选定的左侧分类
    public static $defaultTwoName;          #默认价格分类选定的名 eg:0-100
    public static $goodsTypeId;             #goodsOneId | goodsTwoId
    /**
     * 商品分类（会员） 左侧分类
     * @author r7 <email@email.com>
     * @param str $typeId 一级 | 二级分类id
     * @return str
     */
    static public function typeLeftBanner($typeId = NULL)
    {
        #分类名称
        $goodsType = para('goodsClassShow');
        $html =  '';
        if( $goodsType == '一级分类' ){
            $data = findAll('goodsOne',"xian = '显示' ORDER BY list");
            self::$goodsTypeId = 'goodsOneId'; #分类表id
        }else if( $goodsType == '二级分类' ){
            $data = findAll('goodsTwo',"xian = '显示' ORDER BY list");
            self::$goodsTypeId = 'goodsTwoId'; #分类表id
        }
        foreach ($data as $key => $val)
        {
            $class = '';
            if( (empty($typeId) && $key == 0) || $typeId == $val['id'] ) $class = 'current';
            //( $key == 0 ) ? self::$defaultTypeId = $val['id'] : self::$defaultTypeId = $typeId;
            ( $key == 0 ) ? self::$defaultTypeId = $val['id'] : '';
            $html .= "<li class='{$class}'><a href='mGoodsClass.php?tid={$val['id']}'>{$val['name']}</a></li>";
        }
        return $html;
    }
    /**
     * 商品分类(会员) 列表
     * @author r7 <email@email.com>
     * @param str $typeId 二级分类id
     * @return str
     */
    static public function typeGoodsList($typeId = NULL)
    {
        global $kehu;
        $html = '';
        empty( $typeId ) ? $tid = self::$defaultTypeId : $tid = $typeId;
        $goodsTypeId = self::$goodsTypeId;
        $dataArr = findAll('goods',"$goodsTypeId = '$tid' AND xian = '显示'");
        if($dataArr)
        {
            foreach ($dataArr as $val)
            {
                if( $kehu['type'] == '普通会员' || $kehu['type'] == '高级会员' ){
                    $priceArr = findOne('goodsSku',"goodsId  = '{$val['id']}' AND type = '默认'");
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>零售价：￥".floatval($priceArr['price'])."</em> 
                    </p>
                    <p class='textSale'>
                        <em class='text-price'>批发价：￥".floatval($priceArr['retailPrice'])."</em>
                        <em class='text-price'>利润:￥".floatval($priceArr['profit'])."</em>
                    </p>";
                }
                else  if( empty( $kehu['tel'] ) )
                {
                    $sql = "SELECT MIN(thePatch) minThePatch FROM goodsSku WHERE goodsId = '{$val['id']}' AND type = '分类价格'";
                    $minPatch = myQuery($sql);
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>起批量:{$minPatch['0']['minThePatch']}</em>
                        <em class='text-price'>销量:{$val['salesVolume']}</em>
                    </p>";
                }else{
                    $priceArr = findOne('goodsSku',"goodsId  = '{$val['id']}' AND type = '默认'");
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>批发价：￥".floatval($priceArr['retailPrice'])."</em>
                        <em class='text-price'>利润:￥".floatval($priceArr['profit'])."</em>
                    </p>";
                }
                $html .= "
                <li>
                    <a href='".root."m/mGoodsMx.php?gid={$val['id']}' class='mui-dis-flex'>
                        <img src='".root."{$val['ico']}'>
                        <label>
                            <p class='nameSpc'>{$val['name']}</p>
                            {$centerHtml}
                        </label>
                    </a>
                </li>";
            }
            return $html;
        }else{
            return '商品正在上架中';
        }
    }
    /**
     * 价格分类左侧banner
     * @author r7
     * @return str | NULL
     */
    public static function monTypeBanner($price = NULL)
    {
        $priceArr = explode( '、' , para('priceName') );
        $num = 0;
        foreach ($priceArr as $val)
        {
            ++$num;
            ( (empty($price) && $num == 1) || $price == $val ) ? $class = 'current' : $class = '';
            $html .= "<li class='{$class}'><a href='mGoodsClass.php?type=monList&pr={$val}'>{$val}</a></li>";
            self::$defaultTwoName = $priceArr['0'];
        }
        return $html;
    }
    /**
     * 价格分类商品列表
     * @author r7 <email@email.com>
     * @param [str] $twoName 价格分类 eg:100-200
     * @return str
     */
    public static function monGoodsList( $twoName = NULL )
    {
        if( empty($twoName) ) $twoName  = self::$defaultTwoName;
        $arr = explode('-',$twoName);
        $sPrice = $arr['0'];
        $ePrice = $arr['1'];
        $sql = "SELECT * FROM goods WHERE xian = '显示' AND price BETWEEN $sPrice AND $ePrice";
        $res = myQuery($sql);
        if( $res['0']['sqlRow'] > 0 )
        {
            foreach ($res as $key => $val)
            {
                if( $kehu['type'] == '普通会员' || $kehu['type'] == '高级会员' ){
                    $priceArr = findOne('goodsSku',"goodsId  = '{$val['id']}' AND type = '默认'");
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>零售价：￥".floatval($priceArr['price'])."</em> 
                    </p>
                    <p class='textSale'>
                        <em class='text-price'>批发价：￥".floatval($priceArr['retailPrice'])."</em>
                        <em class='text-price'>利润:￥".floatval($priceArr['profit'])."</em>
                    </p>";
                }
                else if( empty( $kehu['tel'] ) )
                {
                    $sql = "SELECT MIN(thePatch) minThePatch FROM goodsSku WHERE goodsId = '{$val['id']}' AND type = '分类价格'";
                    $minPatch = myQuery($sql);
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>起批量：{$minPatch['0']['minThePatch']}</em>
                        <em class='text-price'>销量:{$val['salesVolume']}</em>
                    </p>";
                }else{
                    $priceArr = findOne('goodsSku',"goodsId  = '{$val['id']}' AND type = '默认'");
                    $centerHtml = "
                    <p class='textSale'>
                        <em class='text-price'>批发价：￥".floatval($priceArr['retailPrice'])."</em>
                        <em class='text-price'>利润:￥".floatval($priceArr['profit'])."</em>
                    </p>";
                }
                $html .= "
                <li>
                    <a href='".root."m/mGoodsMx.php?gid={$val['id']}' class='mui-dis-flex'>
                        <img src='".root."{$val['ico']}'>
                        <label>
                            <p class='nameSpc'>{$val['name']}</p>
                            {$centerHtml}
                        </label>
                    </a>
                </li>";
            }
        }else{
            $html = "商品正在上架中";
        }
        return $html;
    }
    /**不同类型价格展示 */
    public static function priceShow($val)
    {

    }





}

/**
 * 商品详情类
 * @author r7
 */
class GoodsMx
{
    /**
     * 视频及橱窗显示
     * @author r7
     * @param str   $goodsId 商品id
     * @return str
     */
    public static $goodsData;
    public static $goodsSkuDataArr;
    public static $goodsSkuTypeOne; #一级规格名称
    public static $goodsSkuTypeTwo; #二级规格名称
    public static $fistSkuProfit;   #第一个规格利润信息
    public static $defaultGoodsSku; #默认的规格
    public static $defaultSkuImg;   #初始默认的规格图片
    public static $goodsDefaultData;#商品默认数据

    
    static public function videoAndImgShow($goodsId)
    {
        $goodsData = findOne('goods',"id = '$goodsId'");
        self::$goodsData = $goodsData;
        $htmlStr = "";#初始化
        if(!empty($goodsData['videoUrl']))
        {
            $htmlStr = "<video id='voc' poster= \" ".root."{$goodsData['poster']}\" src=\"{$goodsData['videoUrl']}\" controls='' style='width:100%; height:288px;'></video>";
        }else{
            $goodsWinData = findAll('goodsWin',"goodsId = '{$goodsId}' ORDER BY time");
            foreach ($goodsWinData as $key => $val) {
                $htmlStr .= "
                    <div class='swiper-slide'>
                        <a href=''><img src='".root."{$val['src']}'></a>
                    </div>";
            }
        }
        return $htmlStr;
    }

    /**
     * 商品详情规格弹窗
     * @author a7 <email@email.com>
     * @param str $goodsId
     * @return void
     */
    static public function goodsSkuBuild($goodsId)
    {
        $goodsSkuData = findAll('goodsSku',"goodsId = '$goodsId' AND type = '分类价格' ORDER BY endPatch");
        //$defaultGoodsSku = findOne('goodsSku',"goodsId = '$goodsId' AND type = '默认'");
        $goodsDefaultData = findOne('goodsSku',"goodsId = '$goodsId' AND defaultData = '默认'");
        self::$defaultGoodsSku  = $defaultGoodsSku;
        self::$goodsSkuDataArr  = $goodsSkuData;
        self::$goodsDefaultData = $goodsDefaultData;
        if( $goodsSkuData ){
            $type = [];//含有相同一级规格 规格数组
            foreach ($goodsSkuData as $val)
            {
                $type[$val['name']][] = $val;
                //规格图片赋值
                if( !empty($val['img']) ){
                    $skuImg = $val['img'];
                    if( empty( $type[$val['name']]['0']['skuImg'] ) ){
                        $type[$val['name']]['0']['skuImg'] = $skuImg = $val['img'];
                        if( empty(self::$defaultSkuImg) ){
                            self::$defaultSkuImg = $val['img'];
                        }
                    }
                }
            }
        }
        if( is_array($goodsSkuData) ){
            $num = 0;
            foreach ($type as $key => $val)
            {
                ++$num;
                $hideClass = '';
                #规格
                if( $num == 1 && $key == 0){
                    if( empty($val['0']['skuImg']) ){
                        $sku_img = root.self::$goodsData['ico'];
                    }else{
                        $sku_img = root.$val['0']['skuImg'];
                    }
                    $skuStr .= "<li class='goods-on goodsSku' data-skuid='{$val['id']}' data-price='{$val['0']['price']}' data-retailprice='{$val['0']['retailPrice']}' data-profit='{$val['0']['profit']}' data-skuimg='{$sku_img}'>{$key}</li>";
                    #第一个规格利润信息
                    $fistSkuProfit['price']         = $val['0']['price'] ;
                    $fistSkuProfit['retailPrice']   = $val['0']['retailPrice'] ;
                    $fistSkuProfit['profit']        = $val['0']['profit'] ;
                    self::$fistSkuProfit = $fistSkuProfit;
                }else if( $key == 0 ){
                    if( empty($val['0']['skuImg']) ){
                        $sku_img = root.self::$goodsData['ico'];
                    }else{
                        $sku_img = root.$val['0']['skuImg'];
                    }
                    $skuStr .="<li class='goodsSku' data-skuid='{$val['id']}' data-price='{$val['0']['price']}' data-retailprice='{$val['0']['retailPrice']}' data-profit='{$val['0']['profit']}' data-skuimg='{$sku_img}'>{$key}</li>";
                    $hideClass = 'my-hide';
                }
                #等级阶梯
                foreach ($val as $k => $v)
                {
                    if( $v['thePatch'] == 1 && $v['endPatch'] == 1 ){
                        $numInput = "
                            <button type='button' class='amount-btn amount-push'>-</button>
                            <input type='text' class='am-num-text amount-value' value='{$v['thePatch']}' disabled/>
                            <button type='button' class='amount-btn amount-reduce'>+</button>";
                    }else{
                        $numInput = "
                            <button type='button' class='minus amount-btn amount-push' data-min='{$v['thePatch']}'>-</button>
                            <input type='text' class='am-num-text amount-value' value='{$v['thePatch']}' data-min='{$v['thePatch']}' data-min='{$v['thePatch']}' data-max='{$v['endPatch']}'/>
                            <button type='button' class='plus amount-btn amount-reduce' data-max='{$v['endPatch']}'>+</button>";
                    }
                    $goodsSkuTypeTwo .= "
                    <li name='typeTwo' class='skuTypeTwo mui-dis-flex {$hideClass}' data-skid='{$v['id']}' data-typeone='{$key}' data-price='{$v['price']}' data-retailprice='{$v['retailPrice']}' data-profit='{$v['profit']}' data-min='{$v['thePatch']}' data-max='{$v['endPatch']}'>
                        <label class='flex1'>
                            <em data-typeid='{$v['id']}' data-type='{$v['type']}' class='type-two'>{$v['twoName']}<br/>￥{$v['retailPrice']}</em>
                            <span class='fr'>库存：{$v['number']}</span>
                        </label>
                        <p class='mui-dis-flex'>
                            {$numInput}
                            <!--<button type='button' class='minus amount-btn amount-push'>-</button>
                            <input type='text' class='am-num-text amount-value' value='{$v['thePatch']}' />
                            <button type='button' class='plus amount-btn amount-reduce'>+</button>-->
                        </p>
                    </li>";
                }
            }
            self::$goodsSkuTypeOne = $skuStr;
            self::$goodsSkuTypeTwo = $goodsSkuTypeTwo;
        }
    }
    /**
     * 商品是否支持定制 && 商品是否有素材
     */
    static public function goodsCustomMade()
    {
        $data = self::$goodsData;
        $html = '';
        $res = findOne('goodsSku',"goodsId = '{$data['id']}' AND type = '定制'");
        if( $res ){
            $html = "
            <dd class='customization'>
                <label class='mui-dis-flex'><span class='flex1'>该商品支持定制</span><em class='customMade' data-gid='{$data['id']}'>我要定制</em></label>
            </dd>";
        }
        $material = findOne('article',"target = '宣传素材' AND targetId = '{$data['id']}'");
        if( $data['isMaterial'] == '是' || $material ){
            $html .= "
            <dd class='customization'>
                <label class='mui-dis-flex'><span class='flex1'>该商品已上传宣传素材</span><em class='publicity' data-gid='{$data['id']}'>我要宣传</em></label>
            </dd>";
        }
        return $html;
    }
    /**
     * 展示商品优惠
     * @author r7
     * @return void
     */
    public static function goodsCoupon($goodsId)
    {
        global $time;
        global $kehu;
        $sql = "SELECT * FROM coupon WHERE goodsId = '$goodsId' AND endTime >= '$time'";
        $res= myQuery($sql);
        if( $res['0']['sqlRow'] > 0 ){
            $arr = [];      #优惠劵张数
            $num = 0;
            foreach ($res as $val)
            {
                ++$num;
                $kehuCoupon = findOne('kehuCoupon',"khid = '{$kehu['khid']}' AND couponId = '{$val['id']}'");
                $kehuCoupon ? $arr[$num] = 1 : $arr[$num] = 0;
                $couponHtml .= "
                    <div class='mui-shopcoupon-item'>
                        <div class='mui-shopcoupon-main get-coupon' data-couid='{$val['id']}' data-key='{$num}'>
                            <div class='mui-shopcoupon-top'>
                                <div class='mui-shopcoupon-tl'><span class='unit'>￥</span><span class='number'>{$val['moeny']}</span></div>
                                <div class='mui-shopcoupon-tr'>
                                    <p>满{$val['amountMoeny']}元使用</p>
                                </div>
                            </div>
                            <div class='mui-shopcoupon-bottom'>
                                有效期 ".date('Y-m-d',strtotime($val['starTime']))."-".date('Y-m-d',strtotime($val['endTime']))."
                            </div>
                        </div>
                        <div class='mui-shopcoupon-handler'>
                            <span class='gap'></span>
                        </div>
                    </div>";
                $html .= "<label>满{$val['amountMoeny']}减{$val['moeny']}</label>";
            }
            $data['couponHtml'] = $couponHtml;
            $data['showHtml'] = "
                <label class='mui-dis-flex'><span class='flex1'>优惠信息</span><span class='more'>&#xe62e;</span></label>
                <!--<span class='more'>&#xe62e;</span>-->
                <div>
                    <span>促销</span>
                    {$html}
                </div>";
            $data['coupon'] = $arr; #优惠劵信息
            return $data;
        }else{
            return [
                'couponHtml' => '',
                'coupon' => ''
            ];
        }
    }
}

/**
 * 订货单
 * @author r7
 */
class mBuyCar
{
    public static $goodsData = [];//商品数量、积分、总金额
    /**
     * 订货单列表
     * @author r7
     * @return void
     */
    static public function index()
    {

        $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.khid = '{$_SESSION['khid']}' AND b.workFlow = '未选定' AND b.goodsId = g.id ORDER BY b.time DESC";
        $dataArr = myQuery($sql);
//        print_r($dataArr);die;
        $html = '';
        $num = $totalMon = 0;
        if( $dataArr[0]['sqlRow'] > 0 )
        {
            foreach ($dataArr as $key => $val)
            {
                $html .= "
                <li class='one-goods'>
                    <div class='goods-msg'><br />
                        <label class='option-btn'>
                            <span>
                                <input type='checkbox' class='goods-check GoodsCheck' data-bid='{$val['id']}'>
                            </span>
                        </label>
                        <img src='".root."{$val['ico']}'/>
                        <div class='goods-num'>
                            <h2>{$val['name']}</h2>
                            <p class='price'>￥<span class='shop-total-amount GoodsPrice'>{$val['buyPrice']}</span></p>
                            <p class='mui-dis-flex'>
                                <label><span></span></label>
                                <!--<label><span>利：20.00</span></label>-->
                                <button type='button' class='minus amount-btn amount-push' data-bid='{$val['id']}'>-</button>
                                <input type='text' class='am-num-text amount-value' value='{$val['buyNumber']}' />
                                <button type='button' class='plus amount-btn amount-#a41203uce' data-bid='{$val['id']}'>+</button>
                            </p>
                        </div>
                    </div>
                    <div class='options deleteBtn' data-bid='{$val['id']}'><a class='delete'>&#xe607;</a></div>
                </li>";
                $num += $val['buyNumber'];
                $totalMon += $val['buyPrice'] * $val['buyNumber'];
            }
            self::$goodsData['num'] = $num;
            self::$goodsData['totalMon'] = $totalMon;
        }
        return $html;
    }

    /**
     * 默认地址
     * @author r7
     * @return void
     */
    static public function defaultRegion($region = FALSE)
    {
        global $kehu;
        if( $region === TRUE ){
            $region = findOne('address',"id = '{$kehu['address']}'");
            if( !$region ){
                return "
                <dt><a class='mui-dis-flex' href='".root."m/mUser/mPurchaseMx.php'><label class='flex1'><i class='return-people'>&#xe64e;</i><span></span><em>【添加购货人】</em> </label><span class='return-ico'>&#xe62e;</span></a></dt>
                ";
            }else{
                return "
                <dt><a class='mui-dis-flex' href='".root."m/mUser/mPurchaseMx.php'><label class='flex1'><i class='return-people'>&#xe64e;</i><span>{$region['contactName']}</span><em>【更改购货人】</em> </label><span class='return-ico'>&#xe62e;</span></a></dt>
                ";
            }
        }
    }
    public static function shoppingName()
    {
        if( empty($_SESSION['contacts']['kehuName']) ){
            return '      ';
        }else{
            return $_SESSION['contacts']['kehuName'];
        }
    }
    /**
     * mEditOrder 订单列表
     *      couponNum : 优惠劵张数
     *      html : 订单列表
     * $ehu['khid'],$type,$bid
     */
    static public function orderList($khid,$type,$bid = NULL,$pid = NULL)
    {
        global $kehu;
        if( $type == 'one' )
        {
            $sql = "SELECT b.*,g.ico,g.taxPoint FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND b.goodsId = g.id AND b.id = '$bid'";
        }else if( $type == 'all' ){
            $sql = "SELECT b.*,g.ico,g.taxPoint FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND b.workFlow = '已选定' AND b.goodsId = g.id ORDER BY time DESC";
        }

        if( $type == 'goPay' ){
            $res = findOne('pay',"id = '$pid'");
            if( !$res ) return '' ;
            $bidJson = "('".json_decode($res['orderIdGroup'],true)."')";
            $sql = "SELECT b.*,g.ico,g.buyNumber FROM buyCar b,goods g WHERE b.id IN $bidJson AND b.goodsId = g.id";
            $info = myQuery($sql);
            print_r($info);die;
            if( $info['0']['sqlRow'] > 0 )
            {
                echo 'fdsfdsfdsfdsfdsfdsfds';
                $html .="
                <div class='order-goods-mx'>
                    <img src='".root."{$val['ico']}'/>
                    <p>{$val['goodsName']}<br/><span>订量：{$val['buyNumber']}</span></p>
                </div>";
            }
            $returnData['html'] = $html;
            $returnData['goPayTotalPrice'] = $res['money'];
        }else{
            //console_log($sql);
            $data = myQuery($sql);
//            print_r($data);die;
            $returnData = $orderIdGroupJson = [];#返回数据、buyCarID json 初始化
            $taxFree = $num = $shippingFree = 0;
            if( $data[0]['sqlRow'] > 0 )
            {
                foreach ($data as $key => $val)
                {
                    #根据商品查询客户是否有该商品的优惠劵
                    $sql = "SELECT * FROM kehuCoupon k,coupon c WHERE c.goodsId = '{$val['goodsId']}' AND k.khid = '{$kehu['khid']}' AND k.couponId = c.id";
                    $shippingInfo = findOne('goodsSku',"id = '{$val['goodsSkuId']}'");
                    #税费
                    if( !empty( $_SESSION['buyCar']['companyName'] ) ){
                        $taxFree += round( ($val['buyPrice'] * $val['buyNumber'] * $val['taxPoint'] /100) ,2);
                    }
//                    $shippingFree += $shippingInfo['shippingFree'] * $val['buyNumber'];
                    $couponData = myQuery($sql);
                    if( $couponData['0']['sqlRow'] > 0 && $couponData['0']['endTime'] >= $time && $couponData['0']['status'] == '未使用'){
                        ++$num;
                    }
                    $html .="
                    <div class='order-goods-mx'>
                        <img src='".root."{$val['ico']}'/>
                        <p>{$val['goodsName']}<br/><span>订量：{$val['buyNumber']}</span></p>
                    </div>";
                    $orderIdGroupJson[] = $val['id'];
                }
            }else{
                $html = '一件商品都没有';
            }
            $returnData['couponNum']        = $num;             #优惠劵数量
            $returnData['html']             = $html;            #订单列表
            $returnData['taxFree']          = $taxFree;         #税点
            $returnData['shippingFree']     = $shippingFree;    #运费
            $returnData['buyCarId'] = $orderIdGroupJson;
            //console_log($returnData);
        }

        return $returnData;
    }
    /**
     * 购物车 获取总价
     */
    public static function getBuyCarFree($khid,$type,$bid = NULL)
    {
        if( $type == 'one' ){
            $res = findAll('buyCar',"id = '$bid'");
        }else if( $type == 'all' ){
            $res = findAll('buyCar',"khid = '$khid' AND workFlow = '已选定'");
        }
//        print_r($res);die;
        $dataArr = [];
        $price = 0;
        if( $res ){
            foreach ($res as $key => $val)
            {
                $price += $val['buyPrice'] * $val['buyNumber'];
                $data[] = $val['id'];
            }
        }else{
            $price = 0;
        }
        $dataArr['totalPrice'] = $price;
        $dataArr['buyCarId'] = $data;
        return $dataArr;
    }
}
/**
 * 订单管理
 * @author r7
 */
class order
{
    /**
     * 订单 所有订单
     */
    static public function allOrder()
    {
        global $kehu;
        //$res = findAll('buyCar b,goods g',"b.khid = '{$kehu['khid']}' AND b.goodsId = g.id ORDER BY time DESC",'b.*,g.ico');
        $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND (b.workFlow = '已收货' OR b.workFlow = '已评价') AND b.goodsId = g.id ORDER BY time DESC";
        $res = myQuery($sql);
        if($res['0']['sqlRow'] > 0)
        {
            foreach ($res as $key => $val) {
                if( $val['workFlow'] == '已收货' ){
                    $btn = "<a class='user-btn'>去评价</a><a class='user-btn'>再次订购</a>";
                }else if( $val['workFlow'] == '已评价' ){
                    $btn = "<a class='user-btn'>已评价</a><a class='user-btn'>再次订购</a>";
                }
                $html .= "
                <div class='order-lists'>
                    <!--<h2 class='mui-dis-flex'><span class='flex1'>订单号：12345678798</span></h2>-->
                    <dl>
                        <dd><img src='".root."{$val['ico']}'/></dd>
                        <dd class='info'>
                            <p>{$val['goodsName']}</p>
                            <p><span>单价：￥{$val['buyPrice']}</span><span>数量：{$val['buyNumber']}</span><span>{$val['workFlow']}</span></p>
                            <p>
                                {$btn}
                            </p>
                        </dd>
                        <dd><span class='more'>&#xe62e;</span></dd>
                    </dl>
                </div>";
            }
            return $html;
        }else{
            return '暂无订单';
        }

    }
    /**
     * 订单 未付款
     */
    static public function dontPay()
    {
        global $kehu;
        // $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND b.workFlow = '未付款' AND b.goodsId = g.id ORDER BY time DESC";
        $sql = "SELECT * FROM pay WHERE targetId = '{$kehu['khid']}' AND workFlow = '未支付'";
        //console_log($sql);
        $res = myQuery($sql);

        /* if($res['0']['sqlRow'] > 0)
        {
            foreach ($res as $key => $val) {
                $html .= "
                <div class='order-lists'>
                    <!--<h2 class='mui-dis-flex'><span class='flex1'>订单号：12345678798</span></h2>-->
                    <dl>
                        <dd><img src='".root."{$val['ico']}'/></dd>
                        <dd class='info'>
                            <p>{$val['goodsName']}</p>
                            <p><span>单价：￥{$val['buyPrice']}</span><span>数量：{$val['buyNumber']}</span><span>{$val['workFlow']}</span></p>
                            <p>
                                <a class='user-btn'>去支付</a>
                            </p>
                        </dd>
                        <dd><span class='more'>&#xe62e;</span></dd>
                    </dl>
                </div>";
            }
            return $html;
        }else{
            return '暂无订单';
        } */

        if($res['0']['sqlRow'] > 0)
        {
            $orderIdGroup = json_decode($res['0']['orderIdGroup']);
            foreach ($orderIdGroup as $key => $value)
            {
                $val = findOne('buyCar b,goods g',"b.id = '{$value}' AND b.goodsId = g.id ORDER BY time DESC",'b.*,g.ico');
                $html .= "
                    <div class='order-lists'>
                        <!--<h2 class='mui-dis-flex'><span class='flex1'>订单号：12345678798</span></h2>-->
                        <dl>
                            <dd><img src='".root."{$val['ico']}'/></dd>
                            <dd class='info'>
                                <p>{$val['goodsName']}</p>
                                <p><span>单价：￥{$val['buyPrice']}</span><span>数量：{$val['buyNumber']}</span><span>{$val['workFlow']}</span></p>
                                <p>
                                    <a class='user-btn goPay' data-bid='{$value}'>去支付</a>
                                </p>
                            </dd>
                            <dd><span class='more'>&#xe62e;</span></dd>
                        </dl>
                    </div>";
            }
            return $html;
        }else{
            return '暂无订单';
        }
    }
    /**
     * 订单 已发货
     */
    static public function hasSend()
    {
        global $kehu;
        //$res = findAll('buyCar b,goods g',"b.khid = '{$kehu['khid']}' AND b.workFlow = '已发货' AND b.goodsId = g.id ORDER BY time DESC",'b.*,g.ico');
        $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND b.workFlow = '已发货' AND b.goodsId = g.id ORDER BY time DESC";
        $res = myQuery($sql);
        if($res['0']['sqlRow'] > 0)
        {
            foreach ($res as $key => $val) {
                $html .= "
                <div class='order-lists'>
                    <!--<h2 class='mui-dis-flex'><span class='flex1'>订单号：12345678798</span></h2>-->
                    <dl>
                        <dd><img src='".root."{$val['ico']}'/></dd>
                        <dd class='info'>
                            <p>{$val['goodsName']}</p>
                            <p><span>单价：￥{$val['buyPrice']}</span><span>数量：{$val['buyNumber']}</span><span>{$val['workFlow']}</span></p>
                            <p>
                                <a class='user-btn'>查看物流</a>
                            </p>
                        </dd>
                        <dd><span class='more'>&#xe62e;</span></dd>
                    </dl>
                </div>";
            }
            return $html;
        }else{
            return '暂无订单';
        }
    }
    /**
     * 订单 待评价
     */
    static public function waitTalk()
    {
        global $kehu;
        $sql = "SELECT b.*,g.ico FROM buyCar b,goods g WHERE b.khid = '{$kehu['khid']}' AND b.workFlow = '已收货' AND b.goodsId = g.id ORDER BY time DESC";
        $res = myQuery($sql);
        if($res['0']['sqlRow'] > 0)
        {
            foreach ($res as $key => $val) {
                $html .= "
                <div class='order-lists'>
                    <!--<h2 class='mui-dis-flex'><span class='flex1'>订单号：12345678798</span></h2>-->
                    <dl>
                        <dd><img src='".root."{$val['ico']}'/></dd>
                        <dd class='info'>
                            <p>{$val['goodsName']}</p>
                            <p><span>单价：￥{$val['buyPrice']}</span><span>数量：{$val['buyNumber']}</span><span>{$val['workFlow']}</span></p>
                            <p>
                                <a class='user-btn wait-talk' data-gid='{$val['goodsId']}' data-bid='{$val['id']}'>去评价</a>
                                <a class='user-btn sub-rev buyAgain' data-gid='{$val['goodsId']}'>再次订购</a>                                
                            </p>
                        </dd>
                        <dd><span class='more'>&#xe62e;</span></dd>
                    </dl>
                </div>";
            }
            return $html;
        }else{
            return '暂无订单';
        }
    }
}
/**
 * 订单类
 */
class UserOrder
{
    public static function allOrder($khid)
    {
        $page = $_GET['page'];
        $size = $_GET['size'];
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $page =1;
        }
        if(!isset($_GET['size']) || empty($_GET['size'])){
            $size =20;
        }
        $offset = ($page - 1) * $size;

        $sql = "SELECT * FROM `order` WHERE target_khid = '{$khid}' AND o_type = '1' ORDER BY ctime DESC LIMIT {$offset},$size";
        $order = myQuery($sql);
        if( $order['0']['sqlRow'] > 0 )
        {
            $html = '';
            foreach ($order as $val)
            {
                $allGoods = findAll('order_goods',"order_sn='{$val["order_sn"]}'");
                if( !empty($allGoods) )
                {
                    $orderNum = 0;
                    $imgHtml ='';
                    foreach ($allGoods as $key => $v)
                    {
                        $imgHtml .= "<img src='".root."{$v['goods_icon']}'/>";
                        $orderNum += $v['buyNumber'];
                    }
                }
                $buttonType = '';
                switch($val['workFlow']){
                    case '0'://代付款按钮
                        $buttonType = "<input class='goPay user-btn' type='button' value='去支付' data-pid='{$val['order_sn']}' name='goPay'/>";
                        break;
                    case '1':  //待发货
                        $buttonType = "<input name='applyback' class='user-btn' type='button' value='申请退款' data-pid='{$val['order_sn']}'/>";
                        break;
                    case '2': //待收货
                        $buttonType ="<input name='receiveGoods' class='user-btn' type='button' value='确认收货' data-pid='{$val['order_sn']}'/>
                                    <input name='showLogistics' class='user-btn' type='button' value='查看物流' data-pid='{$val['order_sn']}'/>
                                    <input name='applyback' class='user-btn' type='button' value='申请退款' data-pid='{$val['order_sn']}'/>";
                        break;
                    case '4':
                        $buttonType = "<input name='toTalk' class='user-btn' type='button' value='去评价' data-pid='{$val['order_sn']}'/>";
                        break;
                    case '6':
                        $buttonType = "<input name='onlyToShow' class='user-btn' type='button' value='请等待退款' data-pid='{$val['order_sn']}'/>";
                        break;
                    case '8':
                        $buttonType = "<input name='waitAgree' class='user-btn' type='button' value='请等待同意退货' data-pid='{$val['order_sn']}'/>";
                        break;
                    case '9':  //同意退货后才能去填写订单号
                        $buttonType = "<input name='toWriteExpress' class='user-btn' type='button' value='去填写退货运单' data-pid='{$val['order_sn']}'/>";
                        break;
                }
                $html .= "
                    <div class='order-lists'>
                    <h2 class='mui-dis-flex'><span class='flex1'>订单号：{$val['order_sn']}</span></h2>
                        <dl>
                            <dt>
                                {$imgHtml}
                            </dt>
                            <dd><em>共{$orderNum}件 ， 待付款：{$val['money']}元</em></dd>
                            <dd>
                                <em>
                                    {$buttonType}
                                </em>
                            </dd>
                        </dl>
                    </div>";
                $imgHtml = '';
            }
        }
        $dataArr['html'] = $html;
        return $dataArr;
    }
    /**未付款 完成*/
    public static function dontPay($khid)
    {
        $page = $_GET['page'];
        $size = $_GET['size'];
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $page =1;
        }
        if(!isset($_GET['size']) || empty($_GET['size'])){
            $size =20;
        }
        $offset = ($page - 1) * $size;

        $sql = "SELECT * FROM `order` WHERE target_khid = '{$khid}' AND o_type = '1' AND workFlow = '0' ORDER BY ctime DESC LIMIT {$offset},$size";
        $order = myQuery($sql);
        if( $order['0']['sqlRow'] > 0 )
        {
            foreach ($order as $val)
            {
                $allGoods = findAll('order_goods',"order_sn='{$val["order_sn"]}'");
                if( !empty($allGoods) )
                {
                    $orderNum = 0;
                    $imgHtml ='';
                    foreach ($allGoods as $key => $v)
                    {
                        $imgHtml .= "<img src='".root."{$v['goods_icon']}'/>";
                        $orderNum += $v['buyNumber'];
                    }
                }
                $html .= "
                    <div class='order-lists'>
                    <h2 class='mui-dis-flex'><span class='flex1'>订单号：{$val['order_sn']}</span></h2>
                        <dl>
                            <dt>
                                {$imgHtml}
                            </dt>
                            <dd><em>共{$orderNum}件 ， 待付款：{$val['money']}元</em></dd>
                            <dd>
                                <em>
                                    <input class='goPay user-btn' type='button' value='去支付' data-pid='{$val['order_sn']}' name='goPay'/>
                                </em>
                            </dd>
                        </dl>
                    </div>";
                $imgHtml = '';
            }
        }
        $dataArr['html'] = $html;
        return $dataArr;
    }
    //待发货 完成
    public static function tosend($khid)
    {
        $page = $_GET['page'];
        $size = $_GET['size'];
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $page =1;
        }
        if(!isset($_GET['size']) || empty($_GET['size'])){
            $size =20;
        }
        $offset = ($page - 1) * $size;

        $sql = "SELECT * FROM `order` WHERE target_khid = '{$khid}' AND o_type = '1' AND workFlow ='1' ORDER BY ctime DESC LIMIT {$offset},$size";
        $order = myQuery($sql);
        if( $order['0']['sqlRow'] > 0 )
        {
            $html = '';
            foreach ($order as $val)
            {
                $allGoods = findAll('order_goods',"order_sn='{$val["order_sn"]}'");
                if( !empty($allGoods) )
                {
                    $orderNum = 0;
                    $imgHtml ='';
                    foreach ($allGoods as $key => $v)
                    {
                        $imgHtml .= "<img src='".root."{$v['goods_icon']}'/>";
                        $orderNum += $v['buyNumber'];
                    }
//                    if( $val['workFlow'] == '0' ){
//                        $nowStatus = '去支付';
//                        $domName = "name='goPay'";    #dom名称
//                    }else{
//                        $nowStatus = '';
//                        $domName = $orderHtml = '';
//                    }
                }
                $html .= "
                    <div class='order-lists'>
                    <h2 class='mui-dis-flex'><span class='flex1'>订单号：{$val['order_sn']}</span></h2>
                        <dl>
                            <dt>
                                {$imgHtml}
                            </dt>
                            <dd><em>共{$orderNum}件 ， 待付款：{$val['money']}元</em></dd>
                            <dd>
                                <em>
                                    <input name='applyback' class='user-btn' type='button' value='申请退款' data-pid='{$val['order_sn']}'/>
                                </em>
                            </dd>
                        </dl>
                    </div>";
                $imgHtml = '';
            }
        }
        /*
         <dd>
            <em>
                <input class='user-btn' type='button' value='去支付' data-pid='{$val['order_sn']}' {$orderHtml} {$domName}/>
                <input name='buyAgain' class='user-btn' type='button' value='再次订购' data-pid='{$val['order_sn']}'/>
            </em>
        </dd>
         */
        $dataArr['html'] = $html;
        return $dataArr;
    }
    /**已发货 待收货*/
    public static function hasSend($khid)
    {
        $page = $_GET['page'];
        $size = $_GET['size'];
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $page =1;
        }
        if(!isset($_GET['size']) || empty($_GET['size'])){
            $size =20;
        }
        $offset = ($page - 1) * $size;

        $sql = "SELECT * FROM `order` WHERE target_khid = '{$khid}' AND o_type = '1' AND workFlow ='2' ORDER BY ctime DESC LIMIT {$offset},$size";
        $order = myQuery($sql);
        if( $order['0']['sqlRow'] > 0 )
        {
            $html = '';
            foreach ($order as $val)
            {
                $allGoods = findAll('order_goods',"order_sn='{$val["order_sn"]}'");
                if( !empty($allGoods) )
                {
                    $orderNum = 0;
                    $imgHtml ='';
                    foreach ($allGoods as $key => $v)
                    {
                        $imgHtml .= "<img src='".root."{$v['goods_icon']}'/>";
                        $orderNum += $v['buyNumber'];
                    }
                }
                $html .= "
                    <div class='order-lists'>
                    <h2 class='mui-dis-flex'><span class='flex1'>订单号：{$val['order_sn']}</span></h2>
                        <dl>
                            <dt>
                                {$imgHtml}
                            </dt>
                            <dd><em>共{$orderNum}件 ， 待付款：{$val['money']}元</em></dd>
                           <dd>
                                <em>
                                    <input name='receiveGoods' class='user-btn' type='button' value='确认收货' data-pid='{$val['order_sn']}'/>
                                    <input name='showLogistics' class='user-btn' type='button' value='查看物流' data-pid='{$val['order_sn']}'/>
                                    <input name='applyback' class='user-btn' type='button' value='申请退款' data-pid='{$val['order_sn']}'/>
                                </em>
                            </dd>
                        </dl>
                    </div>";
                $imgHtml = '';
            }
        }
        $dataArr['html'] = $html;
        return $dataArr;
    }
    /**待评价 */
    public static function waitTalk($khid)
    {
        $page = $_GET['page'];
        $size = $_GET['size'];
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $page =1;
        }
        if(!isset($_GET['size']) || empty($_GET['size'])){
            $size =20;
        }
        $offset = ($page - 1) * $size;

        $sql = "SELECT * FROM `order` WHERE target_khid = '{$khid}' AND o_type = '1' AND workFlow ='4' ORDER BY ctime DESC LIMIT {$offset},$size";
        $order = myQuery($sql);
        if( $order['0']['sqlRow'] > 0 )
        {
            $html = '';
            foreach ($order as $val)
            {
                $allGoods = findAll('order_goods',"order_sn='{$val["order_sn"]}'");
                if( !empty($allGoods) )
                {
                    $orderNum = 0;
                    $imgHtml ='';
                    foreach ($allGoods as $key => $v)
                    {
                        $imgHtml .= "<img src='".root."{$v['goods_icon']}'/>";
                        $orderNum += $v['buyNumber'];
                    }
                    if( $val['workFlow'] == '0' ){
                        $nowStatus = '去支付';
                        $domName = "name='goPay'";    #dom名称
                    }else{
                        $nowStatus = '';
                        $domName = $orderHtml = '';
                    }
                }
                $html .= "
                    <div class='order-lists'>
                    <h2 class='mui-dis-flex'><span class='flex1'>订单号：{$val['order_sn']}</span></h2>
                        <dl>
                            <dt>
                                {$imgHtml}
                            </dt>
                            <dd><em>共{$orderNum}件 ， 待付款：{$val['money']}元</em></dd>
                           <dd>
                                <em>
                                    <input name='toTalk' class='user-btn' type='button' value='去评价' data-pid='{$val['order_sn']}' {$orderHtml} {$domName}/>
                                </em>
                            </dd>
                        </dl>
                    </div>";
                $imgHtml = '';
            }
        }
        $dataArr['html'] = $html;
        return $dataArr;
    }
    /**订单状态按钮 */
    public static function orderStatus($nowStatus,$buyStatus)
    {
        if( $nowStatus == '去支付' ){
            return '去支付';
        }
        if( $nowStatus == '确认收货' ){
            return '确认收货';
        }
        if( $nowStatus == '去评价' ){
            if($buyStatus == '已付款' || $buyStatus == '已发货' ){
                return '确认收货';
            }
        }
        if( $nowStatus == '' ){
            if($buyStatus == '已付款' || $buyStatus == '已发货' ){
                return '确认收货';
            }
            if($buyStatus == '已收货'){
                return '去评价';
            }
            if($buyStatus == '已评价'){
                return '查看';
            }
            if( $buyStatus == '已退款' ){
                return '已退款';
            }
        }
    }

    public static function userApplyBackGoods($order_sn,$khid){
        $order = findOne('`order`',"order_sn='{$order_sn}' and pay_khid='{$khid}'");
        if(empty($order)){
            returnJSonText('订单不存在');
        }
        if(!in_array($order['workFlow'],[1,2])){
            returnJsonText('对不起，当前订单不允许款/退货');
        }
        switch($order['workFlow']){
            case '1':
                $sql = "update `order` set workFlow=6 where order_sn='{$order_sn}' and pay_khid='{$khid}'";
                $res = mysql_query($sql);
                if($res){
                    $json['warn'] = 2;
                    $json['msg'] = '申请退款成功';
                    returnJson($json);
                }else{
                    returnJsonText('申请退款失败');
                }
                break;
            case '2':
                $sql = "update `order` set workFlow=8 where order_sn='{$order_sn}' and pay_khid='{$khid}'";
                $res = mysql_query($sql);
                if($res){
                    $json['warn'] = 2;
                    $json['msg'] = '申请退货成功，请退货货物';
                    returnJson($json);
                }else{
                    returnJsonText('申请退货失败');
                }
                break;
        }

    }

    public static function myImg($id)
    {
        $img = query("img"," id = '$id' ");
        return $img['src'];
    }
    

}
function orderStatusBtn($nowStatus,$buyStatus)
{
    if( $nowStatus == '去支付' ){
        return '去支付';
    }
    if( $nowStatus == '确认收货' ){
        return '确认收货';
    }
    if( $nowStatus == '去评价' ){
        if($buyStatus == '已付款' || $buyStatus == '已发货' ){
            return '确认收货';
        }
    }
    if( $nowStatus == '' ){
        if($buyStatus == '已付款' || $buyStatus == '已发货' ){
            return '确认收货';
        }
        if($buyStatus == '已收货'){
            return '去评价';
        }
        if($buyStatus == '已评价'){
            return '查看';
        }
        if( $buyStatus == '已退款' ){
            return '已退款';
        }
    }
}
/**
 * 积分商城
 * @author r7
 */
class Integral
{
    public static $addTotal;        #总积分
    public static $subTotal;        #支出及过期积分
    public static $canUseTotal;     #可使用积分

    /**查询当前积分
     *     积分有效时间  para('integralTime')
     *     有效期积分：增加总积分 - 支出 — 过期
     */
    public static function getIntegral($khid)
    {
        global $time;
        $integralTime = para('integralTime');   #积分过期时间
        /*
        $res = findOne('integral',"khid = '{$khid}' ORDER BY id DESC LIMIT 1"); */
        /* if( $res ){
            return $res['laveCode'];
        }else{
            return 0;
        } */

        #增加积分
        $sql = "SELECT SUM(changeCode) addTotal FROM integral WHERE khid = '$khid' AND type = '增加'";
        $addTotal = myQuery($sql);
        $addTotal['0']['sqlRow'] > 0 ? $addTotal = $addTotal['0']['addTotal'] : 0;

        #支出及过期积分
        $sql = "SELECT SUM(changeCode) subTotal FROM integral WHERE khid = '$khid' AND (type = '支出' OR type = '过期' )";
        $subTotal = myQuery($sql);
        $subTotal['0']['sqlRow'] > 0 ? $subTotal = $subTotal['0']['subTotal'] : 0;
        $startCanuse = $addTotal - $subTotal;

        #过期处理
        $sql = "SELECT * FROM integral WHERE khid = '$khid' AND type = '增加' AND status != '已处理' AND DATE_SUB(CURDATE(),INTERVAL {$integralTime} DAY) >= DATE(time)";

        $res = myQuery($sql);
        if( $res['0']['sqlRow'] > 0 )
        {
            foreach ($res as  $val)
            {
                $afterTime = date( 'Y-m-d H:i:s',strtotime('30 days', strtotime( $val['time']) ) );
                if( $startCanuse <= $val['changeCode'] ){
                    $sql = "INSERT INTO `integral`(`khid`, `type`,`changeCode`, `laveCode`,`updateTime`, `time`) VALUES ('{$khid}','过期','{$startCanuse}','{$startCanuse}','{$time}','{$afterTime}')";
                }else{
                    $sql = "INSERT INTO `integral`(`khid`, `type`,`changeCode`, `laveCode`,`updateTime`, `time`) VALUES ('{$khid}','过期','{$val['changeCode']}','{$val['laveCode']}','{$time}','{$afterTime}')";
                }
                mysql_query($sql);
                $sql = "UPDATE integral SET status = '已处理',updateTime = '$time' WHERE id = '{$val['id']}'";
                mysql_query($sql);
                break;
            }
        }
        #增加积分
        $sql = "SELECT SUM(changeCode) addTotal FROM integral WHERE khid = '$khid' AND type = '增加'";
        $addTotal = myQuery($sql);
        $addTotal['0']['sqlRow'] > 0 ? $addTotal = $addTotal['0']['addTotal'] : 0;

        #支出及过期积分
        $sql = "SELECT SUM(changeCode) subTotal FROM integral WHERE khid = '$khid' AND (type = '支出' OR type = '过期' )";
        $subTotal = myQuery($sql);
        $subTotal['0']['sqlRow'] > 0 ? $subTotal = $subTotal['0']['subTotal'] : 0;

        #增加的总积分
        self::$subTotal = $subTotal;                #支出 和 过期积分
        if( $addTotal < $subTotal ){
            $canUseTotal = 0; #能够使用的积分
        }else{
            $canUseTotal = $addTotal - $subTotal;
        }
        self::$canUseTotal = $canUseTotal; #能够使用的积分

    }
    public static function get_integral($khid)
    {
        global $time;
        $integralTime = para('integralTime');   #积分过期时间

        $sql = "SELECT * FROM ";
    }
    /**
     * 积分商品列表页
     * @return
     */
    static public function index()
    {
        $sql = "SELECT g.*,k.id skid,k.integral FROM goods g,goodsSku k WHERE g.goodsOneId = 'fDB85168219GV' AND g.id = k.goodsId LIMIT 10";

        $res = myQuery($sql);
        if( $res[0]['sqlRow'] > 0 )
        {
            foreach ($res as $val)
            {
                $html .= "
                <li class='integral-detail' data-id='{$val['id']}' data-skid='{$val['skid']}'>
                    <a>
                        <img src='".root."{$val['ico']}'/>
                        <p class='nameSpc'>{$val['name']}</p>
                        <p class='textSale'>
                            <label>所需积分：<em class='text-price'>{$val['integral']}</em></label> 
                            <a class='text-sale integral-btn'>兑换</a> 
                        </p>
                        <!--<p class='textSale'>
                            <label>已兑换：<em>22</em>|</label> 
                            <label>剩余：<span>99</span></label> 
                        </p>-->
                    </a>
                </li>";
            }
            return $html;
        }else{
            return '商品正在上架中';
        }
    }
    /**
     * 获得指定商品的积分
     */
    public static function getGoodsIntegral($goodsId)
    {
        $res = findOne('goods g,goodsSku sk',"g.id = '$goodsId' AND g.id = sk.goodsId","g.*,sk.integral");
        if( $res ){
            return $res['integral'];
        }
    }
    /**积分商城详情 */
    public static function getGoodsDetal($goodsId)
    {
        return $html = myArticleMx($goodsId,'商品明细');
    }

}




/**
 * 我的足迹
 */
class browseTraces
{
    public static function index()
    {
        $cookieStr = $_COOKIE['browseTraces'];
        $cookieArr = unserialize($cookieStr);
        $html = '';
        if( is_array($cookieArr) )
        {
            foreach ($cookieArr as $val)
            {
                $goodsInfo = findOne('goods',"id = '{$val}'");
                $html .= "
                <li>
                    <a href='".root."m/mGoodsMx.php?gid={$goodsInfo['id']}'>
                        <img src='".root."{$goodsInfo['ico']}'>
                        <p class='nameSpc'>{$goodsInfo['name']}</p>
                        <!--<p class='textSale'>
                            <em class='text-price'>￥89.00</em>
                            <em class='text-sale'>销量:60</em>
                        </p>-->
                    </a>
                </li>";
            }
        }
        return $html;
    }
}
/**
 * 商品分润
 */
class goodsSubRun
{
    public static $rate;           #毛利率
    public static $parRate;        #拔比利率
    public static $recommendFree;  #推荐佣金
    public static $selfIntegral;   #自购积分
    public static $teamFree;       #团队业绩
    public static $purchaseFree;   #采购提成

    public static function getFree($gid,$goodsSkuId)
    {
        $res = findOne('goodsSku',"goodsId = '$gid' AND id = '$goodsSkuId'");

        if( $res ){
            $rate = round( ( $res['pricing'] - $res['cost'] - $res['free']) / $res['pricing'] , 2)*100;   #毛利率
            $str = $res['pricing'] .'-'. $res['cost'] .'-'. $res['free'] .'/'.$res['pricing'];
        }else {
            return FALSE;
        }
        //pricing 定价
        //grossProfit 毛利率
        //corresponding 对应拨比率
        if( !$res )
        {
            return FALSE;
        }

        if( $rate >= 0 && $rate <= 4.9 ){
            $parRate = 0;   //拔比利率
        }else if( $rate >= 5 && $rate <= 25.9 ){
            $parRate = 1;
        }else if( $rate >= 26 && $rate <= 30.9 ){
            $parRate = 5;
        }else if( $rate >= 31 && $rate <= 35.9 ){
            $parRate = 10;
        }else if( $rate >= 36 && $rate <= 40.9 ){
            $parRate = 15;
        }else if( $rate >= 41 && $rate <= 45.9 ){
            $parRate = 20;
        }else if( $rate >= 46 && $rate <= 50 ){
            $parRate = 24;
        }else if( $rate > 50 ){
            $parRate = 24;
        }

        //$parRate                = $corresponding;           //拔比利率
        self::$rate = $rate ;          //毛利率
        $price = $res['pricing'];                           //定价
        self::$parRate          = $parRate;                  #拔比利率
        self::$recommendFree    = round( ($price * $parRate * 0.4167 / 100) , 2 ) ;#推荐佣金
        self::$selfIntegral     = round( ($price * $parRate * 0.4167 / 100) , 2 ) ;#自购积分
        self::$teamFree         = round( ($price * $parRate * 0.0833 / 100) , 2 ) ;#团队业绩
        self::$purchaseFree     = round( ($price * $parRate * 0.0833 / 100) , 2 ) ;#采购提成
        $arr['毛利率'] = $rate;
        $arr['毛利率Mx'] = $str;
        $arr['拔比利率'] = $parRate;
        $arr['推荐佣金'] = self::$recommendFree;
        $arr['自购积分'] = self::$selfIntegral ;
        $arr['团队业绩'] = self::$teamFree;
        $arr['采购提成'] = self::$purchaseFree;

        console_log($arr);
    }
}




class GoodsCoupon
{
    /**用户能够使用最大的优惠劵 */
    public static function getMaxCoupon($khid)
    {
        global $time;
        #根据buyCar 查询出多规格商品的总价
        $sql = "SELECT COUNT(*) total,SUM(buyPrice * buyNumber) totalPrice,goodsId FROM buyCar WHERE khid = '$khid' AND type = '普通订单' AND workFlow = '已选定' GROUP BY goodsId";
        $buyCarData = myQuery($sql);
        if( $buyCarData['0']['sqlRow'] > 0 )
        {
            $goodsIdArr = [];
            $couponArr  = [];
            /**
             *  查询每件商品的优惠劵
             *  查询客户是否有该优惠劵
             * */
            foreach ($buyCarData as $val)
            {
                //$sql = "SELECT MAX(moeny) maxMoney ,c.*,k.* FROM coupon c,kehuCoupon k WHERE c.goodsId = '{$val['goodsId']}' AND starTime <= '$time' AND endTime >= '$time' AND amountMoeny <= '{$val['totalPrice']}' AND c.id = k.couponId AND ";
                $sql = "SELECT id,goodsId,moeny,amountMoeny FROM coupon WHERE goodsId = '{$val['goodsId']}' AND starTime <= '$time' AND endTime >= '$time' AND amountMoeny <= '{$val['totalPrice']}'";
                $goodsCoupon = myQuery($sql);//该商品的所有可以使用的优惠劵
                if( $goodsCoupon['0']['sqlRow'] > 0 )
                {
                    foreach ($goodsCoupon as $val)
                    {
                        $res = findOne('kehuCoupon',"couponId = '{$val['id']}' AND status = '未使用'");
                        if( $res )
                        {
                            $couponArr[$val['id']] = [
                                'goodsId'   => $val['goodsId'],
                                'money'     => $val['moeny'],
                                'couponId'  => $val['id']
                            ];
                        }
                    }
                }
            }
        }
        $key = array_search(max($couponArr),$couponArr);
        $data['couponId']   = $key;                                     #最大优惠劵id
        $data['money']      = $couponArr[$key]['money'];                #最大优惠劵金额
        $data['data']       = $couponArr;                               #购物车中商品 客户拥有的对应优惠劵
        return $data;
    }
    /**获取优惠劵金额 */
    public static function getCouponMoney()
    {
        global $time;
        #查找未使用的优惠劵
        $res = findAll('kehuCoupon',"khid = '{$_SESSION['khid']}' AND status = '未使用'",'*','couponId');
        if( $res['0']['column'] ){
            #查找未过期 金额最大的优惠劵
            $sql = "SELECT *,MAX(moeny) FROM coupon WHERE id IN {$res['0']['column']} AND starTime <= '$time' AND endTime >= '$time' AND amountMoeny <= '{$_SESSION['totalPrice']}'";
            $info = myQuery($sql);
            if( $info['0']['sqlRow'] > 0 && !empty($info['0']['id']) ){
                //console_log($info);
                $res = findOne('kehuCoupon',"khid = '{$_SESSION['khid']}' AND couponId = '{$info['0']['id']}' AND status = '未使用'");
                $info['0']['couponId'] = $res['id'];    #kehuCoupon id
                $info['0']['couponNum'] = 1;
            }else{
                $info['0']['couponId']   = '' ;
                $info['0']['couponNum']  = 0 ;
                $info['0']['moeny'] = 0 ;
                $_SESSION['coupon']['money'] = 0;
                $_SESSION['coupon']['couponId'] = '';
            }
        }else{
            $info['0']['couponId']   = '' ;
            $info['0']['couponNum']  = 0 ;
            $info['0']['moeny'] = 0 ;
        }
        return $info;
    }
}
function xxj($arr){
    if( $_SESSION['adid'] == 'fg35h4' ){
        echo '<pre>';
            print_r($arr);
        echo '</pre>';    
    }
}
/**
 * 收入筛选类
 * @author r7
 */
class Filter
{
    public static function index($khid,$get)
    {
        if( empty($get) )
        {
            $sql = "SELECT i.*,k.name FROM income i,kehu k WHERE i.khid = '$khid' AND i.srcKhid = k.khid";
        }else{
            $orderTime = $get['time'];
            $orderType = $get['type'];
            switch ($orderTime) {
                case 'all':
                    $orderTime = '';
                    break;
                case 'yes':
                    $orderTime = " AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) = DATE(i.time)";
                    break;
                case 'week':
                    $orderTime = " AND YEARWEEK(date_format(i.time,'%Y-%m-%d')) = YEARWEEK(now())";
                    break;
                case 'month':
                    $orderTime = " AND date_format(i.time,'%Y-%m') = date_format(now(),'%Y-%m')";
                    break;
            }
            switch ($orderType) {
                case 'all':
                    $orderType = "";
                    break;
                case 'team':
                    $orderType = " AND i.type = '团队'";
                    break;
                case 'my':
                    $orderType = " AND i.type = '个人'";
                    break;
                case 'share':
                    $orderType = " AND i.type = '推荐'";
                    break;
            }
            $sql = "SELECT i.*,k.name FROM income i,kehu k WHERE i.khid = '$khid' AND i.srcKhid = k.khid {$orderTime} {$orderType}";
        }
        $res = myQuery($sql);
        if( $res['0']['sqlRow'] > 0 )
        {
            foreach ($res as $val)
            {
                $html .= "
                <dl class='mui-dis-flex'>
                    <dt>
                        <p>{$val['name']}</p>
                        <p>ID：{$val['srcKhid']}</p>
                    </dt>
                    <dd class='flex1'>
                        <ul>
                            <li>订单号：{$val['orderId']}</li>
                            <li>销售额：{$val['sales']}</li>
                            <li>交易时间：{$val['orderTime']}</li>
                            <li>返佣额：{$val['free']}</li>
                        </ul>
                    </dd>
                </dl>";
            }
            return $html;
        }else{
            return '暂无收益';
        }
    }
}
/**
 * 收入类
 * @author r7
 */
class Income
{
    #提现相关数据
    public static $hasWithdraw;         #已提现
    public static $waitWithdraw;        #提现     正在审核金额
    public static $dontPassWithdraw;    #未通过提现审核的金额

    #待结算相关数据
    public static $tobeSettled;         #待结算金额

    #可提现相关数据
    public static $available;           #可提现金额

    #总费用
    public static $totalFree;           #总费用

    public static function index($khid)
    {
        $sql = "SELECT * FROM income WHERE khid = '$khid'";
        $res = myQuery($sql);
        foreach ($res as $key => $val)
        {

        }
    }
    /**已提现 */
    public static function withdraw($khid,$showList = FALSE,$debug = FALSE)
    {
        $sql = "SELECT * FROM withdraw WHERE khid = '$khid' ORDER BY id DESC";
        $res = myQuery($sql);
        $dataArr = [
            'hasWithdraw' => 0,
            'waitWithdraw' => 0,
            'dontPassWithdraw' => 0
        ];  #初始化
        if( $res['0']['sqlRow'] > 0 )
        {
            foreach ($res as $key => $val)
            {
                if( $val['workFlow'] == '审核中' ){
                    $dataArr['waitWithdraw'] += $val['moneny'];

                }else if( $val['workFlow'] == '已通过' ){
                    $dataArr['hasWithdraw'] += $val['moneny'];

                }else if( $val['workFlow'] == '已支付' ){
                    $dataArr['hasWithdraw'] += $val['moneny'];

                }else if( $val['workFlow'] == '未通过' ){
                    $dataArr['dontPassWithdraw'] += $val['moneny'];
                }
                if( $showList === TRUE ){
                    $html .= "
                    <ul>
                        <li>提现时间： {$val['time']}</li>
                        <li>提现金额：{$val['moneny']}元</li>
                        <li><label>状态：</label><span>{$val['workFlow']}</span></li>
                    </ul>";
                }
            }

        }
        self::$hasWithdraw  = $dataArr['hasWithdraw'];          #已提现
        self::$waitWithdraw = $dataArr['waitWithdraw'];         #等待审核金额
        self::$dontPassWithdraw = $dataArr['dontPassWithdraw']; #未通过金额
        /**调试数据 */
        if( $debug ){
            echo '<hr>','已提现数据';
            echo $sql,'<br>';
            echo '<pre>';
            print_r($dataArr);
            echo '</pre>';
        }
        if( $showList === TRUE ) return $html;
    }
    /**
     * 待结算
     *      客户确认收货后30天进入可提现金额中
     * */
    public static function tobeSettled($khid,$showList = FALSE,$debug = FALSE)
    {
        #查询待结算总费用
        $sql = "SELECT SUM(free) tobeSettled FROM income WHERE khid = '$khid' AND type = ( '个人' OR '团队' ) AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE(orderTime)";
        $totalRes = myQuery($sql);
        if( $totalRes['0']['sqlRow'] > 0 )
        {
            is_null( $total['0']['tobeSettled'] ) ?  $total = 0 : $total = $totalRes['0']['tobeSettled'] ;
        }
        //$totalRes['0']['sqlRow'] > 0 ? $total = $totalRes['0']['tobeSettled'] : $total = 0 ;
        self::$tobeSettled = $total;
        if( $showList === TRUE ){
            $sql = "SELECT * FROM income WHERE khid = '$khid' AND type = ( '个人' OR '团队' ) AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE(orderTime)";
            $res = myQuery($sql);
            if( $res['0']['sqlRow'] > 0 ){
                foreach ($res as $val)
                {
                    $html .= "<dt class='mui-dis-flex'><label class='flex1'>待结算金额<i>(审核中)</i><span>{$val['free']}</span></label><em>元</em></dt>";
                }
                $dataArr['html'] = $html;
            }else{
                $dataArr['tobeSettled'] = $total;
                $dataArr['html'] = '';
            }
            return $dataArr;
        }
        /**调试数据 */
        if( $debug ){
            echo '<hr>','待结算数据';
            echo $sql,'<br>';
            echo $total;
        }
    }
    /**可提现
     *       总费用 - 待结算 - 提现( 已提现 审核中及通过 ) + 审核未通过
     */
    public static function availableFree($khid,$debug = FALSE)
    {
        $sql = "SELECT SUM(free) total FROM income WHERE khid = '$khid' ";
        $res = myQuery($sql);
        if( $res['0']['sqlRow'] > 0 ){
            is_null($res['0']['total']) ? $total = 0 : $total = $res['0']['total'];
        }else{
            $total = 0;
        }
        if( self::$hasWithdraw !== 0 ) Income::withdraw($khid);
        if( self::$tobeSettled !== 0 ) Income::tobeSettled($khid);
        $available = $total - self::$tobeSettled - self::$hasWithdraw - self::$waitWithdraw;// + self::$dontPassWithdraw;
        self::$totalFree = $total;      #总费用
        self::$available = $available;  #可提现费用
        /**调试数据 */
        if( $debug ){
            echo '<hr>','可提现数据';
            echo $sql,'<br>';
            echo $total .'-'. self::$tobeSettled .'-'. self::$hasWithdraw .'-'. self::$waitWithdraw;// .'+'. self::$dontPassWithdraw;
        }
    }
}
/**
 * user 基本信息统计类
 * @author  r7
 */
class mUser
{
    public static function index($khid)
    {
        #本周加入人数
        $sql = "SELECT COUNT(*) total FROM kehu WHERE shareId = '$khid' AND YEARWEEK(date_format(time,'%Y-%m-%d')) = YEARWEEK(now()) ";
        $res = myQuery($sql);
        $thisWeek = $res['0']['sqlRow'] > 0 ? $res['0']['total'] : 0;
        #邀请总人数
        $sql = "SELECT COUNT(*) total FROM kehu WHERE shareId = '$khid'";
        $res = myQuery($sql);
        $total = $res['0']['sqlRow'] > 0 ? $res['0']['total'] : 0;
        #新增收入
        $sql = "SELECT SUM(free) FROM income WHERE khid = '$khid' AND to_days(time) = to_days(now)";
        $res = myQuery($sql);
        $todayFree = $res['0']['sqlRow'] > 0 ? $res['0']['total'] : 0;
        #总收入
        $sql = "SELECT SUM(free) FROM khid = '$khid'";
        $res = myQuery($sql);
        $totalFree = $res['0']['sqlRow'] > 0 ? $res['0']['total'] : 0;

        $dataArr['thisWeek']    = $thisWeek;    #本周加入人数
        $dataArr['total']       = $total;       #邀请总人数
        $dataArr['todayFree']   = $todayFree;   #新增收入
        $dataArr['totalFree']   = $totalFree;   #总收入
        return $dataArr;
    }
}
class GoodsList
{
    public static function index($get,$bool = FALSE)
    {
        $oid    = $get['oid'];      #一级分类id
        $tid    = $get['tid'];      #一级分类id
        $type   = $get['type'];     #排序标志
        if( $type == 'price' ){
            $order = $get['orp'];   #价格排序
            switch ($order)
            {
                case 'down':
                    $orderSql = " ORDER BY price DESC";
                    break;
                case 'up':
                    $orderSql = " ORDER BY price ASC";
                    break;
                default:
                    $orderSql = " ORDER BY time DESC";
                    break;
            }
        }else if( $type == 'sales' ){
            $order = $get['ors'];   #销量排序
            switch ($order)
            {
                case 'down':
                    $orderSql = " ORDER BY salesVolume DESC";
                    break;
                case 'up':
                    $orderSql = " ORDER BY salesVolume ASC";
                    break;
                default:
                    $orderSql = " ORDER BY time DESC";
                    break;
            }
        }
        if( $bool === TRUE ){
            $sql = "SELECT * FROM goods WHERE name LIKE '%{$get['keywords']}%' {$orderSql}";
        }else{
            if( !empty($oid) ){
                $sql = "SELECT * FROM goods WHERE goodsOneId = '$oid' AND xian = '显示' {$orderSql}";
            }else if( !empty($tid) ){
                $sql = "SELECT * FROM goods WHERE goodsTwoId = '$tid' AND xian = '显示' {$orderSql}";
            }
        }
        $res= myQuery($sql);
        $dataArr = [];
        if( $res['0']['sqlRow'] > 0 )
        {
            foreach ($res as $key => $val)
            {
                $html .= "
                <li>
                    <a href='".root."m/mGoodsMx.php?gid={$val['id']}'>
                        <img src='".root."{$val['ico']}'/>
                        <p class='nameSpc'>{$val['name']}</p>
                        <p class='textSale'>
                            <em class='text-price'>￥{$val['price']}</em> 
                            <em class='text-sale'>销量:{$val['salesVolume']}</em> 
                        </p>
                    </a>
                </li>";
            }
        }
        $dataArr['html'] = $html;
        return $dataArr;
    }
}




/**
 * 批量处理购物车未选中状态
 *      note:主要用于：购物车提交，但并没有提交订单
 */
function processBuyCarNotSelect($khid)
{
//    $sql = "SELECT * FROM buyCar WHERE khid = '$khid' AND workFlow = '已选定'";
//    $res = myQuery($sql);
//    if( $res['0']['sqlRow'] > 0 )
//    {
//        foreach ($res as $key => $val)
//        {
//            $sql = "UPDATE buyCar SET workFlow = '未选定' WHERE id = '{$val['id']}'";
//            mysql_query($sql);
//        }
//    }
        $sql = "UPDATE buyCar SET workFlow = '未选定' WHERE khid = '$khid'";
        mysql_query($sql);
}
/**
 * 个人中心手机号处理
 * @return void
 */
function phoneBuild($tel)
{
    $newTel = '';
    for ($i=0; $i < 11 ; $i++)
    {
        if( $i == 3 || $i == 7 ){
            $newTel .= ' '.$tel[$i];
        }else{
            $newTel .= $tel[$i];
        }
    }
    return $newTel;
}
/**
 * 购物车获取积分和利润
 * @author  r7
 * @param [type] $khid
 * @return void
 */
function getInegralAndProfit($khid)
{
    $res = findAll('buyCar',"khid = '$khid'");
    $orderNum = $shopNum = $intrgralCode = $totalPrice =  0;//订单数量 商品数量 初始化
    if( $res )
    {
        foreach ($res as $val) {
            $orderNum ++;
            $shopNum += $val['buyNumber'];
            $totalPrice += $val['buyNumber'] * $val['buyPrice'];
            goodsSubRun::getFree($val['goodsId'],$val['goodsSkuId']);
            $intrgralCode += (goodsSubRun::$selfIntegral) * $val['buyNumber'];
        }
    }

    $dataArr['shopNum']         = $shopNum;
    $dataArr['totalPrice']      = $totalPrice;
    $dataArr['intrgralCode']    = round($intrgralCode);
    return $dataArr;
}
/**
 * 图片地址
 *      主要用于微信缓存
 */
function imgt($id)
{
    $res = findOne('img',"id = '$id'");
    return root.$res['src']."?t=".strtotime($res['updateTime']);
}
/**
 * 插入分润
 * @param str $buyCarId
 */
function insertIncome($buyCarId,$pid)
{
    global $kehu;
    global $time;
    $buycar    = findOne('buyCar',"id = '$buyCarId'");      #buyCar信息
    $pay    = findOne('pay',"id = '$pid'");                 #pay 信息
    $dataJosn['buy'] = $buycar;
    $dataJosn['pay'] = $pay;

    #个人
    #推荐佣金
    #自购积分
    #团队业绩
    #采购提成
    if( $buycar && $pay ){
        $timeInt = time() + 30 * 24 * 3600;
        $date = date('Y-m-d H:i:s',$timeInt);
        $sales = $buycar['buyPirce'] * $buycar['buyNumber'];

        #自购积分
        goodsSubRun::getFree($buycar['goodsId'],$buycar['goodsSkuId']);
        $recommendFree  = goodsSubRun::$recommendFree;  #推荐佣金
        $intergral      = goodsSubRun::$selfIntegral;   #自购积分
        $teamFree       = goodsSubRun::$teamFree;       #团队业绩
        $purchaseFree   = goodsSubRun::$purchaseFree;   #采购提成

        if( $pay['purchaserKhid'] == $pay['trgetId'] || empty( $pay['purchaserKhid'] ) )
        {
            $dataJson['区间'] = '自购';
            #上级成员的类型
            $headInfo = findOne('kehu',"khid = '{$kehu['shareId']}'");
            //推荐佣金
            if( ( $kehu['type'] == '普通会员' && in_array($headInfo['type'],['普通会员','高级会员']) ) || ( in_array($kehu['type'],['','高级会员']) && $headInfo['type'] == '高级会员' ) ){
                //团队业绩
                $sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderId`, `orderTime`, `sales`, `free`, `time`) VALUES ('团队','{$headInfo['khid']}','{$_SESSION['khid']}','{$kehu['name']}','$pid','$date','$sales','$teamFree','$time')";

                $dataJson['团队'] = $sql;
            }else if( empty($kehu['type']) && !empty($headInfo['type']) ){
                //推荐佣金
                $sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderId`, `orderTime`, `sales`, `free`, `time`) VALUES ('推荐','{$_SESSION['khid']}','{$_SESSION['khid']}','{$kehu['name']}','$pid','$date','$sales','$recommendFree','$time')";

                $dataJson['推荐'] = $sql;
            }
            $bool = mysql_query($sql);
            $dataJson['bool'] = $bool;
            //自购积分
            $sql = "INSERT INTO `integral`(`khid`, `type`, `goodsId`, `changeCode`, `laveCode`, `updateTime`, `time`) VALUES ('{$_SESSION['khid']}','增加','{$buycar['goodsId']}','$intergral','$intergral','$time','$time')";

            $bool = mysql_query($sql);  //推荐佣金 OR 自购积分
            $dataJson['自购'] = $sql;
            $dataJson['自购bool'] = $bool;
            //采购提成
            $shopName = findOne('goodsSku',"id = '{$buycar['goodsSkuId']}'");
            if( $shopName['clerk'] )
            {
                $admin = findOne('admin',"adid = '{$shopName['clerk']}'");
                $balance = $admin['money'] + $purchaseFree;
                $sql = "INSERT INTO `record`(`typeid`, `type`, `direction`, `money`, `balance`, `time`) VALUES ('{$_SESSION['khid']}','采购提成','收入','$purchaseFree','$balance','$time')";
                $bool = mysql_query($sql);

                $dataJson['采购添加'] = $bool;

                $sql = "UPDATE admin SET money = '$balance' WHERE adid = '{$shopName['clerk']}'";
                $bool = mysql_query($sql);

                $dataJson['采购修改'] = $bool;
            }
            #为他人代购
        }else if( $pay['purchaserKhid'] != $pay['khid'] ){
            $other = findOne('kehu',"khid = '{$pay['purchaserKhid']}'");
            $otherHead = findOne('kehu',"khid = '{$other['shareId']}'");
            if( $other )
            {
                $dataJson['区间'] = '代购';

                if( ( $other['type'] == '普通会员' && in_array($otherHead['type'],['普通会员','高级会员']) ) || ( in_array($other['type'],['','高级会员']) ) && $otherHead['type'] == '高级会员'  )
                {   #团队业绩
                    $sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderId`, `orderTime`, `sales`, `free`, `time`) VALUES ('团队','{$otherHead['khid']}','{$other['khid']}','{$other['name']}','$pid','$date','$sales','$teamFree','$time')";

                    $dataJson['团队'] = $sql;
                }else if( empty($other['type'] && !empty($otherHead['type']) ) ){
                    #推荐
                    $sql = "INSERT INTO `income`(`type`, `khid`, `srcKhid`, `srcName`, `orderId`, `orderTime`, `sales`, `free`, `time`) VALUES ('推荐','{$otherHead['khid']}','{$other['khid']}','{$other['name']}','$pid','$date','$sales','$recommendFree','$time')";

                    $dataJson['推荐'] = $sql;
                }
                $bool = mysql_query($sql);
                $dataJson['代购bool'] = $bool;

                $sql = "INSERT INTO `integral`(`khid`, `type`, `goodsId`, `changeCode`, `laveCode`, `updateTime`, `time`) VALUES ('{$other['khid']}','增加','{$buycar['goodsId']}','$intergral','$intergral','$time','$time')";
                $bool = mysql_query($sql);
                $dataJson['积分sql'] = $sql;
                $dataJson['积分bool'] = $bool;

                //采购提成
                $shopName = findOne('goodsSku',"id = '{$buycar['goodsSkuId']}'");
                if( $shopName['clerk'] )
                {
                    $admin = findOne('admin',"adid = '{$shopName['clerk']}'");
                    $balance = $admin['money'] + $purchaseFree;

                    $sql = "INSERT INTO `record`(`typeid`, `type`, `direction`, `money`, `balance`, `time`) VALUES ('{$other['khid']}','采购提成','收入','$purchaseFree','$balance','$time')";
                    $bool = mysql_query($sql);

                    $dataJson['采购添加流水sql'] = $sql;
                    $dataJson['采购添加流水bool'] = $bool;

                    $sql = "UPDATE admin SET money = '$balance' WHERE adid = '{$shopName['clerk']}'";
                    $bool = mysql_query($sql);

                    $dataJson['采购修改sql'] = $sql;
                    $dataJson['采购修改bool'] = $bool;
                }
            }
        }
    }else{
        return '';
    }
    return $dataJson;
}


/**
 * 商品分润
 */
class skuSubRun
{
    public static $rate;           #毛利率
    public static $parRate;        #拔比利率
    public static $recommendFree;  #推荐佣金
    public static $selfIntegral;   #自购积分
    public static $teamFree;       #团队业绩
    public static $purchaseFree;   #采购提成

    public static function getFree($price,$cost,$free,$shippingFree)
    {
        empty($shippingFree) ? $shippingFree = 0 : $shippingFree = $shippingFree;
        empty($free) ? $free = 0 : $free = $free;
        
        if(  !empty($price) && !empty($cost) && isset($free) && isset($shippingFree) ){
            $rate = round( ( $price - $cost - $free - $shippingFree ) / $price , 4) * 100;   #毛利率
            $str = $price .'-'. $cost .'-'. $free .'-'. $shippingFree .'/'.$price;
            $dataArr['str'] = $str;
        }else{
            $dataArr['warn'] = '数据不完整';
            return $dataArr;
        }
        if( $rate >= 0 && $rate <= 4.9 ){
            $parRate = 0;   //拔比利率
        }else if( $rate >= 5 && $rate <= 25.9 ){
            $parRate = 1;
        }else if( $rate >= 26 && $rate <= 30.9 ){
            $parRate = 5;
        }else if( $rate >= 31 && $rate <= 35.9 ){
            $parRate = 10;
        }else if( $rate >= 36 && $rate <= 40.9 ){
            $parRate = 15;
        }else if( $rate >= 41 && $rate <= 45.9 ){
            $parRate = 20;
        }else if( $rate >= 46 && $rate <= 50 ){
            $parRate = 24;
        }else if( $rate > 50 ){
            $parRate = 24;
        }

        self::$rate = $rate;                                 #毛利率
        self::$parRate          = $parRate;                  #拔比利率
        self::$recommendFree    = round( ($price * $parRate * 0.4167 / 100) , 2 ) ;#推荐佣金
        self::$selfIntegral     = round( ($price * $parRate * 0.4167 / 100) , 2 ) ;#自购积分
        self::$teamFree         = round( ($price * $parRate * 0.0833 / 100) , 2 ) ;#团队业绩
        self::$purchaseFree     = round( ($price * $parRate * 0.0833 / 100) , 2 ) ;#采购提成

        $arr['毛利率'] = $rate;
        $arr['毛利率Mx'] = $str;
        $arr['拔比利率'] = $parRate;
        $arr['推荐佣金'] = self::$recommendFree;
        $arr['自购积分'] = self::$selfIntegral ;
        $arr['团队业绩'] = self::$teamFree;
        $arr['采购提成'] = self::$purchaseFree;
        $dataArr['warn']       = '计算成功';
        $dataArr['price']       = $price;
        $dataArr['rate']        = $rate;
        $dataArr['parRate']     = $parRate;
        $dataArr['recommendFree']   = self::$recommendFree; //推荐佣金
        $dataArr['selfIntegral']    = self::$selfIntegral ; //自购积分
        $dataArr['teamFree']        = self::$teamFree;      //团队业绩
        $dataArr['purchaseFree']    = self::$purchaseFree;  //采购提成
        return $dataArr;
    }
}


/********多图图文编辑版本*********/
//变量解释：$Target为文章对象，$TargetName为文章对象的表名称，$TargetId为当前文章主人的id号,$imgurl为图片的子文件夹名称，$ImgMaxWidth为图片的最大宽度（超过此宽度则会缩放为此宽度）
function myarticle($Target,$TargetId,$imgurl,$ImgMaxWidth){
    $html = "";
    $sql = mysql_query("SELECT * FROM article WHERE targetId = '$TargetId' AND target='宣传素材'");
    $num = mysql_num_rows($sql);
    if(mysql_num_rows($sql)==0){
        $content .= "<li>没有任何宣传素材</li>";
    }else{
        while($array = mysql_fetch_array($sql)){
                $content .= "<li ArticleWordContentId='{$array['id']}' class='articleMx' style='width:200px;'>".neirong($array['word'])."".ProveImgShow($array['img'],'暂无图片')."</li>";

        }
        $html .= "<div class='profitDiv'>
                        <div class='profitinside'>
                            <p style='color: #3F7F7F;font-size: 20px;'>宣传素材展示区</p>
                            <ul style='height: 100px;'>
                            {$content}
                            </ul>
                        </div>
                    </div>";
    }
    $html .= "
	<!--窗口浮标开始-->
	<div id='addMaterialImgButton'>
		<img src='".root."img/images/ArticleAddImg.png'>
		<p>素材图片</p>
	</div>
	<div id='addMaterialWordButton'>
		<img src='".root."img/images/ArticleAddWord.png'>
		<p>添加素材文字</p>
	</div>
	<!--窗口浮标结束-->
	<!--文字编辑弹出层开始-->
	<div class='hide' id='articleWordEdit'>
		<div class='dibian'></div>
		<div class='win' style='width:600px; height:354px; margin:-172px 0 0 -300px;'>
			<p class='winTitle'>文字编辑器<span class='winClose' onClick=\"$('#articleWordEdit').hide()\">×</span></p>
			<form name='articleWordForm'>
				<textarea name='articleText' class='textarea' style='width:590px; height:260px; border:0;'></textarea>
				<input name='articleTextId' type='hidden'>
				<input name='target' type='hidden' value='{$Target}'>
				<input name='targetId' type='hidden' value='{$TargetId}'>
			</form>
			<p class='winFooter'><input type='button' class='button' value='提交文字' onclick=\"Sub('articleWordForm','".root."library/libData.php?type=articleWordEdit')\"></p>
		</div>
	</div>
	<!--文字编辑弹出层结束-->
	<!--更新序列号弹出层开始-->
	<div class='hide' id='articleListEdit'>
		<div class='dibian'></div>
		<div class='win' style='width:300px; height:127px; margin:-63px 0 0 -150px;'>
			<p class='winTitle'>更新段落序列号<span class='winClose' onClick=\"$('#articleListEdit').hide()\">×</span></p>
			<div class='padding'>
			<form name='articleListForm'>
			段落序列号：<input name='articleListText' type='text' class='text textPrice'>
			<input name='artcleListId' type='hidden'>
			</form>
			</div>
			<p class='winFooter'><input type='button' class='button' onclick=\"Sub('articleListForm','".root."library/libData.php?type=articleListEdit')\" value='更新段落序列号'></p>
		</div>
	</div>
	<!--更新序列号弹出层结束-->
	<!--隐藏表单开始-->
	<div class='hide'>
	<form name='MaterialImgForm' method='post' action='".root."control/ku/adpost.php?type=MaterialImg' enctype='multipart/form-data'>
		<input name='ImgArticle[]' id='file' type='file' multiple='multiple' onchange='document.MaterialImgForm.submit()' />>
		<input name='TargetIdOne' type='hidden' value='{$TargetId}'>
	</form>
	<form name='adMaterialImgForm' method='post' action='".root."library/libPost.php?type=articleImgEdit' enctype='multipart/form-data'>
		<input name='articleImg' type='file' onchange='document.adMaterialImgForm.submit()' />
		<input name='artcleImgId' type='hidden'>
		<input name='Target' type='hidden' value='{$Target}'>
		<input name='TargetId' type='hidden' value='{$TargetId}'>
		<input name='imgurl' type='hidden' value='{$imgurl}'>
		<input name='ImgMaxWidth' type='hidden' value='{$ImgMaxWidth}'>
	</form>
	</div>
	<!--隐藏表单结束-->
	<script>
	$(document).ready(function(){
		//新增文字段落
		$('#addMaterialWordButton').click(function(){
			$('#articleWordEdit').show();
			document.articleWordForm.articleText.value = '';
			document.articleWordForm.articleTextId.value = '';
		});
		//新增图片
		$('#addMaterialImgButton').click(function(){
			//document.articleImgForm.articleImg[].click();
			document.getElementById('file').click();
            document.adMaterialImgForm.artcleImgId.value = '';
		});
		//编辑已有段落
		$('[articleEditType]').click(function(){
			if($(this).attr('articleEditType') == 'word'){
				$('#articleWordEdit').show();
				var articleId = $(this).attr('isid');
				document.articleWordForm.articleTextId.value= articleId;
				$.post('".root."library/libData.php',{ArticleTextId:articleId},function(data){
					document.articleWordForm.articleText.value = data.word;
				},'json');
			}else{
				document.articleImgForm.articleImg.click();
				document.articleImgForm.artcleImgId.value= $(this).attr('isid');
			}
		});
		//弹出序列号编辑层
		$('[articleEditList]').click(function(){
			$('#articleListEdit').show();
			document.articleListForm.articleListText.value = $(this).attr('articleEditList'); 
			document.articleListForm.artcleListId.value = $(this).attr('isid'); 
		});
	});
	</script>
	";
    return $html;
}

/*
 * 查询物流信息函数
 * $num 快递单号
 * $type 类型 $type = 'status' 输出签收状态
 */
function kdQuery($num,$type=NULL){
    $bodys = "";
    $appcode = para("kdAppCode");  //快递AppCode
    if(empty($num)) {
        $bodys = "暂无该物流信息";
    }else{
        $url = "http://jisukdcx.market.alicloudapi.com/express/query?number={$num}&type=auto";
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$url, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $result = curl_exec($curl);
        $jsonarr = json_decode($result,true);
        $listArr = $jsonarr['result']['list']; //物流信息
        if ($type=="status") {
            $status = $jsonarr['result']['issign']; //物流状态 1 已签收 
            if($status == 1){
                $bodys = "已签收";
            }else {
                $bodys = "未签收";
            }
        }else{
            foreach ($listArr as $key => $val) {
                if ($key==0) {
                    $classStr = " class='first'";
                }else{
                    $classStr="";
                }
                $bodys .= "
                <li{$classStr}>
                    <i class='node-icon'></i>
                    <span class='time'>".$val['time']."</span>
                    <span class='txt'>".$val['status']."</span>
                </li>";
            }
        }
        return $bodys;
    }
}

//将二维数组的子数组的元素设置为数组的键
function setColtoKey($array,$column){
    $newArray = [];
    foreach($array as $key => $val){
        $newArray[$val[$column]] = $val;
    }
    return $newArray;
}

function returnJson($json){
    echo json_encode($json,JSON_UNESCAPED_UNICODE );
    die;
}
function returnJsonText($text){
    $json['warn'] = $text;
    echo json_encode($json,JSON_UNESCAPED_UNICODE );
    die;
}
function newPdo1()
{
    $pdo = new PDO('mysql:host='.$GLOBALS['conf']['ServerName'].';dbname='.$GLOBALS['conf']['DatabaseName'], $GLOBALS['conf']['UserName'], $GLOBALS['conf']['password'] );
    $pdo->query('set names utf8');
    return $pdo;
}
<?php
//商品详细页
include "ku/adfunction.php";
ControlRoot("adGoods");
$sql = "select * from special where isShow = '显示'";
$pdo = newPdo();
$a = $pdo->query($sql);
$select = $a->fetchAll(PDO::FETCH_ASSOC);
if(empty($get['id'])){
    $title = "新建商品";
    $button = "新建";
    $goods['isIndex'] = "是";
    $goods['xian'] = "显示";
    $goods['customMade'] = "否";
    $special = '';
    $special.= "<select name='recommendArea'>";
    $special.= "<option value='0'>无</option>";
    foreach($select as $k=>$v){
        $special.= "<option value={$v['id']}>{$v['specialName']}</option>";
    }
    $special.="</select>";
}else{
    $title = "";
    //$goods = query("goods"," id = '$get[id]' ");
    $pdo = newPdo();
    $sql = "select * from goods as g left join special as s on g.recommendArea = s.spid where g.id = '$get[id]'";
    $a = $pdo->query($sql);
    $goods = $a->fetch(PDO::FETCH_ASSOC);
    $special = '';
    $special.= "<select name='recommendArea'>";
    if($goods['recommendArea']==0){
        $special.= "<option selected='selected' value='0'>无</option>";
        foreach($select as $k=>$v){
            $special.= "<option value={$v['id']}>{$v['specialName']}</option>";
        }
    }else{
        $special.= "<option value='0'>无</option>";
        foreach($select as $k=>$v){
            if($v['spid']==$goods['recommendArea']){
                $special.= "<option selected='selected' value={$v['id']}>{$v['specialName']}</option>";
            }else{
                $special.= "<option value={$v['id']}>{$v['specialName']}</option>";
            }
        }
    }
    $special.="</select>";
    if($goods['id'] != $get['id']){
        $_SESSION['warn'] = "未找到本商品";
        header("location:{$root}control/adGoods.php");
        exit(0);
    }
    //上架权限
    if(power("adGoods","xian")){
        $xianHtml = "<tr>
                        <td>&nbsp;显示状态（商品上下架）：</td>
                        <td colspan='3'>".radio('GoodsShow',array('显示','隐藏'),$goods['xian'])."</td>
                    </tr>";
    }else{
        $xianHtml = "";
    }
    //财务权限（毛利率编辑）
    if(power("adGoods","editProfit")){
        $profitHtml = "
                            <td style='width:120px;'><span class='red'>*</span>定价</td>
                            <td><input name='pricing' style='height: 24px;' type='text' id='pricingOne' value=''/>&nbsp;元</td>
                        <tr>
                            <td style='width:120px;'><span class='red'>对应毛利率：</span></td>
                            <td><input name='grossProfit' style='height: 24px;' type='text' id='grossProfitOne' value=''/>%</td>
                            <td style='width:120px;'><span class='red'>拨比毛利率：</span></td>
                            <td colspan='3'><input style='height: 24px;' name='corresponding' type='text' id='correspondingOne'value=''/>%</td>
                        </tr>";
    }else{
        $profitHtml = "";
    }
    //列表图像
    $goodsIco = "
  <tr>
    <td>商品列表图像：</td>
    <td>
<!--缩略图展示-->    
  ".ProveImgShow($goods['ico'])."
    <span onclick='document.GoodsIcoForm.GoodsIcoUpload.click();' class='spanButton'>更新</span>
    <span class='smallword'></span>
    </td>
  </tr>
  ";
    //橱窗图片
    $GoodsWin = "
  <tr>
    <td>商品橱窗图：</td>
    <td>
  ";
    $GoodsWinSql = mysql_query(" select * from goodsWin where goodsId = '$goods[id]' order by time desc ");
    $GoodsWinNum = mysql_num_rows($GoodsWinSql);
    if($GoodsWinNum == 0){
        $GoodsWin .= "一张图片都没有";
    }else{
        while($array = mysql_fetch_array($GoodsWinSql)){
            $GoodsWin .= "
                <div class='img_div'>
                    <div class='imgClose' delImg='{$array['id']}'>×</div> 
                    <a class='GoodsWin' target='_blank' href='{$root}{$array['src']}'> <img src='{$root}{$array['src']}'></a>
                    </div>
                <div>";
        }
    }
    if($GoodsWinNum < 100){
        $GoodsWin .= "<span onclick=\"$('#GoodsWinUpload').click();\" class='spanButton'>新增</span>";
    }
    //商品规格
    $goodsKu = "";
    $RuleSql = mysql_query(" select * from goodsSku where goodsId = '$goods[id]' order by updateTime desc ");
    if(mysql_num_rows($RuleSql) == 0){
        $goodsKuInfo .= "<tr><td colspan='20'>一条记录都没有</td></tr>";
    }else{
        while($Rule = mysql_fetch_array($RuleSql)){
            if($Rule['defaultData'] == "默认"){
                $defaultBn = "";
                $dclass = "red";
            }else{
                $defaultBn = "<span defaultData='{$Rule['id']}' class='spanButton'>设置</span>";
                $dclass = "";

            }
            $goodsKuInfo .="
      <tr class='$dclass'>
        <td><span EditRule='{$Rule['id']}' class='spanButton'>编辑</span></td>
        <td>{$Rule['name']}</td>
         <td>{$Rule['twoName']}</td>
        <td>{$Rule['skuNum']}</td>
        <td>{$Rule['type']}</td>
        <td>{$Rule['salesVolume']}</td>
        <td>{$Rule['price']}</td>
        <td>{$Rule['retailPrice']}</td>
        <td>{$Rule['cost']}</td>
        <td>{$Rule['free']}</td>
        <td>{$Rule['shippingFree']}</td>
        <td>{$Rule['thePatch']}</td>
        <td>{$Rule['endPatch']}</td>
        <td>{$Rule['profit']}</td>
        <td>{$Rule['number']}{$Rule['skuUnit']}</td>
        <td>{$Rule['integral']}</td>
        <td>{$Rule['shippingPlace']}</td> 
        <td>{$Rule['skuSeat']}</td>
        <td>{$Rule['factory']}</td>
        <td>{$Rule['clerk']}</td>
        <td>".ProveImgShow($Rule['img'],'暂无图片')."</td>
        <td>{$Rule['updateTime']}</td>
        <td>
            <span updateSkuImg='{$Rule['id']}' class='spanButton' name='skuImgUpload'>更新</span><br>
            {$defaultBn}
            <br>
            <span value='{$Rule['id']}' name='deleteSpec' class='spanButton'>删除</span>
        </td>
      </tr>
      ";
        }
    }
    $goodsKu .="
  <form>
  <table class='tableMany'>
        <tr>
              <td><span EditRule='' class='spanButton' >添加</span></td>
              <td>规格名称</td>
              <td>二级规格名称</td>
              <td>货号</td>
              <td>规格类型</td>
              <td>单品销量</td>
              <td>零售价</td>
              <td>批发价</td>
              <td>成本费</td>
              <td>手续费</td>
              <td>运费</td>
              <td>起批量</td>
              <td>截止起批量</td>
              <td>利润</td>
              <td>库存</td>
              <td>所需兑换积分</td>
              <td>发货地</td>
              <td>货位信息</td>
              <td>厂家信息</td>
              <td>采货员</td>
              <td>规格图片</td>
              <td>更新时间</td>
              <td>操作</td>
        </tr>
    {$goodsKuInfo}
  </table>
    </form>
  ";
    //评论条数
    $talkfenye ="";
//商品评论详情
    $talkMx = "";
    $sql = "select * from talk WHERE targetId='$get[id]'";
    paging($sql," order by time desc",5);
    $talkfenye .=" <span class='smallWord floatRight'>
			共找到{$num}条评论&nbsp;&nbsp;
            第{$page}页/
            共{$AllPage}页
		</span>";
    if($num > 0){
        while ($tval = mysql_fetch_assoc($query)){
            $kehuData = query("kehu","khid='$tval[khid]'");
            $talkMx .="<tr>
                            <td>{$tval['khid']}</td>
                            <td>{$kehuData['name']}</td>
                            <td>{$kehuData['wxNickName']}</td>
                            <td>{$tval['word']}</td>
                            <td>{$tval['grade']}</td>
                            <td>{$tval['xian']}</td>
                            <td>{$tval['list']}</td>
                            <td>{$tval['time']}</td>
                            <td><span talkid='{$tval['id']}' class='spanButton'>编辑</span></td>
                        </tr>";
        }
    }else{
        $talkMx .="<tr><td colspan='9'>暂无此商品评论</td></tr>";
    }
    $goodsTalk .="
                 <form>
                      <table class='tableMany'>
                            <tr>
                                  <td style='width: 85px;'>购买人ID</td>
                                  <td style='width: 70px;'>购买人姓名</td>
                                  <td style='width: 56px;'>微信昵称</td>
                                  <td>评论内容</td>
                                  <td>评分</td>
                                  <td>状态</td>
                                  <td>序号</td>
                                  <td style='min-width: 142px;'>创建时间</td>
                                  <td>操作</td>
                            </tr>
                                  {$talkMx}
                      </table>
                 </form>";
    //关联素材
    $sql = "SELECT * FROM article WHERE targetId = '$goods[id]' AND target='宣传素材'";
    $acontentMx = "";
    $sres = mysql_query($sql);
    $snum = mysql_num_rows($sres);
    if($snum > 0){
        while ($aval = mysql_fetch_assoc($sres)){
            $acontentMx .="<tr>
                            <td><span articRule='{$aval['id']}' class='spanButton'>编辑</span></td>
                            <td>{$aval['id']}</td>
                            <td><a target='_blank' title='点击查看大图' href='{$root}{$aval['img']}'>".ProveImgShow($aval['img'])."</a></td>
                            <td>{$aval['word']}</td>
                            <td>{$aval['list']}</td>
                            <td>{$aval['updateTime']}</td>
                            <td>{$aval['time']}</td>
                            <td><span articDele='{$aval['id']}' class='spanButton'>删除</span></td>
                        </tr>";
        }
    }else{
        $acontentMx .="<tr><td colspan='9'>还未添加任何素材</td></tr>";
    }
    $acontent .=" <form>
                      <table class='tableMany'>
                           <tr>
                               <td><span articRule='' class='spanButton'>添加素材</span></td>
                               <td>素材编号</td>
                               <td>素材图片</td>
                               <td>素材文字</td>
                               <td>序号</td>
                               <td>更新时间</td>
                               <td>创建时间</td>
                               <td>操作</td>
                           </tr>
                                  {$acontentMx}
                      </table>
                 </form>";
    //其他参数
    $title = $goods['name'];
    $button = "更新";
    //商品详情图
    $article = "<div class='kuang smallword'>产品详情的图片超过950px时会被压缩</div>
  ".article("商品明细",$goods['id'],$goods['goodsTypeOneId'],950);
    //商品宣传素材
//    $Material = "<div class='kuang smallword'>商品素材图超过200就会被压缩</div>
//  ".myarticle("宣传素材",$goods['id'],$goods['goodsTypeOneId'],200);
    //视频窗口
    $videoUrl = "";
    //视频封面
    $posterHtnl = "";
    if (!empty($goods['videoUrl'])) {
        $videoUrl = "<div  class='kuang'>
<embed src='{$goods['videoUrl']}' allowFullScreen='true' quality='high' width='790' height='600' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash'></embed>
</div>";
    }else{
        $videoUrl .=" <div class='kuang'>暂无商品视频展示</div>";
    }
    if(empty($goods['poster'])){
        $posterHtnl .= "<span class='spanButton' onclick='document.posterForm.GoodsPosterUpload.click();'>添加视频封面</span>";
    }else{
        $posterHtnl .= "<a target='_blank' title='点击查看大图' href='{$root}{$goods['poster']}'><img style='width:150px;' src='{$root}{$goods[poster]}'/></a><span class='spanButton' onclick='document.posterForm.GoodsPosterUpload.click();'>更新视频封面</span>";
    }

}
//推荐专区
/*$tuijian = query("para","paid='recommendArea'");
$tz = $tuijian['paValue'];
if(strstr($tz,'、') !==false){
    $recommendArea = explode("、",$tz);
}else{
    $recommendArea = $tz;
}
foreach ($recommendArea as $val){
    if(!empty($val)){
        $tuiHtml .= "<option value='$val'>$val</option>";
    }
}*/
$onion = array(
    "商品管理" => root."control/adGoods.php",
    $title => $ThisUrl
);
echo head("ad").adheader($onion);
?>
    <div class="minHeight">
        <!--商品资料开始-->
        <div class="kuang">
            <img src="<?php echo root."img/images/text.png";?>">
            <form name="GoodsForm" >
                <table class="tableRight">
                    <tr>
                        <td><span class="red">*</span>&nbsp;商品ID：</td>
                        <td>
                            <?php echo kong($goods['id']);?>
                            <a target="_blank" href="<?php echo root."m/mGoodsMx.php?gid=".$goods['id'];?>"><span class="spanButton FloatRight">预览商品</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;商品名称：</td>
                        <td>
                            <input name="goodsName" type="text" class="text" value="<?php echo $goods['name'];?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;商品所属专题：</td>
                        <td>
                            <?php echo $special;?>
                            <!-- <select name="recommendArea">
                                <?php if($goods['recommendArea']==0){?>
                                    <option value="0" selected="selected">无</option>
                                    <?php foreach($select as $k=>$v){?>
                                    <option value=<?php echo $v['id']?>><?php echo $v['specialName']?></option>
                                <?php }?>
                                <?php }else{?>
                                <option value="0">无</option>
                                <?php foreach($select as $k=>$v){?>
                                    <option <?php 
                                        if($v['id']==$goods['recommendArea']){
                                            echo "selected";
                                        }
                                    ?> value=<?php echo $v['id']?>><?php echo $v['specialName']?></option>
                                <?php }?>
                                <?php }?>
                            </select> -->
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;分类</td>
                        <td>
                            <?php echo IDSelect("goodsOne order by list ","goodsOneId","select","id","name","--商品分类--",$goods['goodsOneId']);?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>摘要：</td>
                        <td>
                            <textarea name="summary" class="textarea"  ><?php echo $goods['summary'];?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>促销信息：</td>
                        <td>
                            <input type="text" style="width: 601px;" name="promotion" class="text TextPrice" value="<?php echo $goods['promotion'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>商品详细参数：</td>
                        <td>
                            <textarea name="parameter" class="textarea" style="height: 160px;"><?php echo $goods['parameter'];?></textarea>
                        </td>
                    </tr>
                    <!--                    <tr>-->
                    <!--                        <td><span class="red">*</span>&nbsp;商品零售价：</td>-->
                    <!--                        <td><input name="price" type="text" value="--><?php //echo $goods['price'];?><!--">&nbsp;&nbsp;元</td>-->
                    <!--                    </tr>-->
                    <!--                    <tr>-->
                    <!--                        <td><span class="red">*</span>&nbsp;商品批发价：</td>-->
                    <!--                        <td><input name="priceMarket" type="text" value="--><?php //echo $goods['priceMarket'];?><!--">&nbsp;&nbsp;元</td>-->
                    <!--                    </tr>-->
                    <tr>
                        <td>&nbsp;发票税点：</td>
                        <td><input name="taxPoint" type="text" value="<?php echo $goods['taxPoint'];?>">&nbsp;&nbsp;%</td>
                    </tr>
                    <?php echo $goodsIco.$GoodsWin;?>

                    <tr>
                        <td>&nbsp;商品视频展示地址：</td>
                        <td>
                            <textarea name="videoAddress" style="width: 400px;height: 100px;" value="" id="videoAddress"/><?php echo $goods['videoUrl'];?></textarea>
                            &nbsp;&nbsp;<input type="button" id="videButton" value="<?php if(!empty($goods['videoUrl'])){echo '修改视频地址';}else{echo '添加视频地址';}?>" class="button"/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;视频封面：</td>
                        <td><?php echo $posterHtnl;?></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>&nbsp;排序号：</td>
                        <td><input name="GoodsList" type="text" class="text TextPrice" value="<?php echo $goods['list'];?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;首页推荐：</td>
                        <td><?php echo radio("isIndex",array("否","是"),$goods['isIndex']);?></td>
                    </tr>
                    <?php echo $xianHtml;?>
                    <tr>
                        <td>&nbsp;是否支持定制：</td>
                        <td><?php echo radio("customMade",array("否","是"),$goods['customMade']);?></td>
                    </tr>
                    <tr>
                        <td>更新时间：</td>
                        <td><?php echo kong($goods['updateTime']);?></td>
                    </tr>
                    <tr>
                        <td>创建时间：</td>
                        <td><?php echo kong($goods['time']);?></td>
                    </tr>
                    <tr>
                        <td><input name="goodsid" type="hidden" value="<?php echo $goods['id'];?>"></td>
                        <td><input type="button" class="button" value="<?php echo $button;?>" onclick="Sub('GoodsForm','<?php echo root;?>control/ku/addata.php?type=upGoods')"></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php echo $goodsKu.$acontent.$talkfenye.$goodsTalk.fenye($ThisUrl,7).$article.$videoUrl;?>
        <!--规格弹出编辑层开始-->
        <div class="hide" id="adGoodsRule">
            <div class="dibian"></div>
            <div class="win" style="width: auto!important; height:auto!important; margin: -220px 0px 0px -300px;">
                <p class="winTitle">编辑商品规格<span onclick="$('#adGoodsRule').hide()" class="winClose">×</span></p>
                <form name="SpecForm">
                    <div style="height:400px;    overflow-y: scroll;">
                        <table class="tableRight">
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>规格名称：</td>
                                <td colspan="3"><input name="specName" type="text" class="text" value="" id="specName"></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>二级规格名称：</td>
                                <td colspan="3"><input name="twoName" type="text" class="text TextPrice" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>货号：</td>
                                <td colspan="3"><input name="skuNum" type="text" class="text TextPrice" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>规格类型：</td>
                                <td><?php echo radio("typeprice",array("定制","分类价格"),$Rule['type']);?></td>
                                <td style="width:120px;"><span class="red">*</span>规格重量：</td>
                                <td><input name="weight" style="height: 24px;" type="text" value=""/>&nbsp;Kg</td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>零售价：</td>
                                <td><input name="price" style="height: 24px;" type="text" value=""/>&nbsp;元</td>
                                <td style="width:120px;"><span class="red">*</span>批发价：</td>
                                <td><input name="retailPrice" style="height: 24px;" type="text" value=""/>&nbsp;元</td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>成本费：</td>
                                <td><input name="cost" style="height: 24px;" type="text" value=""/>&nbsp;元</td>
                                <td style="width:120px;"><span class="red">*</span>手续费：</td>
                                <td><input name="free" style="height: 24px;" type="text" value=""/>&nbsp;元</td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>运费：</td>
                                <td><input name="shippingFree" style="height: 24px;" type="text" value=""/>&nbsp;元</td>
                                <!--毛利率展示开始-->
                                <?php echo $profitHtml;?>
                                <!--定价-->
                                <input name="pricing" type="hidden" id="pricing" value=""/>
                                <!--对应毛利率-->
                                <input name="grossProfit" type="hidden" id="grossProfit" value=""/>
                                <!--拨比毛利率-->
                                <input name="corresponding" type="hidden" id="corresponding" value=""/>
                                <!--毛利率展示结束-->
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>起批量：</td>
                                <td><input name="thePatch" style="height: 24px;" type="text" value=""/></td>
                                <td style="width:120px;"><span class="red">*</span>截止起批量：</td>
                                <td><input name="endPatch" style="height: 24px;" type="text" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>利润：</td>
                                <td><input name="profit" style="height: 24px;" type="text" value=""/></td>
                                <td style="width:120px;"><span class="red">*</span>库存：</td>
                                <td><input name="number" style="height: 24px;" type="text" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>数量单位：</td>
                                <td colspan="3"><input name="skuUnit" style="height: 24px;" type="text" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>发货信息：</td>
                                <td><input name="shippingPlace" style="height: 24px;" type="text" value=""/></td>
                                <td style="width:120px;">所需兑换积分：</td>
                                <td><input name="integral" style="height: 24px;" type="text" value=""/></td>
                            </tr>
                            <tr>
                                <td style="width:120px;"><span class="red">*</span>发货地：</td>
                                <td><input name="skuSeat" style="height: 24px;" type="text" value=""/></td>
                                <td style="width:120px;">厂家信息：</td>
                                <td><input name="factory" style="height: 24px;" type="text" value=""/></td>
                            </tr>
                            <tr id="imputedRes"></tr>
                            <tr id="imputedMx"></tr>
                            <tr>
                                <td style="width:120px;">
                                    <input name="specId" type="hidden"/>
                                    <input name="GoodsId" type="hidden" value="<?php echo $get['id'];?>">
                                </td>
                                <td><input type="button" id="imputedPrice" class="button" value="提交计算"></td>
                                <td colspan="3"><input type="button" class="button" onclick="Sub('SpecForm','<?php echo root."control/ku/addata.php?type=updateSku";?>')" value="确认提交"></td>

                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <!--规格弹出编辑层结束-->
        <!--评论编辑层开始-->
        <div class="hide" id="talkRule">
            <div class="dibian"></div>
            <div class="win" style="width: 600px; height:auto!important; margin: -174px 0px 0px -300px;">
                <p class="winTitle">编辑评论<span onclick="$('#talkRule').hide()" class="winClose">×</span></p>
                <form name="talkForm">
                    <table class="tableRight">
                        <tr>
                            <td>评分：</td>
                            <td><input name="grade" type="text" class="text" value=""/></td>
                        </tr>
                        <tr>
                            <td>评论内容：</td>
                            <td><textarea name="word" style="width:410px; height: 80px;"></textarea></td>
                        </tr>
                        <tr>
                            <td>评论图片</td>
                            <td id="talikImg"></td>
                        </tr>
                        <tr>
                            <td>显示状态：</td>
                            <td><?php echo radio("xian",array("显示","隐藏"),$goods['xian']);?></td>
                        </tr>
                        <tr>
                            <td>
                                <input name="goodsid" type="hidden" value="<?php echo $get['id']?>"/>
                                <input name="talkId" type="hidden"/>
                            </td>
                            <td><input type="button" class="button" onclick="Sub('talkForm','<?php echo root."control/ku/addata.php?type=updateTalk";?>')" value="确认提交"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <!--评论编辑层结束-->
        <!--素材编辑层开始-->
        <div class="hide" id="articleRule">
            <div class="dibian"></div>
            <div class="win" style="width: 600px; height:auto!important; margin: -174px 0px 0px -300px;">
                <p class="winTitle">编辑素材<span onclick="$('#articleRule').hide()" class="winClose">×</span></p>
                <form name="articleForm">
                    <table class="tableRight">
                        <tr>
                            <td>宣传文字：</td>
                            <td><textarea name="articword" style="width:410px; height: 80px;"></textarea></td>
                        </tr>
                        <tr>
                            <td>宣传图片</td>
                            <td id="articleImg" class="relative">
                                <span class="goodsImg"></span>
                                <div  class="img_list"></div>
                                <input name="img"  class="file_input" type="file" multiple="multiple" style="width: 64px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="relative"></td>
                            <td>
                                <span class="goodsImg"></span>
                                <div id="img_area" class="img_list"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>排序：</td>
                            <td><input name="alist" type="text TextPrice" class="text" value=""/></td>
                        </tr>
                        <tr>
                            <td>
                                <input name="articGoodsid" type="hidden" value="<?php echo $get['id']?>"/>
                                <input name="articleId" type="hidden"/>
                            </td>
                            <td><input type="button" class="button" onclick="Sub('articleForm','<?php echo root."control/ku/addata.php?type=updateMaterial";?>')" value="确认提交"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <!--评论编辑层结束-->
        <!--隐藏域开始-->
        <div class="hide">
            <form name="GoodsIcoForm" action="<?php echo root."control/ku/adpost.php?type=goodsIco";?>" method="post" enctype="multipart/form-data">
                <input name="GoodsIcoUpload" type="file" onchange="document.GoodsIcoForm.submit();">
                <input name="GoodsId" type="hidden" value="<?php echo $goods['id'];?>">
            </form>
            <form name="GoodsWinForm" action="<?php echo root."control/ku/adpost.php?type=goodsWin";?>" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
                <input name="GoodsWinUpload[]" id="GoodsWinUpload" type="file" multiple="multiple" onchange="$('[name=GoodsWinForm]').submit();">
                <input name="GoodsId" type="hidden" value="<?php echo $goods['id'];?>">
            </form>
            <form name="GoodsMaterialForm" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
                <input name="img" class="file_input" type="file" multiple="multiple" id="avc1"/>
                <input type="text" name="img">
                <input name="GoodsId" type="hidden" value="<?php echo $goods['id'];?>">
            </form>
            <!--视频封面添加-->
            <form name="posterForm" action="<?php echo root."control/ku/adpost.php?type=goodsPoster";?>" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
                <input name="GoodsPosterUpload" type="file" onchange="document.posterForm.submit();">
                <input name="GoodsId" type="hidden" value="<?php echo $goods['id'];?>">
            </form>
            <!--规格图片添加-->
            <form name="skuForm" action="<?php echo root."control/ku/adpost.php?type=goodsSku";?>" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
                <input name="sukUpload" type="file" onchange="document.skuForm.submit();">
                <input name="sukId" type="hidden" value="<?php echo $goods['id'];?>">
            </form>
        </div>
    </div>
    <!--隐藏域结束-->
    <script>
        //橱窗图点击
        /* function GoodsWinUp() {
            $("#GoodsWinUpload").click();
            if($("#GoodsWinUpload").change()){
                console.log(111);
                //$("[name=GoodsWinForm]").submit();
            }
        } */
        $("[name='skuImgUpload']").on('click',function(){
            var skuId = $(this).attr('updateSkuImg');
            console.log(skuId);
            $("[name='sukId']").val( skuId );
            $("[name='sukUpload']").click();
        })
        $(function(){
            //提交计算
            $("#imputedPrice").click(function(){
                var pricing         = $("input[name=pricing]").val();//定价
                var cost            = $("input[name=cost]").val();//成本
                var free            = $("input[name=free]").val();//手续费
                var shippingFree   = $("input[name=shippingFree]").val();//运费
                //recommendFree 推荐佣金
                //selfIntegral 自购积分
                //teamFree 团队业绩
                //purchaseFree 采购提成
                $.post(root+"control/ku/addata.php?type=imputedPrice",{pricing:pricing,cost:cost,free:free,shippingFree:shippingFree},function(data){
                    warn(data.warn);
                    if(data.warn == "计算成功"){
                        var resHtml = "<td style='width:120px;'><span class='red'>推荐佣金：*</span></td><td><input style='height: 24px;' type='text' value="+data.recommendFree+"></td><td style='width:120px;'><span class='red'>自购积分：</span></td><td><input style='height: 24px;' type='text' value="+data.selfIntegral+"></td>";
                        var MxHtml = "<td style='width:120px;'><span class='red'>团队业绩：</span></td><td><input style='height: 24px;' type='text' value="+data.teamFree+"></td><td style='width:120px;'><span class='red'>采购提成：</span></td><td><input style='height: 24px;' type='text' value="+data.purchaseFree+"></td>";
                        $("#imputedRes").html(resHtml);
                        $("#imputedMx").html(MxHtml);
                        $("input[name=grossProfit]").val(data.rate);
                        $("input[name=corresponding]").val(data.parRate);
                    }
                },"json");
            });
            //利率变化赋值
            $("#pricingOne").change(function () {
                $("#pricing").val($(this).val());
            });
            $("#grossProfitOne").change(function () {
                $("#grossProfit").val($(this).val());
            });
            $("#correspondingOne").change(function () {
                $("#corresponding").val($(this).val());
            });
            //删除图片
            $("[delImg]").click(function(){
                //删除图片
                var imgId = $(this).attr("delImg");
                warn("确定删除该图片！");
                $("#warnSure").click(function(){
                    var content= $(this).html();
                    if(content == "确定"){
                        $.post(root+"control/ku/addata.php?type=DeleteImg",{imId:imgId},function (data) {
                            warn(data.warn);
                            $("#warnSure,#warnCancel,.winClose").click(function(){
                                window.location.reload();
                            });
                        },"json")
                    }else{
                        $("#warn").hide();
                    }
                });
            });
            //查询一级分类
            var GoodsForm = document.GoodsForm;
            GoodsForm.goodsOneId.onchange = function(){
                $.post(root+"control/ku/addata.php?type=queryOne",{goodsTypeOneIdGetTwoId:this.value},function(data){
                    GoodsForm.goodsTypeTwoId.innerHTML = data.two;
                },"json");
            };
            //删除商品规格
            $("[name=deleteSpec]").click(function(){
                $.post(root+"control/ku/addata.php?type=deleteSpecId",{deleteSpecId:$(this).attr("value")},function(data){
                    if(data.warn == "2"){
                        location.reload();
                    }else{
                        warn(data.warn);
                    }
                },"json");
            });
            //显示规格弹出层
            $("[EditRule]").click(function(){
                var editId = $(this).attr("editrule");
                alert(editId);
                $.post(root+"control/ku/addata.php?type=updateSkuone",{skuId:editId},function(data){
                    $("input[name=specName]").val(data.warn['name']);//规格名称
                    $("input[name=twoName]").val(data.warn['twoName']);//二级规格名称
                    $("input[name=skuNum]").val(data.warn['skuNum']);//货号
                    $("input[name=price]").val(data.warn['price']);//零售价
                    $("input[name=retailPrice]").val(data.warn['retailPrice']);//批发价
                    $("input[name=thePatch]").val(data.warn['thePatch']);//起批量
                    $("input[name=endPatch]").val(data.warn['endPatch']);//截止起批量
                    $("input[name=profit]").val(data.warn['profit']);//利润
                    $("input[name=number]").val(data.warn['number']);//库存
                    $("input[name=shippingPlace]").val(data.warn['shippingPlace']);//发货信息
                    $("input[name=integral]").val(data.warn['integral']);//所需兑换积分
                    $("input[name=skuSeat]").val(data.warn['skuSeat']);//发货地
                    $("input[name=factory]").val(data.warn['factory']);//厂家信息
                    $("input[name=cost]").val(data.warn['cost']);//成本费
                    $("input[name=free]").val(data.warn['free']);//手续费
                    $("input[name=shippingFree]").val(data.warn['shippingFree']);//运费
                    $("input[name=pricing]").val(data.warn['pricing']);//定价
                    $("input[name=grossProfit]").val(data.warn['grossProfit']);//对应毛利率
                    $("input[name=corresponding]").val(data.warn['corresponding']);//拨比毛利率
                    $("input[name=weight]").val(data.warn['weight']);//商品规格重量
                    $("input[name=skuUnit]").val(data.warn['skuUnit']);//规格单位
                    if(data.warn['type'] != null){
                        $('[name=typeprice][value='+data.warn['type']+']')[0].checked=true;
                    }
                },"json");
                $("#adGoodsRule").show();
                $("input[name=specId]").val(editId);
            });
            $("#videButton").click(function(){
                var goodsid=$("input[name=GoodsId]").val();
                var videoAddress=$("#videoAddress").val();
                $.post(root+"control/ku/addata.php?type=goodsVideo",{goodsId:goodsid,videoAddress:videoAddress},function(data){
                    if(data.warn == "2"){
                        location.reload(data.href);
                    }else{
                        warn(data.warn);
                    }
                },"json");
            });
            //弹出评论编辑
            $("[talkid]").click(function(){
                var talkid = $(this).attr("talkid");
                $.post(root+"control/ku/addata.php?type=talkmx",{id:talkid},function(data){
                    $("textarea[name=word]").html(data.warn['word']);//内容
                    $("input[name=grade]").val(data.warn['grade']);//评分
                    $("radio[name=xian]").val(data.warn['xian']);//评分
                    $("#talikImg").html(data.talkimg);
                },"json");
                $("#talkRule").show();
                $("input[name=talkId]").val(talkid);
            });
            //弹出素材编辑层
            $("[articRule]").click(function(){
                var articleId = $(this).attr("articRule");
                $.post(root+"control/ku/addata.php?type=articleMx",{id:articleId},function(data){
                    $("textarea[name=articword]").html(data.warn['aword']);//内容
                    $("#img_area").html(data.aimg);//图片
                    $("input[name=alist]").val(data.warn['alist']);//序号
                    $("input[name=articleId]").val(data.warn['id']);//序号
                },"json")
                $("#articleRule").show();
            });
            //删除宣传素材
            $("[articDele]").click(function(){
                var arId = $(this).attr("articDele");
                warn("确定删除该素材！");
                $("#warnSure,#warnCancel").click(function(){
                    var content= $(this).html();
                    if(content == "确定"){
                        $.post(root+"control/ku/addata.php?type=DeleteGoodsArticle",{arId:arId},function (data) {
                            warn(data.warn);
                            $("#warnSure,#warnCancel,.winClose").click(function(){
                                window.location.reload();
                            });
                        },"json")
                    }else{
                        $("#warn").hide();
                    }
                });
            });
            //设置默认规格
            $("[defaultData]").click(function(){
                var sid = $(this).attr("defaultData");
                $.post(root+"control/ku/addata.php?type=defaultData",{sid:sid},function(data){
                    warn(data.warn);
                    $("#warnSure,#warnCancel,.winClose").click(function(){
                        window.location.reload();
                    });
                },"json")
            });
            //宣传素材图片添加
            //    触发事件用的是change，因为files是数组，需要添加下标
            var file_input=document.getElementsByClassName("file_input")[0];
            $("#imgbut").onclick = function() {
                file_input.click();
            };
            //    触发事件用的是change，因为files是数组，需要添加下标
            file_input.addEventListener("change",function(){
                var obj=this;
                var obj_name=this.files[0].name;
                var img_length=obj.files.length;
                for(var i=0;i<img_length;i++)
                {
                    if(!(/image\/\w+/).test(obj.files[i].type))
                    {
                        alert("上传的图片格式错误，请上传图片");
                        return false;
                    }
                    var reader = new FileReader();
                    reader.error=function(e){
                        alert("读取异常")
                    }
                    reader.onload = function(e){
                        //div_html是包括图片和图片名称的容器
                        var img_html=' <div class="imgClose" onclick="">&times;</div>'+
                            '<img style="width: 120px;" src="'+e.target.result+'"/>' +
                            '<textarea class="hide" id="img64" name="imgSet[]">'+e.target.result+'</textarea>';
                        var div_html=document.createElement("div");
                        var p_html=document.createElement("p");
                        div_html.innerHTML=img_html;
                        div_html.className="img_div";
                        document.getElementsByClassName("img_list")[0].appendChild(div_html);
                        $(".imgClose").click(function(){
                            $(this).parents(".img_div").remove();
                        });
                    };
                    reader.onloadstart=function(){
                        console.log("开始读取"+obj_name);
                    }
                    reader.onprogress=function(e){
                        if(e.lengthComputable){
                            console.log("正在读取文件")
                        }
                    };
                    reader.readAsDataURL(obj.files[i]);

                }
            });
        });
    </script>
<?php echo PasWarn(root."control/ku/data.php").warn().adfooter(); ?>
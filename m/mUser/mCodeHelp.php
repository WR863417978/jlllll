<?php
include "../../library/mFunction.php";
echo head('m');
$data = findOne('codeExplain',"khid = '{$_SESSION['khid']}' AND status = '待审核' ORDER BY time DESC LIMIT 1");
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root; ?>m/mUser/mInfoCode.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">申诉</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-编辑信息-申诉-->
<div class="container">
    <div class="mui-pt45 mui-mbottom60">
        <form name="userCode">
        <dl class="header-search">
            <dd><p>请填写申诉信息</p></dd>
            <dt class="mui-dis-flex"><i>默认推荐码</i><input type="search" name='defaultCode' class="header-stext" value="<?php echo $kehu['shareId'];?>" readonly='readonly' maxlength="15" placeholder="0125522"></dt>
            <dt class="mui-dis-flex"><i>更改推荐码</i><input type="search" name='changeCode' class="header-stext" value="<?php echo $kehu['shareId'];?>" maxlength="15" placeholder="222222"></dt>
            <dt class="mui-dis-flex"><i>邀请人姓名</i><input type="search" name='shareName' class="header-stext" value="<?php echo $data['shareName'];?>" maxlength="15" placeholder="请填写更改人真实姓名"></dt>
            <dt class="mui-dis-flex"><i>邀请人手机</i><input type="search" name='shareTel' class="header-stext" value="<?php echo $data['shareTel'];?>" maxlength="15" placeholder="请填写聚礼预留手机"></dt>
            <dt class="mui-dis-flex"><i>申请人姓名</i><input type="search" name='explainName' class="header-stext" value="<?php echo $data['explainName'];?>" maxlength="15" placeholder="请填写申诉人真实姓名"></dt>
            <dt class="mui-dis-flex"><i>申请人手机</i><input type="search" name='explainTel' class="header-stext" value="<?php echo $data['explainTel'];?>" maxlength="15" placeholder="请填写申诉人真实姓名"></dt>
            <dd class="invite">
                <h4>说明</h4>
                <ul>
                    <li>3-5个工作日反馈</li>
                </ul>
            </dd>
        </dl>
        <input type="hidden" name="kehuId" value='<?php echo $_SESSION['khid'];?>'>
        </form>
        <input type="button" class="addPassenger_btn" value="提 交"/>
    </div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn(); ?>
<!--//-->
<script>
$(function(){
    changeNav();
    $('.addPassenger_btn').click(function(){
        $.post(root+"library/mData.php?type=editUserCode",$("[name='userCode']").serialize(),function(data){
            if(data.warn == 2){
                mwarn('提交成功');
            }else{
                mwarn(data.warn);
            }
        },'json'); 
    });
})
</script>
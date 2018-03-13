<?php
include "../../library/mFunction.php";
echo head('m');
$shopImgHtml = '';
if(!empty($kehu['shopImg']))
{
    $shopImgHtml = "<img onclick=\"$('[name=UserShopImgUpload]').click();\" src=\"".root."{$kehu['shopImg']}\"/>";
}else{
    $shopImgHtml = "<img onclick=\"$('[name=UserShopImgUpload]').click();\" src='".img('wOZ84129241GJ')."'/>";
}
$info = findOne('address',"id = '{$kehu['address']}'");
$address = Region($info['regionId']);
?>
<!--头部-->
<div class="header header-fixed">
    <div class="nesting"> <a href="<?php echo root; ?>m/mUser/mUser.php" class="header-btn header-return"><span class="return-ico">&#xe614;</span></a>
        <div class="align-content">
            <p class="align-text">会员信息</p>
        </div>
        <a href="#" class="header-btn"></a>
    </div>
</div>
<!--//-->
<!--会员中心-->
<div class="container">
    <div class="user mui-pt45 mui-mbottom60">
        <div class="edit-top">
            <h3>编辑个人信息</h3>
            <label>请填写真实信息哟</label>
        </div>
        <form name="UserInfo">
        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">店铺形象</span>
                    <label>
                        <?php echo $shopImgHtml;?>
                        <span class="more">&#xe62e;</span>
                    </label>
                </a>
            </li>
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">店铺名称</span>
                    <input class='no-border' type="text" placeholder="请输入店铺名称" name="userShopName" value='<?php echo $kehu['shopName'];?>'>
                </a>
            </li>
            <li>
                <a href="<?php echo root; ?>m/mUser/mInfoCode.php" class="mui-dis-flex">
                    <span class="flex1">邀请码</span>
                    <label><i><?php echo kong($kehu['shareId']);?></i><span class="more">&#xe62e;</span></label>
                </a>
            </li>
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">真实姓名</span>
                    <input class='no-border' type="text" placeholder="请输入真实姓名"   name="userName" value='<?php echo $kehu['name'];?>'>
                </a>
            </li>
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">性别</span>
                    <div class="choice">
                        <label class="sex"><em>男</em><input type="radio" name="sex" value="男" <?php echo $kehu['wxSex'] == '男' ? 'checked':'';?>><i></i></label>
                        <label class="sex"><em>女</em><input type="radio" name="sex" value="女" <?php echo $kehu['wxSex'] == '女' ? 'checked':'';?>>><i></i></label>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">身份证号</span>
                    <input class='no-border' type="text" placeholder="请正确输入身份证号" name="userCardNum" value='<?php echo $kehu['IdCard'];?>'>
                </a>
            </li>
            <li>
                <a href="<?php echo root;?>m/mUser/mCard.php" class="mui-dis-flex">
                    <span class="flex1">添加银行卡</span>
                    <label><?php echo $kehu['bankName'] ? '已添加' : '未添加' ;?></label>
                </a>
            </li>
            <li>
                <a href="<?php echo root; ?>m/mUser/mAddress.php" class="mui-dis-flex">
                    <span class="flex1">通讯地址</span>
                    <label><i><?php echo $address;?></i><span class="more">&#xe62e;</span></label>
                </a>
            </li>
        </ul>
        <ul class="mui-mtop10 user-wrap-style1">
            <li>
                <a href="<?php echo root; ?>m/mUser/mPhone.php" class="mui-dis-flex">
                    <span class="flex1">绑定手机</span>
                    <label><i><?php echo kong($kehu['tel']);?></i><span class="more">&#xe62e;</span></label>
                </a>
            </li>
            <li>
                <a href="#" class="mui-dis-flex">
                    <span class="flex1">电子邮件</span>
                    <input class='no-border' name='userEmail' type="text" placeholder="请填写邮箱地址" value='<?php echo $kehu['email'];?>'/>
                </a>
            </li>
        </ul>
        <input type="hidden" name="userId" value='<?php echo $kehu['khid'];?>'>
        </form>
        <input type="button" class="addPassenger_btn" value="保 存"/>
    </div>
</div>
<!--//-->
<div class='hide'>
    <form name="UserShopImgForm" action="<?php echo $root;?>library/mPost.php?type=shopImg" method="post" enctype="multipart/form-data" change="Upload" style="display:none;">
        <input name="UserShopImgUpload" type="file" onchange="$('[name=UserShopImgForm]').submit();">
        <input name="userId" type="hidden" value="<?php echo $kehu['khid'];?>">
    </form>
</div>
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
$(function(){
    changeNav();
    $('.addPassenger_btn').on('click',function(){
        $.post(root+"library/mData.php?type=editUserInfo",$("[name='UserInfo']").serialize(),function(data){
            if(data.warn == 2){
                //location.reload();
                mwarn('修改成功');
            }else{
                mwarn(data.warn);
            }
        },"json");
    });
})
</script>



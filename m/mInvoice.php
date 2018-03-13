<?php
include "../library/mFunction.php";
echo head('m');
$type = $get['type'];
$bid = $get['bid'];
?>
<!--头部-->
<div class="header header-fixed">
	<div class="nesting"> <a href="javascript:;" class="header-btn header-return" onclick="windowBack();"><span class="return-ico">&#xe614;</span></a>
		<div class="align-content">
			<p class="align-text">发票信息</p>
		</div>
		<a href="#" class="header-btn"></a> 
	</div>
</div>
<!--//-->
<!--会员中心-编辑信息-推荐码-->
<div class="container">
	<div class="mui-pt45 mui-mbottom60 pay">
		<dl class="header-search">
			<dd><p class="mui-dis-flex"><i></i><span>发票类型</span></p></dd>
			<dd>
				<div class="select-pay">
					<div class="select-pay-title mui-dis-flex">
						<span class="invoice on">纸质发票</span>
						<span class='invoice'>电子发票</span>
					</div>
                    <div class="select-pay-con">
                        <div  style="display: block;">
                            <ul>
                                <li>
                                    <!--<h3>说明：</h3>-->
                                    <label>
                                        <i></i>
                                        <em>电子发票与纸质发票具备同等法律效应，可支持报销入账</em>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div></div>
                    </div>

				</div>
			</dd>
		</dl>
		<dl class="header-search myshow">
			<dd><p  class="mui-dis-flex"><i></i><span>发票抬头</span></p></dd>
			<dd>
                <form name="companyForm">
                    <ul class="invoice">
                        <li class="mui-dis-flex">
                            <span>单位名称:</span>
                            <input name='comName' class="flex1" placeholder="请输入单位名称" value='<?php echo $_SESSION['buyCar']['companyName'];?>'/>
                        </li>
                        <li class="mui-dis-flex">
                            <span>发票税号:</span>
                            <input name='comNum' class="flex1" placeholder="请输入发票税号" value='<?php echo $_SESSION['buyCar']['taxNum'];?>'/>
                        </li>
                    </ul>
                    <input type="hidden" name="khid" value='<?php echo $kehu['khid'];?>'>
                    <input type="hidden" name="buyType" value='<?php echo $type;?>'>
                    <input type="hidden" name="bid" value='<?php echo $bid;?>'>					
                    <input type="hidden" name="type" value='纸质发票'>                    
                </form>
				
			</dd>
		</dl>
		<input type="button" class="addPassenger_btn" value="确 定"/>
	</div>
</div>
<!--//-->
<!--底部-->
<?php echo mFooter(),mWarn();?>
<!--//-->
<script>
    $(function(){
        changeNav();
        var invoiceType = $('div.select-pay').find('span.on').text();
        var formInvoiceType = $('form[name=companyForm]').find('input[name=type]');
        formInvoiceType.val(invoiceType);

		$(".select-pay-title span").on("click",function(){
			var li_index = $(this).index();
			$(this).addClass("on").siblings().removeClass("on");
			$(".select-pay-con div").eq(li_index).show().siblings().hide();
            //从新赋值
            var invoiceType = $('div.select-pay').find('span.on').text();
            var formInvoiceType = $('form[name=companyForm]').find('input[name=type]');
            formInvoiceType.val(invoiceType);
		});
	});
//     $('.invoice').on('click',function(){
//        var $this = $(this)
//            key = $this.html();
//        if( key == '纸质发票' ){
//            $('.myshow').hide();
//        }else if( key == '电子发票' ){
//            $('.myshow').show();
//        }
//    });
    $('.addPassenger_btn').on('click',function(){
        $.post("<?php echo root;?>library/mData.php?type=addInvoice",$("[name='companyForm']").serialize(),function(data){
            if(data.warn == 2){
                window.history.back(-1);
            }else{
                mwarn(data.warn);
            }
        },'json');
    });
    
</script>
<?php 
// +----------------------------------------------------------------------
// | Description: 退款类
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
include "adfunction.php";
include "wxRefund.php";
if($get['type'] == 'refund'){
	$order_sn = $post['order_sn'];
	$sql = "select * from `order` where order_sn = '$order_sn'";
	$pdo = newPdo();
	$a = $pdo->query($sql);
	$data = $a->fetch(PDO::FETCH_ASSOC);
	//微信支付
	if($data['pay_type_online'] == 2){
		if(time()-strtotime($data['ctime'])>60*60*24*365){
			$result['code'] = '201';
			$result['msg'] = '超过一年的订单不能退款';
		}else{
			$wx = new wxRefund();
			$res = $wx->index($data['money'],$data['order_sn']);
			//退款成功
			if($res['info']['return_code'] == 'SUCCESS'){
				//修改状态
				$sql = "update `order` set workFlow = '7' where order_sn = '$order_sn'";
				$num = $pdo->exec($sql);
				if($num){
					$result['code'] = '200';
					$result['msg'] = '退款成功';
				}else{
					$result['code'] = '202';
					$result['msg'] = '未知错误';
				}
			//退款失败
			}else{
				$result['code'] = '203';
				$result['msg'] = '退款失败';
			}
		}
	}
	echo json_encode($result);
}

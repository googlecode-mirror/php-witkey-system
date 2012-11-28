<?php

define ( "IN_KEKE", true );

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');


//验证返回的结果
$verify_result = Sys_payment::factory('alipayjs')->get_alipay_notify()->verifyNotify();

///验证失败
if(!Keke_valid::not_empty($verify_result)){
	echo 'error';
}

//商户订单号
$out_trade_no = $_POST ['out_trade_no']; 
//获取总价格 
$total_fee = $_POST ['total_fee']; 

//chmod('log.txt',7777);
//KEKE_DEBUG and $fp = file_put_contents ( 'log.txt', var_export ( $_POST, 1 ), FILE_APPEND );

echo "success";
if ($_POST ['trade_status'] == 'TRADE_FINISHED' || $_POST ['trade_status'] == 'TRADE_SUCCESS') { 
	//交易成功业务处理 
	list ($uid, $order_id ,$rid ) = explode ( '-', $out_trade_no, 3 );
	
	
	
}
 

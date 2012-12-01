<?php define ( "IN_KEKE", true );
require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

$paypal = Sys_payment::factory('paypal');

// Enable test mode if needed
//$myPaypal->enableTestMode ();
$valid  = $paypal->validateIpn();

if ($valid===FALSE) {
  Keke::show_msg('校验失败,数据可疑',Cookie::get('last_page'),'error');
}
if ($paypal->ipnData ['payment_status'] == 'Completed') {
	
	Keke::show_msg('即时到帐支付成功,付款金额：'.$paypal->ipnData[' '],Cookie::get('last_page'));
} else {
	Keke::show_msg('即时到帐支付失败',Cookie::get('last_page'),'error');
}
 
<?php
/**
 * paypal Ö§¸¶Ìø×ªÒ³Ãæ
 */

require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_comm.php');
require "Paypal.php";

$myPaypal = new Paypal ();

// Log the IPN results
$myPaypal->ipnLog = TRUE;
// Enable test mode if needed
$myPaypal->enableTestMode ();
// Check validity and write down it
list ( $_, $charge_type, $uid, $obj_id, $order_id, $model_id ) = explode ( '-', $myPaypal->ipnData ['custom'], 6 );
$total_fee = $myPaypal->ipnData ['payment_gross'];
$fac_obj = new pay_return_fac_class ( $charge_type, $model_id, $uid, $obj_id, $order_id, $total_fee, 'paypal' );

if ($myPaypal->validateIpn ()) {
	if ($myPaypal->ipnData ['payment_status'] == 'Completed') {
		//file_put_contents ( 'log.txt', var_export ( $myPaypal->ipnData, 1 ), FILE_APPEND );
		$response = $fac_obj->load ( );
		$fac_obj->return_notify ( 'paypal',$response);
	} else {
		//file_put_contents ( 'log.txt', var_export ( $myPaypal->ipnData, 1 ), FILE_APPEND );
		$fac_obj->return_notify ( 'paypal');
	}
} else {
	//file_put_contents ( 'log.txt', var_export ( $myPaypal->ipnData, 1 ), FILE_APPEND );
	$fac_obj->return_notify ( 'paypal');
}
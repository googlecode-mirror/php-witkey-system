<?php

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_comm.php');

include_once 'Paypal.php';

// Create an instance of the paypal library


$myPaypal = new Paypal ();

if (KEKE_DEBUG) {
	// Log the IPN results
	$myPaypal->ipnLog = TRUE;
	
	// Enable test mode if needed
	$myPaypal->enableTestMode ();
}
// Check validity and write down it


if ($myPaypal->validateIpn ()) {
	if ($myPaypal->ipnData ['payment_status'] == 'Completed') {
		list ( $_, $charge_type, $uid, $obj_id, $order_id, $model_id ) = explode ( '-', $myPaypal->ipnData ['custom'], 6 );
		$total_fee = $myPaypal->ipnData ['payment_gross'];
		$fac_obj = new pay_return_fac_class ( $charge_type, $model_id, $uid, $obj_id, $order_id, $total_fee, 'paypal' );
		$fac_obj->load ( );
	}
	file_put_contents ( 'log.txt', var_export ( $myPaypal->ipnData, 1 ), FILE_APPEND );
} else {
	file_put_contents ( 'log.txt', var_export ( $myPaypal->ipnData, 1 ), FILE_APPEND );
}


<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

//echo Keke_user_register::instance('keke')->gen_secode('123456','7sghrm');

$a = (float)'1.2';
$a = -$a;
var_dump($a);

 

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


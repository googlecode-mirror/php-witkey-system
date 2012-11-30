<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

$res = Keke_msg::instance()->send_sms('13545368115','任务完成');
/* $res = a::b();*/
var_dump($res); 
 


 

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


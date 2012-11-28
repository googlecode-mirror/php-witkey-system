<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

$res = Sys_finance::get_instance('5')->
set_action('pub_task')
->set_mem(array(':task_id'=>'12',':task_title'=>'找人做一人上设计'))
->cash_out(150,0,'task',12);

var_dump($res);
 

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


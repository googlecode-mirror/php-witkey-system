<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

//echo Keke_user_register::instance('keke')->gen_secode('123456','7sghrm');
//ÓïÑÔ°ü¼ÓÔØ²âÊÔ

function test(){
$where = "model_type='task' and model_status = 1";
		
$models = DB::select('model_code')->from('witkey_model')->where($where)->execute();

Keke::$_log->add(log::INFO, 'call_back')->write();
}

array_map('test', array());

die;

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

//echo Keke_user_register::instance('keke')->gen_secode('123456','7sghrm');
//ÓïÑÔ°ü¼ÓÔØ²âÊÔ

DB::insert('witkey_task_temp')->set(array('task_title','task_desc'))
->value(array("ta'rtr'sk,task",'test1'))->execute();


//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


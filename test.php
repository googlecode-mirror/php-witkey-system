<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';
 

class a{
	
	static function b(){
		
		register_shutdown_function(array('a','send_msg'),1,484,0.01);
		return true;
	}
	
	static function send_msg($uid,$rid,$cash){
		Keke_msg::instance()->to_user($uid)
		->set_tpl('recharge_success')
		->set_var(array('{��ֵ����}'=>$rid,'{��ֵ���}'=>$cash))
		->send();
	}
	
} 

$res = a::b();
var_dump($res);




 

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


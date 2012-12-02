<?php define ( "IN_KEKE", true );
require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

$paypal = Sys_payment::factory('paypal');

// Enable test mode if needed
//$myPaypal->enableTestMode ();
$valid  = $paypal->validateIpn();


if ($valid===FALSE) {
  Keke::show_msg('У��ʧ��,���ݿ���',Cookie::get('last_page'),'error');
}
if ($paypal->ipnData ['payment_status'] == 'Completed') {
	list ($uid, $order_id ,$rid ) = explode ( '-', $paypal->ipnData ['custom'], 3 );
	//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
	if(Sys_payment::set_recharge_status($uid,$rid, '', $paypal->ipnData ['mc_gross'],'paypal')===FALSE){
		Keke::show_msg('��Ҫ�ظ�ˢ��',Cookie::get('last_page'),'error');
	}
	
	if($order_id>0){
		//��������Ϣ
	}
	
	Keke::show_msg('��ʱ����֧���ɹ�,�����'.$paypal->ipnData['mc_gross '],Cookie::get('last_page'));
}elseif($paypal->ipnData ['payment_status'] == 'Pending'){
	Keke::show_msg('���ĸ����Ҫ�տȷ��',Cookie::get('last_page'));
} else {
	Keke::show_msg('��ʱ����֧��ʧ��',Cookie::get('last_page'),'error');
}
 
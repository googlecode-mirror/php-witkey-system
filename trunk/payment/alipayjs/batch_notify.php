<?php  define ( "IN_KEKE", true );

require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

//����ó�֪ͨ��֤���
(bool)$verify_result = Sys_payment::factory('alipayjs')->get_alipay_notify()->verifyNotify();

if($verify_result===FALSE){
	echo 'error';
	return false;
}

echo 'success';

//��ȡ��������������ת�˳ɹ�����ϸ��Ϣ
$success_str = $_POST ['success_details']; 

//��ȡ��������������ת��ʧ�ܵ���ϸ��Ϣ
$fail_str = $_POST ['fail_details']; 


$detail_arr = Sys_payment::factory('alipayjs')->batch_unpack_detail($success_str, $fail_str);

set_time_limit(0);

Sys_payment::factory('alipayjs')->batch_notify($detail_arr);


/* $batch_obj = pay_batch_fac_class::get_instance ( 'alipayjs' );

$batch_obj->success_notify ( $success_details, $fail_details ); */

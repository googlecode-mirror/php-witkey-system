<?php  define ( "IN_KEKE", true );

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');


//��֤���صĽ��
$verify_result = Sys_payment::factory('alipayjs')->get_alipay_notify()->verifyNotify();

///��֤ʧ��
if((bool)$verify_result===FALSE){
	echo 'error';
	return false;
}

echo "success";
//�̻�������
$out_trade_no = $_POST ['out_trade_no']; 
//�����
$total_fee = $_POST ['total_fee']; 
//���˺�
$buyer_email = $_POST['buyer_email']; 

//chmod('log.txt',7777);
//KEKE_DEBUG and $fp = file_put_contents ( 'log.txt', var_export ( $_POST, 1 ), FILE_APPEND );
if ($_POST ['trade_status'] == 'TRADE_FINISHED' || $_POST ['trade_status'] == 'TRADE_SUCCESS') { 
	//���׳ɹ�ҵ���� 
	list ($uid, $order_id ,$rid ) = explode ( '-', $out_trade_no, 3 );
	//KEKE_DEBUG AND file_put_contents ( 'log.txt', var_export ( $_POST, 1 ), FILE_APPEND );
	
	//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
	if(Sys_payment::set_recharge_status($uid,$rid, $buyer_email, $total_fee,'֧����')===FALSE){
		return false;
	}
	
	if($order_id>0){
		//��������Ϣ
	}
	//�ı䶩��״̬���Լ�������Ӧ��ҵ����
	
	
	
}
 

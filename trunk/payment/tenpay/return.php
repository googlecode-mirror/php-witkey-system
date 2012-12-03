<?php  define ( "IN_KEKE", true );

require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

require ("classes/ResponseHandler.class.php");

$pay_config = Sys_payment::factory('tenpay')->get_pay_config();

	/* ����֧��Ӧ����� */
	$resHandler = new ResponseHandler();
	$resHandler->setKey($pay_config['key']);

	//�ж�ǩ��
	if($resHandler->isTenpaySign()===FALSE) {
		echo "<br/>" . "��֤ǩ��ʧ��" . "<br/>";
		echo $resHandler->getDebugInfo() . "<br>";
		die;
	}
	
	//֪ͨid
	$notify_id = $resHandler->getParameter("notify_id");
	//�̻�������
	$out_trade_no = $resHandler->getParameter("out_trade_no");
	//�Ƹ�ͨ������
	$transaction_id = $resHandler->getParameter("transaction_id");
	//���,�Է�Ϊ��λ
	$total_fee = $resHandler->getParameter("total_fee");
	//�����ʹ���ۿ�ȯ��discount��ֵ��total_fee+discount=ԭ�����total_fee
	$discount = $resHandler->getParameter("discount");
	//֧�����
	$trade_state = $resHandler->getParameter("trade_state");
	//����ģʽ,1��ʱ����
	$trade_mode = $resHandler->getParameter("trade_mode");
	
	$total_fee = Curren::output($total_fee/100);
	//var_dump($resHandler->getAllParameters());die;
	if("1" == $trade_mode ) {
		if( "0" == $trade_state){ 
		 	Keke::show_msg('��ʱ����֧���ɹ�,�����'.$total_fee,Cookie::get('last_page'));
		} else {
			//�������ɹ�����
			//echo "<br/>" . "��ʱ����֧��ʧ��" . "<br/>";
			Keke::show_msg('��ʱ����֧��ʧ��',Cookie::get('last_page'),'error');
		}
	} 
	
 
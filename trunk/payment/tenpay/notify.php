<?php  define ( "IN_KEKE", true );


require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');
require ("classes/ResponseHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");



//Keke::$_log->add(Log::DEBUG, "�����̨�ص�ҳ��")->write();

$pay_config = Sys_payment::factory('tenpay')->get_pay_config();


	/* ����֧��Ӧ����� */
		$resHandler = new ResponseHandler();
		$resHandler->setKey($pay_config['key']);

	//�ж�ǩ��
		if($resHandler->isTenpaySign()) {
	
	//֪ͨid
		$notify_id = $resHandler->getParameter("notify_id");
		
		//Keke::$_log->add(Log::DEBUG, "�����̨notify_id:$notify_id")->write();
	//ͨ��֪ͨID��ѯ��ȷ��֪ͨ�����Ƹ�ͨ
	//������ѯ����
		$queryReq = new RequestHandler();
		$queryReq->init();
		$queryReq->setKey($pay_config['key']);
		$queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
		$queryReq->setParameter("partner", $pay_config['pid']);
		$queryReq->setParameter("notify_id", $notify_id);
		
	//ͨ�Ŷ���
		$httpClient = new TenpayHttpClient();
		$httpClient->setTimeOut(5);
	//������������
		$httpClient->setReqContent($queryReq->getRequestURL());
	
	//��̨����
		if($httpClient->call()) {
	//���ý������
			$queryRes = new ClientResponseHandler();
			$queryRes->setContent($httpClient->getResContent());
			$queryRes->setKey($pay_config['key']);
		   //Keke::$_log->add(Log::DEBUG, "�����̨notify_id:$notify_id")->write();
		if($resHandler->getParameter("trade_mode") == "1"){
			// �ж�ǩ�����������ʱ���ʣ�
			// ֻ��ǩ����ȷ,retcodeΪ0��trade_stateΪ0����֧���ɹ�
			if ($queryRes->isTenpaySign () && $queryRes->getParameter ( "retcode" ) == "0" && $resHandler->getParameter ( "trade_state" ) == "0") {
				//Keke::$_log->add(Log::DEBUG,"��ʱ������ǩID�ɹ�")->write();
				//echo "success";
				//ȡ���������ҵ����
				$out_trade_no = $resHandler->getParameter("out_trade_no");
				//�Ƹ�ͨ������
				$transaction_id = $resHandler->getParameter("transaction_id");
				//���,�Է�Ϊ��λ
				$total_fee = $resHandler->getParameter("total_fee");
				//�����ʹ���ۿ�ȯ��discount��ֵ��total_fee+discount=ԭ�����total_fee
				//$discount = $resHandler->getParameter("discount");
				
				//------------------------------
				//����ҵ��ʼ
				//------------------------------
				list ($uid, $order_id ,$rid ) = explode ( '-', $out_trade_no, 3 );
				
				
				//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
				if(Sys_payment::set_recharge_status($uid,$rid, '', $total_fee/100,'�Ƹ�ͨ')===FALSE){
					return false;
				}
				
				if($order_id>0){
					//��������Ϣ
				}
				
				echo 'success';
				//------------------------------
				//����ҵ�����
				//------------------------------
				//Keke::$_log->add(Log::DEBUG,"��ʱ���ʺ�̨�ص��ɹ�")->write();
				
				
			} else {
	//����ʱ�����ؽ������û��ǩ����д��־trade_state��retcode��retmsg��ʧ�����顣
	//echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->                         getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
			   //Keke::$_log->add(Log::DEBUG,"��ʱ���ʺ�̨�ص�ʧ��")->write();
			   echo "fail";
			}
		} 
		
		
	//��ȡ��ѯ��debug��Ϣ,���������Ӧ�����ݡ�debug��Ϣ��ͨ�ŷ�����д����־�����㶨λ����
	   
		/* $h =  "<br>------------------------------------------------------<br>";
		$h .=  "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
		$h .= "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		$h .= "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		$h .= "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
		$h .= "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
		
		Keke::$_log->add(Log::DEBUG,$h)->write(); */
	}else
	 {
		//ͨ��ʧ��
		echo "fail";
		//��̨����ͨ��ʧ��,д��־�����㶨λ����
		echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	 } 
	
	
   } else {
    echo "<br/>" . "��֤ǩ��ʧ��" . "<br/>";
    echo $resHandler->getDebugInfo() . "<br>";
}

<?php  defined('IN_KEKE') OR die('access deined');
require 'PayRequestHandler.php';
/**
 * �Ƹ�֧ͨ���ӿ���
 * @author Michael
 * @version 3.0 2012-12-28
 *
 */
class Tenpay extends Sys_payment {
    /**
     * @var RequestHandler
     */
	private  $_request;

	function get_pay_html($method, $pay_amount, $subject, $order_id, $rid,$bank_code=NULL){
		$this->init_param($pay_amount, $subject, $order_id, $rid, $bank_code);
		if($method=='form'){
			return $this->buildRequestForm('�ύ��...');    	
		}else{
			return $this->_request->getRequestURL();
		}
		
	}
	
	function init_param($pay_amount, $subject, $order_id,$rid,$bank_code){
		global $_K;
		/* ����֧��������� */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->_pay_config['key']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
		
		$out_trade_no = "{$_SESSION['uid']}-{$order_id}-$rid";
		$return_url = $_K ['siteurl'] . '/payment/tenpay/return.php';
		$notify_url = $_K ['siteurl'] . '/payment/tenpay/notify.php';
		$body = "(from:" . $_SESSION['username'] . ")";
		//----------------------------------------
		//����֧������ 
		//----------------------------------------
		$reqHandler->setParameter("partner", $this->_pay_config['pid']);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $pay_amount*100);  //�ܽ��
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", $body);
		$reqHandler->setParameter("bank_type", (int)$bank_code);  	  //�������ͣ�Ĭ��Ϊ�Ƹ�ͨ
		//�û�ip
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//�ͻ���IP
		$reqHandler->setParameter("fee_type", "1");               //����
		$reqHandler->setParameter("subject",$subject);          //��Ʒ���ƣ����н齻��ʱ���
		//�����URL
		//return  $reqHandler->getRequestURL();
		 $this->_request =  $reqHandler;
	}

	function buildRequestForm($btn_name){
		$action = $this->_request->getGateURL();
		$sHtml = "<form action='".$action."' name='tenpaysubmit' method='post' target='_blank'>";
		$params = $this->_request->getAllParameters();
		foreach($params as $k => $v) {
			$sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
		}
		$sHtml = $sHtml."<input type='submit' value='".$btn_name."'></form>";
		
		$sHtml = $sHtml."<script>document.forms['tenpaysubmit'].submit();</script>";
		return $sHtml;
		 
	}
	
		
}

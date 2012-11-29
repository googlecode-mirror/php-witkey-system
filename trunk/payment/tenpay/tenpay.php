<?php  defined('IN_KEKE') OR die('access deined');

require 'classes/RequestHandler.class.php';
//require 'classes/ResponseHandler.class.php';
//require 'classes/client/ClientResponseHandler.class.php';
//require 'classes/client/TenpayHttpClient.class.php';

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
		if($method=='post'){
			return $this->buildRequestForm('�ύ��...');    	
		}else{
			return $this->_request->getRequestURL();
		}
		
	}
	
	function init_param($pay_amount, $subject, $order_id,$rid,$bank_code){
		global $_K;
		$order_id= (int)$order_id;
		
		$code_arr = array_flip(self::$_bank_code);
		
		$out_trade_no = "{$_SESSION['uid']}-{$order_id}-$rid";
		
		$return_url = $_K ['siteurl'] . '/payment/tenpay/return.php';
		$notify_url = $_K ['siteurl'] . '/payment/tenpay/notify.php';

		/* ��ȡ�ύ����Ʒ���� */
		$product_name = $subject;
		/* ��ȡ�ύ����Ʒ�۸� */
		$order_price = $pay_amount;
		/* ��ȡ�ύ�ı�ע��Ϣ */
		$remarkexplain = 'from:'.$_SESSION['username'];
		/* �ӿ����� */
		$trade_mode=1;
		/* ֧������ */
		$bank_type_value=$code_arr[$bank_code];
		/* ��Ʒ�۸񣨰����˷ѣ����Է�Ϊ��λ */
		$total_fee = $order_price*100;
		/* ��Ʒ���� */
		$desc = "��Ʒ��".$product_name.",��ע:".$remarkexplain;
		 
		/* ����֧��������� */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->_pay_config['key']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
		
		//----------------------------------------
		//����֧������ 
		//----------------------------------------
		$reqHandler->setParameter("partner", $this->_pay_config['pid']);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee);  //�ܽ��
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", $desc);
		$reqHandler->setParameter("bank_type", $bank_type_value);  	  //�������ͣ�Ĭ��Ϊ�Ƹ�ͨ
		//�û�ip
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//�ͻ���IP
		$reqHandler->setParameter("fee_type", "1");               //����
		$reqHandler->setParameter("subject",$desc);          //��Ʒ���ƣ����н齻��ʱ���
		
		//ϵͳ��ѡ����
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //ǩ����ʽ��Ĭ��ΪMD5����ѡRSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //�ӿڰ汾��
		$reqHandler->setParameter("input_charset", CHARSET);   	  //�ַ���
		$reqHandler->setParameter("sign_key_index", "1");    	  //��Կ���
		
		//ҵ���ѡ����
		$reqHandler->setParameter("attach", "");             	  //�������ݣ�ԭ�����ؾͿ�����
		$reqHandler->setParameter("product_fee", "");        	  //��Ʒ����
		$reqHandler->setParameter("transport_fee", "0");      	  //��������
		$reqHandler->setParameter("time_start", date("YmdHis"));  //��������ʱ��
		$reqHandler->setParameter("time_expire", "");             //����ʧЧʱ��
		$reqHandler->setParameter("buyer_id", "");                //�򷽲Ƹ�ͨ�ʺ�
		$reqHandler->setParameter("goods_tag", "");               //��Ʒ���
		$reqHandler->setParameter("trade_mode",$trade_mode);              //����ģʽ��1.��ʱ����ģʽ��2.�н鵣��ģʽ��3.��̨ѡ�����ҽ���֧�������б�ѡ�񣩣�
		$reqHandler->setParameter("transport_desc","");              //����˵��
		$reqHandler->setParameter("trans_type","1");              //��������
		$reqHandler->setParameter("agentid","");                  //ƽ̨ID
		$reqHandler->setParameter("agent_type","");               //����ģʽ��0.�޴���1.��ʾ������ģʽ��2.��ʾ����ģʽ��
		$reqHandler->setParameter("seller_id","");                //���ҵ��̻���
		
		
		
		//�����URL
		//$reqUrl = $reqHandler->getRequestURL();
		$this->_request  = $reqHandler;
		//echo "<br/><a href=\"$reqUrl\" target=\"_blank\">�Ƹ�֧ͨ��</a>";
		//��ȡdebug��Ϣ,����������debug��Ϣд����־�����㶨λ����
		/**/
		//$debugInfo = $reqHandler->getDebugInfo();
		
		
	}

	function buildRequestForm($btn_name){
		$action = $this->_request->getGateURL();
		$url = $this->_request->getRequestURL();
		$sHtml = "<form action='".$action."' name='tenpaysubmit' method='post'>";
		$params = $this->_request->getAllParameters();
		foreach($params as $k => $v) {
			$sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
		}
		$sHtml = $sHtml."<input type='submit' value='".$btn_name."'></form>";
		
		$sHtml = $sHtml."<script>document.forms['tenpaysubmit'].submit();</script>";
		return $sHtml;
		
	}
	
	/**
	 * �Ƹ�ͨ�����д���������ͼƬ�Ķ�Ӧ��ϵ����
	 * @var array
	 */
	private  static $_bank_code = array(
			"1001"=>"17",
			"1002"=>"10",
			"1003"=>"2",
			"1004"=>"9",
			"1005"=>"1",
			"1006"=>"4",
			"1008"=>"8",
			"1009"=>"12",
			"1010"=>"18",
			"1020"=>"5",
			"1021"=>"7",
			"1022"=>"3",
			"1024"=>"20",
			"1025"=>"22",
			"1027"=>"6",
			'1028'=>'27',
			"1032"=>"11",
			"1033"=>"14",
			"1052"=>"19",
			"8001"=>"logo",
	);
}

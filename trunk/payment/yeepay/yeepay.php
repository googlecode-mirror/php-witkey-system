<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );


/**
 * �ױ�֧����Ʒ��װ
 * 
 * @author Michael
 * @version 3.0 2012-11-30
 *         
 */
class Yeepay extends Sys_payment {
	private $_debug = 1;
	private $_reqUrl;
	private $_config = array ();
	function __construct() {
		parent::__construct ( 'yeepay' );
		if ($this->_debug === 1) {
			// ���Ե�ַ
			$this->_reqUrl = "http://tech.yeepay.com:8080/robot/debug.action";
		} else {
			// ��ʽ��ַ
			$this->_reqUrl = "https://www.yeepay.com/app-merchant-proxy/node";
		}
	}
	function get_pay_html($method, $pay_amount, $subject, $order_id, $rid, $bank_code = NULL) {
		$this->init_param ( $pay_amount, $subject, $order_id, $rid );
		if ($method == 'post') {
			return $this->buildRequestForm ( '�ύ��...' );
		} else {
			return $this->getRequestURL ();
		}
	}
	/**
	 * ���������װ
	 */
	function init_param($pay_amount, $subject, $order_id, $rid) {
		global $_K;
		$order_id = ( int ) $order_id;
		
		$out_trade_no = "{$_SESSION['uid']}-{$order_id}-$rid";
		
		$return_url = $_K ['siteurl'] . '/payment/yeepay/return.php'; // �ص���ַ
		
		$p1_MerId = $this->_pay_config ['pid'];
		$merchantKey = $this->_pay_config ['key'];
		
		$this->_config ['p1_MerId'] = $p1_MerId;
		/*
		 * $p1_MerId			= "10001126856";																										#����ʹ��
		 * $merchantKey	=
		 * "69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl";		#����ʹ��
		 */
		$logName = "YeePay_HTML.log";
		
		// ҵ������
		// ֧�����󣬹̶�ֵ"Buy" .
		$p0_Cmd = "Buy";
		$this->_config ['p0_Cmd'] = $p0_Cmd;
		// �ͻ���ַ
		// Ϊ"1": ��Ҫ�û����ͻ���ַ�����ױ�֧��ϵͳ;Ϊ"0": ����Ҫ��Ĭ��Ϊ "0".
		$p9_SAF = "0";
		$this->_config ['p9_SAF'] = $p9_SAF;
		$p2_Order = date(YmdHis).mt_rand(1, 2000);
		$this->_config ['p2_Order'] = $p2_Order;
		// ֧�����,����.
		// ��λ:Ԫ����ȷ����.
		$p3_Amt = $pay_amount;
		$this->_config ['p3_Amt'] = $p3_Amt;
		// ���ױ���,�̶�ֵ"CNY".
		$p4_Cur = "CNY";
		$this->_config ['p4_Cur'] = $p4_Cur;
		// ��Ʒ����
		// ����֧��ʱ��ʾ���ױ�֧���������Ķ�����Ʒ��Ϣ.
		$p5_Pid = $subject;
		$this->_config ['p5_Pid'] = $p5_Pid;
		// ��Ʒ����
		$p6_Pcat = "";
		$this->_config ['p6_Pcat'] = $p6_Pcat;
		// ��Ʒ����
		$p7_Pdesc = 'from:' . $_SESSION ['username'];
		$this->_config ['p7_Pdesc'] = $p7_Pdesc;
		// �̻�����֧���ɹ����ݵĵ�ַ,֧���ɹ����ױ�֧������õ�ַ�������γɹ�֪ͨ.
		$p8_Url = $return_url;
		$this->_config ['p8_Url'] = $p8_Url;
		// �̻���չ��Ϣ
		// �̻�����������д1K ���ַ���,֧���ɹ�ʱ��ԭ������.
		$pa_MP = $out_trade_no;
		
		$this->_config ['pa_MP'] = $pa_MP;
		// ֧��ͨ������
		// Ĭ��Ϊ""�����ױ�֧������.��������ʾ�ױ�֧����ҳ�棬ֱ����ת�������С�������֧��������һ��ͨ��֧��ҳ�棬���ֶο����ո�¼:�����б����ò���ֵ.
		$pd_FrpId = "";
		$this->_config ['pd_FrpId'] = $pd_FrpId;
		// Ӧ�����
		// Ĭ��Ϊ"1": ��ҪӦ�����;
		$pr_NeedResponse = "1";
		$this->_config ['pr_NeedResponse'] = $pr_NeedResponse;
		// ��ǩ����������ǩ����
		$hmac = $this->getReqHmacString ( $p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse );
		$this->_config ['hmac'] = $hmac;
	}
	function buildRequestForm($btn_name) {
		$action = $this->_reqUrl;
		
		$sHtml = "<form action='" . $action . "' name='yeepaysubmit' method='post'>";
		$params = $this->_config;
		foreach ( $params as $k => $v ) {
			$sHtml .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
		}
		$sHtml = $sHtml . "<input type='submit' value='" . $btn_name . "'></form>";
		 
		$sHtml .="<script>document.forms['yeepaysubmit'].submit();</script>";
		return $sHtml;
	}
	function getRequestURL() {
		$url = $this->_reqUrl . '?';
		return $url .= http_build_query ( $this->_config );
	}
	
	// ����������ǩ����
	function getReqHmacString($p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse) {
		
		$p0_Cmd = $this->_config['p0_Cmd'];
		$p9_SAF = $this->_config['p9_SAF'];
		$p1_MerId = $this->_pay_config['pid'];
		$merchantKey = $this->_pay_config['key'];
		// include 'merchantProperties.php';
		 
		// ��ǩ������һ�������ĵ��б�����ǩ��˳�����
		$sbOld = "";
		// ��ҵ������
		$sbOld = $sbOld . $p0_Cmd;
		// ���̻����
		$sbOld = $sbOld . $p1_MerId;
		// ���̻�������
		$sbOld = $sbOld . $p2_Order;
		// ��֧�����
		$sbOld = $sbOld . $p3_Amt;
		// �뽻�ױ���
		$sbOld = $sbOld . $p4_Cur;
		// ����Ʒ����
		$sbOld = $sbOld . $p5_Pid;
		// ����Ʒ����
		$sbOld = $sbOld . $p6_Pcat;
		// ����Ʒ����
		$sbOld = $sbOld . $p7_Pdesc;
		// ���̻�����֧���ɹ����ݵĵ�ַ
		$sbOld = $sbOld . $p8_Url;
		// ���ͻ���ַ��ʶ
		$sbOld = $sbOld . $p9_SAF;
		// ���̻���չ��Ϣ
		$sbOld = $sbOld . $pa_MP;
		// ��֧��ͨ������
		$sbOld = $sbOld . $pd_FrpId;
		// ���Ƿ���ҪӦ�����
		$sbOld = $sbOld . $pr_NeedResponse;
		
		$this->logstr ( $p2_Order, $sbOld, $this->HmacMd5 ( $sbOld, $merchantKey ) );
		
		return $this->HmacMd5 ( $sbOld, $merchantKey );
	}
	function getCallbackHmacString($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType) {
		
		// include 'merchantProperties.php';
		$p1_MerId = $this->_pay_config ['pid'];
		$merchantKey = $this->_pay_config ['key'];
		// �ü���ǰ���ַ���
		$sbOld = "";
		// ���̼�ID
		$sbOld = $sbOld . $p1_MerId;
		// ����Ϣ����
		$sbOld = $sbOld . $r0_Cmd;
		// ��ҵ�񷵻���
		$sbOld = $sbOld . $r1_Code;
		// �뽻��ID
		$sbOld = $sbOld . $r2_TrxId;
		// �뽻�׽��
		$sbOld = $sbOld . $r3_Amt;
		// ����ҵ�λ
		$sbOld = $sbOld . $r4_Cur;
		// ���ƷId
		$sbOld = $sbOld . $r5_Pid;
		// �붩��ID
		$sbOld = $sbOld . $r6_Order;
		// ���û�ID
		$sbOld = $sbOld . $r7_Uid;
		// ���̼���չ��Ϣ
		$sbOld = $sbOld . $r8_MP;
		// �뽻�׽����������
		$sbOld = $sbOld . $r9_BType;
		
		$this->logstr ( $r6_Order, $sbOld, $this->HmacMd5 ( $sbOld, $merchantKey ) );
		return $this->HmacMd5 ( $sbOld, $merchantKey );
	}
	
	// ȡ�÷��ش��е����в���
	function getCallBackValue(&$r0_Cmd, &$r1_Code, &$r2_TrxId, &$r3_Amt, &$r4_Cur, &$r5_Pid, &$r6_Order, &$r7_Uid, &$r8_MP, &$r9_BType, &$hmac) {
		$r0_Cmd = $_REQUEST ['r0_Cmd'];
		$r1_Code = $_REQUEST ['r1_Code'];
		$r2_TrxId = $_REQUEST ['r2_TrxId'];
		$r3_Amt = $_REQUEST ['r3_Amt'];
		$r4_Cur = $_REQUEST ['r4_Cur'];
		$r5_Pid = $_REQUEST ['r5_Pid'];
		$r6_Order = $_REQUEST ['r6_Order'];
		$r7_Uid = $_REQUEST ['r7_Uid'];
		$r8_MP = $_REQUEST ['r8_MP'];
		$r9_BType = $_REQUEST ['r9_BType'];
		$hmac = $_REQUEST ['hmac'];
		
		return null;
	}
	function CheckHmac($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac) {
		if ($hmac == $this->getCallbackHmacString ( $r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType ))
			return true;
		else
			return false;
	}
	function HmacMd5($data, $key) {
 
		
		// ��Ҫ���û���֧��iconv���������Ĳ���������������
		$key = iconv ( "GB2312", "UTF-8", $key );
		$data = iconv ( "GB2312", "UTF-8", $data );
		
		$b = 64; // byte length for md5
		if (strlen ( $key ) > $b) {
			$key = pack ( "H*", md5 ( $key ) );
		}
		$key = str_pad ( $key, $b, chr ( 0x00 ) );
		$ipad = str_pad ( '', $b, chr ( 0x36 ) );
		$opad = str_pad ( '', $b, chr ( 0x5c ) );
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		
		return md5 ( $k_opad . pack ( "H*", md5 ( $k_ipad . $data ) ) );
	}
	function logstr($orderid, $str, $hmac) {
		// include 'merchantProperties.php';
		if($this->_debug==0){
			return false;
		}
		//$logName = "YeePay_HTML.log";
		$str =  "\r\n" . date ( "Y-m-d H:i:s" ) . "|orderid[" . $orderid . "]|str[" . $str . "]|hmac[" . $hmac . "]" ;
		Keke::$_log->add(Log::DEBUG, $str)->write(); 
	}
}

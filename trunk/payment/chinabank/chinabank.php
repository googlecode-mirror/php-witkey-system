<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

class Chinabank extends Sys_payment {
	
	private $_config = array();
	
	function get_pay_html($method, $pay_amount, $subject, $order_id, $rid) {
		
	}
	
	function init_param($pay_amount, $subject, $order_id, $obj_id, $rid) {
		global $_K;
		$partner = $this->_pay_config['pid'];
		$security_code = $this->_pay_config ['key'];
		
		$return_url = $_K ['siteurl'] . '/payment/chinabank/return.php';
		
		$notify_url = $_K ['siteurl'] . '/payment/chinabank/notify.php';
		
		$show_url = $_K ['siteurl'] . "/index.php/user";
		
		$out_trade_no = "{$_SESSION['uid']}-{$order_id}-$rid";
		
		$total_money = $pay_amount;
		
		$text = $pay_amount . "CNY" . $out_trade_no . $partner . $return_url . $security_code; // md5����ƴ�մ�,ע��˳���ܱ�
		$v_md5info = strtoupper ( md5 ( $text ) ); // md5�������ܲ�ת���ɴ�д��ĸ
		$this->_config = array (
				"v_mid" => $partner,
				"v_oid" => $out_trade_no,
				"v_amount" => $pay_amount,
				"v_moneytype" => "CNY",
				"v_url" => $return_url,
				"v_md5info" => $v_md5info 
		);
		
	}
	
	function buildRequestForm() {
		// echo 1;
		$sHtml = "<form id='E_FORM' name='E_FORM'  action='https://Pay3.chinabank.com.cn/PayGate' method='post'>";
		
		foreach ($this->_config as $key=>$val){
			$sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
		}
		$sHtml = $sHtml . "<input type='submit'  value='����ȷ�ϸ���'/>";
		$sHtml .= "<script>document.forms[\"E_FORM\"].submit();</script>";
		return $sHtml;
	}
	
	function getRequestURL(){
		$url = "https://Pay3.chinabank.com.cn/PayGate?";
		return $url .= http_build_query($this->_config);
	}
}


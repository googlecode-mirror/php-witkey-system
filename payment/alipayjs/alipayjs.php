<?php defined('IN_KEKE') or die('access denied');

require 'alipay_function.php';
require 'alipay_service.php';

class Alipayjs extends Sys_payment {
	
	private  $_sign_type='MD5';
	
	private  $_service = 'create_direct_pay_by_user';
	
	function set_service($value){
		$this->_service = $value;
		return $this;
	}
	
	function get_pay_url($charge_type, $pay_amount, $subject, $order_id, $model_id = null, $obj_id = null ) {
		$parameter = $this->init_param($charge_type, $pay_amount, $subject, $order_id,$model_id , $obj_id );
		$alipay = new alipay_service ( $parameter, $this->_pay_config ['key'], $this->_sign_type );
	    return $alipay->create_url ();
	}
	/**
	 * @see Sys_payment::get_pay_form()
	 */
	function get_pay_form($charge_type, $pay_amount,  $subject, $order_id, $model_id = null, $obj_id = null) {
		$parameter = $this->init_param($charge_type, $pay_amount, $subject, $order_id,$model_id , $obj_id );
		$alipay = new alipay_service ( $parameter, $this->_pay_config ['key'], $this->_sign_type );
		return $alipay->build_postform ();
	}
	/**
	 * 初始化参数
	 * @return array
	 */
	function init_param($charge_type, $pay_amount,  $subject, $order_id, $model_id = null, $obj_id = null){
		global $_K;
		$body = "(from:" . $_SESSION['username'] . ")";
		return array (
				"service" => $this->_service,
				"partner" => $this->_pay_config ['pid'],
				"return_url" => $_K ['siteurl'] . '/payment/alipayjs/return.php',
				"notify_url" => $_K ['siteurl'] . '/payment/alipayjs/notify.php',
				"_input_charset" => CHARSET,
				"subject" => $subject,
				"body" => $body,
				"out_trade_no" => "charge-{$charge_type}-{$_SESSION['uid']}-{$obj_id}-{$order_id}-{$model_id}",
				"total_fee" => $pay_amount,
				"payment_type" => "1",
				"show_url" => $_K ['siteurl'] . "/index.php?do=user&view=finance",
				"seller_email" => $this->_pay_config ['pay_account'],
				"extend_param"=>"isv^kk11"
		  );
	}
	
}
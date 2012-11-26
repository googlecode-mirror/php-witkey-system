<?php defined('IN_KEKE') or die('access denied');

require 'alipay_function.php';
require 'alipay_service.php';
/**
 * ֧������ʱ���˽ӿ�,���� �����url����form
 * 
 * ��������������ݣ���ѹ�������ص�����
 * @author Michael
 * @version 3.0 2012-11-26 
 *
 */
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
	 * ������������URL
	 * @param string $detail_data �����ϸ����
 	 * @param $method ������Ӧ��ʽ form�����ر���url����������
	 * @return string url
	 *
	 */
	function get_batch_url($detail_data, $method = 'form') {
		global $_K;
		$body = $subject = "�����������";
		$pay_date = date ( Ymd );
		$batch_no = $pay_date . date ( hms );
		$detail_data = $this->batch_pack_detail($detail_data);
		$parameter = array (
				"service" => 'batch_trans_notify',
				"partner" => $this->_pay_config ['pid'],
				"email" => Keke_valid::email($this->_pay_config ['pay_account'])?$this->_pay_config ['pay_account']:'',
				"account_name" => $this->_pay_config ['pay_account'],
				"notify_url" => $_K ['siteurl'] ."/payment/alipayjs/batch_notify.php",
				"_input_charset" => strtoupper(CHARSET),
				"pay_date" => $pay_date,
				"batch_no" => $batch_no,
				"batch_num" => $detail_data['batch_num'],
				"batch_fee"=>$detail_data['batch_fee'],
				"detail_data"=>$detail_data['detail_data']
		);
		$alipay = new alipay_service ( $parameter, $this->_pay_config ['key'], $this->_sign_type,'batch');
		if ($method == 'form') {
			return $alipay->build_postform ('get');
		} else {
			return $alipay->create_url ();
		}
	}
	
	/**
	 * ��ʼ������
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
	
	/**
	 * ����������ݴ��,�õ��������������ѣ���������������ϸ���ַ���
	 * @param array $detail_data �������ֵ�����
	 * $v�����ʽ[uid,account,username,fee,wid]
	 * @return array (batch_fee,batch_num,batch_data)
	 */
	public function batch_pack_detail($detail_data) {
		$detail_arr = array ();
		$detail_str = '';
		$batch_fee = 0;
		foreach ( (array)$detail_data as $v ) {
			$v ['fee'] = self::get_to_cash( $v ['fee'] );
			$detail_str .= "|" . implode ( "^", $v );
			$batch_fee += floatval ( $v ['fee'] );
		}
		$detail_str = substr ( $detail_str, 1 );
		 
		$detail_arr ['batch_fee'] = $batch_fee;
		$detail_arr ['batch_num'] = count ( $detail_data );
		$detail_arr ['detail_data'] = $detail_str;
		return $detail_arr;
	}
	/**
	 * �����������ݽ�ѹ�ɴ����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public  function batch_unpack_detail($success_str, $fail_str) {
		$detail_arr = array ();
		$detail_str = $success_str . $fail_str;
		if ($detail_str) {
			$arr1 = array_filter ( explode ( "|", $detail_str ) );
			foreach ( $arr1 as $vs ) {
				$v = explode ( "^", $vs );
				if (! empty ( $v )) {
					$detail_arr [$v [0]] = array ("withdraw_id" => $v [0], "fee" => $v [3], "status" => $v [4], "desc" => $v [5], "time" => $v [7] );
				}
			}
		}
		return array_filter ( $detail_arr );
	}
	/**
	 * ֧�������ص�֪ͨ����
	 * @param $detail_arr �����ϸ��Ϣ����
	 * @param $status ��Ӧ״̬
	 */
	public function batch_notify($detail_arr, $status = true) {
		global $_lang,$_K;
		$ids = implode ( ",", array_keys ( $detail_arr ) );
		$info = Arr::get_arr_by_key(DB::select()->from('witkey_withdraw')->where("wid in ($ids)")->execute(),'wid');
		
		foreach ( $detail_arr as $k => $v ) {
			switch ($v ['status']) {
				case "S" :
					/** ���ֳɹ�*/
					$w_cash = self::get_to_cash($info [$k]['cash']);
					$fee = $info [$k]['cash'] - $w_cash;
					Database::instance()->execute( sprintf ( " update %switkey_withdraw set status='1',fee=%.2f where wid ='%d'", TABLEPRE,$fee, $k ) );
					/** �û���Ϣ��ʾ*/
					$arr = array($_lang['sitename']=>$_K['sitename'],$_lang['tx_cash']=>$w_cash);
					Keke_msg::instance()->set_tpl('draw_success')->set_var($arr)->to_user($info[$k]['uid'])->send();
					
					break;
				case "F" :
					/** ����ʧ��*/
					$sql = "update :Pwitkey_withdraw set status=':status' where wid =':wid'";
 					
					DB::query($sql,Database::UPDATE)->tablepre(':P')->param(':status', 2)->param(':wid', $k)->execute();
					
					$v_arr = array('��վ����'=>$_K['sitename'],
							'���ַ�ʽ'=>$info[$k]['type'],
							'�ʻ�'=>$info[$k]['bank_account'],
							'���ֽ��'=>$v ['withdraw_cash']);
					Keke_msg::instance()->set_tpl('withdraw_fail')->set_var($arr)->to_user($info[$k]['uid'])->send();    
					break;
			}
		
		}
	}
	
	/**
	 * ��ȡ����ʵ�����õĽ��,����֧����������
	 * 
	 * ������������վҪ�յ������Ѻ󣬴��֧�����Ľ��
	 * 
	 * @param  $cash ----�û����ֽ��
	 * @return $real_cash  -----�û��ɻ�õ�ʵ�ʽ��
	 */
	public static function get_to_cash($cash){
		//��ȡ��վ����
	 
		$config_info = Arr::get_arr_by_key(DB::select()->from('witkey_pay_config')
		->where("k in('per_charge','per_low','per_high')")->execute(),'k');
		
		$min_cash = $config_info['per_low']['v'];
		$middle_profit = $config_info['per_charge']['v'];
		$max_cash = $config_info['per_high']['v'];
		//����
		if($cash<1){
			return $cash;
		}
			
		if($cash<=200){
			$real_cash = abs($cash - $min_cash);
		}elseif($cash>200&&$cash<=5000){
			$real_cash = $cash - $cash*$middle_profit/100;
		}elseif($cash>5000){
			$real_cash = $cash - $max_cash;
		}
		return $real_cash;
	}
	
}
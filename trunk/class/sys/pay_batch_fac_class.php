<?php
/**
 * �����������(֧�ֵ���)��ҵ������
 * @author Administrator
 *
 */
Keke_lang::load_lang_class('pay_batch_fac_class');
class pay_batch_fac_class {
	private $_pay_mode;
	private $_pay_config;
	
	public static function get_instance($pay_mode) {
		static $obj = null;
		if ($obj == null) {
			$obj = new pay_batch_fac_class ( $pay_mode );
		}
		return $obj;
	}
	public function __construct($pay_mode) {
		$this->_pay_mode = $pay_mode;
		$this->_pay_config = Keke::get_payment_config ( $pay_mode );
	}
	/**      ***********************����ҵ��������**************************     */
	/**
	 * ��������
	 * @param ��� $fee
	 */
	public function format_money($fee) {
		$func_name = $this->_pay_mode . "_format_money";
		return $this->$func_name ( $fee );
	}
	/**
	 * ������������
	 * @param array $detail_data
	 * $v�����ʽ[uid,account,username,fee,withdraw_id]
	 */
	public function stack_batch($detail_data) {
		$func_name = $this->_pay_mode . "_stack_batch";
		return $this->$func_name ( $detail_data );
	}
	/**
	 * ���ɹ�ҵ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function success_notify($success_str, $fail_str) {
		$func_name = $this->_pay_mode . "_success_notify";
		$detail_arr = $this->unpack_detail_data ( $success_str, $fail_str );
		return $this->$func_name ( $detail_arr );
	}
	/**
	 * ��ѹ�����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function unpack_detail_data($success_str, $fail_str) {
		$func_name = $this->_pay_mode . "_unpack_detail";
		return $this->$func_name ( $success_str, $fail_str );
	}

	
	
	
	/**
	 * ֧����������ʽ����
	 */
 	public function alipayjs_format_money($fee) {
		return keke_finance_class::get_to_cash($fee);
	}
	
	/**
	 * ֧������ѹ�����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function alipayjs_unpack_detail($success_str, $fail_str) {
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
	 * ֧�������ɹ�ҵ����
	 * @param $detail_arr �����ϸ��Ϣ����
	 * @param $status ��Ӧ״̬
	 */
	public function alipayjs_success_notify($detail_arr, $status = true) {
		global $_lang;
		$ids = implode ( ",", array_keys ( $detail_arr ) );
		$info = Keke::get_table_data ( "withdraw_id,uid,username,withdraw_status", "witkey_withdraw", " withdraw_id in ($ids)", "", "", "", "withdraw_id" );
		foreach ( $detail_arr as $k => $v ) {
			if ($info [$k] ['withdraw_status'] == 1) {
				switch ($v ['status']) {
					case "S" :
						/** ���ֳɹ�*/
						$res = dbfactory::execute ( sprintf ( " update %switkey_withdraw set withdraw_status='2' where withdraw_id ='%d'", TABLEPRE, $k ) );
						/** �û���Ϣ��ʾ*/
						Keke::notify_user ( $_lang['tx_pay_success_notice'], $_lang['your_alipay_tx_apply_notice'] . $v [fee] . $_lang['yusn_check_your_accout'], $info [$k] ['uid'], $info [$k] ['username'] );
						break;
					case "F" :
						/** ����ʧ��*/
						$res = dbfactory::execute ( sprintf ( " update %switkey_withdraw set withdraw_status='3' where withdraw_id ='%d'", TABLEPRE, $k ) );
						Keke::notify_user ( $_lang['tx_pay_fail_notice'], $_lang['tx_pay_fail_case_is'] . $v ['desc'], $info [$k] ['uid'], $info [$k] ['username'] );
						break;
				}
			}
		}
	}
	
	/**
	 * ������������
	 * @param array $detail_data
	 * $v�����ʽ[uid,account,username,fee,withdraw_id]
	 */
	public function alipayjs_stack_batch($detail_data) {
		$detail_arr = array ();
		$detail_str = '';
		$batch_fee = 0;
		if (is_array ( $detail_data )) {
			foreach ( $detail_data as $v ) {
				$v ['fee'] = $this->format_money ( $v ['fee'] );
				$detail_str .= "|" . implode ( "^", $v );
				$batch_fee += floatval ( $v ['fee'] );
			}
			$detail_str = substr ( $detail_str, 1 );
		}
		$detail_arr ['batch_fee'] = $batch_fee;
		$detail_arr ['detail_data'] = $detail_str;
		$detail_arr ['batch_num'] = count ( $detail_data );
		return $detail_arr;
	}
	/**      ***********************��������ҵ����**************************     */
	
	/**
	 * ����������ʽ����
	 */
	public function chinabank_format_money($fee) {
	
	}
	/**
	 * ������ѹ�����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function chinabank_unpack_detail($success_str, $fail_str) {
		$detail_arr = array ();
		/** ����������...*/
		/** ����������...*/
		return $detail_arr;
	}
	/**
	 * �������ɹ�ҵ����
	 * @param $detail_arr �����ϸ��Ϣ����
	 * @param $status ��Ӧ״̬
	 */
	public function chinabank_success_notify($detail_arr, $status = true) {
	
	}
	
	/**
	 * ������������
	 * @param array $detail_data
	 * $v�����ʽ[uid,account,username,fee,withdraw_id]
	 */
	public function chinabank_stack_batch($detail_data) {
		$detail_arr = array ();
		return $detail_arr;
	}
	/**      ***********************�Ƹ�ͨҵ����**************************     */
	
	/**
	 * �Ƹ�ͨ������ʽ����
	 */
	public function tenpay_format_money($fee) {
	
	}
	
	/**
	 * �Ƹ�ͨ��ѹ�����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function tenpay_unpack_detail($success_str, $fail_str) {
		$detail_arr = array ();
		/** �Ƹ�ͨ������...*/
		/** �Ƹ�ͨ������...*/
		return $detail_arr;
	}
	/**
	 * �Ƹ�ͨ���ɹ�ҵ����
	 * @param $detail_arr �����ϸ��Ϣ����
	 * @param $status ��Ӧ״̬
	 */
	public function tenpay_success_notify($detail_arr, $status = true) {
	
	}
	
	/**
	 * ������������
	 * @param array $detail_data
	 * $v�����ʽ[uid,account,username,fee,withdraw_id]
	 */
	public function tenpay_stack_batch($detail_data) {
		$detail_arr = array ();
		return $detail_arr;
	}
	/**      ***********************����ҵ����**************************     */
	
	/**
	 * ����������ʽ����
	 */
	public function paypal_format_money($fee) {
	
	}
	/**
	 * ������ѹ�����ϸ����
	 * @param $success_str ���ɹ���ϸ������Ϣ
	 * @param $fail_str    ���ʧ����ϸ������Ϣ
	 */
	public function paypal_unpack_detail($success_str, $fail_str) {
		$detail_arr = array ();
		/** ����������...*/
		/** ����������...*/
		return $detail_arr;
	}
	/**
	 * �������ɹ�ҵ����
	 * @param $detail_arr �����ϸ��Ϣ����
	 * @param $status ��Ӧ״̬
	 */
	public function paypal_success_notify($detail_arr, $status = true) {
	
	}
	
	/**
	 * ������������
	 * @param array $detail_data
	 * $v�����ʽ[uid,account,username,fee,withdraw_id]
	 */
	public function paypal_stack_batch($detail_data) {
		$detail_arr = array ();
		return $detail_arr;
	}
}
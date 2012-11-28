<?php defined('IN_KEKE') or die('access denied');

require 'lib/alipay_submit.class.php'; 
require 'lib/alipay_notify.class.php';
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
	
	private $_alipay_config = array();
	
    function __construct(){
    	parent::__construct('alipayjs');
    	$this->_alipay_config['partner']=$this->_pay_config['pid'];
    	$this->_alipay_config['key']=$this->_pay_config['key'];
    	$this->_alipay_config['sign_type']=$this->_sign_type;
    	$this->_alipay_config['input_charset']=strtolower(CHARSET);
    	$this->_alipay_config['cacert']=getcwd().'\\cacert.pem';
    	$this->_alipay_config['transport']='http';
    }
	
	function set_service($value){
		$this->_service = $value;
		return $this;
	}
	
	function get_pay_html($method,$pay_amount, $subject, $order_id,$rid,$bank_code=NULL) {
		$parameter = $this->init_param($pay_amount, $subject, $order_id,$rid );
		
		$alipay = new AlipaySubmit($this->_alipay_config);
		if($method==='post'){
			return $alipay->buildRequestForm($parameter, $method, '�ύ��...');
		}else{
			return $alipay->buildRequestParaToString($parameter);
		}
 
	}
 	
	/**
	 * ������������URL
	 * @param string $detail_data �����ϸ����
 	 * @param $method ������Ӧ��ʽ form�����ر���url����������
	 * @return string url
	 *
	 */
	function get_batch_html($detail_data, $method = 'form') {
		global $_K;
		$body = $subject = "�����������";
		$pay_date = date ( Ymd );
		$batch_no = $pay_date . date ( hms );
		$detail_data = $this->batch_pack_detail($detail_data);
		$parameter = array (
				"service" => 'batch_trans_notify',
				"partner" => $this->_pay_config ['pid'],
				"email" => Keke_valid::email($this->_pay_config ['pay_account'])?$this->_pay_config ['pay_account']:'',
				"account_name" => $this->_pay_config ['pay_user'],
				"notify_url" => $_K ['siteurl'] ."/payment/alipayjs/batch_notify.php",
				"_input_charset" => trim(strtolower((CHARSET))),
				"pay_date" => $pay_date,
				"batch_no" => $batch_no,
				"batch_num" => $detail_data['batch_num'],
				"batch_fee"=>$detail_data['batch_fee'],
				"detail_data"=>$detail_data['detail_data']
		);
		$alipay = new AlipaySubmit($this->_alipay_config);
		if ($method == 'form') {
			return $alipay->buildRequestForm($parameter, 'post', '�ύ');
		} else {
			return $alipay->buildRequestParaToString($parameter);
		}
	}
	
	/**
	 * ��ʼ������
	 * @return array
	 */
	function init_param($pay_amount,$subject, $order_id,$rid){
		global $_K;
		$body = "(from:" . $_SESSION['username'] . ")";
		$order_id = (int)$order_id;
		$rid = (int)$rid;
		//������ֵ��������ֵ
		return array (
				"service" => $this->_service,
				"partner" => trim($this->_pay_config ['pid']),
				"return_url" => $_K ['siteurl'] . '/payment/alipayjs/return.php',
				"notify_url" => $_K ['siteurl'] . '/payment/alipayjs/notify.php',
				"_input_charset" => CHARSET,
				"subject" => $subject,
				"body" => $body,
				"out_trade_no" => "{$_SESSION['uid']}-{$order_id}-$rid",
				"total_fee" => $pay_amount,
				"payment_type" => "1",
				"show_url" => $_K ['siteurl'] . "/index.php/user/account_index",
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
			$v ['cash'] = self::get_to_cash( $v ['cash'] );
			$v['mem']='withdraw';
			$detail_str .= "|" . implode ( "^", $v );
			$batch_fee += floatval ( $v ['cash'] );
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
	 * @return array detail_arr
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
					//��������,����������ʱ�ͽ�������(����)������� �浽feed�ֶ���
					//$fee = $info [$k]['cash'] - $w_cash;
					//Database::instance()->execute( sprintf ( " update %switkey_withdraw set status='1',fee=%.2f where wid ='%d'", TABLEPRE,$fee, $k ) );
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
	 * alipay notify ����
	 * @return AlipayNotify
	 */
	public function get_alipay_notify(){
		return new AlipayNotify($this->_alipay_config);
	}
}
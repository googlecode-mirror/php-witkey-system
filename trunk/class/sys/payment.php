<?php  defined('IN_KEKE') or die('access denied');
/**
 * ���߳�ֵ������
 * @author Michael
 * @version 2.2 2012-11-24
 *
 */
abstract class Sys_payment {

	protected static $_default = 'alipayjs';
	/**
	 * @var ֧������ 
	 */
	protected $_pay_config = array();
	
	public static $_instances = array();
	/**
	 * ����֧������
	 * @param string $name (alipayjs,chinabank,payapl,tenpay,yeepay)
	 * @return Alipayjs
	 */
	public static function factory($name=NULL){
		if($name===NULL){
			$name = self::$_default;
		}
		if(Keke_valid::not_empty(self::$_instances[$name])){
			return self::$_instances[$name];
		}
		include S_ROOT.'payment/'.$name.'/'.$name.'.php';
		$class = new $name($name);
		return self::$_instances[$name] = $class;
	}
	
	public function __construct($name){
	   $pay_arr = DB::select()->from('witkey_pay_api')->execute();
	   $payment_arr = Arr::get_arr_by_key($pay_arr,'payment');
	   $this->_pay_config = $payment_arr[$name];
	}
	/**
	 * ��ȡ�����url
	 * @param string $charge_type (order,balance)
	 * @param float $pay_amount
	 * @param string $subject
	 * @param int $order_id
	 * @param int $model_id
	 * @param int $obj_id
	 */
	abstract public function get_pay_url($charge_type, $pay_amount,  $subject, $order_id, $model_id = null, $obj_id = null);
	/**
	 * ��ȡ�����form html
	 * @param string $charge_type (order,balance)
	 * @param float $pay_amount
	 * @param string $subject
	 * @param int $order_id
	 * @param int $model_id
	 * @param int $obj_id
	 * @return form
	 */
	abstract public function get_pay_form($charge_type, $pay_amount,  $subject, $order_id, $model_id = null, $obj_id = null);
	
	
}


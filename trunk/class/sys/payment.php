<?php  defined('IN_KEKE') or die('access denied');
/**
 * 在线充值请求处理
 * @author Michael
 * @version 2.2 2012-11-24
 *
 */
abstract class Sys_payment {

	protected static $_default = 'alipayjs';
	/**
	 * @var 支付配置 
	 */
	protected $_pay_config = array();
	
	public static $_instances = array();
	/**
	 * 在线支付工厂
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
	 * 获取付款的url
	 * @param string $charge_type (order,balance)
	 * @param float $pay_amount
	 * @param string $subject
	 * @param int $order_id
	 * @param int $model_id
	 * @param int $obj_id
	 */
	abstract public function get_pay_url($charge_type, $pay_amount,  $subject, $order_id, $model_id = null, $obj_id = null);
	/**
	 * 获取付款的form html
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


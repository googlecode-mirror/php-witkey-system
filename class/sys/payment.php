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
	 * 获取付款的html
	 * @param string $method (post,get)
	 * @param float $pay_amount 充值金额
	 * @param string $subject  标题
	 * @param int $order_id  订单ID
	 * @param int $rid 充值记录ID
	 * @return string (form,url)
	 */
	abstract public function get_pay_html($method,$pay_amount,$subject, $order_id,$rid);
	
	/**
	 * 充值状态,主要用在线下充值
	 */
	public static function recharge_status(){
	      return array('wait'=>'待确认','ok'=>'充值成功','fail'=>'充值失败');
	}
	
	/**
	 * 获取威客实际所得的金额,用在支付宝批量打款处
	 *
	 * 这里面会算出网站要收的手续费后，打给支付宝的金额
	 *
	 * @param  $cash ----用户提现金额
	 * @return $real_cash  -----用户可获得的实际金额
	 */
	public static function get_to_cash($cash){
		//获取网站配置
	
		$config_info = Arr::get_arr_by_key(DB::select()->from('witkey_pay_config')
				->where("k in('per_charge','per_low','per_high')")->execute(),'k');
	
		$min_cash = $config_info['per_low']['v'];
		$middle_profit = $config_info['per_charge']['v'];
		$max_cash = $config_info['per_high']['v'];
		//调试
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


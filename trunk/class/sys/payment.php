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
	 * ��ȡ�����html
	 * @param string $method (post,get)
	 * @param float $pay_amount ��ֵ���
	 * @param string $subject  ����
	 * @param int $order_id  ����ID
	 * @param int $rid ��ֵ��¼ID
	 * @return string (form,url)
	 */
	abstract public function get_pay_html($method,$pay_amount,$subject, $order_id,$rid);
	
	/**
	 * ��ֵ״̬,��Ҫ�������³�ֵ
	 */
	public static function recharge_status(){
	      return array('wait'=>'��ȷ��','ok'=>'��ֵ�ɹ�','fail'=>'��ֵʧ��');
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


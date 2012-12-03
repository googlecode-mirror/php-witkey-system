<?php  defined('IN_KEKE') OR die('access deiend');
/**
 * ���Ҵ���
 * ���ҵ�ת�����
 * 
 */
class Curren {
	
	const CONV_URL = 'http://www.google.com/ig/calculator?hl=en&q=1'; //����ת���ӿ�.CNY=?USD
	static $_curr; //Ĭ�ϱ���
	static $_now; //��ǰ����
	static $_default; //Ĭ�ϱ�����Ϣ
	static $_currencies; //������Ϣ����
	static $_symbol_right;
	

	public static function get_instance() {
		static $_obj = null;
		if ($_obj == null) {
			$_obj = new self();
		}
		return $_obj;
	}
	public function __construct() {
		self::$_currencies = self::get_curr_list();
		self::$_curr = strtoupper ( Keke::$_sys_config ['currency'] ); //Ĭ�ϱ���
		self::$_now = Keke::$_currency;
		self::$_default = self::$_currencies [self::$_curr];
		self::$_symbol_right = self::$_currencies [self::$_curr]['symbol_right'];
	}
	/**
	 * ��ȡ�����б�
	 */
	public static function get_curr_list($code='*'){
		//return self::$_currencies = Keke::get_table_data ($code, 'witkey_currencies', '', '', '', '', 'code', 3600 );
		$res = DB::select()->from('witkey_currencies')->cached(99999,'keke_currencies')->execute();
		return Keke::get_arr_by_key($res,'code');
		
	}
	/**
	 * ������ʾ
	 * @param $v ��ֵ
	 * @param��$dec �Զ�����
	 * @param  $simple  ����ģʽ  ,��ת��������,��format
	 */
	public static function output($v,$dec=-1,$simple=false) {
		self::get_instance();
		$curr = self::$_now;
		$data = self::$_currencies [$curr];
		$v = (float)$v;
		if ($curr != self::$_curr) {
			$v = self::convert ( $v,$dec );
		}
		$dec>-1 and $dec = intval($dec) or $dec = $data ['decimal_places'];
		if($simple){
			return Keke::k_round($v,$dec);
		}else{
			return $data ['symbol_left'] . number_format ( $v, $dec, $data ['decimal_point'], $data ['thousands_point'] ) . $data ['symbol_right'];
		}
	}
	/**
	 * ����ת��[����Ҫ���л���ת������ʹ�ô˷���]
	 * @param $v ������ֵ
	 * @param��$dec �Զ�����
	 * @param $reverse �Ƿ�ת
	 * [
	 *		 false=>����ת������Ĭ�ϻ���ת��Ϊ��ǰѡ�����
	 * 		true=>��ת,���ڲ�����㣬�ӵ�ǰѡ�����ת��ΪĬ�ϻ���
	 * ]
	 */
	public static function convert($v,$dec=-1, $reverse = false) {
		self::get_instance();
		$curr = self::$_now; //��ǰѡ�����
		$defa = self::$_default;
		$data = self::$_currencies [$curr];
		if ($v) {
			if ($curr != self::$_curr) {
				if ($reverse) {
					$rate = 1 / $data ['value'];
					$rate = Keke::k_round ( $rate, $defa ['decimal_places'] );
				} else {
					$rate = $data ['value'];
				}
				$v = Keke::k_round ( $v * $rate, $data ['decimal_places'] );
				if($dec==-1){
					$reverse == true and $dec = $defa ['decimal_places'] or $dec = $data ['decimal_places'];
				}else{
					$dec = intval($dec);
				}
				$v = Keke::k_round ( $v, $dec );
			}
		}
		return $v;
	}
	/**
	 * ���ұ�ǩ
	 * @param $v ��ֵ
	 * @param $dec ���ȡ�Ĭ�ϱ���2λ ��ҳ����ʾ���ѣ�����Ҫ�߾���
	 */
	static function currtags($v,$dec) {
		global $_K;
		$_K ['i'] ++;
		isset($dec) and $dec = intval($dec) or $dec = -1;
		$search = "<!--CURR_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php  echo Curren::output(floatval({$v}),{$dec});  ?>";
		return $search;
	}
	/**
	 * ���±��ֻ���
	 * [֧����������]
	 * @param $mulit �Ƿ�����
	 * @param $ex �����±���
	 */
	public function update($mulit = false, $ex = '') {
		$ex = strtoupper ( $ex );
		$res = false;
		if ($mulit == true) {
			$data = self::$_currencies;
			unset ( $data [self::$_curr] );
			$s = sizeof ( $data );
			if ($s) {
				foreach ( $data as $k => $v ) {
					$res = $this->update ( false, $k );
					if ($res == false) {
						break;
					}
				}
			} else {
				$res = true;
			}
		} else {
			if ($ex) {
				if ($ex == self::$_curr) {
					$res = true;
				} else {
					$url = self::CONV_URL . self::$_curr . '=?' . $ex;
					$data = Keke::get_remote_data ( $url );
					//����{lhs: "1 Chinese yuan",rhs: "0.157406 U.S. dollars",error: "",icc: true}
					$data = explode ( '"', $data );
					$data = explode ( ' ', $data ['3'] );
					$rate = floatval ( $data [0] ); //����
					$rate and $res = Dbfactory::execute ( sprintf ( " update %switkey_currencies set value='%.8f',last_updated='%s' where code='%s'", TABLEPRE, $rate, time (), $ex ) );
				}
			}
		}
		return $res;
	}
}
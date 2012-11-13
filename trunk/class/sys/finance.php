<?php

/**
 * @example �������
 * @access class
 * @author shangk
 *
 *
 */
Keke_lang::load_lang_class ( 'sys_finance' );
class Sys_finance {
	public static $_basic_config;
	// ���
	public static $_cash;
	/**
	 * ����: in ,out
	 */
	public static $_type;
	// ����
	public static $_action;
	// task,service,null
	public static $_obj_type;
	public static $_obj_id;
	/**
	 * ���� ,��̬����
	 */
	public static $_mem;
	public static function init($uid = null) {
		global $_lang;
		global $user_info;
	
		if ($user_info ['uid'] != $uid) {
			$user_info = Keke_user::instance()->get_user_info ( $uid );
		}
		if ($user_info) {
			try {
				// ��̬��������ݲ�����
				return $user_info;
			} catch ( Exception $e ) {
				Keke_exception::handler ( $e );
			}
		} else {
			
			throw new Keke_exception ( $_lang ['uid_parameter_no_value'] );
		}
	}
	/**
	 *
	 *
	 * �����û�֧���ļ��㴦��
	 * 
	 * @param int $uid        	
	 * @param float $cash        	
	 * @param string $action
	 *        	realname_auth=>ʵ����֤(out)
	 *        	bank_auth=>������֤(out)
	 *        	company_auth=>��˾��֤(out)
	 *        	email_auth=>����������֤(out)
	 *        	mobile_auth=>�ֻ���֤(out)
	 *        	buy_vip=>����vip ���(out)
	 *        	buy_service=>�������(out)
	 *        	prom_task=>�ƹ�����(out)
	 *        	hide_task=>��������(out)
	 *        	hide_work=>���ظ��(out)
	 *        	tj_task=>�Ƽ�����(out)
	 *        	pub_task=>��������(out)
	 *        	withdraw=>����(out)
	 *        	task_ext=>��������(out)
	 *        	report=>�ٲô���(out)
	 *        	pay_item=>��ֵ����(out)
	 * @param float $profit
	 *        	- վ������
	 * @param string $obj_type
	 *        	-task,service,order
	 * @param id $obj_id        	
	 * @return boolen true or false �ɹ� or ʧ��
	 */
	public static function cash_out($uid, $cash, $action, $profit = 0, $obj_type = null, $obj_id = null) {
		$user_info = self::init ( $uid );
		$res = false;
		$sys_config = Keke::$_sys_config;
		$fo = new Keke_witkey_finance ();
		$fo->setFina_action ( $action );
		$fo->setFina_type ( "out" );
		$fo->setObj_type ( $obj_type );
		$fo->setObj_id ( $obj_id );
		$fo->setSite_profit ( $profit );
		$fo->setUid ( $user_info ['uid'] );
		$fo->setUsername ( $user_info ['username'] );
		if (empty ( self::$_mem )) { 
			// ���û�г�ʼ�����ɻ��߳�ʼ��ʧ��,�͵���Ĭ�ϼ򵥵���Ϊ����
			$action_arr = keke_global_class::get_finance_action ( $action );
			self::$_mem = $action_arr [$action];
		}
		$fo->setFina_mem ( self::$_mem );
		$user_balance = $user_info ['balance'];
		$user_credit = $user_info ['credit'];
		$credit_allow = intval ( $sys_config ['credit_is_allow'] ) + 0;
		if ($cash && $action) {
			try {
				// �жϻ����Ƿ���
				$credit_allow == 2 and $user_credit = 0;
				// �Ƿ���Ǯ����
				if ($user_balance + $user_credit < $cash) {
					return false;
				}
				// �����ж�,����ȯ����������
				if ($action == 'withdraw') {
					// �۳��˸��ʻ�
					Dbfactory::execute ( "update " . TABLEPRE . "witkey_space set balance = balance-" . abs ( floatval ( $cash ) ) . " where uid ='{$user_info['uid']}'" );
					$fo->setFina_cash ( $cash );
					$fo->setFina_credit ( 0 );
					$fo->setUser_balance ( $user_balance - abs ( $cash ) );
				} else {
					// ����ʣ����֣��ȿ۴���ȯ
					$sy_credit = $user_credit - $cash;
					if ($sy_credit > 0) {
						// �����û�����
						Dbfactory::execute ( "update " . TABLEPRE . "witkey_space set credit = credit-{$cash} where uid ='{$user_info['uid']}'" );
						$fo->setFina_credit ( $cash );
						$fo->setFina_cash ( 0 );
						$fo->setUser_balance ( $user_balance );
						$fo->setUser_credit ( $user_credit - $cash );
					} else {
						// ������������
						Dbfactory::execute ( "update " . TABLEPRE . "witkey_space set credit = credit-{$user_credit},balance = balance-" . abs ( $sy_credit ) . " where uid ='{$user_info['uid']}'" );
						$fo->setFina_credit ( $user_credit );
						$fo->setFina_cash ( abs ( $sy_credit ) );
						$fo->setUser_balance ( $user_balance - abs ( $sy_credit ) );
						$fo->setUser_credit ( 0 );
					}
				}
				$fo->setFina_time ( time () );
				$res = $fo->create ();
			} catch ( Exception $e ) {
				keke_exception::handler ( $e );
			}
		}
		return $res;
	}
	/**
	 * �����û�������㴦��
	 * 
	 * @param int $uid        	
	 * @param float $cash
	 *        	-�ֽ�
	 * @param float $credit
	 *        	- ����
	 * @param string $action
	 *        	- ����
	 *        	online_recharge=>���߳�ֵ(in)
	 *        	offline_recharge=>���߳�ֵ(in)
	 *        	task_bid=>�����б�(in)
	 *        	task_fail=>����ʧ���˿�(in)
	 *        	task_prom=>�����ƹ�ɹ���Ӷ��(in)
	 *        	task_prom_fail=>�����ƹ�ʧ���˿�(in)
	 *        	rights_return=>άȨ����(in)
	 *        	sale_service=>���������(in)
	 *        	admin_recharge=>����Ա��ֵ(in)
	 *        	withdraw_fail=>����ʧ��(in)
	 *        	ucenter_change=>ucenter �һ�(in)
	 * @param string $source
	 *        	- zfb,cft,line,paypal,admin
	 * @param string $obj_type
	 *        	- task,service,order,vip
	 * @param id $obj_id        	
	 * @param float $profit
	 *        	- ����
	 * @return boolen true or false
	 */
	public static function cash_in($uid, $cash, $credit = 0, $action, $source = null, $obj_type = null, $obj_id = null, $profit = 0, $charge = null) {
		// �û����� ��Դ ��ֵ �����б�,����ʧ���˿�ƹ�ʧ���˿�,��������(��Ʒ),������ʧ���˿�,����ʧ���˿�
		
		$user_info = self::init ( $uid );
		$sys_config = self::$_basic_config;
		$fo = new Keke_witkey_finance ();
		$fo->setFina_action ( $action );
		$fo->setFina_type ( "in" );
		$fo->setObj_type ( $obj_type );
		$fo->setObj_id ( $obj_id );
		$fo->setFina_credit ( $credit );
		$fo->setFina_cash ( $cash );
		if (empty ( self::$_mem )) { // ���û�г�ʼ�����ɻ��߳�ʼ��ʧ��,�͵���Ĭ�ϼ򵥵���Ϊ����
			$action_arr = keke_global_class::get_finance_action ( $action );
			self::$_mem = $action_arr [$action];
		}
		$fo->setFina_mem ( self::$_mem );
		$fo->setUser_balance ( $user_info ['balance'] + $cash );
		$fo->setUser_credit ( $user_info ['credit'] + $credit );
		$fo->setUid ( $user_info ['uid'] );
		$fo->setUsername ( $user_info ['username'] );
		$fo->setFina_source ( $source );
		$fo->setSite_profit ( $profit );
		$fo->setFina_mem ( self::$_mem );
		$fo->setRecharge_cash ( $charge !== null ? floatval ( $charge ) : null );
		$sql = "update " . TABLEPRE . "witkey_space set credit = credit+{$credit},balance = balance+" . $cash . " where uid ='{$user_info['uid']}'";
		$res = Dbfactory::execute ( $sql );
		if ($res) {
			$fo->setFina_time ( time () );
			$row = $fo->create ();
			return $row;
		} else {
			return false;
		}
	}
	
	/**
	 * ��ȡ����ʵ�����õĽ��
	 * 
	 * @param $cash ----�û����ֽ��        	
	 * @return $real_cash -----�û��ɻ�õ�ʵ�ʽ��
	 */
	public static function get_to_cash($cash) {
		// ��ȡ��վ����
		$config_info = Keke::get_table_data ( "*", "witkey_pay_config", " k in('per_charge','per_low','per_high')", '', '', '', 'k' );
		$min_cash = $config_info ['per_low'] ['v'];
		$middle_profit = $config_info ['per_charge'] ['v'];
		$max_cash = $config_info ['per_high'] ['v'];
		// ����
		if ($cash < 1) {
			return $cash;
		}
		
		if ($cash <= 200) {
			$real_cash = abs ( $cash - $min_cash );
		} elseif ($cash > 200 && $cash <= 5000) {
			$real_cash = $cash - $cash * $middle_profit / 100;
		} elseif ($cash > 5000) {
			$real_cash = $cash - $max_cash;
		}
		return $real_cash;
	}
	
	/**
	 * ��̨վ��֧�������
	 */
	public static function alipayjs_format_moneys($cash) {
		$website_cash = keke_finance_class::get_to_cash ( $cash );
		$alipay_per_charge = 0.5;
		$alipay_per_low = 1;
		$alipay_per_high = 25;
		
		// ����
		if ($website_cash <= 1) {
			return $website_cash;
		}
		if ($website_cash <= 200) {
			$real_website_cash = $website_cash + $alipay_per_low;
		} elseif ($website_cash > 200 && $website_cash <= 5000) {
			$real_website_cash = $website_cash + $website_cash * $alipay_per_charge / 100;
		} elseif ($website_cash > 5000) {
			$real_website_cash = $website_cash + $alipay_per_high;
		}
		return $real_website_cash;
	}
	/**
	 * ��ʼ�������������
	 * 
	 * @param string $action
	 *        	- ����
	 * @param
	 *        	array or string $data -��Ӧ����ģ���滻����ı�������\r\n
	 *        	�������ĳ�Ա����$_action_arr�Ķ���
	 * @return boolen true or false
	 */
	public static function init_mem($action, array $data) {
		$action_arr = self::get_action_lang ();
		$action_str = $action_arr [$action];
		if (! $action_str)
			return false;
		if (is_array ( $data )) { // Ĭ��ģ���滻
			self::$_mem = strtr ( $action_str, $data );
		} else { // ֱ��ʹ���ַ���ƴ������
			self::$_mem = $data;
		}
	}
	/**
	 * ���в��������Ϊ��Ӧ���¼�����������������
	 * 
	 * @var array $_action_arr	-�������ɶ���
	 *      key������Ϊ(action),value������������,%%����Ҫ�滻�ı���
	 */
	public static function get_action_lang() {
		global $_lang;
		return array ('pub_task' => $_lang ['release'] . ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>', 'task_delay' => $_lang ['extension_task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>', 'buy_service' => $_lang ['purchase_service_goods'] . '<a href="index.php?do=service&sid=:service_id">:title</a>', 'payitem' => $_lang ['purchase'] . ':item_name' . $_lang ['value_add_services'], 'hosted_reward' => $_lang ['managed'] . ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['bounty'], 'task_fail' => ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['failure_refund'], 'host_deposit' => $_lang ['managed'] . ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['earnest_money'], 'task_bid' => $_lang ['involved_task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>��' . $_lang ['manuscript_selected_success'], 'task_auto_return' => ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['automate_return_remain'], 'sale_service' => $_lang ['sell_services_goods'] . '<a href="index.php?do=service&sid=:service_id">:title</a>' . $_lang ['income'], 'task_remain_return' => ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['settlement_balance_return'], 'task_hosted_return' => ':model_name' . $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['managed_balance_return'], 'order_cancel' => $_lang ['order'] . ':order_id' . $_lang ['terminate_rebate'], 'host_return' => $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['managed_commission_refund'], 'host_split' => $_lang ['task'] . '<a href="index.php?do=task&task_id=:task_id">:task_title</a>' . $_lang ['managed_commission_allocate'], 'withdraw' => ':pay_way' . $_lang ['account'] . ':pay_account' . $_lang ['user'] . ':pay_name' . $_lang ['to_cash'], 'withdraw_fail' => ':pay_way' . $_lang ['account'] . ':pay_account' . $_lang ['user'] . ':pay_name' . $_lang ['cash_failed_return_refund'], 'recharge' => ":bank ��ֵ :cash" );
	}
}

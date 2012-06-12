<?php

/**
 * @example �������
 * @access class
 * @author shangk
 *
 *
 */
keke_lang_class::load_lang_class('keke_finance_class');
class keke_finance_class {
	
	public static $_basic_config;
	//���
	public static $_cash;
	/**
	 * ����:   in ,out
	 */
	public static $_type;
	//����
	public static $_action;
	//task,service,null
	public static $_obj_type;
	public static $_obj_id;
	public static $_userinfo;
	
	public static function init($uid = null) {
		global $kekezu;
		global $_lang;
		self::$_basic_config =kekezu::$_sys_config;
		if ($uid) {
			try{
				self::$_userinfo = kekezu::get_user_info ( $uid );
			}catch (Exception $e){
				keke_exception::handler($e);
			}
		} else {
			die ( $_lang['uid_parameter_no_value'] );
		}
	}
	/**
	 *
	 * �����û�֧���ļ��㴦��
	 * @param int $uid
	 * @param float $cash
	 * @param string $action
	 * realname_auth=>ʵ����֤(out)
	 * bank_auth=>������֤(out)
	 * company_auth=>��˾��֤(out)
	 * email_auth=>����������֤(out)
	 * mobile_auth=>�ֻ���֤(out)
	 * buy_vip=>����vip ���(out)
	 * buy_service=>�������(out)
	 * prom_task=>�ƹ�����(out)
	 * hide_task=>��������(out)
	 * hide_work=>���ظ��(out)
	 * tj_task=>�Ƽ�����(out)
	 * pub_task=>��������(out)
	 * withdraw=>����(out)
	 * task_ext=>��������(out)
	 * report=>�ٲô���(out)
	 * pay_item=>��ֵ����(out)
	 * @param float $profit - վ������
	 * @param string $obj_type   -task,service,order
	 * @param id $obj_id
	 * @return boolen true or false �ɹ�   or ʧ��
	 */
	public static function cash_out($uid, $cash, $action, $profit = 0, $obj_type = null, $obj_id = null) {
		self::init ( $uid ); 
		$res = false;
		$sys_config = self::$_basic_config;
		$user_info = self::$_userinfo;
		$fo = new Keke_witkey_finance_class ();
		$fo->setFina_action ( $action );
		$fo->setFina_type ( "out" );
		$fo->setObj_type ( $obj_type );
		$fo->setObj_id ( $obj_id );
		$fo->setSite_profit ( $profit );
		$fo->setUid ( $user_info [uid] );
		$fo->setUsername ( $user_info [username] );
		$user_balance = $user_info ['balance'];
		$user_credit = $user_info ['credit'];
		$credit_allow = intval ( $sys_config ['credit_is_allow'] ) + 0;
		 
		if ($cash && $action) {
			try{
			//�жϻ����Ƿ���
			$credit_allow==2 and $user_credit = 0;
			//�Ƿ���Ǯ����
			if ($user_balance + $user_credit < $cash) {
				return false;
			}
			// �����ж�,����ȯ����������
			if ($action == 'withdraw') {
				//�۳��˸��ʻ�
				db_factory::execute ( "update " . TABLEPRE . "witkey_space set balance = balance-" . abs ( floatval ( $cash ) ) . " where uid ='{$user_info['uid']}'" );
				$fo->setFina_cash ( $cash );
				$fo->setFina_credit ( 0 );
				$fo->setUser_balance ( $user_balance - abs ( $cash ) );
			} else {
				//����ʣ����֣��ȿ۴���ȯ
				$sy_credit = $user_credit - $cash;
				if ($sy_credit > 0) {
					//�����û�����
					db_factory::execute ( "update " . TABLEPRE . "witkey_space set credit = credit-{$cash} where uid ='{$user_info['uid']}'" );
					$fo->setFina_credit ( $cash );
					$fo->setFina_cash ( 0 );
					$fo->setUser_balance ( $user_balance );
					$fo->setUser_credit ( $user_credit - $cash );
				
				} else {
					//������������
					db_factory::execute ( "update " . TABLEPRE . "witkey_space set credit = credit-{$user_credit},balance = balance-" . abs ( $sy_credit ) . " where uid ='{$user_info['uid']}'" );
					$fo->setFina_credit ( $user_credit );
					$fo->setFina_cash ( abs ( $sy_credit ) );
					$fo->setUser_balance ( $user_balance - abs ( $sy_credit ) );
					$fo->setUser_credit ( 0 );
				
				}
			}
			$fo->setFina_time ( time () );
			$res = $fo->create_keke_witkey_finance ();
			}catch (Exception $e){
				keke_exception::handler($e);
			}
			//unique_num ����ֶ������Ѿ�������
			/* $sql = "update " . TABLEPRE . "witkey_finance set unique_num = CONCAT('88',LPAD(LAST_INSERT_ID(),8,'0')) where fina_id = last_insert_id() ";
			db_factory::execute ( $sql ); */
		} 
		return $res;
	}
	/**
	 * �����û�������㴦��
	 * @param int $uid
	 * @param float $cash      -�ֽ�
	 * @param float $credit  - ����
	 * @param string $action   - ����
	 * online_recharge=>���߳�ֵ(in)
	 * line_recharge=>���߳�ֵ(in)
	 * task_bid=>�����б�(in)
	 * task_fail=>����ʧ���˿�(in)
	 * task_prom=>�����ƹ�ɹ���Ӷ��(in)
	 * task_prom_fail=>�����ƹ�ʧ���˿�(in)
	 * rights_return=>άȨ����(in)
	 * sale_service=>���������(in)
	 * admin_recharge=>����Ա��ֵ(in)
	 * withdraw_fail=>����ʧ��(in)
	 * ucenter_change=>ucenter �һ�(in)
	 * @param string $source  - zfb,cft,line,paypal,admin
	 * @param string $obj_type  - task,service,order,vip
	 * @param id $obj_id
	 * @param float $profit  - ����
	 * @return boolen true or false
	 */
	public static function cash_in($uid, $cash, $credit = 0, $action, $source = null, $obj_type = null, $obj_id = null, $profit = 0, $charge = null) {
		//�û����� ��Դ  ��ֵ   �����б�,����ʧ���˿�ƹ�ʧ���˿�,��������(��Ʒ),������ʧ���˿�,����ʧ���˿�
		self::init ( $uid );
		$user_info = self::$_userinfo;
		$sys_config = self::$_basic_config;
		$fo = new Keke_witkey_finance_class ();
		$fo->setFina_action ( $action );
		$fo->setFina_type ( "in" );
		$fo->setObj_type ( $obj_type );
		$fo->setObj_id ( $obj_id );
		$fo->setFina_credit ( $credit );
		$fo->setFina_cash ( $cash );
		$fo->setUser_balance ( $user_info ['balance'] + $cash );
		$fo->setUser_credit ( $user_info ['credit'] + $credit );
		$fo->setUid ( $user_info [uid] );
		$fo->setUsername ( $user_info [username] );
		$fo->setFina_source ( $source );
		$fo->setSite_profit ( $profit );
		$fo->setRecharge_cash ( $charge !== null ? floatval ( $charge ) : null );
		$sql = "update " . TABLEPRE . "witkey_space set credit = credit+{$credit},balance = balance+" . $cash . " where uid ='{$user_info['uid']}'";
		$res = db_factory::execute ( $sql );
		if ($res) {
			$fo->setFina_time ( time () );
			$row = $fo->create_keke_witkey_finance ();
			//unique_num �ֶ��Ѿ�û������
			/* $sql2 = "update " . TABLEPRE . "witkey_finance set  unique_num=CONCAT('88',LPAD(LAST_INSERT_ID(),8,'0')) where fina_id = last_insert_id()";
			db_factory::execute ( $sql2 );
			 */
			return $row;
		} else {
			return false;
		}
	
	}
	/**
	 * ���� �����¼
	 * ���ı��û����
	 * @param array $data  ����
	 * @param string $trust_type ��������
	 * @param string $fina_type �������� in��out
	 */
	public static function finance_trust($data = array(), $trust_type = 'alipay_trust', $fina_type = 'in') {
		$fina_obj = keke_table_class::get_instance ( "witkey_finance" );
		$data ['is_trust'] = 1;
		$data ['trust_type']=$data ['fina_source'] = $trust_type;
		$data ['fina_type']  =$fina_type;
		$fina_id = $fina_obj->save ( $data );
		$sql = "update " . TABLEPRE . "witkey_finance set unique_num = CONCAT('88',LPAD(LAST_INSERT_ID(),8,'0')) where fina_id = last_insert_id() ";
		db_factory::execute ( $sql );
		return $fina_id;
	}
	
	
	
	
	/**
	 * ��ȡ����ʵ�����õĽ�� 
	 * @param  $cash ----�û����ֽ��
	 * @return $real_cash  -----�û��ɻ�õ�ʵ�ʽ��
	 */
	public static function get_to_cash($cash){
		//��ȡ��վ����
		$config_info = kekezu::get_table_data("*","witkey_pay_config"," k in('per_charge','per_low','per_high')",'','','','k');
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
	
	
	
	
	
	
	/**
	 * ��̨վ��֧�������
	 *
	 */
	
	public static function alipayjs_format_moneys($cash){
		$website_cash = keke_finance_class::get_to_cash($cash);
		$alipay_per_charge = 0.5;
		$alipay_per_low = 1;
		$alipay_per_high = 25;
		
		//����
		if($website_cash<=1){ 
			return $website_cash;
		} 
		if($website_cash<=200){
			$real_website_cash  = $website_cash+$alipay_per_low;
			
		}elseif($website_cash>200&&$website_cash<=5000){
			$real_website_cash = $website_cash+$website_cash*$alipay_per_charge/100;
		}elseif($website_cash>5000){
			$real_website_cash = $website_cash+$alipay_per_high;
		}
		return $real_website_cash;
	}
	
	
	
	
	
	

}
  
?>
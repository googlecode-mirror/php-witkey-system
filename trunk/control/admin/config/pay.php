<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ֧������
 * @author Michael	
 * @version v 2.2
 * 2012-10-01
 */
class Control_admin_config_pay extends Controller{
	/**
	 * ֧������
	 */
	function action_index(){
		global $_K,$_lang;
		//���ύʱ
		if(!$_POST){
			//��ȡҪ�༭������
			$res = DB::select('k,v')->from('witkey_pay_config')->execute();
			//��ֵ����
			$pay_config = self::get_arr_by_key($res,'k');
			//����֧������ģ��
			require Keke_tpl::template('control/admin/tpl/config/pay');
			die;
		}
		//����ȫ���
		Keke::formcheck($_POST['formhash']);
		//ȥ��formhash
		unset($_POST['formhash']);
		//��������
		foreach ($_POST as $k=>$v){
			DB::update('witkey_pay_config')->set(array('v'))->value(array($v))
			->where("k = '$k'")->execute();
		}
		//��ת
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_pay','success');
	}
	/**
	 * ��ָ���ļ��������ά����
	 * @param array $array
	 * @param string $key
	 */
	static function get_arr_by_key(array $array,$key){
		$temp = array();
		foreach ($array as $k=>$v){
			$temp[$v[$key]] = $v['v'];
		}
		return $temp;
	}
	/**
	 * ����֧��
	 */
	function action_online(){
		global $_K,$_lang;
		$payment_list = DB::select()->from('witkey_pay_api')->where("type='online'")->execute();
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_online');
	}
	/**
	 * ����֧�� 
	 */
	function action_offline(){
		global $_K,$_lang;
		
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_offline');
	}
	/**
	 * �ı�֧���ӿڵ�״̬ 
	 * @example 0 ���� 1 ����
	 */
	function action_change_status(){
		//״̬
		$status = $_GET['status'];
		//����
		$pay_id = $_GET['pay_id'];
		//�ı�״̬
		DB::update('witkey_pay_api')->set(array('status'))->value($status)
		->where("pay_id='$pay_id'")->execute();
	}
	
}


/* Keke::admin_check_role ( 2 );

$pay_obj = new Keke_witkey_pay_config_class ();

$op or $op = 'config';
$Keke->_cache_obj->gc();
$url = "index.php?do=$do&view=$view&op=$op"; 
if (isset ( $sbt_edit )) { 
	 Dbfactory::execute("TRUNCATE TABLE ".TABLEPRE."witkey_pay_config"); 
	if (is_array ( $fds )) {  
		foreach ( $fds as $k => $v ) {
			$pay_obj->setConfig_id(null); 
			$pay_obj->setK( $k );
			$pay_obj->setV($v); 
			$res .= $pay_obj->create_keke_witkey_pay_config();
		}
	}
	
	if ($res) {
		$Keke->_cache_obj->del ( "keke_witkey_paypal_config" );
		Keke::admin_system_log ( $_lang ['edit_pay_config'] );
		Keke::admin_show_msg ( $_lang ['pay_config_set_success'], $url, 3, '', 'success' );
	} else {
		Keke::admin_show_msg ( $_lang ['pay_config_set_falid'], $url, 3, '', 'warning' );
	}
}
switch ($op) {
	case "config" :
	
		$pay_config = Keke::get_table_data ( "*", "witkey_pay_config", '', '', "", '', 'k' );
		//var_dump($pay_config);
		break;
	case "online" :
		$payment_list = Keke::get_payment_config ();
		break;
	case "trust" :
		if ($ac == 'edit') {
			require S_ROOT . "payment/" . $pay_dir . "/control/admin/admin_edit.php";
		} else {
			$payment_list = Keke::get_payment_config ( '', $op );
		}
		break;
	case "offline" :
		
		$bank_arr = keke_glob_class::get_bank ();
		
		$payment_list = Keke::get_payment_config ( '', $op );
		
		if ($ac) {
			$pay = $payment_list [$payment]; //��������
			

			$pay ['config'] and $pay_config = unserialize ( $pay ['config'] ); //�������� 
			$pay_api_obj = new Keke_witkey_pay_api_class ();
			if ($ac == 'del') {
				$pay_api_obj->setWhere ( " payment='$payment'" );
				$res = $pay_api_obj->del_keke_witkey_pay_api ();
				$res and Keke::admin_show_msg ( $_lang ['delete_success'], "index.php?do=config&view=pay&op=offline", "3", '', 'success' ) or Keke::admin_show_msg ( $_lang ['delete_fail'], "index.php?do=config&view=pay&op=offline", "3", '', 'warning' );
			} elseif ($confirm) {
				$config = serialize ( $conf );
				if ($ac == 'edit') {
					$pay_api_obj->setWhere ( " payment='$payment'" );
					$pay_api_obj->setConfig ( Keke::k_input ( $config ) );
					$res = $pay_api_obj->edit_keke_witkey_pay_api ();
					Keke::admin_system_log ( $_lang ['edit'] . $payment );
				} else {
					if (! Dbfactory::get_count ( sprintf ( " select payment from %switkey_pay_api where payment='%s'", TABLEPRE, $payment ) )) {
						$pay_api_obj->setPayment ( $payment );
						$pay_api_obj->setType ( 'offline' );
						$pay_api_obj->setConfig ( Keke::k_input ( $config ) );
						$res = $pay_api_obj->create_keke_witkey_pay_api ();
						Keke::admin_system_log ( $_lang ['create'] . $payment );
					}
				}
				Keke::empty_cache ();
				$res and Keke::admin_show_msg ( $_lang ['edit_add_success'], "index.php?do=config&view=pay&op=offline", "3", '', 'success' ) or Keke::admin_show_msg ( $_lang ['edit_add_fail'], "index.php?do=config&view=pay&op=offline", "3", '', 'warning' );
			}
			
			require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view . '_offline' );
			die ();
		}
		break;
	case "disable" : //����
		$pay_api_obj = keke_table_class::get_instance ( "witkey_pay_api" );
		$payment_list = Keke::get_table_data ( "*", "witkey_pay_api", "", '', '', '', 'payment' );
		$payment_config = $payment_list [$payname];
		$pay_config = unserialize ( $payment_config ['config'] );
		$pay_config ['pay_status'] = 0;
		$pay ['config'] = serialize ( $pay_config );
		$res = $pay_api_obj->save ( $pay, array ("payment" => $payname ) );
		$op = $ac;
 		$url = "index.php?do=$do&view=$view&op=$op";
		Keke::empty_cache ();
		$res and Keke::admin_show_msg ( $_lang ['close_success'], $url, "3", '', 'success' ) or Keke::admin_show_msg ( $_lang ['close_faile'], $url, "3", '', 'warning' );
		break;
	case "allow" : //����
		$payment_list = Keke::get_table_data ( "*", "witkey_pay_api", "", '', '', '', 'payment' );
		$payment_config = $payment_list [$payname];
		$pay_config = unserialize ( $payment_config ['config'] );
		$pay_config ['pay_status'] = 1;
		$res = Dbfactory::updatetable ( TABLEPRE . 'witkey_pay_api', array ("config" => serialize ( $pay_config ) ), array ("payment" => $payname ) );
		$op = $ac;
 		$url = "index.php?do=$do&view=$view&op=$op";
		Keke::admin_system_log ( "allow" . $payname );
		Keke::empty_cache ();
		$res and Keke::admin_show_msg ( $_lang ['open_success'], $url, "3", '', 'success' ) or Keke::admin_show_msg ( $_lang ['open_fail'], $url, "3", '', 'warning' );
		break;
	default :
		;
		break;
}

require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view ); */
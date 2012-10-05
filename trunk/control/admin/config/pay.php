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
	 * ����֧���б� 
	 */
	function action_offline(){
		global $_K,$_lang;
		//����
		$where = "type='offline'";
		//���������б�
		$payment_list = DB::select()->from('witkey_pay_api')->where($where)->execute();
		//��������
		$bank_arr = keke_global_class::get_bank();
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_offline');
	}
	/**
	 * ����֧����ӱ༭
	 */
	function action_offline_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			
		}
		$bank_arr   = keke_global_class::get_bank();
	 
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_offline_add');
	}
	
	/**
	 * ����֧�����ݱ���
	 */
	function action_offline_save(){
		global $_K,$_lang;
		//����ȫ���
		Keke::formcheck($_POST['formhash']);
		$array = array('pay_name'=>$_POST['pay_name'],
						'payment'=>$_POST['payment'],
						'status'=>$_POST['status'],
						'config'=>serialize($_POST['fds']));
		if($_POST['hdn_pay_id']){
			$where = "pay_id = '{$_POST['hdn_pay_id']}'";
			Model::factory('witkey_pay_api')->setData($array)->setWhere($where)->update();
			$url = "?pay_id={$_POST['hdn_pay_id']}";
		}else{
			Model::factory('witkey_pay_api')->setData($array)->create();
		}
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_pay/offline_add'.$url,'success');
	}
	
	/**
	 * �ı�֧���ӿڵ�״̬ 
	 * @example 0 ���� 1 ����
	 */
	function action_change_status(){
		global $_lang;
		//״̬
		$status = $_GET['status'];
		//����
		$pay_id = $_GET['pay_id'];
		//Ĭ��Ϊ���߽ӿ�
		$type = $_GET['type']?$_GET['type']:'online';
		//�ı�״̬
		DB::update('witkey_pay_api')->set(array('status'))->value(array($status))
		->where("pay_id='$pay_id'")->execute();
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_pay/'.$type,'success');
	}
	/**
	 * ���Ͻӿڱ༭
	 */
	function action_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			//֧��������Ҳ����Ŀ¼
			$dir = $payment_config['payment'];
			//��ʼ����������
			include S_ROOT.'payment/'.$dir.'/config.php';
			//��ʼ���������� $pay_basic ��config.php �е�����
			$init_param = $pay_basic ['initparam'];
			$items = array();
			foreach (explode(';', $init_param) as $v){
				$it = explode ( ":", $v );
				//k Ϊ��,VΪֵ��ֵ�����л������������ݿ���config�ֶ�,����ΪʲôҪ������������Ϊÿ������֧���ӿڵĲ�������һ��
				$items [] = array ('k' => $it ['0'], 'name' => $it ['1'], 'v' => $payment_config [$it ['0']] );
			}
		}
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_add');
	}
	static function get_pay_config($pay_id){
		//��ѯ����
		$where = "pay_id = '".intval($pay_id)."'";
		//ִ�в�ѯ
		$payment_config = DB::select()->from('witkey_pay_api')->where($where)->execute();
		$payment_config = $payment_config[0];
		//�����л�
		$pay_config =  unserialize($payment_config['config']);
		//���л�����ϲ�
		return $payment_config += $pay_config;
	}
	/**
	 * ���߽ӿڵ����ñ���
	 */
	function action_online_save(){
		global  $_lang;
		//form ��ȫ���
		Keke::formcheck($_POST['formhash']);
		//����ִֻֻ��update
		if($_POST['hdn_pay_id']){
			//Ҫ�����ֶ�
			$columns= array('status','config');
			//���µ�ֵ
			$values =array($_POST['status'],serialize($_POST['fds']));
			//ִ������
			$where = "pay_id='{$_POST['hdn_pay_id']}'";
			//��ʼִ��
			DB::update('witkey_pay_api')->set($columns)->value($values)->where($where)->execute();
			Keke::show_msg($_lang['submit_success'],"index.php/admin/config_pay/add?pay_id={$_POST['hdn_pay_id']}",'success');
		} 
		
	}
	/**
	 * ���½ӿ�ɾ��
	 */
	function action_del(){
		$pay_id = $_GET['pay_id'];
		//ɾ�������ᶨ�Ľӿ�
		echo DB::delete('witkey_pay_api')->where("pay_id = '$pay_id' and type='offline'")->execute();
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
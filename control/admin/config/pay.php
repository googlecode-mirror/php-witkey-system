<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 支付配置
 * @author Michael	
 * @version v 2.2
 * 2012-10-01
 */
class Control_admin_config_pay extends Controller{
	/**
	 * 支付配置
	 */
	function action_index(){
		global $_K,$_lang;
		//非提交时
		if(!$_POST){
			//获取要编辑的数据
			$res = DB::select('k,v')->from('witkey_pay_config')->execute();
			//健值重组
			$pay_config = self::get_arr_by_key($res,'k');
			//加载支付配置模板
			require Keke_tpl::template('control/admin/tpl/config/pay');
			die;
		}
		//表单安全检查
		Keke::formcheck($_POST['formhash']);
		//去掉formhash
		unset($_POST['formhash']);
		//批量更新
		foreach ($_POST as $k=>$v){
			DB::update('witkey_pay_config')->set(array('v'))->value(array($v))
			->where("k = '$k'")->execute();
		}
		//跳转
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_pay','success');
	}
	/**
	 * 按指定的键，重组多维数组
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
	 * 在线支付
	 */
	function action_online(){
		global $_K,$_lang;
		$payment_list = DB::select()->from('witkey_pay_api')->where("type='online'")->execute();
		//加载支付配置模板
		require Keke_tpl::template('control/admin/tpl/config/pay_online');
	}
	/**
	 * 线下支付列表 
	 */
	function action_offline(){
		global $_K,$_lang;
		//条件
		$where = "type='offline'";
		//线下银行列表
		$payment_list = DB::select()->from('witkey_pay_api')->where($where)->execute();
		//银行数组
		$bank_arr = keke_global_class::get_bank();
		//加载支付配置模板
		require Keke_tpl::template('control/admin/tpl/config/pay_offline');
	}
	/**
	 * 线下支付添加编辑
	 */
	function action_offline_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			
		}
		$bank_arr   = keke_global_class::get_bank();
	 
		//加载支付配置模板
		require Keke_tpl::template('control/admin/tpl/config/pay_offline_add');
	}
	
	/**
	 * 线下支付数据保存
	 */
	function action_offline_save(){
		global $_K,$_lang;
		//表单安全检查
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
	 * 改变支付接口的状态 
	 * @example 0 禁用 1 启用
	 */
	function action_change_status(){
		global $_lang;
		//状态
		$status = $_GET['status'];
		//主键
		$pay_id = $_GET['pay_id'];
		//默认为在线接口
		$type = $_GET['type']?$_GET['type']:'online';
		//改变状态
		DB::update('witkey_pay_api')->set(array('status'))->value(array($status))
		->where("pay_id='$pay_id'")->execute();
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_pay/'.$type,'success');
	}
	/**
	 * 线上接口编辑
	 */
	function action_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			//支付的名称也就是目录
			$dir = $payment_config['payment'];
			//初始化配置数组
			include S_ROOT.'payment/'.$dir.'/config.php';
			//初始化配置数量 $pay_basic 是config.php 中的数组
			$init_param = $pay_basic ['initparam'];
			$items = array();
			foreach (explode(';', $init_param) as $v){
				$it = explode ( ":", $v );
				//k 为键,V为值，值是序列化，保成在数据库中config字段,这里为什么要这样做，是因为每个在线支付接口的参数都不一样
				$items [] = array ('k' => $it ['0'], 'name' => $it ['1'], 'v' => $payment_config [$it ['0']] );
			}
		}
		//加载支付配置模板
		require Keke_tpl::template('control/admin/tpl/config/pay_add');
	}
	static function get_pay_config($pay_id){
		//查询条件
		$where = "pay_id = '".intval($pay_id)."'";
		//执行查询
		$payment_config = DB::select()->from('witkey_pay_api')->where($where)->execute();
		$payment_config = $payment_config[0];
		//反序列化
		$pay_config =  unserialize($payment_config['config']);
		//序列化数组合并
		return $payment_config += $pay_config;
	}
	/**
	 * 在线接口的配置保存
	 */
	function action_online_save(){
		global  $_lang;
		//form 安全检查
		Keke::formcheck($_POST['formhash']);
		//这里只只执行update
		if($_POST['hdn_pay_id']){
			//要更新字段
			$columns= array('status','config');
			//更新的值
			$values =array($_POST['status'],serialize($_POST['fds']));
			//执行条件
			$where = "pay_id='{$_POST['hdn_pay_id']}'";
			//开始执行
			DB::update('witkey_pay_api')->set($columns)->value($values)->where($where)->execute();
			Keke::show_msg($_lang['submit_success'],"index.php/admin/config_pay/add?pay_id={$_POST['hdn_pay_id']}",'success');
		} 
		
	}
	/**
	 * 线下接口删除
	 */
	function action_del(){
		$pay_id = $_GET['pay_id'];
		//删除线下提定的接口
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
			$pay = $payment_list [$payment]; //银行配置
			

			$pay ['config'] and $pay_config = unserialize ( $pay ['config'] ); //具体配置 
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
	case "disable" : //禁用
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
	case "allow" : //开启
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
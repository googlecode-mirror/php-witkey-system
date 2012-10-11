<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @todo 后台认证项目安装、删除
 * 2011-9-01 11:35:13
 */
class Control_admin_user_auth extends Controller{
	function action_index(){
		global $_K,$_lang;
		//读取需要在列表中要显示的字段
		$auth_item_arr = DB::select('auth_code,auth_title,auth_day,auth_cash,auth_expir,auth_open,update_time')->from('witkey_auth_item')->execute();
// 		var_dump($auth_item_arr);die;
		require keke_tpl::template('control/admin/tpl/user/auth');
	}
	function action_disable(){
		$auth_code = $_GET['auth_code'];
		$auth_open = $_GET['auth_open'];
		//禁用和启用认证，改变auth_open状态
		if ($auth_open==1){
			$where .="auth_code='$auth_code'";
			$columns = array('auth_open');
			$value = array('0');
			DB::update('witkey_auth_item')->set($columns)->value($value)->where($where)->execute();
			keke::show_msg("禁用成功","index.php/admin/user_auth","success");
		}else {
			$where .="auth_code='$auth_code'";
			$columns = array('auth_open');
			$value = array('1');
			DB::update('witkey_auth_item')->set($columns)->value($value)->where($where)->execute();
			keke::show_msg("启用成功","index.php/admin/user_auth","success");
		}
	}
	function action_edit(){
		global $_K,$_lang;
		$auth_code = $_GET['auth_code'];
		$where .="auth_code='$auth_code'";
		$auth_item = DB::select()->from('witkey_auth_item')->where($where)->get_one()->execute();
// 		var_dump($auth_item);die;
		require keke_tpl::template('control/admin/tpl/user/auth_edit');
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		$array = array('auth_code'=>$_POST['auth_code'],
				'auth_day'=>$_POST['auth_day'],
				'auth_cash'=>$_POST['auth_cash'],
				'auth_expir'=>$_POST['auth_expir'],
				'auth_big_ico'=>$_POST['hdn_big_icon'],
				'auth_'
				);
	}
	function action_del(){
		$auth_code = $_GET['auth_code'];
		$where .="auth_code='$auth_code'";
		echo Model::factory('witkey_auth_item')->setWhere($where)->del();
	}
}
/* Keke::admin_check_role ( 38 );

$auth_item_obj = new Keke_witkey_auth_item_class ();
$url = "index.php?do=$do&view=$view";

//删除认证项目
if ($ac === 'del') {
	keke_auth_fac_class::del_auth ( $auth_code, 'auth_item_cache_list' ); //单条删除
	Keke::admin_system_log ( $_lang['delete_auth'] . $auth_code ); //日志记录
} elseif (isset ( $sbt_add )) {
	keke_auth_fac_class::install_auth ( $auth_dir ); //增加认证项目
	Keke::admin_system_log ( $_lang['add_auth'] . $auth_dir ); //日志记录
} elseif (isset ( $sbt_action ) && $sbt_action === $_lang['mulit_delete']) { //批量删除
	keke_auth_fac_class::del_auth ( $ckb, 'auth_item_cache_list' ); //批量操作
	Keke::admin_system_log ( $_lang['mulit_delete_auth'] . $ckb );
} else {
	$where = ' 1 = 1  ';
	intval ( $page_size ) or $page_size = 10 and $page_size = intval ( $page_size );
	$auth_item_obj->setWhere ( $where );
	$count = $auth_item_obj->count_keke_witkey_auth_item ();
	$page or $page = 1 and $page = intval ( $page );
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom("ajax_dom");
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$where .= " order by listorder asc ";
	$auth_item_obj->setWhere ( $where . $pages ['where'] );
	$auth_item_arr = $auth_item_obj->query_keke_witkey_auth_item ();
}

require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
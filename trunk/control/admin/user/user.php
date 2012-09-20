<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
后台用户路由
*/
class Control_admin_user_user extends Controller{
	function action_index(){
		global $_K,$_lang;
		require keke_tpl::template('control/admin/tpl/user/user');
	}
}

/* $views = array("add","list","charge","custom_list","group_add","group_list","custom_add");

$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'add';

require "admin_user_$view.php"; */




<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨����
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-08-30 09:51:34
 */
class Control_admin_lock extends Control{
	
	function action_index(){
		global $_K,$_lang;
		$admin_obj = new Keke_admin();
		$admin_obj->check_screen_lock();
		$unlock_times = $admin_obj->times_limit() ;
		$admin_obj->screen_lock();
		require keke_tpl::template('control/admin/tpl/lock');
	}
	function action_unlock(){
		$admin_obj = new Keke_admin();
		//����,�����Ĵ���������������
		$admin_obj->screen_unlock($_GET['unlock_num'],$_GET['unlock_pwd']);
	}
}

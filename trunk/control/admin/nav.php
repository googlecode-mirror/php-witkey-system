<?php	defined ( 'IN_KEKE' )  or 	exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19����09:25:13
 */
class Control_admin_nav extends Controller{
	function action_index(){
		//����ȫ�ֱ��������԰�
		global $_K,$_lang;
		//��ȡ��̨�ĸ�Ŀ¼����Ŀ¼
		$menus_arr = keke_admin_class::get_admin_menu();
		$menus_arr = $menus_arr['menu'];
 
		require keke_tpl::template('control/admin/tpl/nav');
	}
}


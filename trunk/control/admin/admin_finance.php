<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨�������·��
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-21����10:25:13
 */


$fina_action_arr = keke_glob_class::get_finance_action();

$views = array ('withdraw', 'report', 'all','analysis','recharge','revenue');

(in_array ( $view, $views )) or  $view ='all';

if (file_exists ( ADMIN_ROOT . 'admin_'.$do.'_' . $view . '.php' )) {
	require ADMIN_ROOT . 'admin_'.$do.'_' . $view . '.php';
} else {
	kekezu::admin_show_msg ( $_lang['404_page'],'',3,'','warning' );
}

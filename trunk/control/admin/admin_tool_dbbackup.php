<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿⱸ��
 * @copyright keke-tech
 * @author shang
 * @version v 1.0
 * 2010-5-19����09:25:13
 */


kekezu::admin_check_role ( 17 );
if ($sbt_edit) {
	keke_backup_class::run_backup ();
}if(isset($t)){
	$t == 1 and kekezu::admin_show_msg ( $_lang['backup_success'], "index.php?do=tool&view=dbrestore", 3,'','success' ) or kekezu::admin_show_msg ( $_lang['backup_fail'], "index.php?do=tool&view=backup", 3,'','warning' );
}
require keke_tpl_class::template('control/admin/tpl/admin_'. $do .'_'. $view);
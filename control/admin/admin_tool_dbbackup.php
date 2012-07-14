<?php
/**
 * 数据库备份
 * @copyright keke-tech
 * @author shang
 * @version v 1.0
 * 2010-5-19下午09:25:13
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role ( 17 );
if ($sbt_edit) {
	keke_backup_class::run_backup ();
}if(isset($t)){
	$t == 1 and Keke::admin_show_msg ( $_lang['backup_success'], "index.php?do=tool&view=dbrestore", 3,'','success' ) or Keke::admin_show_msg ( $_lang['backup_fail'], "index.php?do=tool&view=backup", 3,'','warning' );
}
require Keke_tpl::template('control/admin/tpl/admin_'. $do .'_'. $view);
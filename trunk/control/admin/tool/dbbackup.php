<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 数据库备份
 * @copyright keke-tech
 * @author shang
 * @version v 1.0
 * 2010-5-19下午09:25:13
 */
class Control_admin_tool_dbbackup extends Controller{
	
	function action_index(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/tool/dbbackup');
	}
	/**
	 * 备份数据库
	 */
	function action_save(){
		global  $_lang;
		if(!$_GET['t']){
			keke_backup_class::run_backup ();
		}else{
			if($_GET['t']==1){
				Keke::show_msg ('系统提示',  "index.php/admin/tool_dbrestore",'提交成功','success' );
			}else{
				Keke::show_msg ( '系统提示', "index.php/admin/tool_backup", '提交失败','warning' );
			}
		}
		
	}
}

/* Keke::admin_check_role ( 17 );
if ($sbt_edit) {
	keke_backup_class::run_backup ();
}
if(isset($t)){
	$t == 1 and Keke::admin_show_msg ( $_lang['backup_success'], "index.php?do=tool&view=dbrestore", 3,'','success' ) or Keke::admin_show_msg ( $_lang['backup_fail'], "index.php?do=tool&view=backup", 3,'','warning' );
}
require keke_tpl_class::template('control/admin/tpl/admin_'. $do .'_'. $view); */
<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿⱸ��
 * @copyright keke-tech
 * @author shang
 * @version v 1.0
 * 2010-5-19����09:25:13
 */
class Control_admin_tool_dbbackup extends Control_admin{
	
	function action_index(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/tool/dbbackup');
	}
	/**
	 * �������ݿ�
	 */
	function action_save(){
		global  $_lang;
		if(!$_GET['t']){
			keke_backup_class::run_backup ();
		}else{
			if($_GET['t']==1){
				Keke::show_msg ($_lang ['operate_success'], "admin/tool_dbrestore",'success' );
			}else{
				Keke::show_msg ( $_lang ['operate_fialed'], "admin/tool_backup", 'warning' );
			}
		}
		
	}
}
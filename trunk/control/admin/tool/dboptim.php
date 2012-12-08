<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿ��Ż�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */
class Control_admin_tool_dboptim extends Control_admin{
	
	/**
	 * ���Ż�,����ģ���õ�
	 */
	function action_index(){
		global $_K,$_lang;
		//���еı�״̬
		$table_arr = Dbfactory::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" );
		//��ȡ�����Ż��ı�
		$optim_table_list = array();
		foreach ( $table_arr as $k => $v ) {
			//��Ƭ���ݴ���>0�ı� 
			if($v ['Data_free'] > 0){
				$optim_table_list[$k]=$v;
			} 
		}
		require Keke_tpl::template('control/admin/tpl/tool/dboptim');
	}
	/**
	 * ���޸�������ģ���õ�
	 */
	function action_repair(){
		 
		require Keke_tpl::template('control/admin/tpl/tool/dbrepair');
	}
	/**
	 * �Ż�
	 */
	function action_optim(){
		global $_K,$_lang;
		$optimizetables = $_POST['optimizetables'];
		if(empty($optimizetables)){
			Keke::show_msg ( $_lang ['no_select_table'], 'admin/tool_dboptim',  'warning' );
		}
		 
		foreach ( $optimizetables as $v ) {
			//�Ż���
			Dbfactory::execute ( "OPTIMIZE TABLE " . $v ); 
		}
		Keke::show_msg ( $_lang ['operate_success'], 'admin/tool_dboptim',  'success' );
	}
	/**
	 * �޸�
	 */
	function action_dbrepair(){
		global $_K,$_lang;
		
		$table_arr = Dbfactory::query ( " SHOW TABLES" );
		//�޸���
		foreach ( $table_arr as $v ) {
			Dbfactory::execute ( "REPAIR TABLE " . $v ['Tables_in_' . DBNAME] );
		}
		Keke::show_msg (  $_lang ['operate_success'], 'admin/tool_dboptim/repair', 'success' );
	}
	
}
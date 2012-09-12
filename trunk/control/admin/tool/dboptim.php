<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿ��Ż�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */
class Control_admin_tool_dboptim extends Controller{
	
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
				array_push($optim_table_list[$k], $v);
			} 
		}
		require Keke_tpl::template('control/admin/tpl/tool/dboptim');
	}
	/**
	 * ���޸�������ģ���õ�
	 */
	function action_repair(){
		global $_K,$_lang;
		require Keke_tpl::template('control/admin/tpl/tool/dbrepair');
	}
	/**
	 * �Ż�
	 */
	function action_optim(){
		global $_K,$_lang;
		$optimizetables = $_POST['optimizetables'];
		if(empty($optimizetables)){
			Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim', $_lang ['no_select_table'], 'warning' );
		}
		 
		foreach ( $optimizetables as $v ) {
			//�Ż���
			Dbfactory::execute ( "OPTIMIZE TABLE " . $v ); 
		}
		Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim', $_lang ['operate_success'], 'success' );
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
		Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim/repair', $_lang ['operate_success'], 'success' );
	}
	
}



/* if ($op == 'repair') { //�޸�
	if ($is_submit) {
		$table_arr = Dbfactory::query ( " SHOW TABLES" );
		foreach ( $table_arr as $v ) {
			Dbfactory::execute ( "REPAIR TABLE " . $v ['Tables_in_' . DBNAME] ); //�Ż�
		}
		Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim&op=repair', 3, Keke::lang ( "operate_success" ), 'success' );
	}
} else {
	//����Ż�
	if ($is_submit) {
		$optimizetables or Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, $_lang ['no_select_table'], 'warning' );
		foreach ( $optimizetables as $v ) {
			Dbfactory::execute ( "OPTIMIZE TABLE " . $v ); //�Ż�
		}
		Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, Keke::lang ( "operate_success" ), 'success' );
	} else {
		$table_arr = Dbfactory::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" );
		foreach ( $table_arr as $k => $v ) { //��ȡ�����Ż��ı�
			$v ['Data_free'] > 0 and $table_free_list [$k] = $v;
		}
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
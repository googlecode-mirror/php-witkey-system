<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 数据库优化
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20下午13:25:13
 */
class Control_admin_tool_dboptim extends Controller{
	
	/**
	 * 表优化,加载模板用的
	 */
	function action_index(){
		global $_K,$_lang;
		//所有的表状态
		$table_arr = Dbfactory::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" );
		//获取可以优化的表
		$optim_table_list = array();
		foreach ( $table_arr as $k => $v ) {
			//碎片数据大于>0的表 
			if($v ['Data_free'] > 0){
				array_push($optim_table_list[$k], $v);
			} 
		}
		require Keke_tpl::template('control/admin/tpl/tool/dboptim');
	}
	/**
	 * 表修复，加载模板用的
	 */
	function action_repair(){
		global $_K,$_lang;
		require Keke_tpl::template('control/admin/tpl/tool/dbrepair');
	}
	/**
	 * 优化
	 */
	function action_optim(){
		global $_K,$_lang;
		$optimizetables = $_POST['optimizetables'];
		if(empty($optimizetables)){
			Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim', $_lang ['no_select_table'], 'warning' );
		}
		 
		foreach ( $optimizetables as $v ) {
			//优化表
			Dbfactory::execute ( "OPTIMIZE TABLE " . $v ); 
		}
		Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim', $_lang ['operate_success'], 'success' );
	}
	/**
	 * 修复
	 */
	function action_dbrepair(){
		global $_K,$_lang;
		
		$table_arr = Dbfactory::query ( " SHOW TABLES" );
		//修复表
		foreach ( $table_arr as $v ) {
			Dbfactory::execute ( "REPAIR TABLE " . $v ['Tables_in_' . DBNAME] );
		}
		Keke::show_msg ( $_lang ['operate_notice'], 'index.php/admin/tool_dboptim/repair', $_lang ['operate_success'], 'success' );
	}
	
}



/* if ($op == 'repair') { //修复
	if ($is_submit) {
		$table_arr = Dbfactory::query ( " SHOW TABLES" );
		foreach ( $table_arr as $v ) {
			Dbfactory::execute ( "REPAIR TABLE " . $v ['Tables_in_' . DBNAME] ); //优化
		}
		Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim&op=repair', 3, Keke::lang ( "operate_success" ), 'success' );
	}
} else {
	//表的优化
	if ($is_submit) {
		$optimizetables or Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, $_lang ['no_select_table'], 'warning' );
		foreach ( $optimizetables as $v ) {
			Dbfactory::execute ( "OPTIMIZE TABLE " . $v ); //优化
		}
		Keke::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, Keke::lang ( "operate_success" ), 'success' );
	} else {
		$table_arr = Dbfactory::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" );
		foreach ( $table_arr as $k => $v ) { //获取可以优化的表
			$v ['Data_free'] > 0 and $table_free_list [$k] = $v;
		}
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
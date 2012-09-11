<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 数据库优化
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20下午13:25:13
 */

if ($op == 'repair') { //修复
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
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
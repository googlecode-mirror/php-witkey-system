<?php
/**
 * ���ݿ��Ż�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
if ($op == 'repair') { //�޸�
	if ($is_submit) {
		$table_arr = dbfactory::query ( " SHOW TABLES" );
		foreach ( $table_arr as $v ) {
			dbfactory::execute ( "REPAIR TABLE " . $v ['Tables_in_' . DBNAME] ); //�Ż�
		}
		kekezu::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim&op=repair', 3, kekezu::lang ( "operate_success" ), 'success' );
	}
} else {
	//����Ż�
	if ($is_submit) {
		$optimizetables or kekezu::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, $_lang ['no_select_table'], 'warning' );
		foreach ( $optimizetables as $v ) {
			dbfactory::execute ( "OPTIMIZE TABLE " . $v ); //�Ż�
		}
		kekezu::admin_show_msg ( $_lang ['operate_notice'], 'index.php?do=tool&view=dboptim', 3, kekezu::lang ( "operate_success" ), 'success' );
	} else {
		$table_arr = dbfactory::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" );
		foreach ( $table_arr as $k => $v ) { //��ȡ�����Ż��ı�
			$v ['Data_free'] > 0 and $table_free_list [$k] = $v;
		}
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
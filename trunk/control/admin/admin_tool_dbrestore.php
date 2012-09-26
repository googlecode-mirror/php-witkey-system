<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿ�ָ�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */


Keke::admin_check_role ( 18 );

$Dbfactory = new Dbfactory ();
$file_obj = new keke_file_class ();
$backup_patch = S_ROOT . './data/backup/';
$file_arr = $file_obj->get_dir_file_info ( $backup_patch );


switch ($ac) {
	//��ԭsql�����ļ�
	case 'restore' :
		set_time_limit ( 0 );
		$file_sql = file_get_contents ( $backup_patch . $file_arr [$restore_name] [name] );
		$file_sql = htmlspecialchars_decode ( $file_sql );
		$sql = str_replace ( "\r\n", "\n", $file_sql );
		$ret = array ();
		$num = 0;
		foreach ( explode ( ";\n#####", trim ( $sql ) ) as $query ) {
			$ret [$num] = '';
			$queries = explode ( "\n", trim ( $query ) );
			foreach ( $queries as $query ) {
				$ret [$num] .= (isset ( $query [0] ) && $query [0] == '#') || (isset ( $query [1] ) && isset ( $query [1] ) && $query [0] . $query [1] == '--') ? '' : $query;
			}
			$num ++;
		}
		
		foreach ( $ret as $vvv ) {
			empty ( $vvv ) or $res .= Dbfactory::execute ( $vvv );
		}
		if ($res) {
			Keke::admin_system_log ( $_lang['restore_database_operate_success'] . $file_arr [$restore_name] [name] );
			
			Keke::echojson ( $_lang['database_restore_success'], 1 );
		} else {
			Keke::admin_system_log ( $_lang['restore_database_operate_fail'] );
			Keke::echojson ( $_lang['database_restore_fail'], 0 );
		}
		break;
	case 'del' :
		//ɾ��sql�����ļ�
		$res = unlink ( $backup_patch . $file_arr [$restore_name] [name] );
		if ($res) {
			Keke::admin_system_log ( $_lang['delete_database_backup_file'] . $file_arr [$restore_name] [name] );
			Keke::admin_show_msg ( $_lang['delete_database_backup_file_success'], 'index.php?do=' . $do . '&view=' . $view ,3,'','success');
		} else {
			Keke::admin_show_msg ( $_lang['delete_database_backup_file_fail'], 'index.php?do=' . $do . '&view=' . $view ,3,'','warning');
		}
		break;
}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
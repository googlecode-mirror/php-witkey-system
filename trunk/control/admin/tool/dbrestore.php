<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿ�ָ�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */
class Control_admin_tool_dbrestore extends Controller{
	private $_sql_path ;
	private $_file_arr;
	
	function before(){
		//sql�ļ��Ĵ��·��
		$this->_sql_path = S_ROOT . 'data/backup/';
		//��ȡbackupĿ¼�µ��ļ��б�
		$this->_file_arr = keke_file_class::get_dir_file_info ( $this->_sql_path );
	}
	function action_index(){
		global $_K, $_lang;
		
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/tool/dbrestore');
	}
	/**
	 * ��ԭ���ݿ⶯��
	 */
	function action_restore(){
		global  $_lang;
		if($_GET['restore_name']){
			
			//Ҫ��ԭ�������ļ�
			$file_path = $this->_sql_path.$this->_file_arr[$_GET['restore_name']]['name'];
			//��ԭָ�������ݿ��ļ�
			$res = $this->restore($file_path);
			if ($res) {
				//��ԭ���ݿ��ɹ�ϵͳ��־
				Keke::admin_system_log ( $_lang['restore_database_operate_success'] . $this->_file_arr[$_GET['restore_name']]['name'] );
				//����json����	
				Keke::echojson ( $_lang['database_restore_success'], 1 );
			} else {
				//��ԭʧ�ܵ�ϵͳ��־
				Keke::admin_system_log ( $_lang['restore_database_operate_fail'] );
				//����json
				Keke::echojson ( $_lang['database_restore_fail'], 0 );
			}
		}
	}
	
	function action_del(){
		global  $_lang;
		//��ȡbackupĿ¼�µ��ļ��б�
		$file_arr = keke_file_class::get_dir_file_info ( $this->_sql_path );
		//ɾ��sql�����ļ�
		$res = @unlink ($this->_sql_path.$file_arr[$_GET['restore_name']]['name']);
		if ($res) {
			Keke::admin_system_log ( $_lang['delete_database_backup_file'] . $file_arr[$_GET['restore_name']]['name'] );
			echo 1;
			
			//Keke::show_msg('ϵͳ��ʾ','index.php/admin/tool_dbrestore',$_lang['delete_database_backup_file_success'],'success');
			//Keke::admin_show_msg ( $_lang['delete_database_backup_file_success'], 'index.php?do=' . $do . '&view=' . $view ,3,'','success');
		} else {
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/tool_dbrestore',$_lang['delete_database_backup_file_fail'],'warning');
			echo 0;
			//Keke::admin_show_msg ( $_lang['delete_database_backup_file_fail'], 'index.php?do=' . $do . '&view=' . $view ,3,'','warning');
		}
	}
	/**
	 * ִ��ָ����sql�ļ���һ���������ݿ��ļ���ԭ
	 * @param String $file_path
	 * @return boolean
	 */
	function restore($file_path){
		set_time_limit ( 0 );
		$file_sql = file_get_contents ( $file_path );
		$file_sql = htmlspecialchars_decode ( $file_sql );
		//���س��뻻�иĳɻ���
		$sql = str_replace ( "\r\n", "\n", $file_sql );
		$ret = array ();
		$num = 0;
		//��sql�ļ��ֽ⣬�ŵ�$res ����
		foreach ( explode ( ";\n#####", trim ( $sql ) ) as $query ) {
			$ret [$num] = '';
			$queries = explode ( "\n", trim ( $query ) );
			foreach ( $queries as $query ) {
				$ret [$num] .= (isset ( $query [0] ) && $query [0] == '#') || (isset ( $query [1] ) && isset ( $query [1] ) && $query [0] . $query [1] == '--') ? '' : $query;
			}
			$num ++;
		}
		//ѭ��ִ��sql�ļ�
		foreach ( $ret as $vvv ) {
			empty ( $vvv ) or $res .= Dbfactory::execute ( $vvv );
		}
		//����ִ�еĽ��
		return (bool)$res;
	}
	
}

/* Keke::admin_check_role ( 18 );

$Dbfactory = new Dbfactory ();
$file_obj = new keke_file_class ();
$backup_patch = S_ROOT . './data/backup/';
$file_arr = $file_obj->get_dir_file_info ( $backup_patch ); */


/* switch ($ac) {
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

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
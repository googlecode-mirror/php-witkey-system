<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���ݿ�ָ�
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-20����13:25:13
 */
class Control_admin_tool_dbrestore extends Control_admin{
	private $_sql_path ;
	private $_file_arr;
	
	function __construct($request, $response){
		parent::__construct($request, $response);
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
		} else {
			Keke::show_msg($_lang['delete_database_backup_file_fail'],'admin/tool_dbrestore','warning');
			echo 0;
			
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
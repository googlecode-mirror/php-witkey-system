<?php
class Keke_db {
	/**
	 * ��ѯ����
	 * @param $type string       	
	 * @param $sql string       	
	 * @return keke_db_query
	 */
	public static function query($sql, $type=null) {
		return new Keke_db_query ( $sql, $type);
	}
	/**
	 * ���󻯲�ѯ
	 * @param $columns string ',' ����       	
	 * @return keke_db_select
	 */
	public static function select($columns = NULL) {
		return new Keke_db_select ( $columns );
	}
	/**
	 * ���󻯸���
	 */
	public static function update($table=null){
		return new Keke_db_update($table);
	}
	/**
	 * ����ɾ��
	 */
	public static function delete($table=null){
		return new Keke_db_delete($table);
	}
	/**
	 * ���󻯲���
	 */
	public static function insert($table=null,array $columns=null){
		return new Keke_db_insert($table,$columns);
	}
}

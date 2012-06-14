<?php
class Keke_db {
	/**
	 *
	 * @param $type string       	
	 * @param $sql string       	
	 * @return keke_db_query
	 */
	public static function query($sql, $type=null) {
		return new Keke_db_query ( $sql, $type=null );
	}
	/**
	 * @param $columns array       	
	 * @return keke_db_select
	 */
	public static function select($columns = NULL) {
		return new Keke_db_select ( $columns );
	}
	public static function update(){
		return new Keke_db_update();
	}
	
	public static function delete(){
		return new Keke_db_delete();
	}
}

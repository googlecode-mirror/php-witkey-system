<?php
class keke_db {
	/**
	 *
	 * @param $type string       	
	 * @param $sql string       	
	 * @return keke_db_query
	 */
	public static function query($sql, $type=null) {
		return new keke_db_query ( $sql, $type=null );
	}
	/**
	 * @param $columns array       	
	 * @return keke_db_select
	 */
	public static function select($columns = NULL) {
		return new keke_db_select ( $columns );
	}
}

?>
<?php
class dbfactory {
	
	public static $db_obj = null;
	public static $instance = null;
	public static function execute($sql) {
		return database::instance ()->execute ( $sql );
	}
	public static function query($sql, $is_cache = 0, $cache_time = 0, $is_unbuffer = 0) {
		return database::instance ()->query ( $sql );
	}
	public static function select($sql) {
		
		return database::instance ()->query ( $sql, database::SELECT );
	}
	public static function insert($sql) {
		return database::instance ()->query ( $sql, database::INSERT );
	}
	public static function update($sql) {
		return database::instance ()->query ( $sql, database::UPDATE );
	}
	public static function delete($sql) {
		return database::instance ()->query ( $sql, database::DELETE );
	}
	
	public static function inserttable($tablename, $insertsqlarr, $returnid = 1, $replace = false) {
		return database::instance ()->insert ( $tablename, $insertsqlarr, $returnid, $replace );
	}
	public static function updatetable($tablename, $setsqlarr, $wheresqlarr) {
		return database::instance ()->update ( $tablename, $setsqlarr, $wheresqlarr );
	}
	public static function get_one($sql, $cache_time = 0) {
		return database::instance ()->get_one_row ( $sql );
	}
	
	public static function get_table_data($fileds = '*', $table, $where = '', $order = '', $group = '', $limit = '', $pk = '', $cachetime = 0) {
		return database::instance ()->select ( $fileds, $table, $where, $order, $group, $limit, $pk,$cachetime );
	}
}
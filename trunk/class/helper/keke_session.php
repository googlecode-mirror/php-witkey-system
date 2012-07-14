<?php

/** 
 * @author michael	
 * @copyright keke_tech
 * 
 * 
 */
class keke_session {
	public static  $_session_obj ;
	public static $_left_time = 1800;
	public static function get_instance() {
		static $obj = null;
		if ($obj == null) {
			$obj = new keke_session ();
		}
		return $obj;
	}
	
	function __construct() {
		 $this->init ();
	}
	function init() {
		if(!is_object(self::$_session_obj)){
			switch (SESSION_MODULE) {
				case 'mysql' :
					self::$_session_obj = new session_mysql_class ();
					break;
				case 'files' :
					self::$_session_obj = new session_file_class ();
					break;
			}
		}
	}

}

class session_mysql_class extends keke_session {
	
	public $_db;
	function __construct() {
		ini_set ( "session.save_handler", "user" );
		session_module_name ( "user" );
		session_set_save_handler ( array (&$this, "open" ), array (&$this, "close" ), array (&$this, "read" ), array (&$this, "write" ), array (&$this, "destroy" ), array (&$this, "gc" ) );
		session_set_Cookie_params(get_cfg_var ( "session.gc_maxlifetime" ), Cookie::$_path, Cookie::$_domain, Cookie::$_secure, Cookie::$_httponly);
		session_cache_limiter(false);
		session_start ();
	}
	function open($save_path, $sess_name) {
		self::$_left_time = get_cfg_var ( "session.gc_maxlifetime" );
		$this->_db = Database::instance();
		return true;
	}
	function close() {
		return $this->gc ( self::$_left_time );
	}
	function read($session_id) {
		$sql = "select session_data from " . TABLEPRE . "witkey_session where session_id = '$session_id' and session_expirse>" . time ();
		$session_arr = $this->_db->query ( $sql, 1 );
		empty ( $session_arr ) and $session_data = '' or $session_data = $session_arr [0] ['session_data'];
		return $session_data;
	}
	function write($session_id, $session_data) {
		$tablename = TABLEPRE . "witkey_session";
		$_SESSION ['uid'] > 0 and $uid = $_SESSION ['uid'] or $uid = 0;
		$data_arr = array ('session_id' => $session_id, 'session_data' => $session_data, 'session_expirse' => time () + self::$_left_time, 'session_ip' => Keke::get_ip (), 'session_uid' => $uid );
		return $this->_db->insert ( $tablename, $data_arr, 1, 1 );
	    
	}
	function destroy($session_id) {
		$sql = "delete from " . TABLEPRE . "witkey_session where session_id ='$session_id' ";
		return $this->_db->execute( $sql );
	}
	function gc($max_left_time) {
		$sql = "delete from " . TABLEPRE . "witkey_session where session_expirse <" . time ();
		return $this->_db->execute ( $sql );
	}
}
class session_file_class extends keke_session {////
	function __construct() {
		$path = S_ROOT . 'data' . DIRECTORY_SEPARATOR . 'session';
		ini_set ( 'session.save_handler', 'files' );
		session_save_path ( $path );
		session_set_Cookie_params(get_cfg_var ( "session.gc_maxlifetime" ), Cookie::$_path, Cookie::$_domain, Cookie::$_secure, Cookie::$_httponly);
		session_cache_limiter(false);
	    
		session_start ();
	}
}

?>
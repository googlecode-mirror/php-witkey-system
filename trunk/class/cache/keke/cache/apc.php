<?php
// require_once 'acache_class.php';
final class keke_cache_apc extends keke_cache_class {
	function __construct() {
		if(!extension_loaded('apc')) { 
			throw new keke_exception( "apc_cache dosn't load ,please loaded!");
		}
	}
	public function get($id) {
		return apc_fetch($id);
	}
	public function mget($ids) {
		return apc_fetch($keys);
	}
	public function set($id, $value, $expire = 0, $dependency = null) {
		return apc_store($id,$value,$expire);
	}
	public function add($id, $value, $expire = 0, $dependency = null) {
		return apc_add($id,$value,$expire);
	}
	public function del($id) {
		return apc_delete($id);
	}
	public function del_all() {
		return apc_clear_cache('user');
	}
}


?>
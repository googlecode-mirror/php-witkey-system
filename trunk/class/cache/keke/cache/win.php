<?php  defined('IN_KEKE') or die('access denied');

/**
 *  wincache ��Ҫ��fastcgiģʽ����iis�������Ļ����£������Ի���opcode ,�ļ����棬���·�����档
 *  ֧��IIS5,6,7,7.5
 * @author MICHAEL
 * @version 3.0  
 * 2012-12-14
 *
 */
final class Keke_cache_win extends Keke_cache {
	
	function __construct() {
		if(!extension_loaded('wincache')) { 
			throw new Keke_exception( "wincache dosn't load ,please loaded!");
		}
	}
	
	public function get($id) {
		return wincache_ucache_get($this->_sanitize_id($id));
	}
 
	public function set($id, $value, $expire = NULL) {
		if($expire===NULL){
			$expire = Cache::DEFAULT_CACHE_LIFE_TIME;
		}
		wincache_ucache_set($this->_sanitize_id($id),$value,$expire);
		return apc_store($id,$value,$expire);
	}
	
 
	public function del($id) {
		return wincache_ucache_delete($this->_sanitize_id($id));
	}
	
	public function del_all() {
		return wincache_ucache_clear();
	}
}


?>
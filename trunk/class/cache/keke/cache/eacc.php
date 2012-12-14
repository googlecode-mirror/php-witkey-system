<?php defined('IN_KEKE') or die('access denied');

/**
 * eacc ���� apc �������ƣ�Ҳ��opcode ���棬�������ݻ���Ĵ���
 * @author Michael
 * @version 3.0
 *
 */
final class Keke_cache_eacc extends Keke_cache {
	
	function __construct(){
		if(!function_exists('eaccelerator_get')){ 
			throw new Keke_exception("eaccelerator dosn't load ,please loaded!");
		}
	}
	public function get($id) {
 
		$result = eaccelerator_get ( $this->_sanitize_id($id) );
		return $result !== NULL ? $result : false;
	}

	public function set($id, $value, $expire = null) {
        if($expire === null){
        	$expire = keke_cache::DEFAULT_CACHE_LIFE_TIME;
        }
		return eaccelerator_put($this->_sanitize_id($id),$value,$expire);
	}
	public function add($id, $value, $expire = 0, $dependency = null) {
	    return  false;
	}
	public function del($id) {
		return eaccelerator_rm($this->_sanitize_id($id));
	}
	public function del_all() {
		eaccelerator_gc();//������ڻ���
		eaccelerator_clear();
		eaccelerator_removed_scripts();
	}

}

?>
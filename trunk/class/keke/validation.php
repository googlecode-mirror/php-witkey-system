<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ั้ึค
 * @author พลฝญ
 *
 */
class Keke_validation implements ArrayObject {

	protected  $_data = array();
	protected  $_bind ;
	protected  $_errors;
	protected  $_rules;
	
	public function __construct(array $array){
		$this->_data = $array();
	}
	public function offsetSet($index, $newval){
		
	}
	public function offsetGet($index){
		
	}
	public function offsetExists($offset){
		
	}
	public function offsetUnset($index){
		
	}
}
 
?>
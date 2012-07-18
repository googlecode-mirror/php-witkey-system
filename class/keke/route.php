<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
class Keke_route {
	public function __construct($m,$a) {
		empty($m) and $m='index';
		empty($a) and $a='index';
		define('ROUTE_M', $m);
		define('ROUTE_A', $a);
		$this->init();
	}
	
	/**
	 * 调用件事
	 */
	private function init() {
		$controller = $this->load_controller();
		if (method_exists($controller, ROUTE_A)) {
		   if(mb_substr(ROUTE_A,0,1,CHARSET) === '_'){
				exit('You are visiting the action is to protect the private action');
			} else {
				call_user_func(array($controller, ROUTE_A));
			}
		} else {
			exit('Action does not exist.');
		}
	}
	
	/**
	 * 加载控制器
	 * @param string $filename
	 * @param string $m
	 * @return obj
	 */
	private function load_controller() {
			$classname = 'Control_'.ROUTE_M;
			return new $classname;
	}
} 

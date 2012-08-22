<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

class Control_en extends Controller{

	function before(){
	   global $_K;
	}
	
	function action_index(){
		global $_K,$_lang;
// 		 var_dump($_K);
		 
		//var_dump(Keke::$_sys_config+$_K);
		//$_K = array_merge($_K,Keke::$_sys_config);
		
		 require Keke_tpl::template('en');

	}
	function after(){
		
	}
}


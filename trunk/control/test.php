<?php
class Control_test extends Controller{

	function action_index(){
		 global $_K,$_lang;
		 var_dump($this->request->param());
		 die();
		 require Keke_tpl::template('en');
		
	}
 
}

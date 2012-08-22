<?php


class Control_test extends Controller{

	function action_index(){
		 
		 require Keke_tpl::template('en');
		 
		
	}
	function action_getid($id=NULL){
		$id = $this->request->param('id');
		var_dump($id);
	}
	function action_get_ext(){
		//$id = $this->request->param('id');
		var_dump($_GET['id']);
	}
}

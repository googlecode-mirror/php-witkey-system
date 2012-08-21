<?php
class Control_test extends Controller{

	function action_index(){
		$a = Database::instance()->get_query_num();
		var_dump(Keke::execute_time());
		echo 'index';
	}
	function action_getid($id=NULL){
		var_dump($id);
	}
}

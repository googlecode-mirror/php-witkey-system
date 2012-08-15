<?php
class Control_test {
	 

	function index(){
		$a = Database::instance()->get_query_num();
		var_dump(Keke::execute_time());
		echo 'index';
	}
	
}

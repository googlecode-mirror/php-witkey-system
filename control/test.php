<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

class Control_test extends Controller{

	function action_index(){
        $test = 'вс╠Да©';  	 
         
        require Keke_tpl::template('test/link');
	}
 
}

<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

class Control_test extends Controller{

	function action_index(){
        $test = '�ӱ���';  	 

       
        //$this->response->body($test);
       require Keke_tpl::template('link');
	}
 
}

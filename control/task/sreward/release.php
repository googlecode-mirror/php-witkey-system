<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 单赏任务发布
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_sreward_release extends Control_task_base{
    
	function action_index(){
		Keke::init_model();
		$model_info = Keke::$_model_list['1'];
		$r_step = 'step1';
		$base_url = BASE_URL.'/index.php/task/sreward_release/'.$r_step;
		require Keke_tpl::template('release');
	}
	
	
}
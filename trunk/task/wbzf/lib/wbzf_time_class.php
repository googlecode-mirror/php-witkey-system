<?php

final class wbzf_time_class extends time_base_class {

	function __construct() {
		parent::__construct ();
	}
	function validtaskstatus() {
		$this->task_hand_end ();
		//$this->task_choose_end ();
	}
	/**
	 *Ͷ�嵽��
	 *		or ��ѡ��
	 *		or ʧ�ܷ���
	 */
	public function task_hand_end(){
		$task_list = db_factory::query(sprintf(" select * from %switkey_task where task_status=2 and  sub_time < '%s' and model_id = '8' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_hand_obj = new wbzf_task_class($v );
				$task_hand_obj-> time_hand_end();
			}
		}
	}
	/**
	 * ѡ�嵽��
	 * 		or �Զ�ѡ��
	 * 		or ʧ�ܷ���
	 */
// 	public function task_choose_end(){
// 		$task_list = db_factory::query(sprintf(" select * from %switkey_task where task_status=3 and  end_time < '%s' and model_id = '8' ",TABLEPRE,time()));
// 		if(is_array($task_list)){
// 			foreach ( $task_list as $k => $v ) {
// 				$task_choose_obj = wbzf_task_class::get_instance($v );
// 				$task_choose_obj-> time_choose_end();
// 			}
// 		}
		
// 	}
}
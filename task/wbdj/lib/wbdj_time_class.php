<?php

final class wbdj_time_class extends time_base_class {

	function __construct() {
		parent::__construct ();
	}
	function validtaskstatus() {
		$this->task_hand_end ();
	}
	/**
	 *投稿到期.[<end_time]
	 *		[失败、结束]
	 */
	public function task_hand_end(){
		$task_list = db_factory::query(sprintf(" select * from %switkey_task where task_status=2 and  end_time < '%s' and model_id = '9' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_hand_obj = new wbdj_task_class($v );
				$task_hand_obj-> time_hand_end();
			}
		}
	}
}
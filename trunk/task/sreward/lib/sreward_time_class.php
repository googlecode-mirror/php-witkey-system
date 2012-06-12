<?php

final class sreward_time_class extends time_base_class {

	function __construct() {
		parent::__construct ();
	}
	function validtaskstatus() {
		$this->task_hand_end ();
		$this->task_vote_end ();
		$this->task_choose_end ();
		$this->task_notice_end ();
		//$this->task_agreement_end();
	}
	/**
	 *Ͷ�嵽��
	 *		��ѡ��
	 *		ʧ�ܷ���
	 */
	public function task_hand_end(){
		$task_list = dbfactory::query(sprintf(" select * from %switkey_task where task_status=2 and  sub_time < '%s' and model_id = '1' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_hand_obj = sreward_task_class::get_instance($v );
				$task_hand_obj-> time_hand_end();
			}
		}
	}
	/**
	 * ͶƱ����
	 * 		ѡ���빫ʾ
	 * 		ʧ�ܷ���
	 */
	public function task_vote_end(){
		$task_list = dbfactory::query(sprintf(" select * from %switkey_task where task_status=4 and  sp_end_time < '%s' and model_id = '1' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_vote_obj = sreward_task_class::get_instance($v );
				$task_vote_obj-> time_vote_end();
			}
		}
	}
	/**
	 * ѡ�嵽��
	 * 			�빫ʾ
	 * 			��ͶƱ
	 * 			�Զ�ѡ��
	 * 			ʧ�ܷ���
	 */
	public function task_choose_end(){
		$task_list = dbfactory::query(sprintf(" select * from %switkey_task where task_status=3 and  end_time < '%s' and model_id = '1' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_choose_obj = sreward_task_class::get_instance($v );
				$task_choose_obj-> time_choose_end();
			}
		}
		
	}
	/**
	 * ��ʾ����
	 * 		���
	 * 		ʧ�ܷ���
	 */
	public function task_notice_end(){
		$task_list = dbfactory::query(sprintf(" select * from %switkey_task where task_status=5 and  sp_end_time < '%s' and model_id = '1' ",TABLEPRE,time()));
		if(is_array($task_list)){
			foreach ( $task_list as $k => $v ) {
				$task_notice_obj = sreward_task_class::get_instance($v );
				$task_notice_obj-> time_notice_end();
			}
		}
	}
	/**
	 * Э�鵽���Զ�ǩ��
	 */
	public function task_agreement_end(){
		global $model_list;
		$config = unserialize($model_list['1']['config']);
		$agree_list = dbfactory::query(sprintf(" select agree_id,agree_status,on_time from %switkey_agreement where model_id=1 and agree_status<3",TABLEPRE));
		if(is_array($agree_list)){
			foreach ( $agree_list as $k => $v ) {
				$agree_obj = sreward_task_agreement::get_instance($v['agree_id']);
				switch($v['agree_status']){
					case "1"://Ĭ��ǩ��
						if($v['on_time']+$config['auto_agree_time']*24*3600<time()){
						$agree_obj-> agreement_stage_one('1');
						$agree_obj-> agreement_stage_one('2');
						}
						break;
					case "2"://Ĭ�Ͻ���
						if($v['on_time']+$config['max_agree_time']*24*3600<time()){
							$agree_obj->accept_confirm();
						}
						break;
				}
			}
		}
	}
}
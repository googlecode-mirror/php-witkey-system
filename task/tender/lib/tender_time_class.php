<?php
keke_lang_class::load_lang_class('tender_time_class');
final class tender_time_class extends time_base_class {
	
	public $_task_obj;
	public $_task_bid_obj;
	
	function __construct() {
		parent::__construct ();
	}
	function validtaskstatus() {
		$this->_task_bid_obj = new Keke_witkey_task_bid_class();
		$this->_task_obj = new Keke_witkey_task_class();
		$this->task_tbtime_out();
		$this->task_xbtime_out ();


		//$this->task_tptime_end ();
	
	}
	/**
	 * 投标时间结束
	 */
	function task_tbtime_out() {
		global $_lang;
		//获取投标时间结束的任务
		$sql = sprintf("select * from %switkey_task where model_id = 4 and task_status=2 and ".time().">sub_time",TABLEPRE);
		$task_arr = dbfactory::query($sql);
		foreach ($task_arr as $k=>$v){//改变任务状态
			$count = $this->get_task_work($v['task_id'], 0);
			if($count){
				kekezu::notify_user($_lang['tender_notice'], $_lang['you_pub_tender_task']."<a href=index.php?do=task&task_id=".$v['task_id']." >".$v['task_title']."</a>".$_lang['has_choose_tender_please_choose'], $v['uid']);
				$this->set_task_status($v['task_id'],3);
			}else{ 
				$this->set_task_status($v['task_id'],9);
				if ($v['task_union']=='1'){//如果是联盟任务
					$union_obj = new keke_union_class($v['task_id']);
					$union_obj -> change_status('failure');
				}
			//	kekezu::feed_add("$v['username']发布的招标任务:<a href=index.php?do=task&task_id=".$v['task_id']." >".$v['task_title']."</a>,投标期没有威客投标，已失败", $v['uid'], $v['username']);
				kekezu::notify_user($_lang['tender_fail'],$_lang['you_pub_tender_task']."<a href=index.php?do=task&task_id=".$v['task_id']." >".$v['task_title']."</a>".$_lang['submit_tender_no_witkey_fail'], $v['uid']);
			}
		}
	}
	
	/**
	 * 选标时间结束
	 */
	function task_xbtime_out() {
		global $_lang;
		$sql = sprintf("select * from %switkey_task where model_id = 4 and task_status=3 and ".time().">end_time",TABLEPRE);

		$task_arr = dbfactory::query($sql);
				
		foreach ($task_arr as  $k=>$v){
			$count = $this->get_task_work($v['task_id'],4);
			if($count){
				kekezu::notify_user($_lang['tender_notice'], $_lang['you_pub_tender_task']."<a href=index.php?do=task&task_id=".$v['task_id']." >".$v['task_title']."</a>".$_lang['has_success_end'], $v['uid']);
				$this->set_task_status($v['task_id'],5);
			}else{  
				$this->set_task_status($v['task_id'],9);
				if ($v['task_union']=='1'){//如果是联盟任务
					$union_obj = new keke_union_class($v['task_id']);
					$union_obj -> change_status('failure');
				}
				kekezu::notify_user($_lang['tender_fail'],$_lang['you_pub_tender_task']."<a href=index.php?do=task&task_id=".$v['task_id']." >".$v['task_title']."</a>".$_lang['choose_tender_no_choose_fail'], $v['uid']);
			}			
		}
	}
	
//获取稿件信息
	function get_task_work($task_id,$bid_status){
		$this->_task_bid_obj->setWhere("task_id = $task_id and bid_status = $bid_status");
		$count = $this->_task_bid_obj->count_keke_witkey_task_bid();
		if($count>0){
			return $count;
		}else{
			return false;
		}
	}
	
	//改变任务状态
	
	function set_task_status($task_id,$task_status){
			$this->_task_obj->setWhere("task_id = $task_id");
			$this->_task_obj->setTask_status($task_status);
			$res = $this->_task_obj->edit_keke_witkey_task();
			if($res){
				return $res;
			}else{ 
				return false;
			} 
	}
	
	
	
	
}
?>
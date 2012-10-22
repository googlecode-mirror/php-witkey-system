<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 后台列表管理基类
 * @author Michael
 * @version 2.2
   2012-10-21
 */

abstract class Control_task_list extends Control_admin{
    
	/**
	 *
	 * @var 任务id
	 */
	protected  $_task_id ;
	
	function before(){
		$this->_task_id = intval($_GET['task_id']);
	}
    /**
     * 任务推荐
     */
    public function set_recommend(){
    	//改变任务的is_top 为1
    	$where = "task_id = '$this->_task_id'";
    	DB::update('witkey_task')->set(array('is_top'))->value(array(1))->where($where)->execute();
    }
    /**
     * 取消任务推荐
     */
    public function set_unrecommend(){
    	//改变任务的is_top 为0
    	$where = "task_id = '$this->_task_id'";
    	DB::update('witkey_task')->set(array('is_top'))->value(array(0))->where($where)->execute();
    }
    /**
     * 任务冻结
     * 冻结task,任务状态为!('6','7','8','10','11')
     * (2,3,4,5) 可以冻结 ,这里模板上判断
     */
    public function set_freeze(){
    	 
    	//生成冻结记录
    	$task_info = $this->get_task_info();
    	$columns = array('frost_status','task_id','frost_time','admin_uid','admin_username');
    	$values = array($task_info['task_status'],$task_info['task_id'],time(),$_SESSION['admin_uid'],$_SESSION['admin_username']);
    
    	DB::insert('witkey_task_frost')->set($columns)->value($values)->execute();
    	//改变任务状态为7 freeze
    	$this->set_status(7);
    	 
    }
    /**
     * 任务解冻
     */
    public function set_unfreeze(){
    	//改变任务状态，加上任务的结束时间
    	$where = "task_id = $this->_task_id";
    	$frost_info = DB::select()->from('witkey_task_frost')->where($where)->get_one()->execute();
    	$task_info = $this->get_task_info();
    	//新的任务结束时间
    	$end_time = (time () - $frost_info ['frost_time']) + $task_info['end_time'];
    	//新的交稿结束时间,当前时间减冻结时间的差值加在原来的时间上
    	$sub_time = (time () - $frost_info ['frost_time']) +$task_info['sub_time'];
    	$columns = array('sub_time','end_time','task_status');
    	$values = array($sub_time,$end_time,$frost_info['frost_status']);
    	DB::update('witkey_task')->set($columns)->value($values)->where($where)->execute();
    	//删除冻结记录
    	DB::delete('witkey_task_frost')->where($where)->execute();
    	 
    }
    /**
     * 通过审核，任务状态由1->2
     */
    public function set_pass(){
    	global $_lang;
    	//获取任务住处
    	$task_info =  $this->get_task_info();
    	//改变任务状态，及任务的产发布时间与结束时间
    	$end_time = $task_info['end_time'] + (time()-$task_info['start_time']);
    	$where = "task_id = $this->_task_id ";
    	DB::update('witkey_task')->set(array('task_status','start_time','end_time'))->value(array(2,time(),$end_time))->where($where)->execute();
    	//更新任务增值服务的结束时间
    	DB::update('witkey_payitem_record')
    	->set(array('end_time'))
    	->value(array('use_num*24*3600+'.time()))
    	->where("obj_type='task' and obj_id = '$this->_task_id'")->execute();
    	//生成推送feed
    	$feed_arr = array ("feed_username" => array ("content" =>$task_info['username'], "url" => "index.php?do=space&member_id={$task_info['uid']}" ), "action" => array ("content" => $_lang['pub_task'], "url" => "" ), "event" => array ("content" => "{$task_info['task_title']}", "url" => "index.php/task/{$task_info['task_id']}" ) );
    	Sys_feed::set_feed($feed_arr, $task_info['uid'], $task_info['username'],'pub_task',$this->_task_id);
    }
    /**
     * 不通过审核
     * 状态状态1->10 审核失败
     */
    public function set_no_pass(){
    	//改变任务状态
    	$this->set_status(10);
    	$task_info = $this->get_task_info();
    	//退还任务赏金
    	Keke::init_model();
    	$model_name = Keke::$_model_list[$task_info['model_id']]['model_name'];
    	$data = array($model_name,$task_info['task_title']);
    	Sys_finance::init_mem('task_fail', $data);
    	//只退还赏金，其它费用不退
    	Sys_finance::cash_in ( $task_info ['uid'], $task_info ['task_cash'], 0, 'task_fail', 'admin', 'task', $task_info ['task_id'] );
    }
    
    
    /**
     * 获取任务信息
     * @param int $task_id
     * @return Ambigous <string, unknown, Ambigous>
     */
    public function get_task_info($task_id = NUll){
    	if($task_id == NULL){
    		$where = "task_id = '$this->_task_id'";
    	}else{
    		$where = "task_id = '$task_id'";
    	}
    	return  DB::select()->from('witkey_task')->where($where)->get_one()->execute();
    }
    /**
     * 更新任务状态
     * @param int $status
     * @param int $task_id
     */
    public function set_status($status,$task_id=NULL){
    	if($task_id===NULL){
    		$where = "task_id = '$this->_task_id'";
    	}else{
    		$where = "task_id = '$task_id'";
    	}
    	return (bool)DB::update('witkey_task')->set(array('task_status'))->value(array($status))->where($where)->execute();
    }
}
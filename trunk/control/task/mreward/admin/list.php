<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 单赏后台配置列表
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_mreward_admin_list extends Control_task_list{
	/**
	 * @var 模型代码
	 */
	public  $_model_code   = 'mreward';
	/**
	 * 任务列表页
	 */
    function action_index(){
    	global $_K,$_lang;
    	
    	//要显示的字段,即SQl中SELECT要用到的字段
    	$fields = ' `task_id`,`task_title`,`username`,`task_cash`,`model_id`,`task_status`,`indus_id`,`work_num`,`contact`,`start_time`,`is_top`';
    	//要查询的字段,在模板中显示用的
    	$query_fields = array('task'=>$_lang['id'],'task_title'=>$_lang['name'],'task_cash'=>$_lang['cash']);
    	//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
    	$count = intval($_GET['count']);
    	//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
    	$base_uri = BASE_URL."/index.php/task/".$this->_model_code."_admin_list";
    	//添加编辑的uri,add这个action 是固定的
    	$add_uri =  $base_uri.'/add';
    	//删除uri,del也是一个固定的，写成其它的，你死定了
    	$del_uri = $base_uri.'/del';
    	//默认排序字段，这里按时间降序
    	$this->_default_ord_field = 'task_id';
    	//这里要口水一下，get_url就是处理查询的条件
    	extract($this->get_url($base_uri));
    	//查指定类型的任务
    	$model_id = DB::select('model_id')->from('witkey_model')->where("model_code='$this->_model_code'")->get_count()->execute();
    	$where  .= " and model_id = $model_id";
    	//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
    	$data_info = Model::factory('witkey_task')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
    	//列表数据
    	$list_arr = $data_info['data'];
    	//分页数据
    	$pages = $data_info['pages'];
    	
    	$task_status = Control_task_mreward_task::get_task_status();
     	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/list');
    }
    /**
     * 任务编辑
     */
    public function action_add(){
    	
    }
    /**
     * 任务保存
     */
    public function action_save(){
    	
    }
    /**
     * 任务推荐
     */
    public function action_recommend(){
    	
    }
    /**
     * 任务冻结
     */
    public function action_freeze(){
    	
    }
    /**
     * 任务解冻
     */
    public function action_unfreeze(){
    	
    }
    
}
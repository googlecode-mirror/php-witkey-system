<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 单赏后台配置列表
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_sreward_admin_list extends Control_task_list{
	/**
	 * @var 模型代码
	 */
	public  $_model_code   = 'sreward';
 
	/**
	 * 任务列表页
	 */
    function action_index(){
    	global $_K,$_lang;
    	
    	//要显示的字段,即SQl中SELECT要用到的字段
    	$fields = ' `task_id`,`task_title`,`username`,`task_cash`,`model_id`,`task_status`,`indus_id`,`work_num`,`contact`,`start_time`,`is_top`';
    	//要查询的字段,在模板中显示用的
    	$query_fields = array('task_id'=>$_lang['id'],'task_title'=>$_lang['name'],'task_cash'=>$_lang['cash']);
    	//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
    	$count = intval($_GET['count']);
    	//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
    	$base_uri = $this->_base_uri;
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
    	
    	$task_status = Control_task_sreward_task::get_task_status();
     	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/list');
    }
    /**
     * 任务编辑
     */
    public function action_add(){
    	global  $_K ,$_lang;
    	$task_id = $this->_task_id;
    	 //获取任务信息
        $task_info = $this->get_task_info();
         
        $base_uri = $this->_base_uri;
        $process_arr = Control_task_list::can_operate($task_info['task_status']);
        $indus_option_arr = Sys_indus::get_indus_tree($task_info['indus_id']);
        //单赏任务状态
        $status_arr = Control_task_sreward_task::get_task_status();
        //获取任务的增值项
        $payitem_list = Sys_payitem::get_task_payitem($this->_task_id);
        
        $file_list = Control_task_list::get_task_file($this->_task_id);
         
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/task_edit');
    }
    
    /**
     * 任务保存
     */
    public function action_save(){
    	$task_id = $_POST['task_id'];
    	if(!$task_id){
    		return FALSE;
    	}
    	Keke::formcheck($_POST['formhash']);
    	$array = array('task_title'=>$_POST['task_title'],
    			'indus_id'=>$_POST['slt_indus_id'],
    			'task_desc'=>$_POST['task_desc']);
    	$where = "task_id = $task_id";
    	Model::factory('witkey_task')->setData($array)->setWhere($where)->update();
    	$this->request->redirect($this->request->referrer());
    	
    }
    
    /**
     * 任务推荐
     */
    public function action_recommend(){
    	 $this->set_recommend();
    }
    /**
     * 取消任务推荐
     */
    public function action_unrecommend(){
    	//改变任务的is_top 为0
    	$this->set_unrecommend();
    }
    /**
     * 任务冻结
     * 冻结task,任务状态为!('6','7','8','10','11')
	 * (2,3,4,5) 可以冻结 ,这里模板上判断
     */
    public function action_freeze(){
    	$this->set_freeze();
    }
    /**
     * 任务解冻
     */
    public function action_unfreeze(){
    	 $this->set_unfreeze();
    }
    /**
     * 通过审核，任务状态由1->2
     */
    public function action_pass(){
    	$this->set_pass();
    }
    /**
     * 不通过审核 
     * 状态状态1->10 审核失败
     */
    public function action_no_pass(){
    	 $this->set_no_pass();
    }
    /**
     * 删除任务
     */
    public function  action_del(){
    	//删除任务搞件
    	$where = "task_id = $this->_task_id";
    	DB::delete('witkey_task_work')->where($where)->execute();
    	echo DB::delete('witkey_task')->where($where)->execute();
    }
    /**
     * 任务搞件列表页
     */
    public function action_work(){
    	global  $_K ,$_lang;
    	$task_id = $this->_task_id;
    	//获取任务信息
    	$task_info = $this->get_task_info();
    	$base_uri = $this->_base_uri;
        $sql = "SELECT b.work_id,b.work_desc,b.task_id ,b.username,b.work_status,
        		b.work_time,b.hide_work,b.vote_num,
        		GROUP_CONCAT(c.content) as contents, \n".
				"GROUP_CONCAT(d.file_name) as files,GROUP_CONCAT(d.save_name) as save_names\n".
				"from ".TABLEPRE."witkey_task as a \n".
				"right join `".TABLEPRE."witkey_task_work` as b \n".
				"on b.task_id = a.task_id \n".
				"left join ".TABLEPRE."witkey_comment as c\n".
				"on b.work_id = c.obj_id  and c.obj_type='work'\n".
				"left join ".TABLEPRE."witkey_file as d\n".
				"on b.work_id = d.obj_id and d.obj_type = 'work'\n";
				//"where a.task_id=".$task_id."\n".
				//"GROUP BY b.work_id";
        //要查询的字段,在模板中显示用的
        $query_fields = array('work_id'=>$_lang['id'],'work_desc'=>$_lang['name'],'username'=>$_lang['username']);
        //总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
        $count = intval($_GET['count']);
        //基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
        $base_uri = $this->_base_uri;
        //删除uri,del也是一个固定的，写成其它的，你死定了
        $del_uri = $base_uri.'/del';
        //默认排序字段，这里按时间降序
        $this->_default_ord_field = 'work_id';
        //这里要口水一下，get_url就是处理查询的条件
        extract($this->get_url($base_uri.'/work?task_id='.$this->_task_id));
        
        //获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
//      $data_info = Model::factory('witkey_task')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
        $data_info = Model::sql_grid($sql,"a.task_id=".$task_id,$uri,$order,"GROUP BY b.work_id",$page,$count,$_GET['page_size']);
        //列表数据
        $list_arr = $data_info['data'];
        //分页数据
        $pages = $data_info['pages'];
	 
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/task_work');
    }
    /**
     * 任务留言列表页
     */
    public function action_comment(){
    	global  $_K ,$_lang;
    	$task_id = $this->_task_id;
    	$base_uri = $this->_base_uri;
    	//获取任务信息
    	$task_info = $this->get_task_info();
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/task_comment');
    }
    /**
     * 任务互评列表页
     */
    public function action_mark(){
    	global  $_K ,$_lang;
    	$task_id = $this->_task_id;
    	$base_uri = $this->_base_uri;
    	//获取任务信息
    	$task_info = $this->get_task_info();
    	
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/task_mark');
    }
    /**
     * 任务交付列表页
     */
    public function action_agree(){
    	global  $_K ,$_lang;
    	$task_id = $this->_task_id;
    	$base_uri = $this->_base_uri;
    	//获取任务信息
    	$task_info = $this->get_task_info();
    	
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/task_agree');
    }

    
}
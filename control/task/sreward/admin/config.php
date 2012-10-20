<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 单赏配置管理
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_sreward_admin_config extends Controller{
	/**
	 * @var 模型代码
	 */
    protected $_model_code   = 'sreward';
    /**
     * 基本配置
     */
	function action_index(){
    	global $_K,$_lang;
        Keke::init_model();
        $model_list = Keke::get_arr_by_key(Keke::$_model_list,'model_code');
    	$model_info = $model_list[$this->_model_code];
    	//模型信息
    	$model_info += unserialize($model_info['config']);
    	
    	$indus_index = Sys_indus::get_indus_by_index();
    	
    	$milist = explode(',',$model_info['indus_bid']);
    	$indus_arr = Sys_indus::get_industry();
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/config');
    }
    /**
     * 流程配置
     */
    function action_control(){
    	
    }
    /**
     * 保存配置数据
     */
    function action_config_save(){
    	global $_lang;
    	Keke::formcheck($_POST['formhash']);
    	$_POST = Keke_tpl::chars($_POST);
    	$_POST['fds']['indus_bid'] = implode(',', $_POST['fds']['indus_bid']);
    	Model::factory('witkey_model')->setData($_POST['fds'])->setWhere('model_id = '.$_POST['fds']['model_id'])->update();
    	Cache::instance()->del('keke_model');
    	Keke::show_msg($_lang['submit_success'],'task/'.$this->_model_code.'_admin_config');
    }
    /**
     * 保存流程配置 
     */
    function action_control_save(){
    	
    }
    
  
}
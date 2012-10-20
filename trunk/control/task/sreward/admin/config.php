<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �������ù���
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_sreward_admin_config extends Controller{
	/**
	 * @var ģ�ʹ���
	 */
    protected $_model_code   = 'sreward';
    /**
     * ��������
     */
	function action_index(){
    	global $_K,$_lang;
        Keke::init_model();
        $model_list = Keke::get_arr_by_key(Keke::$_model_list,'model_code');
    	$model_info = $model_list[$this->_model_code];
    	//ģ����Ϣ
    	$model_info += unserialize($model_info['config']);
    	
    	$indus_index = Sys_indus::get_indus_by_index();
    	
    	$milist = explode(',',$model_info['indus_bid']);
    	$indus_arr = Sys_indus::get_industry();
    	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/config');
    }
    /**
     * ��������
     */
    function action_control(){
    	
    }
    /**
     * ������������
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
     * ������������ 
     */
    function action_control_save(){
    	
    }
    
  
}
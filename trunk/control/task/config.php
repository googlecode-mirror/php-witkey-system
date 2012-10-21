<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 任务后台配置控制基类
 * @author Michael
 * @version 2.2
   2012-10-19
 */

abstract class Control_task_config extends Controller{
    
	/**
	 * @var 模型配置信息
	 */
	protected $_model_info ;
	/**
	 * @var 行业索引
	 */
	protected $_indus_index;
	/**
	 * @var 绑定行业;
	 */
	protected $_milist;
	/**
	 * @var 所有行业
	 */
	protected $_indus_arr;
	/**
	 * @var 绑定行业的同级行业
	 */
	protected $_sub_indus;
	/**
	 * 模型的基本配置信息
	 * @see Keke_Controller::before()
	 */
	public function before(){
		Keke::init_model();
		$model_list = Keke::get_arr_by_key(Keke::$_model_list,'model_code');
		$model_info = $model_list[$this->_model_code];
		//模型信息
		$model_info += unserialize($model_info['config']);
		$this->_model_info = $model_info;
		
		$this->_indus_index =$indus_index = Sys_indus::get_indus_by_index();
		 
		$this->_milist = $milist = explode(',',$model_info['indus_bid']);
		$this->_indus_arr = $indus_arr = Sys_indus::get_industry();
		//能过子分类得到同类子分类
		if($milist){
			$sql = "select indus_id ,indus_name from %switkey_industry a inner join
			(select  indus_pid  from %switkey_industry where indus_id in('%s') ) b
			on a.indus_pid = b.indus_pid";
			$sql = sprintf($sql,TABLEPRE,TABLEPRE,$model_info['indus_bid']);
			$this->_sub_indus = $sub_indus = DB::query($sql)->execute();
		}
	}
	/**
	 * 保存配置
	 * @return boolean
	 */
	public function config_save(){
		Keke::formcheck($_POST['formhash']);
		$_POST = Keke_tpl::chars($_POST);
		$_POST['fds']['indus_bid'] = implode(',', $_POST['fds']['indus_bid']);
		Model::factory('witkey_model')->setData($_POST['fds'])->setWhere('model_id = '.$_POST['fds']['model_id'])->update();
		Cache::instance()->del('keke_model');
		return TRUE;
	}
	
}
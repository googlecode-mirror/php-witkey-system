<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �����̨���ÿ��ƻ���
 * @author Michael
 * @version 2.2
   2012-10-19
 */

abstract class Control_task_config extends Controller{
    
	/**
	 * @var ģ��������Ϣ
	 */
	protected $_model_info ;
	/**
	 * @var ��ҵ����
	 */
	protected $_indus_index;
	/**
	 * @var ����ҵ;
	 */
	protected $_milist;
	/**
	 * @var ������ҵ
	 */
	protected $_indus_arr;
	/**
	 * @var ����ҵ��ͬ����ҵ
	 */
	protected $_sub_indus;
	/**
	 * ģ�͵Ļ���������Ϣ
	 * @see Keke_Controller::before()
	 */
	public function before(){
		Keke::init_model();
		$model_list = Keke::get_arr_by_key(Keke::$_model_list,'model_code');
		$model_info = $model_list[$this->_model_code];
		//ģ����Ϣ
		$model_info += unserialize($model_info['config']);
		$this->_model_info = $model_info;
		
		$this->_indus_index =$indus_index = Sys_indus::get_indus_by_index();
		 
		$this->_milist = $milist = explode(',',$model_info['indus_bid']);
		$this->_indus_arr = $indus_arr = Sys_indus::get_industry();
		//�ܹ��ӷ���õ�ͬ���ӷ���
		if($milist){
			$sql = "select indus_id ,indus_name from %switkey_industry a inner join
			(select  indus_pid  from %switkey_industry where indus_id in('%s') ) b
			on a.indus_pid = b.indus_pid";
			$sql = sprintf($sql,TABLEPRE,TABLEPRE,$model_info['indus_bid']);
			$this->_sub_indus = $sub_indus = DB::query($sql)->execute();
		}
	}
	/**
	 * ��������
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
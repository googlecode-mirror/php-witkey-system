<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù�����Ʋ�
 * @author Michael
 * 2012-10-03
 */
class Control_admin_config_model extends  Controller {
    /**
     * ��ʼ������ҳ�棬
     * @param string $type ȷ�������Ǹ�����ģ���ļ�
     */
	function action_index($type=NULL){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
	 	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//�����������ͣ�Ĭ��Ϊweb�� 
		//�б�����,ϵͳ��ʼ��ʱ�Ѿ�����,���������ٲ�
		$config_arr = Keke::$_sys_config;
		//�����б�
		$lang_arr = Keke::$_lang_list;
		//Ĭ��Ϊֻ��ʾ������ص�����ģ��
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'task';
		}
		//var_dump($type);die;
		//ģ���б�,�Ѿ���ʼ�����������ٲ�
		Keke::init_model();
		$list = Keke::$_model_list;
		$model_list = array();
		//var_dump($list);
		//��ģ�ͽ���ɸѡ��ԭ���Ƿ���ģ���ϵ�
		foreach ($list as $k=>$v){
			if($v['model_type']==$type){
				$model_list[$k] = $v;
			}
		} 
		
		require Keke_tpl::template('control/admin/tpl/config/model');
	}
	/**
	 * ��ʾ�̳ǵ����ģ��
	 */
	function action_shop(){
		$this->action_index('shop');
	}
	/**
	 * ģ�Ͱ�װ��������ģ�ͻ���
	 */
	function action_install(){
		 
	}
	/**
	 * ж������ģ��
	 */
	function action_uninstall(){
		if(($model_name = $_POST['txt_model_name'])!=null){
			
		}
	} 
	/**
	 * ģ�ͽ��� �ı�ģ�͵�״̬
	 */
	function action_disable(){
		global $_lang;
		$status = $_GET['disable']==1?'0':'1';
		$model_id = $_GET['model_id'];
		$where = "model_id = '$model_id'";
		DB::update('witkey_model')->set(array('model_status'))->value(array($status))->where($where)->execute();
		Cache::instance()->del('keke_model');
		$ac = $_GET['type']=='task'?'index':'shop';
		Keke::show_msg($_lang['submit_success'],'admin/config_model/'.$ac);
	}
 
	
	
}

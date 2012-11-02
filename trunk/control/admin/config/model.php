<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù�����Ʋ�
 * @author Michael
 * 2012-10-03
 */
class Control_admin_config_model extends  Control_admin {
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
		global $_lang;
		$type = $_POST['type']?$_POST['type']:'task';
		if(($model_name = $_POST['txt_model_name'])!=null){
			
			//�ж�ģ���Ƿ��Ѱ�װ
			if(DB::select('model_id')->from('witkey_model')->where("model_code = '$model_name'")->get_count()->execute()){
				Keke::show_msg($_lang['submit_fail'],'admin/config_model','error');
			}
			//ģ��������Ϣ
			$init_config = array();
			//���˵�
			$menu_arr = array();
			//�Ӳ˵�
			$sub_menu_arr = array();
			//���س��������ļ�,
			include S_ROOT.'control/'.$type.'/'.$model_name.'/init_config.php';
			//���ģ������
			if($init_config){
				$config = $init_config['config'];
				unset($init_config['config']);
				$init_config['config'] = serialize($config);
				$init_config['on_time'] = strtotime($init_config['on_time']);
				Model::factory('witkey_model')->setData($init_config)->create();
			}
			//���˵�
			if($menu_arr){
				$where = "submenu_id = ".$menu_arr['submenu_id'];
				if(DB::select('submenu_id')->from('witkey_resource_submenu')->where($where)->get_count()->execute()){
					Model::factory('witkey_resource_submenu')->setData($menu_arr)->setWhere($where)->update();
				}else{
					Model::factory('witkey_resource_submenu')->setData($menu_arr)->create();
				}
			}
			//���Ӳ˵�
			if($sub_menu_arr){
				foreach ($sub_menu_arr as $k=>$v){
					$where = "resource_id = ".$v['resource_id'];
					if(DB::select('resource_id')->from('witkey_resource')->where($where)->get_count()->execute()){
						Model::factory('witkey_resource')->setData($v)->setWhere($where)->update();
					}else{
						Model::factory('witkey_resource')->setData($v)->create();
					}
				}
			}
			Cache::instance()->del('keke_model');
			
			Keke::show_msg($_lang['submit_success'],'admin/config_model');
			
			
		}
	}
	/**
	 * ж������ģ��
	 */
	function action_uninstall(){
		$model_id = $_GET['model_id'];
	    if($model_id){
	    	echo DB::delete('witkey_model')->where('model_id='.$model_id)->execute();
	    }
	    Cache::instance()->del('keke_model');
	    
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

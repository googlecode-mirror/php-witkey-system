<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 22
 * @todo ��̨��֤��Ŀ��װ��ɾ��
 * 2011-9-01 11:35:13
 */
class Control_admin_auth_list extends Control_admin{

	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;

		$auth_item_arr = DB::select('auth_code,auth_title,auth_day,auth_cash,auth_expir,auth_open,update_time')->from('witkey_auth_item')->execute();
        $base_uri = BASE_URL.'/index.php/admin/auth_list';
        
        require keke_tpl::template('control/admin/tpl/auth/list');
 
	}
	/**
	 * �����֤��Ŀ��
	 * @param $auth_dir auth dir
	 * @return   void
	 */
	function action_install() {
		global $_lang;
		$auth_dir = $_POST['auth_dir'];
		if ($auth_dir) {
			$base_uri = 'admin/auth_list';
			$file_path = S_ROOT . "control/auth/" . $auth_dir . "/init_config.php";
			if(file_exists ( $file_path )){
				$menu_arr = array();
				$auth_config  = array();
				//���س�ʼ������
				require $file_path;
				$exists    = DB::select('auth_code')->from('witkey_auth_item')->where("auth_code='$auth_dir'")->get_count()->execute();
				//�����֤���Ѿ����ڣ�����Ҫ�ٴΰ�װ
				$exists and Keke::show_msg($_lang['auth_item_exist_add_fail'],$base_uri,'warning');
				//�������ݿ�
				Model::factory('witkey_auth_item')->setData($auth_config)->create();
				
				//��̨�˵��Ĵ���
				//�˵�id
				$r_id = $menu_arr['resource_id']; 
				$r_id_exists = DB::select('resource_id')->from('witkey_resource')->where("resource_id = $r_id")->get_count()->execute();
				//��̨�˵���ID������Ϊ��Ȩ�޿��Կ���
				if($r_id_exists){
					//������ھ͸���
					Model::factory('witkey_resource')->setData($menu_arr)->setWhere("resource_id = '$r_id'")->update();
				}else{
					//����ʹ���
					Model::factory('witkey_resource')->setData($menu_arr)->create();
				}
				
				//��װ�ɹ� 
				Keke::show_msg($_lang['auth_item_add_success'],$base_uri,'success');
			}else{
				//��װ��ʼ���ļ�������
				Keke::show_msg($_lang['unknow_error_add_fail'],$base_uri,'warning');
			}
		} else {
			//��װ��Ŀ¼����Ϊ��
			Keke::show_msg($_lang['unknow_error_add_fail'],$base_uri,'warning');
		}
	}
	/**
	 * ��Ϊ״̬
	 */
	function action_disable(){
		global  $_lang;
		$auth_code = $_GET['auth_code'];
		$auth_open = intval($_GET['auth_open']);
		$where .="auth_code='$auth_code'";
		$columns = array('auth_open');
		$value = array($auth_open);
		//�ı�auth_open״̬
		DB::update('witkey_auth_item')->set($columns)->value($value)->where($where)->execute();
		keke::show_msg($_lang['submit_success'],"admin/auth_list","success");
		
	}
	/**
	 * �༭��֤����
	 */
	function action_edit(){
		global $_K,$_lang;
		$auth_code = $_GET['auth_code'];
		if($auth_code){
			$where .="auth_code='$auth_code'";
			$auth_item = DB::select()->from('witkey_auth_item')->where($where)->get_one()->execute();
		}
		require keke_tpl::template('control/admin/tpl/auth/edit');
	}
	/**
	 * ������֤����
	 */
	function action_save(){
		global $_lang;
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		$array = array( 
				'auth_day'=>$_POST['auth_day'],
				'auth_cash'=>$_POST['auth_cash'],
				'auth_expir'=>$_POST['auth_expir'],
				'auth_big_ico'=>$_POST['hdn_big_icon'],
				'auth_small_n_ico'=>$_POST['hdn_small_before_icon'],
				'auth_small_ico'=>$_POST['hdn_small_after_icon'],
				'auth_desc'=>$_POST['auth_desc'],
				'auth_open'=>$_POST['auth_open'],
				'listorder'=>$_POST['listorder']
				);
		$auth_code = $_POST['auth_code'];
		$where = " auth_code = '{$_POST['auth_code']}'";
		Model::factory('witkey_auth_item')->setData($array)->setWhere($where)->update();
		Keke::show_msg($_lang['submit_success'],'admin/auth_list/edit?auth_code='.$auth_code,'success');
	}
	/**
	 * ɾ����֤��
	 */
	function action_del(){
		$auth_code = $_GET['auth_code'];
		$where .="auth_code='$auth_code'";
		//Ҫɾ����֤�Ĳ˵�
		$menu_arr = array();
		$path = S_ROOT.'/control/auth/'.$auth_code.'/init_config.php';
		include $path;
		$resource_id = $menu_arr['resource_id'];
		DB::delete('witkey_resource')->where("resource_id = '$resource_id'")->execute();
		
		echo Model::factory('witkey_auth_item')->setWhere($where)->del();
	}

}
 
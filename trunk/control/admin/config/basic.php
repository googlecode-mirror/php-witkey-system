<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù�����Ʋ�
 * @author michael
 *
 */
class Control_admin_config_basic extends  Controller {

	function action_index($type=NULL){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
	 	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//�����������ͣ�Ĭ��Ϊweb�� 
		//�б�����
		$config_arr = Keke::$_sys_config;
		//�����б�
		$lang_arr = Keke::$_lang_list;
		//Ĭ��Ϊϵͳ����ģ��
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'web';
		}
		
		require Keke_tpl::template('control/admin/tpl/config/'.$type);
	}
	/**
	 * ��������
	 */
	function action_basic(){
		$this->action_index('basic');
	}
	/**
	 * seo���� 
	 */
	function action_seo(){
		$this->action_index('seo');
	}
	/**
	 * �ʼ�����
	 */
	function action_mail(){
		$this->action_index('mail');
	}
	
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		$type = $_POST['type'];
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$values = $_POST;
		unset($values['formhas']);
		unset($values['type']);
		foreach ($values as $k=>$v) {
			$where = "k = '$k'";
			DB::update('witkey_config')->set(array('v'))->value(array($v))->where($where)->execute();
		}
		Cache::instance()->del('keke_config');
		//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
		Keke::show_msg('ϵͳ��ʾ','index.php/admin/config_basic/index?type='.$type,'�ύ�ɹ�','success');
		
	}
	
}

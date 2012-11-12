<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �û���������
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-7-16����06:02:31
 * @example //1��ʾ������  2 ��ʾ����uc  3 ��ʾ����pw
 */
class Control_admin_user_integrate extends Control_admin{
	/**
	 * ��ʼ�����ϵ��б�ҳ
	 */
	function action_index(){
		global $_K,$_lang;
		
		require  Keke_tpl::template ( 'control/admin/tpl/user/integrate');
	}
	/**
	 * ucenter����
	 */
	function action_uc(){
		global $_K,$_lang;
		//���������ļ�
		require  S_ROOT.'config/config_ucenter.php';
		//��ȡ�����ļ�������
		$config_ucenter = keke_file_class::read_file(S_ROOT."config/config_ucenter.php");
		if(!$_POST){
			require  Keke_tpl::template ( 'control/admin/tpl/user/integrate_uc');
			die;
		}
		
		Keke::formcheck($_POST['formhash']);
		//��ȡ
		$settingnew  = $_POST['settingnew'];
		//var_dump($settingnew);die;
		//�û�����������õĲ���
		foreach ($settingnew as $k=>$v){
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);".PHP_EOL."/i", "define('$k', '$v');".PHP_EOL, $config_ucenter);
		}
		//д����
		keke_file_class::write_file(S_ROOT."config/config_ucenter.php",$config_ucenter);
		//uc_server �ĵ�ַ
		$bbserver = 'http://'.preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']).($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
		//ucenter�ĵ�ַ
		$default_ucapi = $bbserver.'/ucenter';
		//Ӧ������
		$app_type = 'OTHER';
		//Ӧ������
		$app_name = $_K['website_name']?$_K['website_name']:P_NAME;
		//��ǰӦ�õĵ�ַ
		$app_url = $_K['website_url']?$_K['website_url']:'http://localhost';
		//��uc ��api,���ϵͳ�ж�������Ѷ���ģ��������Ĭ�ϵ�
		$ucapi = $settingnew['UC_API'] ? $settingnew['UC_API'] : (defined('UC_API') && UC_API ? UC_API : $default_ucapi);
		//ucenter��IpĬ�Ͽ���Ϊ��
		$ucip = isset($settingnew['UC_IP']) ? $settingnew['UC_IP'] : '';
		//uc������
		$ucfounderpw = $_POST['uc_creater'];
		//û�ж���Ͷ���ucapi
		UC_API?UC_API:define(UC_API, $settingnew['UC_API']);
		//����uc��client
		include S_ROOT.'client/ucenter/client.php';
		//��fopen��ȡucenter��info
		$ucinfo = uc_fopen($ucapi.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
		//�ֽ�uc_info����Ӧ�ı���
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
	    //�����ok����ͨѶʧ��
		if($status != 'UC_STATUS_OK') {
			//ucͨѶʧ��
			Keke::show_msg($_lang['uc_communication_fail'],'admin/user_integrate/uc','warning');
		} else {
	        //ͨѶ�ɹ�
	        //���ݿ����
			$dbcharset = strtolower(DBCHARSET);
			$ucdbcharset = strtolower($settingnew['UC_DBCHARSET'] ? str_replace('-', '', $settingnew['UC_DBCHARSET']) : $settingnew['UC_DBCHARSET']);
			//uc�ͻ��˰汾�����˰汾�Ƚϲ�һ�£�������ʾ
			if(UC_CLIENT_VERSION > $ucversion) {
				Keke::show_msg($_lang['uc_different_version'],'admin/user_integrate/uc','warning');
			//�Ƚ�ucenter�����ݿ�����뱾�ص����ݿ����
			} elseif($ucdbcharset != $dbcharset) {
				Keke::show_msg($_lang['uc_different_coding'],'admin/user_integrate/uc','warning');
			}
		    //����app��ӵ��������
			$p_arr = array('m'=>'app','a'=>'add',
					'ucfounderpw'=>$ucfounderpw,
					'apptype'=>$app_type,
					'appname'=>$app_name,
					'appurl'=>$app_url,
					'appip'=>$ucip,
					'appcharset'=>CHARSET,
					'appdbcharset'=>$dbcharset,
					'release'=>UC_CLIENT_RELEASE);
			$postdata = http_build_query($p_arr);
			//�������
			$ucconfig = uc_fopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
			
			//var_dump($postdata,$ucconfig);die;
			
	        //���ص�ֵΪ�գ������ʧ��
			if(empty($ucconfig)) {
				Keke::show_msg($_lang['uc_app_fail_to_add'],'admin/user_integrate/uc','error');
			} elseif($ucconfig == '-1') {
				//��1Ϊ�����������
				Keke::show_msg($_lang['uc_error_author_password'],'admin/user_integrate/uc','error');
			} else {
				list($appauthkey, $appid) = explode('|', $ucconfig);
				if(empty($appauthkey) || empty($appid)) {
					//�����Ч
					Keke::show_msg($_lang['uc_app_invalid_to_add'],'admin/user_integrate/uc','error');
				}
			}
		}
	    //��ӳɹ���Ҫдuckey,��uc_appid
		$ucconfig_info = explode('|', $ucconfig);
		$config_ucenter = keke_file_class::read_file(S_ROOT."config/config_ucenter.php");
		$config_ucenter = preg_replace("/define\('UC_KEY',\s*'.*?'\);/s", "define('UC_KEY', '".$ucconfig_info[0]."');", $config_ucenter);
		$config_ucenter = preg_replace("/define\('UC_APPID',\s*'*.*?'*\);/s", "define('UC_APPID', ".$ucconfig_info[1].");", $config_ucenter);
		//дuc�������ļ�
		keke_file_class::write_file(S_ROOT."config/config_ucenter.php",$config_ucenter);
	    //�������ݿ�
	    DB::update('witkey_config')->set(array('v'))->value(array('2'))
	    ->where("k='user_intergration'")->execute();
		//���»���
		Cache::instance()->del('keke_config');
		//ϵͳ��־
		Keke::admin_system_log($_lang['uc_integrate_log']);
		Keke::show_msg($_lang['uc_integrate_success'],"admin/user_integrate/uc",'success');
		
	}
	/**
	 * Phpwind����
	 */
	function action_pw(){
		global $_K,$_lang;
		require S_ROOT.'config/config_pw.php';
		if(!$_POST){
			require  Keke_tpl::template ( 'control/admin/tpl/user/integrate_pw' );
			die();
		}
		Keke::formcheck($_POST['formhash']);
		$config_ucenter = keke_file_class::read_file(S_ROOT."config/config_pw.php");
		$settingnew = $_POST['settingnew'];
		foreach ($settingnew as $k=>$v){
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);".PHP_EOL."/i", "define('$k', '$v');".PHP_EOL, $config_ucenter);
		}
		keke_file_class::write_file(S_ROOT."./config/config_pw.php",$config_ucenter);
	    
		DB::update('witkey_config')->set(array('v'))->value(array('3'))->where("k='user_intergration'")->execute();
	
		Keke::admin_system_log($_lang['pw_integrate_log']);
		//���»���
		Cache::instance()->del('keke_config');
		Keke::show_msg($_lang['phpwind_integrate_success'],"admin/user_integrate/pw",'success');
		
	 		
		 
	}
	function action_del(){
		global $_lang;
		//�������ݿ�
		DB::update('witkey_config')->set(array('v'))
		->value(array(1))
		->where("k='user_intergration'")
		->execute();
		//����ϵͳ����
		Cache::instance()->del('keke_config');
		Keke::show_msg($_lang['success_uninstall'],"admin/user_integrate",'success');
	}
	
}


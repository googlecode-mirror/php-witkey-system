<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �û���������
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-7-16����06:02:31
 * @example //1��ʾ������  2 ��ʾ����uc  3 ��ʾ����pw
 */
class Control_admin_user_integrate extends Controller{
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
		//�û�����������õĲ���
		foreach ($settingnew as $k=>$v){
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);/i", "define('$k', '$v');".PHP_EOL, $config_ucenter);
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
		include S_ROOT.'keke_client/ucenter/client.php';
		//��fopen��ȡucenter��info
		$ucinfo = uc_fopen($ucapi.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
		//�ֽ�uc_info����Ӧ�ı���
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
	    //�����ok����ͨѶʧ��
		if($status != 'UC_STATUS_OK') {
			//ucͨѶʧ��
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_communication_fail'],'warning');
		} else {
	        //ͨѶ�ɹ�
	        //���ݿ����
			$dbcharset = strtolower(DBCHARSET);
			$ucdbcharset = strtolower($settingnew['UC_DBCHARSET'] ? str_replace('-', '', $settingnew['UC_DBCHARSET']) : $settingnew['UC_DBCHARSET']);
			//uc�ͻ��˰汾�����˰汾�Ƚϲ�һ�£�������ʾ
			if(UC_CLIENT_VERSION > $ucversion) {
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_different_version'],'warning');
			//�Ƚ�ucenter�����ݿ�����뱾�ص����ݿ����
			} elseif($ucdbcharset != $dbcharset) {
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_different_coding'],'warning');
			}
		    //����app��ӵ��������
			$postdata = "m=app&a=add
			 &ucfounderpw=".urlencode($ucfounderpw).
			"&apptype=".urlencode($app_type).
			"&appname=".urlencode($app_name).
			"&appurl=".urlencode($app_url).
			"&appip=$ucip
			 &appcharset=".CHARSET.
			'&appdbcharset='.DBCHARSET.
			'&release='.UC_CLIENT_RELEASE;
			//�������
			$ucconfig = uc_fopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
	        //���ص�ֵΪ�գ������ʧ��
			if(empty($ucconfig)) {
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_app_fail_to_add'],'warning');
			} elseif($ucconfig == '-1') {
				//��1Ϊ�����������
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_error_author_password'],'warning');
			} else {
				list($appauthkey, $appid) = explode('|', $ucconfig);
				if(empty($appauthkey) || empty($appid)) {
					//�����Ч
					Keke::show_msg('ϵͳ��ʾ','index.php/admin/user_integrate/uc',$_lang['uc_app_invalid_to_add'],'success');
				}
			}
		}
	    //��ӳɹ���Ҫдuckey,��uc_appid
		$ucconfig_info = explode('|', $ucconfig);
		$config_ucenter = keke_file_class::read_file(S_ROOT."config/config_ucenter.php");
		$config_ucenter = preg_replace("/define\('UC_KEY',\s*'.*?'\);/s", "define('UC_KEY', '".$ucconfig_info['0']."');", $config_ucenter);
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
		Keke::show_msg('ϵͳ��ʾ',"index.php/admin/user_integrate/uc",$_lang['uc_integrate_success'],'success');
		
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
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);/i", "define('$k', '$v');".PHP_EOL, $config_ucenter);
		}
		keke_file_class::write_file(S_ROOT."./config/config_pw.php",$config_ucenter);
	    
		DB::update('witkey_config')->set(array('v'))->value(array('3'))->where("k='user_intergration'")->execute();
	
		Keke::admin_system_log($_lang['pw_integrate_log']);
		//���»���
		Cache::instance()->del('keke_config');
		Keke::show_msg('ϵͳ��ʾ',"index.php/admin/user_integrate/pw",$_lang['phpwind_integrate_success'],'success');
		
	 		
		 
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
		Keke::show_msg('ϵͳ��ʾ',"index.php/admin/user_integrate",$_lang['success_uninstall'],'success');
	}
	
}

/* Keke::admin_check_role(35);

if ($setting == 'del'){
	$config_obj = new Keke_witkey_basic_config_class();
	$config_obj->setWhere("k='user_intergration'");
	$config_obj->setV(1);
	$config_obj->edit_keke_witkey_basic_config();
	$Keke->_cache_obj->gc();

	Keke::admin_system_log($_lang['uninstall_log_msg']);//��־��¼

	Keke::admin_show_msg($_lang['success_uninstall'],"index.php?do=config&view=integration",3,'','success');
}
$config_obj = new Keke_witkey_basic_config_class();
if ($type == 'uc'){
	require_once '../../config/config_ucenter.php';

	if(isset($ac)&& $ac = 'setting'){
		
		$config_ucenter = keke_tpl_class::sreadfile(S_ROOT."/config/config_ucenter.php");
		foreach ($settingnew as $k=>$v){
		
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);/i", "define('$k', '$v');", $config_ucenter);
		}
		keke_tpl_class::swritefile(S_ROOT."./config/config_ucenter.php",$config_ucenter);
		//uc���ϼ��
		$bbserver = 'http://'.preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']).($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
		$default_ucapi = $bbserver.'/ucenter';
		$app_type = 'OTHER';
		$app_name = $basic_config['website_name']?$basic_config['website_name']:P_NAME;
		$app_url = $basic_config['website_url']?$basic_config['website_url']:'http://localhost';
		$ucapi = $settingnew['UC_API'] ? $settingnew['UC_API'] : (defined('UC_API') && UC_API ? UC_API : $default_ucapi);
		$ucip = isset($settingnew['UC_IP']) ? $settingnew['UC_IP'] : '';
		$ucfounderpw = $uc_creater;
		UC_API?UC_API:define(UC_API, $settingnew['UC_API']);
		include_once S_ROOT.'./keke_client/ucenter/client.php';
		$ucinfo = uc_fopen($ucapi.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
	 
		if($status != 'UC_STATUS_OK') {
			//ucͨѶʧ��
			Keke::admin_show_msg($_lang['uc_communication_fail'],'index.php?do=config&view=integration&type=uc',3,'','warning');
		} else {
		
			$dbcharset = strtolower(DBCHARSET ? str_replace('-', '', DBCHARSET) : 'utf8');
			$ucdbcharset = strtolower($settingnew['UC_DBCHARSET'] ? str_replace('-', '', $settingnew['UC_DBCHARSET']) : $settingnew['UC_DBCHARSET']);
			if(UC_CLIENT_VERSION > $ucversion) {
				Keke::admin_show_msg($_lang['uc_different_version'],'index.php?do=config&view=integration&type=uc',3,'','warning');
			} elseif($dbcharset && $ucdbcharset != $dbcharset) {
				Keke::admin_show_msg($_lang['uc_different_coding'],'index.php?do=config&view=integration&type=uc',3,'','warning');
			}

			$postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucfounderpw)."&apptype=".urlencode($app_type)."&appname=".urlencode($app_name)."&appurl=".urlencode($app_url)."&appip=&appcharset=".CHARSET.'&appdbcharset='.DBCHARSET.'&'.$app_tagtemplates.'&release='.UC_CLIENT_RELEASE;
			$ucconfig = uc_fopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);

			if(empty($ucconfig)) {
				Keke::admin_show_msg($_lang['uc_app_fail_to_add'],'index.php?do=config&view=integration&type=uc','',3,'warning');
			} elseif($ucconfig == '-1') {
				Keke::admin_show_msg($_lang['uc_error_author_password'],'index.php?do=config&view=integration&type=uc',3,'','warning');
			} else {
				list($appauthkey, $appid) = explode('|', $ucconfig);
				if(empty($appauthkey) || empty($appid)) {
					Keke::admin_system_log($_lang['add_log_msg']);//��־��¼
					Keke::admin_show_msg(keke::lang('uc_app_invalid_to_add'),'index.php?do=config&view=integration&type=uc',3,'','success');
				}
			}
		}

		$ucconfig_info = explode('|', $ucconfig);
			
		$config_ucenter = keke_tpl_class::sreadfile(S_ROOT."/config/config_ucenter.php");
		
		$config_ucenter = preg_replace("/define\('UC_KEY',\s*'.*?'\);/s", "define('UC_KEY', '".$ucconfig_info['0']."');", $config_ucenter);
		
		
		$config_ucenter = preg_replace("/define\('UC_APPID',\s*'*.*?'*\);/s", "define('UC_APPID', ".$ucconfig_info[1].");", $config_ucenter);
	
		
	 	keke_tpl_class::swritefile(S_ROOT."./config/config_ucenter.php",$config_ucenter);

	 	$config_obj->setWhere(" k = 'user_intergration'");
	 	$config_obj->setV(2);
		$config_obj->edit_keke_witkey_basic_config();

		$Keke->_cache_obj->gc();

		Keke::admin_system_log($_lang['uc_integrate_log']);

		Keke::admin_show_msg($_lang['uc_integrate_success'],"index.php?do=config&view=integration",2,'','success');
	}
	require  $template_obj->template ( 'control/admin/tpl/admin_config_'.$view.'_uc' );
	die();
}else if ($type=='pw'){
	require_once S_ROOT.'/config/config_pw.php';
	if(isset($ac)&& $ac = 'setting'){
		$config_ucenter = keke_tpl_class::sreadfile(S_ROOT."/config/config_pw.php");
		foreach ($settingnew as $k=>$v){
			$config_ucenter = preg_replace("/define\('$k',\s*'.*?'\);/i", "define('$k', '$v');", $config_ucenter);
		}
		keke_tpl_class::swritefile(S_ROOT."./config/config_pw.php",$config_ucenter);
		
		$config_obj->setWhere(" k = 'user_intergration'");
		$config_obj->setV(3);
		$config_obj->edit_keke_witkey_basic_config();
		$keke_cache_obj = new keke_cache_class();
		$keke_cache_obj->del('keke_witkey_basic_config');

		Keke::admin_system_log($_lang['pw_integrate_log']);

		Keke::admin_show_msg($_lang['phpwind_integrate_success'],"index.php?do=config&view=integration",2,'','success');
		
	}
	require  $template_obj->template ( 'control/admin/tpl/admin_config_'.$view.'_pw' );
	die();
}

require  $template_obj->template ( 'control/admin/tpl/admin_config_'.$view );

 */



<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨ģ�����
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2
 * 2012-10-11
 */
class Control_admin_tool_tpl extends Control_admin{
	/**
	 * @var ��ת��
	 */
	private  $_uri;
	
	function before(){
		$this->_uri = "admin/tool_tpl";
	}
	function action_index(){
		global $_K,$_lang;
		
		$list_arr = DB::select()->from('witkey_template')->execute();
		$skins = $this->get_skin_type();
		require Keke_tpl::template('control/admin/tpl/tool/tpl');
	}
	/**
	 * �༭ģ���б�
	 */
	function action_list(){
		global  $_K,$_lang;
		$tplname = $_GET['tplname'];
		$filepath = S_ROOT.'./tpl/'.$tplname;
		$file_obj = new keke_file_class();
		
		$tpllist = $file_obj->get_dir_file_info($filepath,true,true);
		arsort($tpllist);
		$filter_file =array('.htm','.css','.js');
		require Keke_tpl::template ( 'control/admin/tpl/tool/tpl_file_list');
	}
	/**
	 * ��װģ��
	 */
	function action_install(){
		global  $_K,$_lang;
		//�ϴ�·��Ϊ��
		if (! $txt_newtplpath=$_POST['txt_newtplpath']) {
			Keke::show_msg ( $_lang['not_enter_dir'], $this->_uri,'warning' );
		}
		//ģ�������ļ�����
		if (file_exists ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" )) {
			$modinfo = keke_file_class::read_file ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" );
			$mods = explode ( ';', $modinfo );
			$modinfo = array ();
			//��modinfo.txt ������ת��Ϊ����
			foreach ( $mods as $m ) {
				if (! $m)
					continue;
				$m1 = explode ( '=', trim ( $m ) );
				$modinfo [$m1 ['0']] = $m1 ['1'];
			}
			//�ϴ���·��Ҫ��modfino��·��һ���������˳�
			if($txt_newtplpath!=$modinfo['tpl_path']){
			 Keke::show_msg($_lang['tpl_path_do_not_match']."tpl/$txt_newtplpath/modinfo.txt",$this->_uri,'warning');
			}
		
// 			$config_tpl_obj->setWhere ( "tpl_path ='$txt_newtplpath'" );
			$tpl_obj = Model::factory('witkey_template');
			//�жϵ�ǰģ���Ƿ��а�װ��,�����˳�
			if ($tpl_obj->setData("tpl_path ='$txt_newtplpath'")->count()) {
				Keke::show_msg ( $_lang['tpl_alerady_install'], $this->_uri ,'warning' );
			}
		    $array = array('develop'=>$modinfo ['develop'],
		    		    'on_time'=>time(),
		    		    'tpl_path'=>$txt_newtplpath,
		    			'tpl_title'=>$modinfo['tpl_title'],
		    			'tpl_desc'=>$modinfo['tpl_desc'],
		    		    'is_selected'=>1
		    		);
		    //�����ύ������
			$tpl_obj->setData($array)->create();
			Keke::show_msg ( $_lang['tpl_install_success'], $this->_uri,'success' );
		} else {
			Keke::show_msg ($_lang['tpl_not_exists_or_configinfo_err'], $this->_uri,'warning' );
		}
	}
	/**
	 *  ����ģ�������ļ�
	 */
	function action_save(){
		global  $_K,$_lang;
		$_POST = Keke_tpl::chars($_POST);
		$rdo_is_selected = $_POST['rdo_is_selected'];
		$tpl_obj  = new Keke_witkey_template();
		$tpl_obj->setWhere ( 'tpl_id=' . $rdo_is_selected );
		$tpl_obj->setIs_selected ( 1 );
		$res = $tpl_obj->update();
		
		$skin = $_POST['skin'];
		
		
		
		if(is_array($skin)&&!empty($skin)){
			//�ı�ģ����Ƥ���趨
			list($template,$theme) = each($skin);
			//foreach($skin as $k=>$v){
				Dbfactory::execute(sprintf(" update %switkey_template set tpl_pic ='%s' where tpl_title='%s'",TABLEPRE,$theme,$template));
			//}
			//����config ��ֵ template,theme
			DB::update('witkey_config')->set(array('v'))->value(array($template))->where("k='template'")->execute();
			DB::update('witkey_config')->set(array('v'))->value(array($theme))->where("k='theme'")->execute();
		}
		//����ѡ��ģ����Ϊ2
		$tpl_obj->setWhere ( 'tpl_id!=' . $rdo_is_selected );
		$tpl_obj->setIs_selected ( 2 );
		$res = $tpl_obj->update ();
		//���ģ�建��
		Cache::instance()->del('keke_template');
		Cache::instance()->del('keke_config');
		
		Keke::show_msg ($_lang['tpl_config_set_success'], $this->_uri, 'success' );
		
	}
	/**
	 * ɾ����Ĭ��ģ��
	 */
	
	function action_del(){
		global  $_K,$_lang;
		$delid = $_GET['delid'];
		$res = Model::factory('witkey_template')->setWhere('tpl_id=' . intval ( $delid ))->del();
		if ($res) {
			Cache::instance()->del("keke_template" );
			Keke::show_msg ( $_lang['tpl_config_unloading_success'],$this->_uri, 'success' );
		}
	}
	/**
	 * ����ģ��
	 */
	function action_backup(){
		global  $_K,$_lang;
		include S_ROOT.'/class/helper/keke_zip_class.php';
		$tplname = $_GET['tplname'];
		//zip�ļ�����
		$filename = $tplname.'_mod_'.time().'.zip';
		//�ļ����·��
		$names = S_ROOT."data/backup/$filename";
		
		$zip_obj = new zip_file($names);
		
		$zip_obj->set_options(array('basedir'=>'tpl','recurse'=> 1,'overwrite' => 1, 'storepaths' => 1));
		//ָ��Ҫѹ����ģ���ļ�
		$zip_obj->add_files(S_ROOT."tpl/".$tplname);
		//��ʼѹ��
		$red =$zip_obj->create_archive();
		
		$file_path =  "/data/backup/$filename";
		//zip�ļ������򱸷ֳɹ������򱸷�ʧ��
		if(file_exists(S_ROOT.$file_path)){
			Keke::show_msg($_lang['tpl_backup_success'],$this->_uri,'success');
		}else{
			Keke::show_msg($_lang['tpl_backup_fail'],$this->_uri,'warning');
		}
	}
	
	/**
	 * ��ȡ��ǰ��Ƥ��
	 * @return multitype:
	 */
	function get_skin_type(){
		$skins = array();
		//��theme����
		if(($fp = opendir(S_ROOT.SKIN_PATH.'/theme'))!=null){
			//��ȡtheme�µ�Ŀ¼
			while(($skin=readdir($fp))!=null){
				$skin = trim($skin,'.|svn');
				//���浽skins����
				$skin&&$skins[$skin] = $skin.'_skin';
			}
		}
		return array_filter($skins);
	}
	/**
	 * �༭ģ���ļ�
	 */
	function action_edit(){
		global  $_K,$_lang;
        extract($_GET);
        $filename = S_ROOT . './tpl/' . $tplname . '/' . $tname;
        $code_content = htmlspecialchars ( Keke_tpl::sreadfile ( $filename ) );
		require Keke_tpl::template('control/admin/tpl/tool/tpl_file_edit');
	}
	/**
	 * ����༭��ģ���ļ�
	 */
	function action_tpl_save(){
		global  $_K,$_lang;
		//write
		if ($_POST) {
			$_POST = Keke_tpl::chars($_POST);
			$filename =$_POST['filename'];
			$tplname = $_POST['tplname'];
			$tname =$_POST['tname'];
			if (! is_writable ( $filename )) {
				Keke::show_msg ($_lang['file'] . $filename . $_lang['can_not_write_please_check'],"admin/tool_tpl/eidt?tplname=$tplname&tname=$tname", 'warning' );
			}
			Keke_tpl::swritefile ( $filename, htmlspecialchars_decode ( Keke::k_stripslashes ( $_POST['txt_code_content'] ) ) );
			Keke::admin_system_log ( $_lang['edit_template'] . $tplname . '/' . $tname );
			Keke::show_msg ($_lang['tpl_edit_success'],"admin/tool_tpl/edit?tplname=$tplname&tname=$tname", 'success' );
		}
	}
}
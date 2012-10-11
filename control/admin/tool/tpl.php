<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台模板管理
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2
 * 2012-10-11
 */
class Control_admin_tool_tpl extends Controller{
	/**
	 * @var 跳转用
	 */
	private  $_uri;
	
	function before(){
		$this->_uri = "index.php/admin/tool_tpl";
	}
	function action_index(){
		global $_K,$_lang;
		
		$list_arr = DB::select()->from('witkey_template')->execute();
		$skins = $this->get_skin_type();
		require Keke_tpl::template('control/admin/tpl/tool/tpl');
	}
	/**
	 * 编辑模板列表
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
	 * 安装模板
	 */
	function action_install(){
		global  $_K,$_lang;
		//上传路径为空
		if (! $txt_newtplpath=$_POST['txt_newtplpath']) {
			Keke::show_msg ( $_lang ['operate_notice'], $this->_uri,$_lang['not_enter_dir'],'warning' );
		}
		//模板配置文件存在
		if (file_exists ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" )) {
			$modinfo = keke_file_class::read_file ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" );
			$mods = explode ( ';', $modinfo );
			$modinfo = array ();
			//将modinfo.txt 的内容转换为数组
			foreach ( $mods as $m ) {
				if (! $m)
					continue;
				$m1 = explode ( '=', trim ( $m ) );
				$modinfo [$m1 ['0']] = $m1 ['1'];
			}
			//上传的路径要与modfino的路径一至，否则退出
			if($txt_newtplpath!=$modinfo['tpl_path']){
			 Keke::show_msg($_lang ['operate_notice'],$this->_uri,$_lang['tpl_path_do_not_match']."tpl/$txt_newtplpath/modinfo.txt",'warning');
			}
		
// 			$config_tpl_obj->setWhere ( "tpl_path ='$txt_newtplpath'" );
			$tpl_obj = Model::factory('witkey_template');
			//判断当前模板是否有安装过,否则退出
			if ($tpl_obj->setData("tpl_path ='$txt_newtplpath'")->count()) {
				Keke::show_msg ( $_lang ['operate_notice'], $this->_uri ,$_lang['tpl_alerady_install'],'warning' );
			}
		    $array = array('develop'=>$modinfo ['develop'],
		    		    'on_time'=>time(),
		    		    'tpl_path'=>$txt_newtplpath,
		    			'tpl_title'=>$modinfo['tpl_title'],
		    			'tpl_desc'=>$modinfo['tpl_desc'],
		    		    'is_selected'=>1
		    		);
		    //保存提交的数据
			$tpl_obj->setData($array)->create();
			Keke::admin_show_msg ( $_lang ['operate_notice'], $this->_uri,$_lang['tpl_install_success'],'success' );
		} else {
			Keke::admin_show_msg ($_lang ['operate_notice'], $this->_uri,$_lang['tpl_not_exists_or_configinfo_err'],'warning' );
		}
	}
	/**
	 *  保存模板配置文件
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
			//改变模板上皮肤设定
			list($template,$theme) = each($skin);
			//foreach($skin as $k=>$v){
				Dbfactory::execute(sprintf(" update %switkey_template set tpl_pic ='%s' where tpl_title='%s'",TABLEPRE,$theme,$template));
			//}
			//更变config 的值 template,theme
			DB::update('witkey_config')->set(array('v'))->value(array($template))->where("k='template'")->execute();
			DB::update('witkey_config')->set(array('v'))->value(array($theme))->where("k='theme'")->execute();
		}
		//将非选定模板设为2
		$tpl_obj->setWhere ( 'tpl_id!=' . $rdo_is_selected );
		$tpl_obj->setIs_selected ( 2 );
		$res = $tpl_obj->update ();
		//清除模板缓存
		Cache::instance()->del('keke_template');
		Cache::instance()->del('keke_config');
		
		Keke::show_msg ($_lang['tpl_config_set_success'], $this->_uri, 'success' );
		
	}
	/**
	 * 删除非默认模板
	 */
	
	function action_del(){
		global  $_K,$_lang;
		$delid = $_GET['delid'];
		$res = Model::factory('witkey_template')->setWhere('tpl_id=' . intval ( $delid ))->del();
		if ($res) {
			Cache::instance()->del("keke_template" );
			Keke::show_msg ( $_lang ['operate_notice'], $this->_uri,$_lang['tpl_config_unloading_success'], 'success' );
		}
	}
	/**
	 * 备份模板
	 */
	function action_backup(){
		global  $_K,$_lang;
		include S_ROOT.'/class/helper/keke_zip_class.php';
		$tplname = $_GET['tplname'];
		//zip文件名称
		$filename = $tplname.'_mod_'.time().'.zip';
		//文件存放路径
		$names = S_ROOT."data/backup/$filename";
		
		$zip_obj = new zip_file($names);
		
		$zip_obj->set_options(array('basedir'=>'tpl','recurse'=> 1,'overwrite' => 1, 'storepaths' => 1));
		//指定要压缩的模板文件
		$zip_obj->add_files(S_ROOT."tpl/".$tplname);
		//开始压缩
		$red =$zip_obj->create_archive();
		
		$file_path =  "/data/backup/$filename";
		//zip文件存在则备分成功，否则备份失败
		if(file_exists(S_ROOT.$file_path)){
			Keke::show_msg($_lang['operate_notice'],$this->_uri,$_lang['tpl_backup_success'],'success');
		}else{
			Keke::show_msg($_lang['operate_notice'],$this->_uri,$_lang['tpl_backup_fail'],'warning');
		}
	}
	
	/**
	 * 获取当前的皮肤
	 * @return multitype:
	 */
	function get_skin_type(){
		$skins = array();
		//打开theme夹子
		if(($fp = opendir(S_ROOT.SKIN_PATH.'/theme'))!=null){
			//读取theme下的目录
			while(($skin=readdir($fp))!=null){
				$skin = trim($skin,'.|svn');
				//保存到skins数组
				$skin&&$skins[$skin] = $skin.'_skin';
			}
		}
		return array_filter($skins);
	}
	/**
	 * 编辑模板文件
	 */
	function action_edit(){
		global  $_K,$_lang;
        extract($_GET);
        $filename = S_ROOT . './tpl/' . $tplname . '/' . $tname;
        $code_content = htmlspecialchars ( Keke_tpl::sreadfile ( $filename ) );
		require Keke_tpl::template('control/admin/tpl/tool/tpl_file_edit');
	}
	/**
	 * 保存编辑的模板文件
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
				Keke::show_msg ($_lang ['operate_notice'],"index.php/admin/tool_tpl/eidt?tplname=$tplname&tname=$tname",$_lang['file'] . $filename . $_lang['can_not_write_please_check'], 'warning' );
			}
			Keke_tpl::swritefile ( $filename, htmlspecialchars_decode ( Keke::k_stripslashes ( $_POST['txt_code_content'] ) ) );
			Keke::admin_system_log ( $_lang['edit_template'] . $tplname . '/' . $tname );
			Keke::show_msg ($_lang ['operate_notice'],"index.php/admin/tool_tpl/edit?tplname=$tplname&tname=$tname", $_lang['tpl_edit_success'],'success',2 );
		}
	}
		
	
}

/* Keke::admin_check_role ( 28 );

$config_tpl_obj = new Keke_witkey_template_class ();

$tpl_arr = $config_tpl_obj->query_keke_witkey_template ();
$skins    = get_skin_type();
function get_skin_type(){
	$skins = array();
	if($fp = opendir(S_ROOT.SKIN_PATH.'/theme')){
		while($skin=readdir($fp)){
			$skin = trim($skin,'.|svn');
			$skin&&$skins[$skin] = $skin.'_skin';
		}
	}
	return array_filter($skins);
}

if ($sbt_edit) {
	if ($sbt_edit == $_lang['use']) {
		$config_tpl_obj->setWhere ( 'tpl_id=' . $rdo_is_selected );
		$config_tpl_obj->setIs_selected ( 1 );
		$res = $config_tpl_obj->edit_keke_witkey_template ();

		if(is_array($skin)&&!empty($skin)){
			foreach($skin as $k=>$v){
				Dbfactory::execute(sprintf(" update %switkey_template set tpl_pic ='%s' where tpl_title='%s'",TABLEPRE,$v,$k));
				//$_SESSION['theme']=$v;
			}
		}
		$config_tpl_obj = new Keke_witkey_template_class ();
		$config_tpl_obj->setWhere ( 'tpl_id!=' . $rdo_is_selected );
		$config_tpl_obj->setIs_selected ( 2 );
		$res = $config_tpl_obj->edit_keke_witkey_template ();
		$config_tpl_obj->setWhere ( " is_selected =1 limit 1 " );
		$config_tpl_arr = $config_tpl_obj->query_keke_witkey_template ();
		if ($res) {
			$Keke->_cache_obj->del ( "keke_witkey_template" );
			$Keke->_cache_obj->set ( "keke_witkey_template", $config_tpl_arr );
			Keke::admin_show_msg ( $_lang['tpl_config_set_success'], 'index.php?do=config&view=tpl',3,'','success' );
		}
	}
	if ($sbt_edit == $_lang['from_dir_install'] || $sbt_edit == 'uploadreturn') {
		if (! $txt_newtplpath) {
			Keke::admin_show_msg ( $_lang['not_enter_dir'], 'index.php?do=config&view=tpl',3,'','warning' );
		}

		if (file_exists ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" )) {
			$file_obj = new keke_file_class ();
			$modinfo = $file_obj->read_file ( S_ROOT . "./tpl/$txt_newtplpath/modinfo.txt" );
			$mods = explode ( ';', $modinfo );
			$modinfo = array ();
			foreach ( $mods as $m ) {
				if (! $m)
					continue;
				$m1 = explode ( '=', trim ( $m ) );
				$modinfo [$m1 ['0']] = $m1 ['1'];
			}
			$txt_newtplpath!=$modinfo['tpl_path'] and Keke::admin_show_msg($_lang['tpl_path_do_not_match']."tpl/$txt_newtplpath/modinfo.txt",'index.php?do=config&view=tpl',3,'','warning');
				
			$config_tpl_obj->setWhere ( "tpl_path ='$txt_newtplpath'" );
			if ($config_tpl_obj->count_keke_witkey_template ()) {
				Keke::admin_show_msg ( $_lang['tpl_alerady_install'], 'index.php?do=config&view=tpl',3,'','warning' );
			}
				
			$config_tpl_obj->setDevelop ( $modinfo ['develop'] );
			$config_tpl_obj->setOn_time ( time () );
			$config_tpl_obj->setTpl_path ( $txt_newtplpath );
			$config_tpl_obj->setTpl_title ( $modinfo ['tpl_title'] );
			$config_tpl_obj->setTpl_desc ( $modinfo ['tpl_desc'] );
			$config_tpl_obj->setIs_selected(1);
			$config_tpl_obj->create_keke_witkey_template ();
			Keke::admin_show_msg ( $_lang['tpl_install_success'], 'index.php?do=config&view=tpl',3,'','success' );
		} else {
			Keke::admin_show_msg ( $_lang['tpl_not_exists_or_configinfo_err'], 'index.php?do=config&view=tpl',3,'','warning' );
		}
	}

	if ($sbt_edit == $_lang['local_upload']) {
		$upload_obj = new keke_upload_class ( UPLOAD_ROOT, array ("zip" ), UPLOAD_MAXSIZE );
		$files = $upload_obj->run ( 'uploadtplfile', 1 ); //上传文件
		if ($files != 'The uploaded file is Unallowable!') {
			//获得文件名
			$mod_file = $files ['0'] ['saveName'];
			if ($mod_file) {
				$mod_file = "data/uploads/" . UPLOAD_RULE . $mod_file;
			}
		}

		$file_obj = new keke_file_class ();
		$dirs = array ();
		$fso = opendir ( "../../tpl" );
		while ( $flist = readdir ( $fso ) ) {
			if (is_dir ( "../../tpl/" . $flist )) {
				$dirs [$flist] = $flist;
			}
		}
		closedir ( $fso );

		include '../../lib/helper/keke_zip_class.php';
		$zip_obj = new zip_file ( "../../" . $mod_file );
		$zip_obj->set_options ( array ('inmemory' => 1 ) );
		$zip_obj->extractZip ( "../../" . $mod_file, '../../' );
		unlink ( "../../" . $mod_file );

		$fso = opendir ( "../../tpl" );
		while ( $flist = readdir ( $fso ) ) {
			if (is_dir ( "../../tpl/" . $flist )) {
				if (! $dirs [$flist]) {
					$newaddfile = $flist;
					break;
				}
			}
		}
	}

	if (! $newaddfile) {
		Keke::admin_show_msg ( $_lang['tpl_upload_success'], 'index.php?do=config&view=tpl',3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['tpl_upload_success_install'], 'index.php?do=config&view=tpl&sbt_edit=uploadreturn&txt_newtplpath=' . $newaddfile,3,'','success' );
	}

}

if ($delid) {
	$config_tpl_obj->setWhere ( 'tpl_id=' . intval ( $delid ) );
	$res = $config_tpl_obj->del_keke_witkey_template ();
	if ($res) {

		$Keke->_cache_obj->del ( "keke_witkey_template" );
		Keke::admin_show_msg ( $_lang['tpl_config_unloading_success'], 'index.php?do=config&view=tpl',3,'','warning' );
	}
}

require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view ); */



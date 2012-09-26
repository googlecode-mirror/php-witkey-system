<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-24����10:17:13
 */

 
Keke::admin_check_role(51);
include_once '../../lib/helper/keke_zip_class.php';
$filename = $tplname.'_mod_'.time().'.zip';
$names = "../../data/backup/$filename";
$zip_obj = new zip_file($names);
$zip_obj->set_options(array('recurse'=> 1,'overwrite' => 1, 'storepaths' => 1));
$zip_obj->add_files("../../tpl/".$tplname);
$zip_obj->create_archive();
$file_path =  "/data/backup/$filename";
if(file_exists(S_ROOT.$file_path)){
	Keke::admin_show_msg($_lang['operate_notice'],'index.php?do=config&view=tpl',3,$_lang['tpl_backup_success'],'success');
}else{
	Keke::admin_show_msg($_lang['operate_notice'],'index.php?do=config&view=tpl',3,$_lang['tpl_backup_fail'],'warning');
}
//keke_file_class::file_down($filename, $file_path);
 
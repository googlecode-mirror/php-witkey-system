<?php	defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author yuan
 * @version v 2.0
 * 2010-6-28 18:17:13
 */

kekezu::admin_check_role (28);
$filepath = S_ROOT.'./tpl/'.$tplname;
$file_obj = new keke_file_class();

$tpllist = $file_obj->get_dir_file_info($filepath,true,true); 
arsort($tpllist);
require_once $template_obj->template ( 'control/admin/tpl/admin_tpl_tpllist');


<?php	defined ( 'IN_KEKE' ) or 	exit ( 'Access Denied' );
/**
 * �༭��ǩģ��
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-26����11:58:41
 */




Keke::admin_check_role (29);
$filename = S_ROOT.'./control/admin/tpl/template_tag_'.$tplname.'.htm';
$code_content = "";

if (file_exists($filename)) {
	$fp=fopen($filename,"r"); 
	while (!feof($fp)) {   
		$code_content  .= fgets($fp);   
	}

	fclose($fp);
}

require_once $template_obj->template ( 'control/admin/tpl/admin_tpl_'.$view );
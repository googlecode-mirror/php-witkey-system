<?php
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-6-29����09:30:37
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role ( 51 );

$filename = S_ROOT . './tpl/' . $tplname . '/' . $tname;
$code_content = htmlspecialchars ( Keke_tpl::sreadfile ( $filename ) );

//write
if ($sbt_edit) {
	$filename = S_ROOT . $tname;
	if (! is_writable ( $filename )) {
		Keke::admin_show_msg ( $_lang['file'] . $filename . $_lang['can_not_write_please_check'], "index.php?do=tpl&view=tpllist&tplname=$tplname",3,'','warning' );
	}
	
	Keke_tpl::swritefile ( $filename, htmlspecialchars_decode ( Keke::k_stripslashes ( $txt_code_content ) ) );
	Keke::admin_system_log ( $_lang['edit_template'] . $tplname . '/' . $tname );
	Keke::admin_show_msg ( $_lang['tpl_edit_success'], "index.php?do=tpl&view=tpllist&tplname=$tplname",3,'','success' );
}

require $template_obj->template ( 'control/admin/tpl/admin_tpl_' . $view );




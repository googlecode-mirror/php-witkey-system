<?php
/**
 * @copyright keke-tech
 * @author Liyingqing
 * @version v 1.3
 * 2011-07-09
 */
defined ( 'ADMIN_KEKE' ) or exit('Access Denied');
$tab_obj = keke_table_class::get_instance("witkey_prom_item");
$upload_obj = new keke_upload_class(UPLOAD_ROOT,array("gif",'jpeg','jpg','png'),UPLOAD_MAXSIZE);


if( $sbt_edit ) {
		$fds['prom_type']=='site' and $fds['item_type'] = 'img';
		$fds['on_time'] = time();
		$files = $upload_obj->run('item_pic',1);
		$files!='The uploaded file is Unallowable!' and $item_pic = $files['0']['saveName'];
		$item_pic and $fds['item_pic'] = $item_pic;
		if ($item_id) {
			$edit=$tab_obj->save($fds,$pk); //关系编辑
			Keke::admin_system_log($_lang['edit_prom_material'] . $item_id );
			$edit &&  Keke::admin_show_msg($_lang['prom_material_edit_success'],'',3,'','success');//die
		}
		$add = $tab_obj->save($fds); //关系添加
		Keke::admin_system_log($_lang['add_prom_material']);
		$add && Keke::admin_show_msg($_lang['prom_material_add_success'],'',3,'','success');
} else {
	$item_id and $item_info = dbfactory::get_one(" select * from ".TABLEPRE."witkey_prom_item where item_id = '$item_id'");
}
require $template_obj->template ( 'control/admin/tpl/admin_'.$do.'_' . $view );
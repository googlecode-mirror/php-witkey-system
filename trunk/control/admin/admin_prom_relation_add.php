<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-02 11:40:30
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );

$prom_arr = dbfactory::query('select prom_id,prom_item,prom_code from ' . TABLEPRE . 'witkey_prom_rule where type!="auth";') ; //�ƹ����� prom_type
$tab_obj  = keke_table_class::get_instance("witkey_prom_relation");
//update����
if( isset($sbt_edit) ) {	
	$fds['on_time'] = time();
	$relation_id and $edit=$tab_obj->save($fds,$pk) or $add=$tab_obj->save($fds);//��ϵ�༭�����
	if($relation_id){
		kekezu::admin_system_log($_lang['edit_prom_relation_data'] . $relation_id);
		kekezu::admin_show_msg( $edit ? $_lang['edit_success'] : $_lang['edit_fail'],'',3,'',$edit?'success':'warning');
	} else {
		kekezu::admin_system_log($_lang['add_prom_relation']);
		kekezu::admin_show_msg( $add ? $_lang['creat_relation_success'] : $_lang['creat_relation_fail'],'',3,'',$add?'success':'warning' );
	}
}

//���� ��relation_id��Ϊ�༭, û����Ϊ���
isset($relation_id) && $relation_info = dbfactory::get_one(" select * from ".TABLEPRE."witkey_prom_relation where relation_id = '$relation_id'");

require $template_obj->template('control/admin/tpl/admin_'.$do."_".$view);
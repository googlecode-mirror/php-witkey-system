<?php
/**
 * ���λ��� private ��ʱ�ļ�
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-21 ����05:43:22
 * @encoding GBK
 */

$url = "index.php?do=$do&view=$view&target_id=$target_id";

if (isset ( $stb_add )) {
 
	eval ("\$arr=".Keke::k_stripslashes($position).";") ;
	$position = serialize( $arr); 
	$insertsqlarr = array ('name' => $name, 'code' => $code, 'description' => $description, 'targets' => $targets, 'position' => $position, 'ad_size' => $ad_size, 'ad_num' => $ad_num, 'sample_pic' => $sample_pic );
	if ($target_id) {
		$result = dbfactory::updatetable ( "keke_witkey_ad_target", $insertsqlarr, array ("target_id" => $target_id ) );
	} else {
		$result = dbfactory::inserttable ( 'keke_witkey_ad_target', $insertsqlarr );
	}
	$result && Keke::admin_show_msg ( $_lang['add_submit_success'],$url,3,'','success' );
}
//��ʼ����Ϣ

if ($target_id) {
	$target_arr = dbfactory::get_one ( "select * from keke_witkey_ad_target where target_id='$target_id'" );
	$target_arr['position'] = var_export(unserialize($target_arr['position']),1);
}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
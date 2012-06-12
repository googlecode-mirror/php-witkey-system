<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-12-25 下午   2:32:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$record_obj = new Keke_witkey_auth_record_class();
$p_space_style = keke_global_class::get_p_space_style (); //获取个人空间风格
$p_style_arr = keke_global_class::get_p_space_name (); //获取个人空间名字
$e_space_style = keke_global_class::get_e_space_style (); //获取企业空间风格
$e_style_arr = keke_global_class::get_e_space_name (); //获取企业空间名字

$bg_repeat = array('no-repeat'=>$_lang['not_repeat'],'repeat-x'=>$_lang['x_repeat'],'repeat-y'=>$_lang['y_repeat'],'repeat'=>$_lang['default']);//跟空间的背景有关验证数组
$bg_scroll = array('scroll'=>$_lang['scroll'],'fixed'=>$_lang['fixed']);//跟空间的背景有关验证数组
$bg_position = array('left'=>$_lang['upper_left_corner'],'center'=>$_lang['center'],'right'=>$_lang['upper_right_corner']);//跟空间的背景有关验证数组
if ($sbt_edit) {
	$shop_obj = keke_table_class::get_instance ( "witkey_shop" );
	 
	$conf ['uid'] = $uid;
	$conf ['username'] = kekezu::escape($username);
	$conf ['shop_name'] = kekezu::escape($shop_name);
	$conf ['shop_type'] = kekezu::escape($shop_type);
	intval($shop_info['shop_id']) or $conf['shop_background'] = $file_temp;
	$shop_slogans and $conf ['shop_slogans'] = kekezu::escape($shop_slogans);
	$style_name and $conf ['shop_skin'] = $style_name or $conf ['shop_skin'] = 'default';
	
	/*begin 空间背景图片的定位*/
	$bgstyle= array();
	array_key_exists(strval($repeat), $bg_repeat) && $bgstyle['repeat'] = strval($repeat);
	array_key_exists(strval($scroll), $bg_scroll) && $bgstyle['scroll'] = strval($scroll);
	array_key_exists(strval($position), $bg_position) && $bgstyle['position'] = strval($position).' top';
	$bgstyle && $conf['shop_backstyle'] = serialize($bgstyle);
	/*end 空间背景图片的定位*/
 	
	if($clear_all){
		$conf['shop_background']='';
	}
	
 	$sql = sprintf("select shop_id from %switkey_shop where uid=%d ",TABLEPRE,$uid); 
	$shop_info = db_factory::query($sql);
	$pk['shop_id'] = $shop_info['0']['shop_id']; 
	$res = $shop_obj->save ($conf, $pk );
	$res and kekezu::show_msg ( $_lang['operate_notice'], "index.php?do=space&member_id=$uid", 3, $_lang['edit_space_success'], 'success') or kekezu::show_msg ( $_lang['operate_notice'], $ac_url, 3, $_lang['edit_space_fail'], 'warning' );
	
}

if ($ajax) {
	$fieldss = array('logo','shop_background','banner');
	if (!$fields || !in_array($fields, $fieldss)){
		kekezu::echojson( $_lang['fail_set'], "0" );
	}
	$fields && isset ( $filePath ) and $res = db_factory::execute ( sprintf ( " update %switkey_shop set %s='%s' where shop_id='%d'", TABLEPRE, $fields, $filePath, $shop_info ['shop_id'] ) );
	$res and kekezu::echojson ( $_lang['successfully_set'], "1" ) or kekezu::echojson( $_lang['fail_set'], "0" );
}
if ($rever && $rever=='change'){
	$sql = sprintf("update %switkey_shop set shop_background=null where shop_id=%d", TABLEPRE, $shop_info ['shop_id']);
	$result = db_factory::execute($sql);
	$result && kekezu::echojson ( $_lang['successfully_set'], "1" ) or kekezu::echojson ( $_lang['fail_set'], "0" );
}
$shop_backstyle = unserialize($shop_info['shop_backstyle']);
// var_dump($shop_backstyle);

require keke_tpl_class::template ( "user/" . $do . "_" . $op . "_" . $opp );
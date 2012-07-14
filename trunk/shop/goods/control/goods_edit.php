<?php
/**
 * 商品管理
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-9 12:10
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$ser_id = intval ($ser_id);
$price_unit = keke_shop_release_class::get_price_unit ();
if ($sbt_edit) {
	if(CHARSET=='gbk'){
		$title = Keke::utftogbk($title);
		$content = Keke::utftogbk($content);
		$unite_price = Keke::utftogbk($unite_price);
	}
	$s_obj = new Keke_witkey_service_class();
	$s_obj->setWhere(" service_id='$ser_id' ");
	$s_obj->setTitle($title);
	$s_obj->setPrice($price);
	$s_obj->setUnite_price($unite_price);
	$s_obj->setPic($pic);
	$s_obj->setContent($content);
	$s_obj->setIndus_id($indus_id);
	$s_obj->setIndus_pid($indus_pid);
	$res = $s_obj->edit_keke_witkey_service();
	$res and Keke::echojson('',1) or Keke::echojson('',0);
} else {
	$title = $_lang ['edit_goods'];
	$ext = '.jpg,.jpeg,.gif,.png,.bmp';
	$model_list [$model_id] ['config'] && $config = unserialize ( $model_list [$model_id] ['config'] );
	$ser_info = dbfactory::get_one ( sprintf ( " select floor(price) price,indus_id,indus_pid,title,unite_price,pic,
				content,submit_method,file_path from %switkey_service where service_id='%d' and uid='%d'", TABLEPRE, $ser_id, $uid ) );
	$ser_info['pic']&&$f_info = dbfactory::get_one(sprintf(" select file_id,file_name from %switkey_file where obj_type='service'
					and uid='%d' and save_name='%s'", TABLEPRE, $uid, $ser_info ['pic'] ) );
	$fid	   = intval($f_info['file_id']);
	$file_name = $f_info['file_name'];
}
require Keke_tpl::template ( "shop/goods/tpl/default/goods_edit" );

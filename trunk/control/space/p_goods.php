<?php
/**
 * ���˿ռ���Ʒչʾ
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-����11:04:44
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );


//�ռ��ַ
 $p_url =$_K['siteurl']."/index.php?do=space&member_id=$member_id";
$service_obj = new Keke_witkey_service_class ();
$where = " service_status='2' and uid = " . intval ( $member_id );
$title and $where .= " and title like '%" . $title . "%'";

$service_obj->setWhere ( $where );
$service_arr = $service_obj->query_keke_witkey_service ();

/**��Ҹ�������**/
$buyer_aid = keke_user_mark_class::get_user_aid ( intval($member_id), '2', null, '1' );
//���ͼ���
$skill_arr = array_filter(explode ( ',', $member_info ['skill_ids']));
require Keke_tpl::template ( SKIN_PATH . "/space/{$type}_{$view}" );


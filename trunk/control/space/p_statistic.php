<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ���˿ռ��û�ͳ��
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-����11:04:44
 * @version V2.0
 */
$url = "index.php?do=space&member_id=$member_id&view=statistic";
$credit_level = unserialize ( $member_info ['seller_level'] );
/**���Ҹ�������**/
$seller_aid = keke_user_mark_class::get_user_aid ( $member_id, '2', null, '1' );

//���ͺ�����
$good_rate  = get_witkey_good_rate($member_info);
function get_witkey_good_rate($user_info){
	$st = $user_info['seller_total_num'];
	return $st?(number_format($user_info['seller_good_num']/$st,2)*100).'%':'0%'; 
}

/*������*/
$auth_arr = get_auth($member_info);
//��֤��Ϣ
function get_auth($user_info){
	$auth_item = keke_auth_base_class::get_auth_item ();
	$auth_temp = array_keys ( $auth_item );
	$user_info ['user_type'] == 2 and $un_code = 'realname' or $un_code = "enterprise";
	$t = implode ( ",", $auth_temp );
	$auth_info = Dbfactory::query ( " select a.auth_code,a.auth_status,b.auth_title,b.auth_small_ico,b.auth_small_n_ico from " . TABLEPRE . "witkey_auth_record a left join " . TABLEPRE . "witkey_auth_item b on a.auth_code=b.auth_code where a.uid ='".$user_info['uid']."' and FIND_IN_SET(a.auth_code,'$t')", 1, -1 );
	$auth_info = Keke::get_arr_by_key ( $auth_info, "auth_code" );
	return array('item'=>$auth_item,'info'=>$auth_info,'code'=>$un_code);
}


//���۵���Ʒ�� 
$sale_num = intval(Dbfactory::get_count(sprintf(" select count(order_id) count from %switkey_order where seller_uid='%d' and model_id in (6,7)",TABLEPRE,$member_id)));
//�������Ʒ�� 
$buy_num = intval(Dbfactory::get_count(sprintf(" select count(order_id) count from %switkey_order where order_uid='%d' and model_id in (6,7)",TABLEPRE,$member_id)));

//AJAX��ҳ����
$page or $page = 1;
$page_size or $page_size=10;
$come or $come='gz';
$p_url = "index.php?do=space&member_id=$member_id&view=statistic&come=$come";
$page_obj = $Keke->_page_obj;
$page_obj->setAjax(1);
$page_obj->setAjaxDom('ajax_dom');
$page_obj->setAjaxCove(1);


/*��������ajax���� */
if($isajax){
	switch ($sx){
		case 'good':
			//���Թ����ĺ���
			if($come=='gz'){
				$result = keke_user_mark_class::get_user_mark($member_id,1,1,1);
			}else{
				$result = keke_user_mark_class::get_user_mark($member_id,1,2,1);
			}
 			break;
		case 'middle':
			if($come=='gz'){
				$result = keke_user_mark_class::get_user_mark($member_id,1,1,2);
			}else{
				$result = keke_user_mark_class::get_user_mark($member_id,1,2,2);
			}
			 
			break;
		case 'bad':
			if($come=='gz'){
				$result = keke_user_mark_class::get_user_mark($member_id,1,1,3);
			}else{
				$result = keke_user_mark_class::get_user_mark($member_id,1,2,3);
			}
			break;
		case 'all':
			if($come=='gz'||empty($come)){
				$result = keke_user_mark_class::get_user_mark($member_id,1,1);
			}else{
				$result = keke_user_mark_class::get_user_mark($member_id,1,2);
			}
			break;
	}
	$p_url .="&isajax=true&sx=$sx";
	$pages = $page_obj->page_by_arr($result, $page_size, $page, $p_url);
	$result = $pages['data'];
	if($m_ajax)//����ajax����
	{
		require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );
	}else {
 		require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_sx" );
	}
	die;
}

if($come=='gz'||empty($come)){
	$result = keke_user_mark_class::get_user_mark($member_id,1,1);
}else{
	$result = keke_user_mark_class::get_user_mark($member_id,1,2);
}
$pages = $page_obj->page_by_arr($result, $page_size, $page, $p_url);
$result = $pages['data'];
require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );
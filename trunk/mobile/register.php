<?php
/**
 * @copyright keke-tech
 */
defined ( 'IN_KEKE' )&&defined('ISWAP')&&ISWAP or kekezu::echojson ($wap_msg, 0);
$reg_obj = new keke_register_class(2);//��ʼ������
 
$reg_uid = $reg_obj->user_register ( $account, md5 ( $password ), $email, '', 0, $password );//�û�ע��
$user_info = keke_user_class::get_user_info ( $reg_uid );
wap_base_class::update_load_status($user_info['uid']);//���µ�½״̬
$reg_obj->register_login ( $user_info );//�û���¼
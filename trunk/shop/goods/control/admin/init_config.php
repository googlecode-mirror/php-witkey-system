<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-10 13:51:34
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$init_menu = array(
    $_lang['order_manage']=>'index.php?do=model&model_id=6&view=order',
	$_lang['goods_manage']=>'index.php?do=model&model_id=6&view=list&status=0',
	$_lang['goods_config']=>'index.php?do=model&model_id=6&view=config'
);
$init_config = array(
	'model_id'=>6,
	'model_code'=>'goods',
	'model_name'=>$_lang['witkey_goods'],
	'model_dir'=>'goods',
	'model_dev'=>'kekezu',
	'model_type'=>'shop',
	'model_status'=>1,//
	'hide_mode'=>1,//�Ƿ���˽��ģ��
	'is_audit'=>1,//�Ƿ���Ҫ���
	'goods_rate'=>20,//��Ʒ��ɱ���
	'goods_fail_rate'=>10,//ʧ����ɱ���
	'mark_day'=>'2',//Ĭ�ϻ�������
	'min_account'=>10//�ɽ���С�ܶ�
);

<?php defined('IN_KEKE') or 	exit('Access Denied');
/**
 * �������������ʼ�������ļ�
 */
//�Ӳ˵�ID
$sub_menu_arr[] = array(
		'resource_id'=>210,
		'resource_url'=>'index.php/shop/goods_admin_order',
		'resource_name'=>'��Ʒ����',
		'submenu_id'=>66);
$sub_menu_arr[] = array(
		'resource_id'=>211,
		'resource_url'=>'index.php/shop/goods_admin_list',
		'resource_name'=>'��Ʒ�б�',
		'submenu_id'=>66);
$sub_menu_arr[] = array(
		'resource_id'=>212,
		'resource_url'=>'index.php/shop/goods_admin_config',
		'resource_name'=>'��Ʒ����',
		'submenu_id'=>66);
//���˵�ID
$menu_arr = array(
		//ID
		'submenu_id'=>'66',
		//���ļ����� 
		'submenu_name'=>'������Ʒ',
		//һ���˵�code
		'menu_name'=>'shop',
		'listorder'=>'2'
);


$init_config = array(
	'model_id'=>6,
	'model_code'=>'goods',
	'model_name'=>'������Ʒ',
	'model_type'=>'shop',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'on_time'=>'2012-10-20',
	'listorder'=>'1',
	//������Ϣ
	'config'=>array(
	)
);
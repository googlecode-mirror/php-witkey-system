<?php defined('IN_KEKE') or 	exit('Access Denied');
/**
 * �����б������ʼ�������ļ�
 */
//�Ӳ˵�ID
$sub_menu_arr[] = array(
		'resource_id'=>208,
		'resource_url'=>'index.php/task/dtender_admin_list',
		'resource_name'=>'�����б�',
		'submenu_id'=>64);
$sub_menu_arr[] = array(
		'resource_id'=>209,
		'resource_url'=>'index.php/task/dtender_admin_config',
		'resource_name'=>'��������',
		'submenu_id'=>64);
//���˵�ID
$menu_arr = array(
		//ID
		'submenu_id'=>'64',
		//���ļ����� 
		'submenu_name'=>'�����б�',
		//һ���˵�code
		'menu_name'=>'task',
		'listorder'=>'5'
);


$init_config = array(
	'model_id'=>5,
	'model_code'=>'dtender',
	'model_name'=>'�����б�',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'on_time'=>'2012-10-27',
	'listorder'=>'1',
	//������Ϣ
	'config'=>array(
	)
);
<?php defined('IN_KEKE') or 	exit('Access Denied');
/**
 * �������������ʼ�������ļ�
 */
//�Ӳ˵�ID
$sub_menu_arr[] = array(
		'resource_id'=>202,
		'resource_url'=>'index.php/task/mreward_admin_list',
		'resource_name'=>'�����б�',
		'submenu_id'=>61);
$sub_menu_arr[] = array(
		'resource_id'=>203,
		'resource_url'=>'index.php/task/mreward_admin_config',
		'resource_name'=>'��������',
		'submenu_id'=>61);
//���˵�ID
$menu_arr = array(
		//ID
		'submenu_id'=>'61',
		//���ļ����� 
		'submenu_name'=>'��������',
		//һ���˵�code
		'menu_name'=>'task',
		'listorder'=>'2'
);


$init_config = array(
	'model_id'=>2,
	'model_code'=>'mreward',
	'model_name'=>'��������',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'on_time'=>'2012-10-27',
	'listorder'=>'2',
	//������Ϣ
	'config'=>array(
	'audit_cash'=>10,
	'is_auto_adjourn'=>1,
	'adjourn_day'=>2,
	'deduct_scale'=>1,
	'defeated_money'=>2,
	'task_min_cash'=>10,
	'show_limit_time'=>1,
	'agree_sign_time'=>10,
	'agree_complete_time'=>5)
);
<?php defined('IN_KEKE') or 	exit('Access Denied');
/**
 * �������������ʼ�������ļ�
 */
//�Ӳ˵�ID
$sub_menu_arr[] = array(
		'resource_id'=>200,
		'resource_url'=>'index.php/task/sreward_admin_list',
		'resource_name'=>'�����б�',
		'submenu_id'=>60);
$sub_menu_arr[] = array(
		'resource_id'=>201,
		'resource_url'=>'index.php/task/sreward_admin_config',
		'resource_name'=>'��������',
		'submenu_id'=>60);
//���˵�ID
$menu_arr = array(
		//ID
		'submenu_id'=>'60',
		//���ļ����� 
		'submenu_name'=>'��������',
		//һ���˵�code
		'menu_name'=>'task',
		'listorder'=>'0'
);


$init_config = array(
	'model_id'=>1,
	'model_code'=>'sreward',
	'model_name'=>'��������',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'on_time'=>'2012-10-20',
	'listorder'=>'1',
	//������Ϣ
	'config'=>array(
	'audit_cash'=>10,
	'is_auto_adjourn'=>1,
	'adjourn_day'=>2,
	'deduct_scale'=>1,
	'defeated_money'=>2,
	'task_min_cash'=>10,
	'vote_limit_time'=>2,
	'show_limit_time'=>1,
	'reg_vote_limit'=>2,
	'agree_sign_time'=>10,
	'agree_complete_time'=>5)
);
<?php defined('IN_KEKE') or 	exit('Access Denied');
/**
 * ��ͨ�б������ʼ�������ļ�
 */
//�Ӳ˵�ID
$sub_menu_arr[] = array(
		'resource_id'=>206,
		'resource_url'=>'index.php/task/tender_admin_list',
		'resource_name'=>'�����б�',
		'submenu_id'=>63);
$sub_menu_arr[] = array(
		'resource_id'=>207,
		'resource_url'=>'index.php/task/tender_admin_config',
		'resource_name'=>'��������',
		'submenu_id'=>63);
//���˵�ID
$menu_arr = array(
		//ID
		'submenu_id'=>'63',
		//���ļ����� 
		'submenu_name'=>'��ͨ�б�',
		//һ���˵�code
		'menu_name'=>'task',
		'listorder'=>'4'
);


$init_config = array(
	'model_id'=>4,
	'model_code'=>'tender',
	'model_name'=>'��ͨ�б�',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'on_time'=>'2012-10-20',
	'listorder'=>'1',
	//������Ϣ
	'config'=>array(
	 
	'is_auto_adjourn'=>1,
	'adjourn_day'=>2,
	'deduct_scale'=>1,
	'defeated_money'=>2,
	 
	'vote_limit_time'=>2
	  )
);
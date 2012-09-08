<?php
/**
 * 单人悬赏任务初始化配置文件
 */

defined('ADMIN_KEKE') or 	exit('Access Denied');

$init_menu = array(
	$_lang['task_manage']=>'index.php?do=model&model_id=9&view=list&status=0',
	$_lang['task_config']=>'index.php?do=model&model_id=9&view=config',
	 
);


$init_config = array(
	'model_id'=>9,
	'model_code'=>'wbdj',
	'model_name'=>$_lang['wbdj'],
	'model_dir'=>'wbdj',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1,
	'audit_cash'=>10,
	'is_auto_adjourn'=>1,
	'adjourn_day'=>2,
	'deduct_scale'=>1,
	'defeated_money'=>2,
	'is_comment'=>1,
	'task_min_cash'=>10,
	'vote_limit_time'=>2,
	'show_limit_time'=>1,
	'reg_vote_limit_time'=>2,
	'agree_is_open'=>1,
	'sina_affect_rule'=>array(
				1 =>array('min' => 0,'max'=> 100,'cash' => 100),
				2 =>array('min' => 101,'max'=> 100,'cash' => 200),
				3 =>array('min' => 101,'max'=> 400,'cash' => 300),
				4 =>array('min' => 401,'max'=> 750,'cash' => 400),
				5 =>array('min' => 751,'max'=> 1000,'cash' => 500),
				6 =>array('min' => 1001,'max'=> 2000,'cash' => 600),
				7 =>array('min' => 4001,'max'=> 6000,'cash' => 700),
				8 =>array('min' => 6001,'max'=> 8000,'cash' => 800),
				9 =>array('min' => 8001,'max'=> 20000,'cash' => 900),
				10 =>array('min' => 20001,'max'=> 100000,'cash' => 10000)
		),
	'ten_affect_rule'=>array(
				1 =>array('min' => 0,'max'=> 100,'cash' => 100),
				2 =>array('min' => 101,'max'=> 100,'cash' => 200),
				3 =>array('min' => 101,'max'=> 400,'cash' => 300),
				4 =>array('min' => 401,'max'=> 750,'cash' => 400),
				5 =>array('min' => 751,'max'=> 1000,'cash' => 500),
				6 =>array('min' => 1001,'max'=> 2000,'cash' => 600),
				7 =>array('min' => 4001,'max'=> 6000,'cash' => 700),
				8 =>array('min' => 6001,'max'=> 8000,'cash' => 800),
				9 =>array('min' => 8001,'max'=> 20000,'cash' => 900),
				10 =>array('min' => 20001,'max'=> 100000,'cash' => 10000)
		)
);
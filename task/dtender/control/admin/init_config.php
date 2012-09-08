<?php

if(!defined('IN_KEKE')) {
	exit('Access Denied');
}

$init_menu= array(
	$_lang['task_manage']=>'index.php?do=model&model_id=5&view=list&status=0',
	$_lang['task_config']=>'index.php?do=model&model_id=5&view=config',
);




//是否开启审核
//订金金额   （任务失败，订金不退还）
//提成比例    （如：网站提取成交金额的20%）
//竞标时间
//议标时间
//打款期限
//进行中是否选标

$init_config = array(
	'model_id'=>5,
	'model_code'=>'dtender',
	'model_type'=>'task',
	'model_name'=>$_lang['deposit_tender'],
	'model_dir'=>'dtender',
	'model_dev'=>'kekezu',
	'open_audit'=>'open',
	'deposit'=>'100',
	'task_rate'=>20,
	'task_fail_rate'=>10,
	'bid_time'=>3,
	'select_time'=>4,
	'pay_limit_time'=>5,
	'open_select'=>'open',
	'on_time'=>time(),
);

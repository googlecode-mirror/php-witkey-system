<?php
define( "IN_KEKE" ,true);
	$model_name = "wbzf";
	/*********Table witkey_model start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_model'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_model` (
		`model_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`model_id`)
			,	`model_code` varchar(20) default null 
			,	`model_name` varchar(20) default null 
			,	`model_dir` varchar(20) default null 
			,	`model_type` char(10) default null 
			,	`model_dev` varchar(20) default null 
			,	`model_status` int(11) default null 
			,	`model_desc` text default null 
			,	`on_time` int(11) default null 
			,	`hide_mode` int(11) default null 
			,	`listorder` int(11) default 0 
			,	`model_intro` varchar(255) default null 
			,	`indus_bid` text default null 
			,	`config` text default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_id model_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_code' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_code model_code varchar(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_code varchar(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_name' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_name model_name varchar(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_name varchar(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_dir' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_dir model_dir varchar(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_dir varchar(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_type' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_type model_type char(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_type char(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_dev' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_dev model_dev varchar(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_dev varchar(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_status' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_status model_status int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_status int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_desc' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_desc model_desc text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_desc text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='on_time' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  on_time on_time int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add on_time int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='hide_mode' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  hide_mode hide_mode int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add hide_mode int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='listorder' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  listorder listorder int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add listorder int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='model_intro' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(255)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  model_intro model_intro varchar(255)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add model_intro varchar(255) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='indus_bid' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  indus_bid indus_bid text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add indus_bid text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_model where Field='config' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_model  change column  config config text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_model add config text null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	
	db_factory::execute("replace into ".TABLEPRE."witkey_model (`model_id`,`model_code`,`model_name`,`model_dir`,`model_type`,`model_dev`,`model_status`,`model_desc`,`on_time`,`hide_mode`,`listorder`,`model_intro`,`indus_bid`,`config`) values ('8','wbzf','转发微博','wbzf','task','kekezu','1','微博营销&lt;br /&gt;','1337908538',0,'8','威客服务是网络商城的一种交易模式1。','','a:11:{s:10:\"audit_cash\";s:1:\"0\";s:8:\"min_cash\";s:2:\"20\";s:9:\"task_rate\";s:2:\"20\";s:14:\"task_fail_rate\";s:2:\"10\";s:10:\"end_action\";s:5:\"split\";s:10:\"witkey_num\";s:2:\"10\";s:8:\"defeated\";s:1:\"2\";s:7:\"min_day\";s:2:\"30\";s:11:\"choose_time\";s:2:\"20\";s:15:\"ten_affect_rule\";a:10:{i:1;a:3:{s:3:\"min\";i:0;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"1\";}i:2;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"2\";}i:3;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:400;s:4:\"cash\";s:1:\"3\";}i:4;a:3:{s:3:\"min\";i:401;s:3:\"max\";i:750;s:4:\"cash\";s:1:\"4\";}i:5;a:3:{s:3:\"min\";i:751;s:3:\"max\";i:1000;s:4:\"cash\";s:1:\"5\";}i:6;a:3:{s:3:\"min\";i:1001;s:3:\"max\";i:2000;s:4:\"cash\";s:1:\"6\";}i:7;a:3:{s:3:\"min\";i:4001;s:3:\"max\";i:6000;s:4:\"cash\";s:1:\"7\";}i:8;a:3:{s:3:\"min\";i:6001;s:3:\"max\";i:8000;s:4:\"cash\";s:1:\"8\";}i:9;a:3:{s:3:\"min\";i:8001;s:3:\"max\";i:20000;s:4:\"cash\";s:1:\"9\";}i:10;a:3:{s:3:\"min\";i:20001;s:3:\"max\";i:100000;s:4:\"cash\";s:2:\"10\";}}s:16:\"sina_affect_rule\";a:10:{i:1;a:3:{s:3:\"min\";i:0;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"1\";}i:2;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"2\";}i:3;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:400;s:4:\"cash\";s:1:\"3\";}i:4;a:3:{s:3:\"min\";i:401;s:3:\"max\";i:750;s:4:\"cash\";s:1:\"4\";}i:5;a:3:{s:3:\"min\";i:751;s:3:\"max\";i:1000;s:4:\"cash\";s:1:\"5\";}i:6;a:3:{s:3:\"min\";i:1001;s:3:\"max\";i:2000;s:4:\"cash\";s:1:\"6\";}i:7;a:3:{s:3:\"min\";i:4001;s:3:\"max\";i:6000;s:4:\"cash\";s:1:\"7\";}i:8;a:3:{s:3:\"min\";i:6001;s:3:\"max\";i:8000;s:4:\"cash\";s:1:\"8\";}i:9;a:3:{s:3:\"min\";i:8001;s:3:\"max\";i:20000;s:4:\"cash\";s:1:\"9\";}i:10;a:3:{s:3:\"min\";i:20001;s:3:\"max\";i:100000;s:4:\"cash\";s:2:\"10\";}}}')");
					}
	/*********Table witkey_model end **************/
	/*********Table witkey_priv_item start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_priv_item'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_priv_item` (
		`op_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`op_id`)
			,	`model_id` int(11) default null 
			,	`op_code` varchar(20) default null 
			,	`op_name` varchar(50) default null 
			,	`allow_times` int(1) default null 
			,	`limit_obj` int(111) default null 
			,	`condit` varchar(200) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='op_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  op_id op_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add op_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='model_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  model_id model_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add model_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='op_code' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  op_code op_code varchar(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add op_code varchar(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='op_name' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(50)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  op_name op_name varchar(50)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add op_name varchar(50) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='allow_times' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  allow_times allow_times int(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add allow_times int(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='limit_obj' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(111)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  limit_obj limit_obj int(111)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add limit_obj int(111) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_item where Field='condit' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_item  change column  condit condit varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_item add condit varchar(200) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('62','8','work_hand','交稿',0,'1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('63','8','pub','发布任务','1','2','')");
					}
	/*********Table witkey_priv_item end **************/
	/*********Table witkey_priv_rule start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_priv_rule'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_priv_rule` (
		`r_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`r_id`)
			,	`item_id` int(11) default null 
			,	`mark_rule_id` char(5) default null 
			,	`rule` char(5) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_rule where Field='r_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_rule  change column  r_id r_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_rule add r_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_rule where Field='item_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_rule  change column  item_id item_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_rule add item_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_rule where Field='mark_rule_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(5)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_rule  change column  mark_rule_id mark_rule_id char(5)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_rule add mark_rule_id char(5) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_priv_rule where Field='rule' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(5)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_priv_rule  change column  rule rule char(5)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_priv_rule add rule char(5) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('171','62','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('172','62','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('173','62','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('174','62','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('175','62','5','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('176','62','6','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('177','63','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('178','63','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('179','63','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('180','63','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('181','63','5','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('182','63','6','')");
					
	}
	/*********Table witkey_priv_rule end **************/
	/*********Table witkey_task_wbzf start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_wbzf'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_wbzf` (
		`wbzf_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`wbzf_id`)
			,	`task_id` varchar(50) default null 
			,	`wb_platform` char(20) default null 
			,	`is_focus` tinyint(1) default 0 
			,	`is_comment` tinyint(1) default 0 
			,	`is_at` tinyint(1) default 0 
			,	`repost_url` varchar(200) default null 
			,	`wb_content` varchar(500) default null 
			,	`wb_img` varchar(200) default null 
			,	`at_num` tinyint(2) default 0 
			,	`unit_price` text default null 
			,	`assign` tinyint(1) default null 
			,	`pay_amount` decimal(10,2) default 0.00 
			,	`fans_count` int(11) default 0 
			,	`is_repost` tinyint(1) default null 
			,	`ten_content` varchar(500) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='wbzf_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  wbzf_id wbzf_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add wbzf_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(50)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  task_id task_id varchar(50)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add task_id varchar(50) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='wb_platform' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  wb_platform wb_platform char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add wb_platform char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='is_focus' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  is_focus is_focus tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add is_focus tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='is_comment' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  is_comment is_comment tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add is_comment tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='is_at' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  is_at is_at tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add is_at tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='repost_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  repost_url repost_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add repost_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='wb_content' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(500)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  wb_content wb_content varchar(500)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add wb_content varchar(500) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='wb_img' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  wb_img wb_img varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add wb_img varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='at_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(2)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  at_num at_num tinyint(2)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add at_num tinyint(2) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='unit_price' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  unit_price unit_price text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add unit_price text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='assign' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  assign assign tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add assign tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='pay_amount' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,2)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  pay_amount pay_amount decimal(10,2) default 0.00");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add pay_amount decimal(10,2) null default 0.00 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='fans_count' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  fans_count fans_count int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add fans_count int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='is_repost' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  is_repost is_repost tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add is_repost tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf where Field='ten_content' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(500)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf  change column  ten_content ten_content varchar(500)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf add ten_content varchar(500) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_wbzf end **************/
	/*********Table witkey_task_wbzf_work start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_wbzf_work'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_wbzf_work` (
		`zfwk_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`zfwk_id`)
			,	`task_id` int(11) default null 
			,	`work_id` int(11) default null 
			,	`wb_type` char(20) default null 
			,	`fans` int(11) default null 
			,	`wb_url` varchar(200) default null 
			,	`wb_account` varchar(100) default null 
			,	`wb_sid` bigint(20) default null 
			,	`get_cash` decimal(10,3) default 0.000 
			,	`wb_data` text default null 
			,	`hf_num` int(10) default 0 
			,	`focus_num` int(10) default 0 
			,	`wb_num` int(10) default 0 
			,	`faver_num` int(10) default 0 
			,	`create_day` int(10) default null 
			,	`fgd_num` int(10) default 0 
			,	`hyd_num` int(10) default 0 
			,	`cbd_num` int(10) default 0 
			,	`yxl_num` int(10) default 0 
			,	`wb_leve` int(10) default 0 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='zfwk_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  zfwk_id zfwk_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add zfwk_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add work_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_type' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_type wb_type char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_type char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='fans' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  fans fans int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add fans int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_url wb_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_account' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_account wb_account varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_account varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_sid' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="bigint(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_sid wb_sid bigint(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_sid bigint(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='get_cash' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  get_cash get_cash decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add get_cash decimal(10,3) null default 0.000 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_data' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_data wb_data text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_data text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='hf_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  hf_num hf_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add hf_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='focus_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  focus_num focus_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add focus_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_num wb_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='faver_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  faver_num faver_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add faver_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='create_day' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  create_day create_day int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add create_day int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='fgd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  fgd_num fgd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add fgd_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='hyd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  hyd_num hyd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add hyd_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='cbd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  cbd_num cbd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add cbd_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='yxl_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  yxl_num yxl_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add yxl_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbzf_work where Field='wb_leve' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbzf_work  change column  wb_leve wb_leve int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbzf_work add wb_leve int(10) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_wbzf_work end **************/
	/*********Table witkey_task_work start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_work'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_work` (
		`work_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`work_id`)
			,	`task_id` int(11) default 0 
			,	`uid` int(11) default 0 
			,	`username` char(50) default null 
			,	`work_title` varchar(100) default null 
			,	`work_price` decimal(10,3) default 0.000 
			,	`work_desc` text default null 
			,	`work_file` varchar(100) default null 
			,	`work_pic` varchar(100) default null 
			,	`work_time` int(11) default 0 
			,	`hide_work` int(11) default null 
			,	`vote_num` int(11) unsigned default 0 
			,	`comment_num` int(11) default 0 
			,	`work_status` int(11) default 0 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='uid' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  uid uid int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add uid int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='username' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(50)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  username username char(50)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add username char(50) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_title' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_title work_title varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_title varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_price' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_price work_price decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_price decimal(10,3) null default 0.000 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_desc' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_desc work_desc text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_desc text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_file' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_file work_file varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_file varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_pic' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_pic work_pic varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_pic varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_time' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_time work_time int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_time int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='hide_work' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  hide_work hide_work int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add hide_work int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='vote_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11) unsigned"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  vote_num vote_num int(11) unsigned");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add vote_num int(11) unsigned null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='comment_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  comment_num comment_num int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add comment_num int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_work where Field='work_status' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_work  change column  work_status work_status int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_work add work_status int(11) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_work end **************/
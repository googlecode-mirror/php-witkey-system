<?php
define( "IN_KEKE" ,true);
	$model_name = "wbdj";
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_model (`model_id`,`model_code`,`model_name`,`model_dir`,`model_type`,`model_dev`,`model_status`,`model_desc`,`on_time`,`hide_mode`,`listorder`,`model_intro`,`indus_bid`,`config`) values ('9','wbdj','点击微博','wbdj','task','kekezu','1','通过微博内容点击量计算稿件费用<br />','1332468981',0,'0','通过微博内容点击量计算稿件费用','','a:12:{s:10:\"audit_cash\";s:1:\"1\";s:8:\"min_cash\";s:1:\"4\";s:9:\"task_rate\";s:1:\"4\";s:14:\"task_fail_rate\";s:1:\"4\";s:10:\"end_action\";s:6:\"refund\";s:10:\"witkey_num\";s:0:\"\";s:8:\"defeated\";s:1:\"1\";s:7:\"min_day\";s:1:\"3\";s:11:\"choose_time\";s:1:\"3\";s:9:\"hand_time\";s:1:\"3\";s:16:\"sina_affect_rule\";a:10:{i:1;a:3:{s:3:\"min\";i:0;s:3:\"max\";i:100;s:4:\"cash\";s:3:\"0.1\";}i:2;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:100;s:4:\"cash\";s:3:\"0.2\";}i:3;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:400;s:4:\"cash\";s:3:\"0.3\";}i:4;a:3:{s:3:\"min\";i:401;s:3:\"max\";i:750;s:4:\"cash\";s:3:\"0.4\";}i:5;a:3:{s:3:\"min\";i:751;s:3:\"max\";i:1000;s:4:\"cash\";s:3:\"0.5\";}i:6;a:3:{s:3:\"min\";i:1001;s:3:\"max\";i:2000;s:4:\"cash\";s:3:\"0.6\";}i:7;a:3:{s:3:\"min\";i:4001;s:3:\"max\";i:6000;s:4:\"cash\";s:3:\"0.7\";}i:8;a:3:{s:3:\"min\";i:6001;s:3:\"max\";i:8000;s:4:\"cash\";s:3:\"0.8\";}i:9;a:3:{s:3:\"min\";i:8001;s:3:\"max\";i:20000;s:4:\"cash\";s:3:\"0.9\";}i:10;a:3:{s:3:\"min\";i:20001;s:3:\"max\";i:100000;s:4:\"cash\";s:1:\"1\";}}s:15:\"ten_affect_rule\";a:10:{i:1;a:1:{s:4:\"cash\";s:3:\"0.1\";}i:2;a:1:{s:4:\"cash\";s:3:\"0.2\";}i:3;a:1:{s:4:\"cash\";s:3:\"0.3\";}i:4;a:1:{s:4:\"cash\";s:3:\"0.4\";}i:5;a:1:{s:4:\"cash\";s:3:\"0.5\";}i:6;a:1:{s:4:\"cash\";s:3:\"0.6\";}i:7;a:1:{s:4:\"cash\";s:3:\"0.7\";}i:8;a:1:{s:4:\"cash\";s:3:\"0.8\";}i:9;a:1:{s:4:\"cash\";s:3:\"0.9\";}i:10;a:1:{s:4:\"cash\";s:1:\"1\";}}}')");
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('66','9','work_hand','交稿',0,'1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('67','9','pub','发布任务',0,'2','')");
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('195','66','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('196','66','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('197','66','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('198','66','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('199','66','5','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('200','67','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('201','67','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('202','67','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('203','67','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('204','67','5','')");
					
	}
	/*********Table witkey_priv_rule end **************/
	/*********Table witkey_task_wbdj start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_wbdj'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_wbdj` (
		`wbdj_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`wbdj_id`)
			,	`task_id` int(11) default null 
			,	`wb_platform` char(20) default null 
			,	`wb_content` varchar(140) default null 
			,	`wb_img` varchar(200) default null 
			,	`click_price` decimal(10,2) default 0.00 
			,	`prom_url` varchar(150) default null 
			,	`pay_amount` decimal(10,2) default 0.00 
			,	`click_count` int(11) default 0 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='wbdj_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  wbdj_id wbdj_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add wbdj_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='wb_platform' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  wb_platform wb_platform char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add wb_platform char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='wb_content' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(140)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  wb_content wb_content varchar(140)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add wb_content varchar(140) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='wb_img' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  wb_img wb_img varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add wb_img varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='click_price' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,2)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  click_price click_price decimal(10,2) default 0.00");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add click_price decimal(10,2) null default 0.00 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='prom_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(150)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  prom_url prom_url varchar(150)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add prom_url varchar(150) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='pay_amount' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,2)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  pay_amount pay_amount decimal(10,2) default 0.00");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add pay_amount decimal(10,2) null default 0.00 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj where Field='click_count' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj  change column  click_count click_count int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj add click_count int(11) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_wbdj end **************/
	/*********Table witkey_task_wbdj_views start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_wbdj_views'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_wbdj_views` (
		`view_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`view_id`)
			,	`task_id` int(11) default null 
			,	`work_id` int(11) default null 
			,	`djwk_id` int(11) default null 
			,	`refre_url` varchar(100) default null 
			,	`user_ip` varchar(18) default null 
			,	`user_agent` varchar(200) default null 
			,	`click_time` int(10) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='view_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  view_id view_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add view_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add work_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='djwk_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  djwk_id djwk_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add djwk_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='refre_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  refre_url refre_url varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add refre_url varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='user_ip' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(18)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  user_ip user_ip varchar(18)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add user_ip varchar(18) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='user_agent' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  user_agent user_agent varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add user_agent varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_views where Field='click_time' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_views  change column  click_time click_time int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_views add click_time int(10) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_wbdj_views end **************/
	/*********Table witkey_task_wbdj_work start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_wbdj_work'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_wbdj_work` (
		`djwk_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`djwk_id`)
			,	`task_id` int(11) default null 
			,	`work_id` int(11) default null 
			,	`wb_type` char(20) default null 
			,	`click_num` int(11) default 0 
			,	`wb_url` varchar(200) default null 
			,	`jump_url` varchar(200) default null 
			,	`wb_account` varchar(200) default null 
			,	`wb_sid` bigint(20) default null 
			,	`get_cash` decimal(10,3) default 0.000 
			,	`wb_data` text default null 
			,	`ip` varchar(18) NOT NULL 	,PRIMARY KEY  (`ip`)
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='djwk_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  djwk_id djwk_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add djwk_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add work_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='wb_type' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  wb_type wb_type char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add wb_type char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='click_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  click_num click_num int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add click_num int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='wb_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  wb_url wb_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add wb_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='jump_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  jump_url jump_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add jump_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='wb_account' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  wb_account wb_account varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add wb_account varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='wb_sid' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="bigint(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  wb_sid wb_sid bigint(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add wb_sid bigint(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='get_cash' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  get_cash get_cash decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add get_cash decimal(10,3) null default 0.000 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='wb_data' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  wb_data wb_data text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add wb_data text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_wbdj_work where Field='ip' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(18)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_wbdj_work  change column  ip ip varchar(18)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_wbdj_work add ip varchar(18) not null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_wbdj_work end **************/
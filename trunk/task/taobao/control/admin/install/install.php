<?php
define( "IN_KEKE" ,true);
	$model_name = "taobao";
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_model (`model_id`,`model_code`,`model_name`,`model_dir`,`model_type`,`model_dev`,`model_status`,`model_desc`,`on_time`,`hide_mode`,`listorder`,`model_intro`,`indus_bid`,`config`) values ('10','taobao','淘宝任务','taobao','task','kekezu','1','单人悬赏常用于发布一些时间短，需要创意型的任务，例如给宝宝起名，店铺起名，设计网站logo，贺卡设计<img alt=\"微笑\" src=\"resource/js/xheditor/xheditor_emot/default/smile.gif\" /><img alt=\"微笑\" src=\"resource/js/xheditor/xheditor_emot/default/smile.gif\" /><br />','1331606214',0,'0','单人悬赏常用于发布一些时间短，需要创意型的任务，例如给宝宝起名，店铺起名，设计网站logo，贺卡设计','','a:10:{s:10:\"audit_cash\";s:2:\"10\";s:8:\"min_cash\";s:2:\"10\";s:9:\"task_rate\";s:2:\"20\";s:14:\"task_fail_rate\";s:2:\"10\";s:8:\"defeated\";s:1:\"1\";s:7:\"min_day\";s:1:\"1\";s:11:\"choose_time\";s:1:\"7\";s:11:\"affect_rule\";a:4:{i:1;a:3:{s:3:\"min\";s:1:\"1\";s:3:\"max\";s:1:\"5\";s:4:\"cash\";s:1:\"1\";}i:2;a:3:{s:3:\"min\";s:1:\"6\";s:3:\"max\";s:2:\"10\";s:4:\"cash\";s:1:\"2\";}i:3;a:3:{s:3:\"min\";s:2:\"11\";s:3:\"max\";s:2:\"15\";s:4:\"cash\";s:1:\"3\";}i:4;a:3:{s:3:\"min\";s:2:\"16\";s:3:\"max\";s:2:\"20\";s:4:\"cash\";s:1:\"4\";}}s:16:\"sina_affect_rule\";a:10:{i:1;a:3:{s:3:\"min\";i:0;s:3:\"max\";i:50;s:4:\"cash\";s:1:\"1\";}i:2;a:3:{s:3:\"min\";i:51;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"2\";}i:3;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:150;s:4:\"cash\";s:1:\"3\";}i:4;a:3:{s:3:\"min\";i:151;s:3:\"max\";i:200;s:4:\"cash\";s:1:\"4\";}i:5;a:3:{s:3:\"min\";i:201;s:3:\"max\";i:250;s:4:\"cash\";s:1:\"5\";}i:6;a:3:{s:3:\"min\";i:251;s:3:\"max\";i:300;s:4:\"cash\";s:1:\"6\";}i:7;a:3:{s:3:\"min\";i:301;s:3:\"max\";i:400;s:4:\"cash\";s:1:\"7\";}i:8;a:3:{s:3:\"min\";i:401;s:3:\"max\";i:500;s:4:\"cash\";s:1:\"8\";}i:9;a:3:{s:3:\"min\";i:501;s:3:\"max\";i:600;s:4:\"cash\";s:1:\"9\";}i:10;a:3:{s:3:\"min\";i:601;s:3:\"max\";i:700;s:4:\"cash\";s:2:\"10\";}}s:15:\"ten_affect_rule\";a:10:{i:1;a:3:{s:3:\"min\";i:0;s:3:\"max\";i:50;s:4:\"cash\";s:1:\"1\";}i:2;a:3:{s:3:\"min\";i:51;s:3:\"max\";i:100;s:4:\"cash\";s:1:\"2\";}i:3;a:3:{s:3:\"min\";i:101;s:3:\"max\";i:150;s:4:\"cash\";s:1:\"3\";}i:4;a:3:{s:3:\"min\";i:151;s:3:\"max\";i:200;s:4:\"cash\";s:1:\"4\";}i:5;a:3:{s:3:\"min\";i:201;s:3:\"max\";i:250;s:4:\"cash\";s:1:\"5\";}i:6;a:3:{s:3:\"min\";i:251;s:3:\"max\";i:300;s:4:\"cash\";s:1:\"6\";}i:7;a:3:{s:3:\"min\";i:301;s:3:\"max\";i:400;s:4:\"cash\";s:1:\"7\";}i:8;a:3:{s:3:\"min\";i:401;s:3:\"max\";i:500;s:4:\"cash\";s:1:\"8\";}i:9;a:3:{s:3:\"min\";i:501;s:3:\"max\";i:600;s:4:\"cash\";s:1:\"9\";}i:10;a:3:{s:3:\"min\";i:601;s:3:\"max\";i:700;s:4:\"cash\";s:2:\"10\";}}}')");
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('70','10','work_hand','交稿',0,'1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_item (`op_id`,`model_id`,`op_code`,`op_name`,`allow_times`,`limit_obj`,`condit`) values ('71','10','pub','发布任务','1','2','')");
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
	
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('268','70','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('269','70','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('270','70','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('271','70','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('272','70','5','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('273','70','6','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('293','71','1','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('275','71','2','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('276','71','3','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('277','71','4','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('278','71','5','')");
					
	db_factory::execute("replace into ".TABLEPRE."witkey_priv_rule (`r_id`,`item_id`,`mark_rule_id`,`rule`) values ('279','71','6','')");
				
	}
	/*********Table witkey_priv_rule end **************/
	/*********Table witkey_task_taobao start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_taobao'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_taobao` (
		`taobao_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`taobao_id`)
			,	`task_id` varchar(50) default null 
			,	`wb_platform` char(20) default null 
			,	`is_focus` tinyint(1) default 0 
			,	`is_comment` tinyint(1) default 0 
			,	`is_at` tinyint(1) default 0 
			,	`is_zf` tinyint(1) default 0 
			,	`zf_url` varchar(200) default null 
			,	`wb_content` varchar(140) default null 
			,	`wb_img` varchar(200) default null 
			,	`at_num` tinyint(2) default null 
			,	`at_user` varchar(200) default null 
			,	`assign` tinyint(1) default null 
			,	`taobao_user` varchar(100) default null 
			,	`zf_obj` char(10) default null 
			,	`unit_price` text default null 
			,	`click_count` int(5) default 0 
			,	`pay_amount` decimal(10,3) default 0.000 
			,	`sy_amount` decimal(10,3) default null 
			,	`is_repost` tinyint(1) default 0 
			,	`ten_content` varchar(500) default null 
			,	`prom_url` varchar(150) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='taobao_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  taobao_id taobao_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add taobao_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(50)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  task_id task_id varchar(50)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add task_id varchar(50) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='wb_platform' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  wb_platform wb_platform char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add wb_platform char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='is_focus' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  is_focus is_focus tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add is_focus tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='is_comment' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  is_comment is_comment tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add is_comment tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='is_at' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  is_at is_at tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add is_at tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='is_zf' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  is_zf is_zf tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add is_zf tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='zf_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  zf_url zf_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add zf_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='wb_content' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(140)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  wb_content wb_content varchar(140)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add wb_content varchar(140) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='wb_img' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  wb_img wb_img varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add wb_img varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='at_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(2)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  at_num at_num tinyint(2)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add at_num tinyint(2) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='at_user' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  at_user at_user varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add at_user varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='assign' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  assign assign tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add assign tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='taobao_user' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  taobao_user taobao_user varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add taobao_user varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='zf_obj' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  zf_obj zf_obj char(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add zf_obj char(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='unit_price' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  unit_price unit_price text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add unit_price text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='click_count' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(5)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  click_count click_count int(5)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add click_count int(5) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='pay_amount' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  pay_amount pay_amount decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add pay_amount decimal(10,3) null default 0.000 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='sy_amount' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  sy_amount sy_amount decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add sy_amount decimal(10,3) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='is_repost' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="tinyint(1)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  is_repost is_repost tinyint(1)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add is_repost tinyint(1) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='ten_content' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(500)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  ten_content ten_content varchar(500)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add ten_content varchar(500) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao where Field='prom_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(150)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao  change column  prom_url prom_url varchar(150)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao add prom_url varchar(150) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_taobao end **************/
	/*********Table witkey_task_taobao_views start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_taobao_views'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_taobao_views` (
		`view_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`view_id`)
			,	`task_id` int(11) default null 
			,	`work_id` int(11) default null 
			,	`tbwk_id` int(11) default null 
			,	`refer_url` varchar(100) default null 
			,	`user_ip` varchar(18) default null 
			,	`user_agent` varchar(200) default null 
			,	`click_time` int(10) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='view_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  view_id view_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add view_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add work_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='tbwk_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  tbwk_id tbwk_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add tbwk_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='refer_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  refer_url refer_url varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add refer_url varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='user_ip' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(18)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  user_ip user_ip varchar(18)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add user_ip varchar(18) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='user_agent' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  user_agent user_agent varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add user_agent varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_views where Field='click_time' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_views  change column  click_time click_time int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_views add click_time int(10) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_taobao_views end **************/
	/*********Table witkey_task_taobao_work start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_task_taobao_work'");
	
	$table_exist = $table_exist[0]?true:false;
	
	if (!$table_exist){
	
	$res = db_factory::execute("CREATE TABLE IF NOT EXISTS `".TABLEPRE."witkey_task_taobao_work` (
		`tbwk_id` int(11) NOT NULL auto_increment 	,PRIMARY KEY  (`tbwk_id`)
			,	`task_id` int(11) default null 
			,	`work_id` int(11) default null 
			,	`wb_type` char(20) default null 
			,	`fans` int(11) default 0 
			,	`wb_url` varchar(200) default null 
			,	`wb_account` varchar(100) default null 
			,	`wb_sid` bigint(20) default null 
			,	`get_cash` decimal(10,3) default 0.000 
			,	`wb_data` text default null 
			,	`hf_num` int(10) default 0 
			,	`focus_num` int(10) default 0 
			,	`wb_num` int(10) default 0 
			,	`faver_num` int(10) default 0 
			,	`create_day` int(10) default 0 
			,	`fgd_num` int(10) default 10 
			,	`hyd_num` int(10) default 0 
			,	`cbd_num` int(10) default 0 
			,	`yxl_num` int(10) default 0 
			,	`wb_leve` int(10) default 0 
			,	`click_num` int(11) default 0 
			,	`ip` char(20) default null 
			,	`jump_url` varchar(200) default null 
			) ENGINE=MyISAM  DEFAULT CHARSET=".DBCHARSET."");
		
	}else{
	
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='tbwk_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  tbwk_id tbwk_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add tbwk_id int(11) not null default null auto_increment");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='task_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  task_id task_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add task_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='work_id' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  work_id work_id int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add work_id int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_type' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_type wb_type char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_type char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='fans' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  fans fans int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add fans int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_url wb_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_url varchar(200) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_account' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(100)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_account wb_account varchar(100)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_account varchar(100) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_sid' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="bigint(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_sid wb_sid bigint(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_sid bigint(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='get_cash' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="decimal(10,3)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  get_cash get_cash decimal(10,3) default 0.000");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add get_cash decimal(10,3) null default 0.000 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_data' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="text"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_data wb_data text");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_data text null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='hf_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  hf_num hf_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add hf_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='focus_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  focus_num focus_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add focus_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_num wb_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='faver_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  faver_num faver_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add faver_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='create_day' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  create_day create_day int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add create_day int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='fgd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  fgd_num fgd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add fgd_num int(10) null default 10 ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='hyd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  hyd_num hyd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add hyd_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='cbd_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  cbd_num cbd_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add cbd_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='yxl_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  yxl_num yxl_num int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add yxl_num int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='wb_leve' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(10)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  wb_leve wb_leve int(10)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add wb_leve int(10) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='click_num' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="int(11)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  click_num click_num int(11)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add click_num int(11) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='ip' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="char(20)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  ip ip char(20)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add ip char(20) null default null ");
		}
		
		$col_info = db_factory::query("show COLUMNS FROM ".TABLEPRE."witkey_task_taobao_work where Field='jump_url' ");
		$col_info = $col_info[0];
		if($col_info){
			if($col_info["Type"]!="varchar(200)"){
				db_factory::execute("alter  table ".TABLEPRE."witkey_task_taobao_work  change column  jump_url jump_url varchar(200)");
			}
		}
		else{
			db_factory::execute("alter table ".TABLEPRE."witkey_task_taobao_work add jump_url varchar(200) null default null ");
		}
		
	}
	$table_exist=false;
	if(!$table_exist){
	}
	/*********Table witkey_task_taobao_work end **************/
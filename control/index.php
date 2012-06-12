<?php
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-26早上11:49:00
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = "index";

/**
 * 首页行业
 */
$indus_list = kekezu::$_indus_p_arr;
$indus_c_arr = kekezu::$_indus_c_arr;

/**
 * 首页最近二周任务中标额统计
 */
$two_week_task_in = dbfactory::query ( sprintf ( " select sum(fina_cash) cash,WEEK(date(from_unixtime(fina_time)),1) week from %switkey_finance where fina_action='task_bid' and fina_type='in' group by week limit 0,2", TABLEPRE ), 1, 600 ); // 任务
$this_week_task_in = str_pad ( number_format ( $two_week_task_in ['0'] ['cash'], 2, ".", "," ), 10, 0, STR_PAD_LEFT );
$two_week_task_in ['0'] ['cash'] >= $two_week_task_in ['1'] ['cash'] and $task_in_up = 1;

/**
 * 首页最近二周任务发布量统计
 */
$two_week_task_count = dbfactory::query ( sprintf ( " select count(task_id) count,WEEK(date(from_unixtime(start_time)),1) week from %switkey_task group by week limit 0,2", TABLEPRE ), 1, 600 ); // 数量统计
$this_week_task_count = str_pad ( intval ( $two_week_task_count ['0'] ['count'] ), 10, 0, STR_PAD_LEFT );
$two_week_task_count ['0'] ['count'] >= $two_week_task_count ['1'] ['count'] and $task_count_up = 1;

/**
 * 首页最近二周服务少收额统计
 */
$two_week_service_in = dbfactory::query ( sprintf ( " select sum(fina_cash) cash,WEEK(date(from_unixtime(fina_time)),1) week from %switkey_finance where fina_action='sale_service' and fina_type='in' group by week limit 0,2", TABLEPRE ), 1, 600 ); // 商城
$this_week_service_in = str_pad ( number_format ( $two_week_service_in ['0'] ['cash'], 2, ".", "," ), 10, 0, STR_PAD_LEFT );
$two_week_service_in ['0'] ['cash'] >= $two_week_service_in ['1'] ['cash'] and $service_in_up = 1;

/**
 * 首页最近二周服务发布量统计
 */
$two_week_service_count = dbfactory::query ( sprintf ( " select count(service_id) count,WEEK(date(from_unixtime(on_time)),1) week from %switkey_service where service_status='2' group by week limit 0,2", TABLEPRE ), 1, 600 ); // 数量统计
$this_week_service_count = str_pad (  intval ( $two_week_service_count ['0'] ['count'] ), 10, 0, STR_PAD_LEFT );
$two_week_service_count ['0'] ['count'] >= $two_week_service_count ['1'] ['count'] and $service_count_up = 1;

/**
 * 首页最近二周注册用户统计
 */
$two_week_register = dbfactory::query ( sprintf ( " select count(uid) count,WEEK(date(from_unixtime(reg_time)),1) week from %switkey_space where status!='2' group by week limit 0,2", TABLEPRE ), 1, 600 ); // 注册用户
$this_week_register = str_pad ( intval ( $two_week_register ['0'] ['count'] ), 10, 0, STR_PAD_LEFT );
$two_week_register ['0'] ['count'] >= $two_week_register ['1'] ['count'] and $register_count_up = 1;

/**
 * 首页最近二周认证用户统计
 */
$two_week_auth = dbfactory::query ( sprintf ( " select count(record_id) count,WEEK(date(from_unixtime(end_time)),1) week from %switkey_auth_record where auth_status='1' group by week limit 0,2", TABLEPRE ), 1, 600 ); // 认证用户
$this_week_auth = str_pad (  intval ( $two_week_auth ['0'] ['count']), 10, 0, STR_PAD_LEFT );
$two_week_auth ['0'] ['count'] >= $two_week_auth ['1'] ['count'] and $auth_count_up = 1;

/**
 * 首页feed
 */

$feed_list = dbfactory::query ( "select uid,username,title,feed_time from " . TABLEPRE . "witkey_feed order by feed_time desc limit 0,4", 1, 3600 );
$mode_list = kekezu::$_model_list;
$cash_coverage = kekezu::get_table_data ( "cash_rule_id,start_cove,end_cove", "witkey_task_cash_cove", "", "", "", "", "cash_rule_id", 3600 );

/**
 * 推荐任务
 */
$task_recomm_3 = dbfactory::query ( sprintf ( " select task_id,uid,username,task_title,task_cash,model_id,view_num,focus_num,work_num,task_cash_coverage from %switkey_task where is_top='1' and task_status='2' order by start_time desc limit 0,3", TABLEPRE ), 1, 600 );

$sql = " select task_id,task_title,task_cash,view_num,focus_num,work_num,task_cash_coverage
		 from %switkey_task  where is_top='1' and (task_status='2' or task_status ='3' or task_status ='4' or task_status ='5' or task_status ='6')
		  order by start_time desc limit 3,33";
$recomm_task = dbfactory::query ( sprintf ( $sql, TABLEPRE ), true, 3600 );

/**
 * 推荐商品
 */
$range = range ( 1, 24 );
$recomm_service = dbfactory::query ( sprintf ( "select service_id,pic,ad_pic,title from %switkey_service where is_top='1' and service_status='2' order by on_time desc limit 0,26", TABLEPRE ), 1, 600 );

/**
 * 新闻
 */
$news_list = kekezu::get_table_data ( "art_id,art_title,art_pic,content,pub_time", "witkey_article", "(art_cat_id='5' or art_cat_id='6' or art_cat_id='17' or art_cat_id='365')", " pub_time desc", "", "10", "", 3600 );
/**
 * 媒体报道
 */
/*
 * $media_list =
 * kekezu::get_table_data("art_id,art_title,art_pic,content,pub_time","witkey_article","art_cat_id='7'","","","10","",3600);
 */
/**
 * 首页案例
 */
$case_list = kekezu::get_table_data ( "case_id,obj_id,obj_type,case_img,case_title,case_price", "witkey_case", "", "", "", "7", "", 3600 );

/**
 * 首页人才
 */
$talent_list = dbfactory::query ( sprintf ( " select uid,username from %switkey_space where status!=2 order by reg_time desc limit 0,16", TABLEPRE ), 1, 600 );
// var_dump($talent_list);
/**
 * 收入排行
 */
$income_rank = dbfactory::query ( sprintf ( " select sum(fina_cash) as cash,uid,username from %switkey_finance where fina_type='in' and fina_action!='task_fail' group by uid order by cash desc limit 0,5 ", TABLEPRE ), 1, 600 ); // 收入排行
if (isset ( $ajax )) {
	switch ($ajax) {
		case "task" :
			/**
			 * 最新任务
			 */
			$sql2 = " select task_id,task_title,task_cash,view_num,focus_num,work_num,task_cash_coverage
		 from %switkey_task  where  (task_status='2' or task_status ='3' or task_status ='4' or task_status ='5' or task_status ='6') 
		  order by start_time desc limit 0,42";
			$task_lastest = dbfactory::query ( sprintf ( $sql2, TABLEPRE ), true, 3600 );
			require keke_tpl_class::template ( "ajax/index" );
			die ();
			break;
		case "shop" :
			/**
			 * 最新商品
			 */
			$service_lastes = dbfactory::query ( sprintf ( "select service_id,pic,ad_pic,title from %switkey_service where   service_status='2' order by on_time desc limit 0,26", TABLEPRE ), 1, 600 );
			require keke_tpl_class::template ( "ajax/index" );
			die ();
			break;
	}
	if (in_array ( $ajax, array (
			'rules',
			'withdraw',
			'safe' 
	) )) {
		// var_dump($ajax);
		$cat_arr = array (
				'rules' => 100,
				'withdraw' => 297,
				'safe' => 203 
		);
		$art_arr = get_art ( $cat_arr [$ajax] );
		require keke_tpl_class::template ( "ajax/index" );
		die ();
	}
}

/**
 * 文章排行
 */
$art_notice_arr = get_art ( 17 );
 
$page_title = $_K ['html_title'];

function get_art($cat_id) {
	$sql = "select a.art_id,a.art_title from " . TABLEPRE . "witkey_article a left join " . TABLEPRE . "witkey_article_category b  on a.art_cat_id = b.art_cat_id
 					where a.art_cat_id = $cat_id  or b.art_cat_pid like '%{ $cat_id }%' order by a.pub_time desc limit 4";
	return dbfactory::query ( $sql, 0, 600 );
}
 
require keke_tpl_class::template ( $do );
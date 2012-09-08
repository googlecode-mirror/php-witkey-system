<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$std_cache_name = 'task_cache_'.$pub_mode.'_'.$model_id.'_'.$t_id.'_' . substr ( md5 ( $uid ), 0, 6 );
$release_obj = taobao_release_class::get_instance ( $model_id,$pub_mode);

$release_obj->get_task_obj ( $std_cache_name ); //获取任务信息对象
$release_obj->pub_mode_init($std_cache_name,$init_info);//发布模式初始化相关信息
$r_info = $release_obj->_std_obj->_release_info; //任务发布信息
$task_config = $release_obj->_task_config; //任务配置
$ajax =='check_priv' and $release_obj->check_pub_priv('','json');
$fans_arr = $task_config['sina_affect_rule'];
$rate_arr = array('10','15','25','30','40','45','60','70','80','90');
foreach ($fans_arr as $k=>$v){
	$fans_arr[$k]['rate'] = $rate_arr[$k-1];
}
$min =time()+ 24*3600*$task_config['min_day'];
$min = date("Y-m-d",$min); 
$payitem_arr = keke_payitem_class::get_payitem_info('employer','wbzf'); //获取该任务所有的增值服务  
$payitem_standard = keke_payitem_class::payitem_standard (); //收费标准
switch ($r_step) { //任务发布步骤
	case "step1" :
		switch ($ajax) {
			case "getmaxday" : //获取最大天数
				$release_obj->get_max_day ( $task_cash );
				break;
		}
	
		if (kekezu::submitcheck($formhash)) {
			$r_info and $_POST = array_merge ( $r_info, $_POST );
			//任务赏金转换
			$_POST['txt_task_cash'] = keke_curren_class::convert($_POST['txt_task_cash'],0,true);
			$release_obj->save_task_obj ( $_POST, $std_cache_name ); //信息保存
			header ( "location:index.php?do=release&pub_mode=$pub_mode&t_id=$t_id&model_id={$model_id}&r_step=step2" );
			die ();
		} else{
			$default_max_day = $release_obj->_default_max_day; //当前预算下的最大天数
		}
		break;
	case "step2" :		
		if (kekezu::submitcheck($formhash)) {
			$r_info and $_POST = array_merge ( $r_info, $_POST);
 			$_POST['txt_title'] = kekezu::escape($txt_title);
 			$_POST['tar_content'] = $tar_content;
			$release_obj->save_task_obj ($_POST, $std_cache_name ); //信息保存
			header ( "location:index.php?do=release&pub_mode=$pub_mode&t_id=$t_id&model_id={$model_id}&r_step=step3" );
			die ();
		} else {
			$release_obj->check_access ( $r_step, $model_id, $r_info ); //页面进入权限检测
			$kf_info	 = $release_obj->_kf_info; //随机客服
			$indus_p_arr = $release_obj->get_bind_indus(); //父级行业
			$indus_arr   = $release_obj->get_task_indus($r_info ['indus_pid']); //子集行业
			$ext_types   = kekezu::get_ext_type (); //附件允许类型
			//var_dump();
 		}
 		if($api&&$nick){
	 			$api      = $api;
	 			$nick     = $nick;
 			switch ($api){
 				case "shop":
 					CHARSET=='gbk' and $nick = kekezu::utftogbk($nick);
 					keke_taobaoke_class::get_shop_info($nick);
 					die();
 					break;
 				case "items":
 					$title = '商品列表';
 					$data = keke_taobaoke_class::get_items_info($nick);
 					require keke_tpl_class::template("task/taobao/tpl/".$kekezu->_template."/taobao_goods");
 					die();
 					break;
 				default:
 					kekezu::echojson('不存在的接口',0);die();
 					break;
 			}
 		}
		break;
	case "step3" :
			$limit_max =ceil(( strtotime($release_info['txt_task_day']) - time())/3600/24); 
	switch ($ajax) {
			case "save_payitem" : 
				$release_obj->save_pay_item ( $item_id, $item_code, $item_name, $item_cash, $std_cache_name ,$item_num);
				break;
			case "rm_payitem" :	
				$release_obj->remove_pay_item ( $item_id, $std_cache_name );
				break;
		}
		if (kekezu::submitcheck($formhash)) {
		 
			$r_info and $_POST = array_merge ( $r_info, $_POST );
			$release_obj->save_task_obj ( $_POST, $std_cache_name ); //信息保存
			$task_id = $release_obj->pub_task (); //任务记录产生 
			$release_obj->save_task_taobao($task_id);//存储转发信息
			$release_obj->update_task_info ( $task_id, $std_cache_name ); //完成发布，更新信息
		} else {
			$release_obj->check_access ( $r_step, $model_id, $r_info ); //页面进入权限检测
			$item_list = keke_payitem_class::get_payitem_config ('employer',$model_info['model_code'] );//雇主增值服务项
			//$trust_list = kekezu::get_payment_config('','trust','1');//担保交易列表
			
			$standard = keke_payitem_class::payitem_standard ();//增值服务收费标准中文
			$total_cash = $release_obj->get_total_cash ( $r_info ['txt_task_cash'] ); //任务总金额
			$item_info = $release_obj->get_pay_item (); //任务附加项获取
		}
		break;
	case "step4" :
		$release_obj->check_access ( $r_step, $model_id, $r_info,$task_id ); //页面进入权限检测
		break;
}

require keke_tpl_class::template ( 'release' );
		
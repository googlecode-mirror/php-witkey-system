<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 充值审核
 * @copyright keke-tech
 * @author fu
 * @version v 22
 * 2012-10-9 15:18:30
 */
class Control_admin_finance_recharge extends Controller{

	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;

		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `order_id`,`username`,`order_type`,`pay_type`,`pay_money`,`pay_time`,`order_status` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('order_id'=>$_lang['id'],'username'=>$_lang['username'],'order_type'=>$_lang['order_type']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//finance本来是一个目录，由于没有定义tool为目录的路由,所以这个控制层的文件来finance_recharge So这里不能写为finance/recharge
		$base_uri = BASE_URL."/index.php/admin/finance_recharge";

		//添加编辑的uri,add这个action 是固定的
		//$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'order_id';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_order_charge')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//用户组
		$group_arr = keke_admin_class::get_user_group ();
		
		//充值订单类型
		$charge_type_arr = keke_global_class::get_charge_type();
		
		//充值类型
		$bank_arr = keke_global_class::get_bank();
		//充值订单状态
		$status_arr = keke_order_class::get_order_status();
		//线下支付方式
		//$offline_pay=Keke::get_table_data ( "*", "witkey_pay_api", " type='offline'", '', '', '', 'payment' );
		$offline_pay=DB::select()->from('witkey_pay_api')->where("type='offline'")->execute(); 
		$offline_pay= Keke::get_arr_by_key($offline_pay,'payment');
		//var_dump($list_arr);die;
		require Keke_tpl::template('control/admin/tpl/finance/recharge');

	}
	/**
	 * 订单记录的删除,支持单与多删除
	 */
	function action_del(){
		//删除单条,这里的file_id 是在模板上的请求连接中有的
		if($_GET['order_id']){
			$where = 'order_id = '.$_GET['order_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'order_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_order_charge')->setWhere($where)->del();

	}
	
	/**
	 * 审核充值订单
	 */
	function action_update(){
		global $_lang;
		$array = array(
					'order_status'=>'ok'
				);
		$page = $_GET['page'];
		if($_GET['order_id']){
			$where = 'order_id = '.$_GET['order_id'];
			//获取充值信息
// 			$order_info = Model::factory("witkey_order_charge")->setData($array)->setWhere($where)->query();
			
			//DB::update('witkey_order_charge')->set($columns)->value($values)->where($where)->execute();
			$order_info = DB::select()->from('witkey_order_charge')->where($where)->execute();
			$order_info = $order_info[0];
			
			if ($order_info [order_status] == 'ok'){
				Keke::admin_show_msg($_lang['payment_has_been_success_no_need_repeat'], BASE_URL.'index.php/admin/finance_recharge',3,'','warning');
			}
			//用户信息
			//$user_info = keke_user_class::get_user_info($order_info ['uid']);
			//充值状态
			//Model::factory("witkey_order_charge")->setData($array)->setWhere($where)->update();
			//充值
			keke_finance_class::cash_in($order_info['uid'], $order_info['pay_money'],0,'offline_charge','','offline_charge');
			//发送站内信给用户
			//keke_msg_class::send_private_message('充值成功', '您充值了'.$order_info['pay_money'], $order_info['uid'], $order_info['username']);
			//充值日志
			Keke::admin_system_log ( $_lang['confirm_payment_recharge'].$_GET['order_id']);
			//成功跳转提示
			Keke::show_msg('付款成功',BASE_URL.'/index.php/admin/finance_recharge','success');
		}
	}

}

// Keke::admin_check_role (76 );

// $recharge_obj = new Keke_witkey_order_charge_class(); //实例化充值表对象
// $page_obj = $Keke->_page_obj; //实例化分页对象
// $charge_type_arr=keke_glob_class::get_charge_type();/*充值订单类型*/
// $status_arr = keke_order_class::get_order_status();
// $offline_pay=Keke::get_table_data ( "*", "witkey_pay_api", " type='offline'", '', '', '', 'payment' ); //线下支付方式
// //var_dump($offline_pay);
// //分页
// $w [page_size] and $page_size = intval ( $w [page_size] ) or $page_size =10;
// intval ( $page ) or $page = '1';
// $url = "index.php?do=$do&view=$view&w[order_status]=$w[order_status]&w[order_id]=$w[order_id]&w[order_type]=$w[order_type]&w[username]=$w[username]&w[page_size]=$page_size&w[ord]=$w[ord]&page=$page";

// $bank_arr     = keke_glob_class::get_bank();
// if (isset ( $ac )) { //处理财务清单申请
// $order_info=Dbfactory::get_one(" select * from ".TABLEPRE."witkey_order_charge where order_id = ".intval($order_id));
// //邮件
// $message_obj = new keke_msg_class ();
// $order_info or Keke::admin_show_msg ( $_lang['charge_num_not_exist'], $url,3,'','warning');
// 	switch ($ac) {
// 		case 'pass' : //审核充值订单
// 				if ($order_info [order_status] == 'ok') {
 
// 					Keke::admin_show_msg ( $_lang['payment_has_been_success_no_need_repeat'], $url,3,'','warning');
 
// 				}
// 				$recharge_obj->setWhere ( 'order_id =' . $order_id );
// 				$recharge_obj->setOrder_status('ok' );//充值审核通过
// 				$res = $recharge_obj->edit_keke_witkey_order_charge();
// 				$user_info = Keke::get_user_info ( $order_info [uid] );
// 				/** 通知用户*/ 
// 				$v_arr = array ($_lang['charge_amount'] => $order_info['pay_money']);
// 				keke_shop_class::notify_user ( $user_info[uid], $user_info[username], "pay_success", $_lang['line_recharge_success'], $v_arr );
// 					/*新增财务记录*/
// 				keke_finance_class::cash_in($user_info['uid'], $order_info['pay_money'],0,'offline_charge','','offline_charge');
				
// 				Keke::admin_system_log ( $_lang['confirm_payment_recharge'].$order_id);
// 				Keke::admin_show_msg ( $_lang['message_about_recharge_success'], $url,3,'','success' );
// 		break;
// 	//删除充值订单
// 	case 'del' :
// 			$recharge_obj->setWhere ( ' order_id=' . $order_id );
// 			$res = $recharge_obj->del_keke_witkey_order_charge();
			 
// 			$user_info = Keke::get_user_info ( $order_info [uid] );
// 			$v = array ($_lang['recharge_single_num'] => $order_id,$_lang['recharge_cash'] => $order_info [pay_money] );
// 			$message_obj->send_message ( $user_info ['uid'], $user_info ['username'], 'recharge_fail', $_lang['recharge_fail'], $v, $user_info [email], $user_info ['mobile'] );
				
// 			Keke::admin_system_log ( $_lang['delete_apply_forwithdraw'] . $order_id );
// 			Keke::admin_show_msg ( $_lang['message_about_delete'], $url,3,'','success' );
// 		;
// 		break;	
// 	}
 
// }elseif (isset ( $ckb )) { //批量删除
// 	$ids = implode ( ',', $ckb );
// 	if (count ( $ids )) {
	
// 		$recharge_obj->setWhere ( " order_id in ($ids) and order_status = 'wait' " );
// 		$nodraw_arr = $recharge_obj->query_keke_witkey_order_charge();	//待审核的充值记录
// 		$del_ids=array();
// 		switch ($sbt_action) {
// 			case $_lang['mulit_delete'] : //批量删除
// 				//待审核的退款处理后，
// 				foreach ( $nodraw_arr as $k=>$v ) {
// 					$del_ids[$k]=$v[order_id];
					
// 					$message_obj = new keke_msg_class ();//邮件
// 					$user_info=keke_user_class::get_user_info($v[uid]);//用户信息
// 					$v = array ($_lang['recharge_single_num'] =>$v['order_id'],$_lang['recharge_cash'] => $v [pay_money] );
// 					$message_obj->send_message ( $user_info ['uid'], $user_info ['username'], 'recharge_fail', $_lang['recharge_fail'], $v, $user_info [email], $user_info ['mobile'] );
// 				}
// 				//审核通过的直接删除
// 				$del_ids=implode(",", $del_ids);
// 				if($del_ids){
// 					$recharge_obj->setWhere ( " order_id in ($del_ids)" );
// 					$res = $recharge_obj->del_keke_witkey_order_charge();
// 					Keke::admin_system_log ( $_lang['delete_recharge_order'].$del_ids );
// 				}
// 				break;
// 		}
		
// 		if ($res) {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url,3,'','success');
// 		} else {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning');
// 		}
	
// 	} else {
// 		Keke::admin_show_msg ( $_lang['please_select_an_item_to_operate'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 	}

// } else {
// 	$where = ' 1 = 1 '; //默认查询条件
// 	$w ['order_id'] and $where .= " and order_id = '$w[order_id]' ";
// 	$w ['order_type'] and $where .= " and order_type = '$w[order_type]'";
// 	$w ['order_status'] and $where .= " and order_status = '$w[order_status]' ";
// 	$w ['username'] and $where .= " and username like '%$w[username]%' ";

// 	is_array($w['ord']) and $where .= ' order by '.$w['ord'][0].' '.$w['ord'][1] or $where.=' order by order_id desc' ;
	
// 	//$w ['ord'] and $where .= " order by $w[ord]" or $where .= "order by pay_time desc ";
// 	//查询统计
// 	$recharge_obj->setWhere ( $where );
// 	$count = $recharge_obj->count_keke_witkey_order_charge();
// 	$page_obj->setAjax(1);
// 	$page_obj->setAjaxDom("ajax_dom");
// 	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );

// 	//查询结果数组
// 	$recharge_obj->setWhere ( $where . $pages [where] );
// 	$recharge_arr = $recharge_obj->query_keke_witkey_order_charge();
// }

// require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
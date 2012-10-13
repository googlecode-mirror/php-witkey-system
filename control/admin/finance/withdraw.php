<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 财务--提现审核
 * @copyright keke-tech
 * @author fu
 * @version v 22
 * 2012-10-9 15:18:30
 */
class Control_admin_finance_withdraw extends Controller{

	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;

		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `withdraw_id`,`pay_username`,`username`,`pay_account`,`pay_type`,`withdraw_cash`,`withdraw_status` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('withdraw_id'=>$_lang['financial_id'],'username'=>$_lang['username']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//tool本来是一个目录，由于没有定义tool为目录的路由,所以这个控制层的文件来too_file So这里不能写为tool/file
		$base_uri = BASE_URL."/index.php/admin/finance_withdraw";

		//添加编辑的uri,add这个action 是固定的
		//$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'withdraw_id';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_withdraw')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//用户组
		$group_arr = keke_admin_class::get_user_group ();

		//充值订单类型
		$charge_type_arr = keke_global_class::get_charge_type();

		//提现类型
		$bank_arr = keke_global_class::get_bank();
		
		//提现状态
		$status_arr = keke_global_class::withdraw_status();
		
		$paytype_list = Keke::get_table_data ( "payment,config", "witkey_pay_api", " type!='trust'", "", "", "", "payment" );
		//var_dump($bank_arr);die;
		require Keke_tpl::template('control/admin/tpl/finance/withdraw');

	}
	/**
	 * 订单记录的删除,支持单与多删除
	 */
	function action_del(){
		//删除单条,这里的file_id 是在模板上的请求连接中有的
		if($_GET['withdraw_id']){
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			echo  Model::factory('witkey_withdraw')->setWhere($where)->del();
			
		}
		if($_GET['ids']){
			$ids = $_GET['ids'];
			if (count ( $_GET['ids'] )) {
						//待审核的提出记录
						//$nodraw_arr = Model::factory("witkey_withdraw")->setWhere(" withdraw_id in ('$ids') and withdraw_status =1 ")->query();
						$nodraw_arr = db::select('*')->from("witkey_withdraw")->where("withdraw_id in ('$ids') and withdraw_status =1")->execute();
						$nodraw_arr = $nodraw_arr[0];
						//var_dump($nodraw_arr);die;
						switch ($sbt_action) {
							case $_lang['mulit_nopass']: //批量退款
								//待审核的退款处理后，
								
								foreach ( $nodraw_arr as $v ) {
									$withdraw_id = $v ['withdraw_id'];
									$where = "withdraw_id = '$withdraw_id' ";
									$withdraw_info = db::select('*')->from("witkey_withdraw")->where($where)->execute();
									$withdraw_info = $withdraw_info[0];
									$withdraw_cash = $withdraw_info ['withdraw_cash'];
									$uid = $withdraw_info ['uid'];
									$username = $withdraw_info ['username'];
									$pay_way = array_merge(keke_global_class::get_bank(),keke_global_class::get_online_pay());
					
									$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
									keke_finance_class::init_mem('withdraw_fail', $data);
									keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );
								}
								//审核通过的批量退款
								Model::factory("witkey_withdraw")->setData(array('withdraw_status'=>'3'))->setWhere('withdraw_id in ('.$_GET['ids'].')')->update();
								Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $ids );
								break;
							case $_lang['mulit_review']: //批量审核
								//字段数组
								$array = array(
											'withdraw_status'=>'2',
											'process_uid'=>$admin_info ['uid'],
										'process_username'=>$admin_info ['username'],
										'process_uid'=>$admin_info ['uid'],
										'process_time'=>time(),
										);
								//更新操作状态
								Model::factory("witkey_withdraw")->setData($array)->setWhere(' withdraw_id in (' . $ids . ') ')->update();
								$withdraw_arr = db::select('*')->from("witkey_withdraw")->where("withdraw_id in ('$ids') and withdraw_status =1")->execute();
								$withdraw_arr = $withdraw_arr[0];
								foreach ( $withdraw_arr as $withdraw_info ) {
									$withdraw_id = $withdraw_info ['withdraw_id'];
									/*更新手续费*/
									if(in_array($withdraw_id,$ids)){
										$fee = $withdraw_info['withdraw_cash'] - keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']);
										Dbfactory::execute(sprintf(' update %switkey_withdraw set fee=%.2f where withdraw_id=%d',TABLEPRE,$fee,$withdraw_id));
									}
									if ($withdraw_info ['withdraw_status'] != 1) {
										continue;
									}
					
	
								}
			
								Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $ids );
								break;
			
						}
			
						if ($res) {
							Keke::admin_show_msg ( $_lang['mulit_operate_success'], BASE_URL."/index.php/admin/finance_withdraw/index",3,'','success');
						} else {
							Keke::admin_show_msg ( $_lang['mulit_operate_fail'], BASE_URL."/index.php/admin/finance_withdraw/index",3,'','warning');
						}
			
					} else {
						Keke::admin_show_msg ( $_lang['choose_operate_item'], BASE_URL."/index.php/admin/finance_withdraw/index",3,'','warning' );
					}
		}
		
		

	}

	//提现信息浏览
	function action_info(){
		global $_K,$_lang;
		if($_GET['withdraw_id']){
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			$withdraw_info = db::select('*')->from("witkey_withdraw")->where($where)->execute();
			$withdraw_info = $withdraw_info[0];
			$bank_arr = keke_global_class::get_bank();
			$status_arr = keke_global_class::withdraw_status();
			$k_arr   = array_keys($bank_arr);
			require Keke_tpl::template('control/admin/tpl/finance/withdraw_info');
		}
	}
	
	//提现审核通过
	function action_pass(){
		if ($_GET['withdraw_id']) {
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			//获取提现信息
			$withdraw_info = db::select('*')->from("witkey_withdraw")->where($where)->execute();
			$withdraw_info = $withdraw_info[0];
		
			//提现金额
			$w_cash = $withdraw_info['withdraw_cash'];
			//实际得到金额
			$e_cash = Keke_finance::get_to_cash($w_cash);
			//提现手续费
			$site_in = $w_cash - $e_cash;
			//操作字段数组
			$array = array(
					'withdraw_status'=>'2',
					'process_uid'=>'1',
					'process_username'=>'admin',
					'process_time'=>time(),
					'fee'=>$site_in
			);
			//更新审核通过的状态
			$res = Model::factory("witkey_withdraw")->setData($array)->setWhere($where)->update();
			//提现金额
			
			//::cash_in($uid, $cash, $action);
			Keke_finance::cash_out($withdraw_info['uid'], $withdraw_info[''], 'withdraw');
			$tar_content = "帐户:".$withdraw_info['pay_account']." 提现成功！提现金额为：".$withdraw_info['withdraw_cash'].",实际获得：".keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']); //提现内容
			//站内信
			
			Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $withdraw_id );
			$res and Keke::show_msg('系统提示',"admin/finance_withdraw/index",3,'提交成功','success');
		}
		
	}
	
	//不通过审核
	function action_nopass(){
		$array = array(
				'withdraw_status'=>'3',
				'process_uid'=>'1',
				'process_username'=>'admin',
				'process_time'=>time(),
		);
		if($_GET['withdraw_id']){
			
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			
			Model::factory("witkey_withdraw")->setData($array)->setWhere($where)->update();//审核状态更新操作
			//提现金额
			$withdraw_cash = $withdraw_info ['withdraw_cash'];
			$uid = $withdraw_info  ['uid']; //提现uid
			$username = $withdraw_info  ['username'];//提现用户名
			$pay_way = array_merge(keke_global_class::get_bank(),keke_global_class::get_online_pay());
			$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
			keke_finance_class::init_mem('withdraw_fail', $data);
			keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );
			$tar_content  = "帐户:".$withdraw_info['pay_account']." 提现失败！"; //提现内容
			keke_msg_class::send_private_message($_lang['fail_and_check_you_account'], $tar_content, $uid, $username);
			Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $withdraw_id );
			Keke::show_msg ( $_lang['delete_audit_withdraw_success'], 'admin/finance_withdraw','success' );
		}else{
			Keke::show_msg('记录不存在','admin/finance_withdraw','warning');
		}
	}
}



// Keke::admin_check_role ( 5 );
// $withdraw_obj = new Keke_witkey_withdraw_class (); //实例化提现表对象
// $user_space_obj = new Keke_witkey_space_class (); //实例化用户信息表对象
// $page_obj = $Keke->_page_obj; //实例化分页对象
// $paytype_list = Keke::get_table_data ( "payment,config", "witkey_pay_api", " type!='trust'", "", "", "", "payment" );
// $status_arr  = keke_glob_class::withdraw_status();
// $bank_arr = keke_glob_class::get_bank();
// //分页
// $w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size = 10;
// $page and $page = intval ( $page ) or $page = '1';
// $url = "index.php?do=$do&view=$view&w[pay_type]=".$w['pay_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

// $withdraw_id and $withdraw_info = Dbfactory::get_one ( "select * from " . TABLEPRE . "witkey_withdraw where withdraw_id = '$withdraw_id'" );
// if (isset ( $ac )) { //处理财务清单申请
// 	switch ($ac) {
// 		case 'pass' : //审核提现申请
// 			if ($withdraw_info['withdraw_status']) {
// 				if ($is_submit) {
					
// 					$user_space_info = Keke::get_user_info ( $withdraw_info ['uid'] );
					
// 					if ($withdraw_info ['withdraw_status'] != 1) {
// 						Keke::admin_show_msg ( $_lang['no_need_to_repeat'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 					}
					
// 					$withdraw_obj->setWhere ( 'withdraw_id=' . $withdraw_id );
// 					//提现审核通过
// 					$withdraw_obj->setWithdraw_status ( 2 );
// 					$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 					$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 					$withdraw_obj->setProcess_time ( time () );
// 					$fee = $withdraw_info['withdraw_cash']-keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']);
// 					$withdraw_obj->setFee($fee);
// 					$res = $withdraw_obj->edit_keke_witkey_withdraw ();
					
// 					$feed_arr = array ("feed_username" => array ("content" => $withdraw_info ['username'],
// 					 "url" => "index.php?do=space&member_id=".$user_space_info['uid']),
// 					 "action" => array ("content" => $_lang['withdraw'], "url" => "" )
// 					, "event" => array ("content" => $_lang['withdraw_le'] . $withdraw_info ['withdraw_cash'] . $_lang['yuan'], "url" => "" )
// 					 );
// 					Keke::save_feed ( $feed_arr, $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
					
// 					//Keke::feed_add ( '<a href="index.php?do=space&member_id=' . $user_space_info ['uid'] . '" target="_blank">' . $withdraw_info ['username'] . '</a>成功提现了' . $withdraw_info ['withdraw_cash'] . $_lang['yuan'], $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
// 					//邮件
// 					/* $message_obj = new keke_msg_class ();
// 					$t_userinfo = Dbfactory::get_one ( " select mobile,email from " . TABLEPRE . "witkey_space where uid ='".$withdraw_info['uid']."'" );
// 					$v = array ($_lang['withdraw_cash'] => $withdraw_info ['withdraw_cash'],$_lang['account_msg']=>$withdraw_info['pay_account'] );
// 					$message_obj->send_message ( $withdraw_info ['uid'], $withdraw_info ['username'], 'draw_success', $_lang['withdraw_success'], $v, $t_userinfo ['email'], $t_userinfo ['mobile'] );
// 					 */
// 					$v_arr = array('网站名称'=>$_K['sitename'],'提现方式'=>$pay_way[$withdraw_info['pay_type']],'帐户'=>$withdraw_info['pay_account'],'提现金额'=>$withdraw_info['withdraw_cash']);
// 					keke_msg_class::notify_user( $withdraw_info ['uid'] , $withdraw_info ['username'] ,'draw_success',$_lang['withdraw_success'],$v_arr);
					 
					
// 					//$space_info = Keke::get_user_info ( intval ( $withdraw_info ['uid'] ) );
					
// 					//	Keke::update_score_value ( $withdraw_info ['uid'], 'withdraw', 2 );
// 					Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $withdraw_id );
// 					Keke::admin_show_msg ( $_lang['audit_withdraw_pass'], 'index.php?do=' . $do . '&view=' . $view,3,'','success');
// 				}else{
// 					$bank_arr=keke_glob_class::get_bank();
// 					$k_arr   = array_keys($bank_arr);
// 				}
// 				require $template_obj->template ( 'control/admin/tpl/admin_finance_withdraw_info' );
// 				die ();
// 			} else {
// 				Keke::admin_show_msg ( $_lang['audit_withdraw_not_exist'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 			}
// 			;
// 			break;
// 		//拒绝通过并,删除提现申请
// 		case 'nopass' :
// 			if ($withdraw_info) {
// 				$withdraw_obj->setWhere ( 'withdraw_id=' . $withdraw_id );
// 				$withdraw_obj->setWithdraw_status (3);
// 				$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 				$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 				$withdraw_obj->setProcess_time ( time () );
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw();
// 				//现提金额
// 				$withdraw_cash = $withdraw_info ['withdraw_cash'];
// 				$uid = $withdraw_info  ['uid'];
// 				$username = $withdraw_info  ['username'];
				
// // 				$user_info = keke_user_class::get_user_info($uid);

// 				//提现所在的银行名称或者工具名称，如建设银行、支付宝等
// 				$pay_way = array_merge(keke_glob_class::get_bank(),keke_glob_class::get_online_pay());
// 				$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
// 				keke_finance_class::init_mem('withdraw_fail', $data);
// 				keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );

				
// 				$v_arr = array('网站名称'=>$_K['sitename'],'提现方式'=>$pay_way[$withdraw_info['pay_type']],'帐户'=>$withdraw_info['pay_account'],'提现金额'=>$withdraw_info['withdraw_cash']);
// 	         	keke_msg_class::notify_user($uid,$username,'withdraw_fail',$_lang['fail_and_check_you_account'],$v_arr);
	           
// 				Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $withdraw_id );
// 				Keke::admin_show_msg ( $_lang['delete_audit_withdraw_success'], 'index.php?do=' . $do . '&view=' . $view,3,'','success' );
// 			} else {
// 				Keke::admin_show_msg ( $_lang['fail_item_not_exist'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 			}
// 			;
// 			break;
	
// 	}
// } elseif (isset ( $ckb )) { //批量删除
// 	$ids = implode ( ',', $ckb );
// 	if (count ( $ids )) {
// 		//待审核的提出记录
// 		$withdraw_obj->setWhere ( " withdraw_id in ('$ids') and withdraw_status =1 " );
// 		$nodraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();		
// 		$withdraw_obj->setWhere ( ' withdraw_id in (' . $ids . ') ' );				
// 		switch ($sbt_action) {
// 			case $_lang['mulit_nopass']: //批量退款
// 				//待审核的退款处理后，
// 				foreach ( $nodraw_arr as $v ) {
// 					$withdraw_id = $v ['withdraw_id'];
// 					$where = "withdraw_id = '$withdraw_id' ";
// 					$withdraw_info = Dbfactory::get_one ( "select * from " . TABLEPRE . "witkey_withdraw where $where" );
// 					$withdraw_cash = $withdraw_info ['withdraw_cash'];
// 					$uid = $withdraw_info ['uid'];
// 					$username = $withdraw_info ['username'];
// 					$pay_way = array_merge(keke_glob_class::get_bank(),keke_glob_class::get_online_pay());
					
// 					$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
// 					keke_finance_class::init_mem('withdraw_fail', $data);
// 					keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );

					
// 					$v_arr = array('网站名称'=>$_K['sitename'],'提现方式'=>$pay_way[$withdraw_info['pay_type']],'帐户'=>$withdraw_info['pay_account'],'提现金额'=>$withdraw_cash);
// 					keke_msg_class::notify_user($uid,$username,'withdraw_fail',$_lang['fail_and_check_you_account'],$v_arr);
	                
// 				}
// 				//审核通过的批量退款
// 				$withdraw_obj->setWithdraw_status (3);
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw();
// 				Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $ids );
// 				break;
// 			case $_lang['mulit_review']: //批量审核
// 				$withdraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();
// 				$withdraw_obj->setWhere ( ' withdraw_id in (' . $ids . ') ' );
// 				$withdraw_obj->setWithdraw_status ( 2);
// 				$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 				$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 				$withdraw_obj->setProcess_time ( time () );
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw ();
				
// 				foreach ( $withdraw_arr as $withdraw_info ) {
// 					$withdraw_id = $withdraw_info ['withdraw_id'];
// 					/*更新手续费*/
// 					if(in_array($withdraw_id,$ids)){
// 						$fee = $withdraw_info['withdraw_cash'] - keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']);
// 						Dbfactory::execute(sprintf(' update %switkey_withdraw set fee=%.2f where withdraw_id=%d',TABLEPRE,$fee,$withdraw_id));
// 					}
// 					if ($withdraw_info ['withdraw_status'] != 1) {
// 						continue;
// 					}
					
// 					$v_arr = array('网站名称'=>$_K['sitename'],'提现方式'=>$pay_way[$withdraw_info['pay_type']],'帐户'=>$withdraw_info['pay_account'],'提现金额'=>$withdraw_cash);
// 					keke_msg_class::notify_user($withdraw_info ['uid'],$withdraw_info ['username'],'draw_success',$_lang['withdraw_success'],$v_arr);
					
// 					$feed_arr = array ("feed_username" => array ("content" => $withdraw_info ['username'], "url" => "index.php?do=space&member_id=".$space_info['uid']), "action" => array ("content" => $_lang['withdraw'], "url" => "" ), "event" => array ("content" =>$_lang['withdraw_le'].$withdraw_info['withdraw_cash']. $_lang['yuan'],"url" => "" ) );
// 					Keke::save_feed ( $feed_arr, $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
				
// 				}
				
// 				Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $ids );
// 				break;
		
// 		}
		
// 		if ($res) {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_success'], 'index.php?do=' . $do . '&view=' . $view,3,'','success');
// 		} else {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], 'index.php?do=' . $do . '&view=' . $view ,3,'','warning');
// 		}
	
// 	} else {
// 		Keke::admin_show_msg ( $_lang['choose_operate_item'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 	}

// } elseif ($type == 'batch' && $pay_type == 'alipayjs') {
// 	$payment_config = Keke::get_payment_config('alipayjs');
// 	require S_ROOT . "/payment/alipayjs/order.php";
// 	$detail_data = Dbfactory::query ( sprintf ( " select withdraw_id,pay_account,pay_username,withdraw_cash fee,uid from %switkey_withdraw where withdraw_id in (%s) and withdraw_status='1'", TABLEPRE, $ids ) );
// 	echo get_batch_url ($payment_config, $detail_data,'url');
// 	die();
// } else {
// 	$where = ' 1 = 1 '; //默认查询条件
// 	$w ['withdraw_id'] and $where .= " and withdraw_id = '".$w['withdraw_id']."' ";
// 	$w ['username'] and $where .= " and username like '%".$w['username']."%' ";
// 	$w ['pay_type'] and $where .= " and pay_type = '".$w['pay_type']."' ";

// 	is_array($w['ord']) and $where .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'] or $where .= "order by withdraw_id desc";
	
// 	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= "order by withdraw_id desc ";
// 	//查询统计
// 	$withdraw_obj->setWhere ( $where );
// 	$count = $withdraw_obj->count_keke_witkey_withdraw ();
// 	$page_obj->setAjax(1);
// 	$page_obj->setAjaxDom("ajax_dom");
// 	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );
// 	//查询结果数组
// 	$withdraw_obj->setWhere ( $where . $pages ['where'] );
// 	$withdraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();
// }

// require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
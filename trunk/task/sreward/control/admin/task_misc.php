<?php
/**
 * 任务杂项
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$page = max ( $page, 1 );
$limit = max ( $limit, 5 );
$url = 'index.php?do=' . $do . '&model_id=' . $model_id . '&view=edit&task_id=' . $task_id . '&op=' . $op;
switch ($op) {
	case 'work' : //稿件
		if ($ac && $work_id) {
			switch ($ac) {
				case 'del' : //删除
					$res = db_factory::execute ( sprintf ( 'delete  from %switkey_task_work where work_id=%d', TABLEPRE, intval ( $work_id ) ) );
					if ($res) {
						keke_file_class::del_obj_file(intval ( $work_id ),'work',true);
						db_factory::execute ( sprintf ( ' delete from %switkey_comment where obj_id=%d', TABLEPRE, intval ( $work_id ) ) );
					}
					$res and kekezu::echojson ( '', 1 ) or kekezu::echojson ( '', 0 );
					die ();
					break;
				case 'file' : //附件
					$f_list = db_factory::query ( sprintf ( ' select a.file_id,a.file_name,a.save_name from %switkey_file a 
							left join %switkey_task_work b on a.file_id in (b.work_file) where b.work_id=%d and b.work_file is not null ', TABLEPRE, TABLEPRE, $work_id ) );
					break;
				case 'comm' : //留言
					$c_list = db_factory::query ( sprintf ( ' select a.content,a.on_time from %switkey_comment a 
						left join %switkey_task_work b on a.obj_id=b.work_id where b.work_id=%d', TABLEPRE, TABLEPRE, $work_id ) );
					break;
			}
			require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_edit_ext' );
			die ();
		} else {
			$o = keke_table_class::get_instance ( 'witkey_task_work' );
			$tmp = $o->get_grid ( 'task_id=' . $task_id, $url, $page, $limit, ' order by work_status desc,work_time desc ', 1, 'ajax_dom' );
			$list = $tmp ['data'];
			$pages = $tmp ['pages'];
			$satus_arr = sreward_task_class::get_work_status ();
		}
		break;
	case 'comm' : //留言
		if ($ac && $comm_id) {
			$id = intval ( $comm_id );
			switch ($ac) {
				case 'del' : //删除留言
					$sql = ' delete from %switkey_comment where comment_id=%d';
					$type == 1 and $sql .= ' or p_id=%d'; //删除顶级留言，将相应楼层也删除
					$res = db_factory::execute ( sprintf ( $sql, TABLEPRE, $id, $id ) );
					$res and kekezu::echojson ( '', 1 ) or kekezu::echojson ( '', 0 );
					die ();
					break;
				case 'load' : //加载楼层
					$list = db_factory::query ( sprintf ( ' select * from %switkey_comment where p_id=%d', TABLEPRE, $id ) );
					require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_edit_ext' );
					die ();
					break;
			}
		} else {
			$o = keke_table_class::get_instance ( 'witkey_comment' );
			$tmp = $o->get_grid ( 'obj_id=' . $task_id . ' and p_id=0', $url, $page, $limit, ' order by on_time desc ', 1, 'ajax_dom' );
			$list = $tmp ['data'];
			$pages = $tmp ['pages'];
		}
		break;
	case 'mark' : //互评
		$list = db_factory::query ( sprintf ( " select * from %switkey_mark where origin_id=%d and `mark_status`!=0 and model_code='%s'", TABLEPRE, $task_id, $model_info ['model_code'] ) );
		
		break;
	case 'agree' : //交付
		keke_lang_class::loadlang ( 'task_agreement', 'task_sreward' );
		$id = db_factory::get_count ( sprintf ( ' select agree_id from %switkey_agreement where task_id=%d', TABLEPRE, $task_id ) );
		$o = sreward_task_agreement::get_instance ( $id );
		$agree_info = $o->_agree_info; //协议内容
		$buyer_contact = $o->_buyer_contact; //买家联系方式
		$buyer_status_arr = $o->get_buyer_status (); //买家协议状态
		$seller_contact = $o->_seller_contact; //卖家联系方式
		$seller_status_arr = $o->get_seller_status (); //卖家协议状态
		$buyer_uid = $o->_buyer_uid; //买家编号
		$seller_uid = $o->_seller_uid; //卖家编号
		$buyer_username = $o->_buyer_username; //买家(雇主)姓名
		$seller_username = $o->_seller_username; //买家(雇主)姓名
		$agree_status = $o->_agree_status; //协议状态
		$buyer_status = $o->_buyer_status; //买家状态
		$seller_status = $o->_seller_status; //卖家状态
		$status_arr = $o->get_agreement_status ();
		$r = db_factory::get_count ( sprintf ( ' select report_id from %switkey_report where origin_id=%d and report_type=1 and report_status!=4 limit 0,1', TABLEPRE, $task_id ) );
		break;
}
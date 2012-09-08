<?php
/**
 * ÈÎÎñÔÓÏî
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$page = max ( $page, 1 );
$limit = max ( $limit, 5 );
$url = 'index.php?do=' . $do . '&model_id=' . $model_id . '&view=edit&task_id=' . $task_id . '&op=' . $op;
switch ($op) {
	case 'work' : //¸å¼þ
		if ($ac && $work_id) {
			switch ($ac) {
				case 'del' : //É¾³ý
					$res = db_factory::execute ( sprintf ( 'delete  from %switkey_task_taobao_work where work_id=%d', TABLEPRE, intval ( $work_id ) ) );
					$res_taobao = db_factory::execute(sprintf('delete from %switkey_task_work where work_id=%d',TABLEPRE,intval($work_id)));
					$res = ($res&&$res_taobao);
					if ($res) {
						db_factory::execute ( sprintf ( ' delete from %switkey_comment where obj_id=%d', TABLEPRE, intval ( $work_id ) ) );
					}
					$res and kekezu::echojson ( '', 1 ) or kekezu::echojson ( '', 0 );
					die ();
					break;
				case 'comm' : //ÁôÑÔ
					$c_list = db_factory::query ( sprintf ( ' select a.content,a.on_time from %switkey_comment a 
						left join %switkey_task_taobao_work b on a.obj_id=b.work_id where b.work_id=%d', TABLEPRE, TABLEPRE, $work_id ) );
					break;
			}
			require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_edit_ext' );
			die ();
		} else {
			//AJAX·ÖÒ³ÇëÇó
			$page or $page = 1;
			$page_size or $page_size=10;
			$page_obj = $kekezu->_page_obj;
			$page_obj->setAjax(1);
			$page_obj->setAjaxDom('ajax_dom');
			$sql = sprintf("select w_basic.*, w_taobao.`wb_type`,w_taobao.`wb_url`,w_taobao.`click_num` from %switkey_task_work w_basic left join %switkey_task_taobao_work w_taobao on w_basic.`task_id`=w_taobao.`task_id` where w_basic.task_id=%d",TABLEPRE,TABLEPRE,$task_id);
			$tmp = db_factory::query($sql);
			$pages = $page_obj->page_by_arr($tmp, $page_size, $page, $url);
			$list = $pages ['data'];
			$satus_arr = taobao_task_class::get_work_status ();
			$platform_arr = keke_glob_class::get_oauth_type();//Æ½Ì¨Êý×é
		}
		break;
	case 'comm' : //ÁôÑÔ
		if ($ac && $comm_id) {
			$id = intval ( $comm_id );
			switch ($ac) {
				case 'del' : //É¾³ýÁôÑÔ
					$sql = ' delete from %switkey_comment where comment_id=%d';
					$type == 1 and $sql .= ' or p_id=%d'; //É¾³ý¶¥¼¶ÁôÑÔ£¬½«ÏàÓ¦Â¥²ãÒ²É¾³ý
					$res = db_factory::execute ( sprintf ( $sql, TABLEPRE, $id, $id ) );
					$res and kekezu::echojson ( '', 1 ) or kekezu::echojson ( '', 0 );
					die ();
					break;
				case 'load' : //¼ÓÔØÂ¥²ã
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
	case 'mark' : //»¥ÆÀ
		$list = db_factory::query ( sprintf ( " select * from %switkey_mark where origin_id=%d and `mark_status`!=0 and model_code='%s'", TABLEPRE, $task_id, $model_info ['model_code'] ) );
		
		break;
}
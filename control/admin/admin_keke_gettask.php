<?php
/**
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * 2012-2-17
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role ( 135 );
include S_ROOT . '/keke_client/keke/config.php';
$task_data_dir = S_ROOT . 'keke_client/keke/task_list.txt';
if (realpath ( $task_data_dir )) {
	$task_list = file_get_contents ( $task_data_dir );
	$task_list = unserialize ( $task_list );
}
if ($ajax && $pid) { //ajax选择行业
	$option_str = get_indus ( intval ( $pid ) );
	$options = kekezu::echojson ( '', $option_str ? '1' : '0', $option_str );
	die ();
}
if ($ajax && $ajax == 'modify_title') {
	if (! isset ( $t_key ) || ! isset ( $t_index ) || ! isset ( $t_value )) {
		die ();
	}
	if ($task_list [$t_index] ['keke_task_id'] == $t_key) {
		if (strtolower ( CHARSET ) != 'utf-8') { //转码
			$t_value = kekezu::utftogbk ( $t_value );
		}
		$task_list [$t_index] ['task_title'] = $t_value;
		file_put_contents ( $task_data_dir, serialize ( $task_list ) );
	}
	die ();
}
if (isset ( $sbt_action ) || isset ( $add, $add_index, $add_id )) { //写库
	if ($task_list [intval ( $add_index )] && $task_list [intval ( $add_index )] ['keke_task_id'] == intval ( $add_id )) { //添加单条记录
		$task_list_remain = $task_list;
		unset ( $task_list_remain [intval ( $add_index )] ); //剩余的task_list
		$task_add = $task_list [intval ( $add_index )]; //将要添加的union_task
		unset ( $task_list );
		$task_list [] = $task_add;
	}
	$sql = "insert into %switkey_task (`model_id`,`r_task_id`,`task_union`,`task_title`,`task_desc`,`task_cash`,`task_status`,`start_time`,`sub_time`,`indus_id`,`indus_pid`,`task_cash_coverage`) values ";
	$indus_pid = intval ( $p_indus_select );
	$indus_id = intval ( $s_indus_select );
	while ( list ( $key, $value ) = each ( $task_list ) ) {
		$tmode = explode('-',$value['task_id']);
		$model_id = db_factory::get_count(' select model_id from '.TABLEPRE.'witkey_model where model_code="'.$tmode[0].'"');
		$sql .= '('.intval($model_id) .','. intval ( $value ['keke_task_id'] );
		$sql .= ',2,"' . kekezu::k_input($value ['task_title']) . '","' . kekezu::k_input($value ['task_desc']) . '",' . floatval ( $value ['task_cash'] ) . ',2,' . intval ( $value ['start_time'] ) . ',' . intval ( $value ['sub_time'] ) . ',' . $indus_id . ',' . $indus_pid;
		$sql .= $value ['cash_cove'] ? ',' . get_cover_id ( $value ['cash_cove'] ).'),': ',null),';
		$log_ids .= $value ['keke_task_id'] . ',';
	}
	$sql = rtrim ( $sql, ',' );
	$result = db_factory::execute ( sprintf ( $sql, TABLEPRE ) );
	kekezu::admin_system_log ( '[批量]添加联盟任务' . $result );
	if ($result) { //操作成功
		$data = array ('log_details' => rtrim ( $log_ids, ',' ) );
		keke_union_class::union_request ( 'get_task', $data ); //通知联盟
		chmod ( $task_data_dir, 0777 );
		// 		var_dump($task_list_remain);die();
		! empty ( $task_list_remain ) ? file_put_contents ( $task_data_dir, serialize ( $task_list_remain ) ) : unlink ( $task_data_dir ); //单条添加,那么就重新写入数据(数据已改变),批量添加,那么将对应文件删除
		if(!empty($task_list_remain)){
			$url = '?do=keke&view=gettask&remote=1&nojump=1';
		}else{
			$url = '?do=keke&view=getlist';
		}
		kekezu::admin_show_msg ( '提示', $url, 2, '任务添加成功', 'success' );
	}
	kekezu::admin_show_msg ( '提示',$url, 2, '任务添加失败', 'warning' );
}

if (isset ( $ac )) {
	if (isset ( $index, $del_id )) { //删除对应的临时任务(在task_list.txt中)
		if ($task_list [intval ( $index )] && $task_list [intval ( $index )] ['keke_task_id'] == intval ( $del_id )) {
			unset ( $task_list [intval ( $index )] );
			file_put_contents ( $task_data_dir, serialize ( $task_list ) );
			kekezu::admin_show_msg ( '提示', '?do=keke&view=gettask', 2, '任务删除成功', 'success' );
		} else {
			kekezu::admin_show_msg ( '提示', '?do=keke&view=gettask', 2, '任务删除失败', 'warning' );
		}
	}
}

$indus_p_arr = get_indus (); //行业信息


function get_indus($pid = '0') { //将数组转换成option选项
	global $kekezu;
	! $pid && $pid = strval ( 0 );
	$indus_arr = kekezu::get_indus_by_index ( '1', $pid ); //索引行业
	$str = '';
	while ( list ( $key, $value ) = each ( $indus_arr [$pid] ) ) {
		$str .= '<option value="' . $value ['indus_id'] . '">' . $value ['indus_name'] . '</option>';
	}
	return $str;
}

function get_cover_id($price_range) {
	$cover_arr = explode ( '-', $price_range );
	if (sizeof ( $cover_arr ) < 2) {
		return false;
	}
	$start_cover = floor ( $cover_arr [0] );
	$end_cover = floor ( $cover_arr [1] );
	$sql = "select cash_rule_id from %switkey_task_cash_cove where `start_cove`<=%d and `end_cove`>=%d and `start_cove`+`end_cove`>=%d";
	$cove_id = db_factory::get_count ( sprintf ( $sql, TABLEPRE, $start_cover, $end_cover, $start_cover + $end_cover ) );
	return $cove_id;
}

require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

class Control_admin_case extends Controller{
	
	function action_index(){
		global $_K,$_lang;
		$fields = ' `case_id`,`obj_id`,`obj_type`,`case_img`,`case_title`, ';
		$fields  .= '`case_price`,`on_time`';
		
		extract($_GET);
		$count = intval($count);
		//基本uri
		$base_uri = BASE_URL."/index.php/admin/case";
		//添加编辑的uri
		$add_uri =  $base_uri.'/add';
		//删除uri
		$del_uri = $base_uri.'/del';
		
		//通过uri 记住查询条件,访止条件丢失，结果不对
		$query_uri .= "?txt_case_id=$txt_case_id";
		$query_uri .= "&txt_case_title=$txt_case_title";
		$query_uri .= "&slt_obj_type=$slt_obj_type";
		$query_uri .= "&ord[]=$ord[0]&ord[]=$ord[1]";
		$query_uri .= "&slt_page_size=$slt_page_size";
		
		//查询uri
		$uri = $base_uri.$query_uri;
		$add_uri .= $query_uri.'&page='.$page;
		$del_uri .= $query_uri.'&page='.$page;
		
		//初始化where的值
		$where = ' 1=1 ';
		if($txt_case_id){
			$where .= ' and case_id = '.$txt_case_id;
		}
		if($txt_case_title){
			$where .= " and case_title like '%$txt_case_title%' ";
		}
		//初始化order的值
		if($ord[0]){
			$order = ' order by '.$ord[0] .' '. $ord[1];
		}else{
			$order = ' order by case_id desc' ;
		}
		
		$data_info = Model::factory('witkey_case')->get_grid($fields,$where,$uri,$order,$page,$count,$slt_page_size);
		
		$case_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('control/admin/tpl/case');
	}
	function action_add(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/case_add');
		
	}
	
}

/* kekezu::admin_check_role(52);
$case_obj = new Keke_witkey_case_class ();
//分页
 
$w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size =10;
$page and $page = intval ( $page ) or $page = '1';
$url = "index.php?do=$do&view=$view&w[case_id]=".$w['case_id']."&w[art_title]=".$w['art_title']."&w[case_auther]=".
$w['case_auther']."&w[obj_type]=".$w['obj_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

if (isset ( $ac )) { //单个删除
	if ($case_id) {
		switch ($ac) {
			case "del" :
				$case_obj->setWhere ( 'case_id=' . $case_id );
				$res = $case_obj->del_keke_witkey_case ();
				kekezu::admin_system_log( $_lang['delete_case'].':' . $case_id );//日志记录
				$res and kekezu::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or kekezu::admin_show_msg ( $_lang['delete_fail'], $url,3,'','warning' );
				break; 
		}
	} else {
		kekezu::admin_show_msg ( $_lang['del_fail_select_operate'], $url );
	}
} elseif (isset ( $sbt_action )) { //批量删除
	$ckb_string = $ckb;
	is_array ( $ckb_string ) and $ckb_string = implode ( ',', $ckb_string );
	if (count ( $ckb_string )) {
		$case_obj->setWhere ( 'case_id in (' . $ckb_string . ')' );
		$res = $case_obj->del_keke_witkey_case ();//删除
		kekezu::admin_system_log($_lang['mulit_delete_case'].':' . $ckb_string );//日志记录
		$res and kekezu::admin_show_msg ( $_lang['mulit_operate_success'], $url ,3,'','success') or kekezu::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning' );
	} else
		kekezu::admin_show_msg ( $_lang['mulit_del_fail_select_operate'], $url,3,'','warning' );
} else {
	
	$model_list = kekezu::get_table_data ( '*', 'witkey_model', "model_status=1 and model_dir!='employtask'", 'listorder asc ', '', '', 'model_id', null );
	$count = $case_obj->count_keke_witkey_case();
	
//	$sql = "select *,ifnull(case_title,task_title) task_title from ".TABLEPRE."witkey_case as a left join ".TABLEPRE."witkey_task as b on a.obj_id = b.task_id ";
	$sql = "select * from ".TABLEPRE."witkey_case";
	$where = ' where 1 = 1'; //查询
	//条件
	$w ['case_id'] and $where .= " and case_id = '".$w['case_id']."' ";
	$w ['art_title'] and $where .= " and case_title like '%".$w['art_title']."%' ";
	$w ['case_auther'] and $where .= " and case_auther like '%".$w['case_auther']."%' ";
	$w ['obj_type'] and $where .= " and obj_type = '".$w['obj_type']."' ";
	
	$order_where = " order by on_time desc";
	is_array($w['ord']) and $order_where= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	$where=$where.$order_where;
	
	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= " order by case_id desc";//排序
	$kekezu->_page_obj->setAjax(1);
	$kekezu->_page_obj->setAjaxDom("ajax_dom");
	$pages = $kekezu->_page_obj->getPages ( $count, $page_size, $page, $url );
	$sql.=$where.$pages['where'];
	$case_arr =db_factory::query($sql);

}


require Keke_tpl::template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
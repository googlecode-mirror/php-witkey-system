<?php
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08下午02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');

$ops = array('inbox','outbox','send');
$opps= array('system','inbox');
 
in_array($op,$ops) or $op ="inbox";
in_array($opp, $opps) or $opp = "system";

intval($page) or $page = 1;
$url_str = "index.php?do=$do&view=$view&op=$op&opp=$opp";
$msg_obj = keke_table_class::get_instance("witkey_msg");
 
/**
 * 子集菜单
 */
$sub_nav=array(
			array("send"=>array( $_lang['write_message'],"doc-edit")),
			array("inbox"=>array( $_lang['inbox'],"contact-card"),
			   "outbox"=>array( $_lang['outbox'],"cc")));  
/*删除动作*/
if($ac=='del'&&$msg_id&&$op=='inbox'){
    $res = $msg_obj->del("msg_id", intval($msg_id));
    $res and Keke::show_msg( $_lang['delete_success'],$url_str."&page=$page",3,'','success') or Keke::show_msg( $_lang['delete_fail'],$url_str."&page=$page",3,"","warning");
}elseif($ckb){
   $res = $msg_obj->del("msg_id", array_filter($ckb));
   $res and Keke::show_msg( $_lang['delete_selected_success'],$url_str."&page=$page",3,'','success') or Keke::show_msg( $_lang['select_null_for_delete'],$url_str."&page=$page",3,"","warning") ;
}elseif($ac=='view'){
	$msg  = $msg_obj->get_table_info("msg_id", $msg_id);	
	if ($msg['uid']!=$uid&&$msg['to_uid']!=$uid){
		Keke::show_msg( $_lang['message_does_not_exist'],$url_str,3,"","warning");
	}elseif($msg['view_status']==0){
		$msg_obj->save(array("view_status"=>"1"),array("msg_id"=>$msg_id));
	}
   require keke_tpl_class::template ( "user/" . $do . "_".$view."_" . $ac);
   exit();
}else{
	$where = "1=1 ";
	switch ($op) {
	 
		case "inbox":
			if($opp=='inbox'){
				$where.="and uid>0 and to_uid=$uid and msg_status!=1  ";
			}elseif($opp=='system'){
				$where.="and uid<1 and to_uid=$uid and msg_status!=1 ";
			}
		break;
		 
	}
	$order .= " order by msg_id desc";
	
	$res = $msg_obj->get_grid($where, $url_str, $page,12,$order);
	$data = $res['data'];
	$pages = $res['pages'];
}
//var_dump($expression);
if (isset ( $check_username ) && ! empty ( $check_username )) {
	$res =  keke_user_class::check_username ( $check_username );
	 
	if(Keke::$_sys_config['user_intergration']==1){
		if($res){
			echo true;
		}
	}else{
		if($res==-3){
			echo true;
		}
	}
	die ();
}
if($op=='send' || $op=='outbox'){
	require 'user_'.$view.'_'.$op.'.php';
}elseif($op=='inbox'){
	require keke_tpl_class::template ( "user/" . $do . "_".$view."_system");
}


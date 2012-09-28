<?php
/**
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-11-1-����04:50:34
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = 'task';
$basic_url = "index.php?do=task&task_id=$task_id"; //��������
$task_obj = mreward_task_class::get_instance ( $task_info );
$task_info= $task_obj->_task_info;
$cover_cash = kekezu::get_cash_cove('',true);
$task_config =$task_obj->_task_config;
$model_id = $task_info ['model_id'];
$task_status = $task_obj->_task_status;
$indus_arr = $kekezu->_indus_c_arr; //����ҵ��
$indus_p_arr = $kekezu->_indus_p_arr; //����ҵ��
$status_arr = $task_obj->_task_status_arr; //����״̬����
$time_desc = $task_obj->get_task_timedesc (); //����ʱ������
$stage_desc = $task_obj->get_task_stage_desc (); //����׶���ʽ
$related_task = $task_obj->get_task_related ();//��ȡ�������
$delay_rule = $task_obj->_delay_rule;//���ڹ���
$delay_total = sizeof($delay_rule);//�����ڴ���
$delay_count=intval($task_info['is_delay']);//�����ڴ���
$process_can = $task_obj->process_can (); //�û�����Ȩ��
$process_desc = $task_obj->process_desc (); //�û�����Ȩ����������
$task_obj->plus_view_num();//�鿴��һ
$work_prize = $task_obj->get_work_prize();//�������
$task_prize_arr = $task_obj->get_task_prize();//����������
$task_obj->task_tg_timeout();//�ж�Ͷ�����Ƿ����
$task_obj->task_xg_timeout();//�ж�ѡ�����Ƿ����
$task_obj->task_gs_timeout();//�жϸ�ʾ���Ƿ����
$browing_history = $task_obj->browing_history($task_id,$task_info['task_cash']."Ԫ",$task_info['task_title']);
$show_payitem = $task_obj->show_payitem();
$prize_c = $task_obj->get_prize_date();
$wiki_priv = $task_obj->_priv; //��������Ȩ��
$g_info = $task_obj->_g_userinfo;
//var_dump($wiki_priv['comment']);
switch ($op) {
	case "reqedit" : //���󲹳�
        if($task_info['ext_desc']){
		$title = $_lang['edit_supply_demand'];
		}else{
		$title =$_lang['supply_demand'];
		}
		if ($sbt_edit) {
			$task_obj->set_task_reqedit ( $tar_content, '', 'json' );
		} else {
			$ext_desc = $task_info ['ext_desc'];
			require keke_tpl_class::template ( 'task/task_reqedit' );
		}
		die ();
		break;
	case "taskdelay" : //����
		$title = $_lang['task_delay'];
		if($sbt_edit){
			$task_obj->set_task_delay($delay_day, $delay_cash,'','json');
		}else {
			$min_cash = intval($task_config['min_delay_cash']);//������С���ڽ��
			$max_day  = intval($task_config['max_delay']);//���������������
			$this_min_cash = intval($delay_rule[$delay_count]['defer_rate']*$task_info['task_cash']/100);//������С���ڽ��
			$min_cash>$this_min_cash and $real_min = $min_cash or $real_min = $this_min_cash;//������С���
			$credit_allow =  intval($kekezu->_sys_config ['credit_is_allow']);//��ҿ���
			require keke_tpl_class::template("task/task_delay");
		}
		die();
		break;
	case "work_hand" : //����
		$title = $_lang['hand_work'];
		if($sbt_edit){
			$task_obj->work_hand ( $tar_content, $file_ids,$workhide,$qq,$mobile,'','json');
			
		}else {
			$workhide_exists = keke_payitem_class::payitem_exists($uid,'workhide','work');//�������ؽ���
			require keke_tpl_class::template ( 'task/reward_work' );
		}

		die();
		break;
	case "work_choose" : //ѡ��
		$work_info = $task_obj->work_choose ( $work_id, $to_status,'','json');

		break;
	case "start_vote" : //����ͶƱ
		$task_obj->start_vote('','json');
		break;
	case "work_vote" : //����ͶƱ
		$task_obj->set_task_vote($work_id,'','json');
		break;
	case "report" : //�ٱ�
		$transname = keke_report_class::get_transrights_name($type);
		$title=$transname.$_lang['submit'];
		if($sbt_edit){
			$task_obj->set_report ( $obj, $obj_id, $to_uid,$to_username, $type, $file_url, $tar_content);
		}else{
			require keke_tpl_class::template("report");
		}
			die();
		break;
	case "mark" ://����
		$title = $_lang['each_mark'];
		$model_code = $task_obj->_model_code;
		
		require S_ROOT.'control/mark.php';
		die();
		break;
	case "work_del"://���ɾ��
		$task_obj->del_work($work_id,'','json');
		break;
	case "comment" : //�������
		switch ($obj_type) {
			case "task" :
				break;
			case "work" :
				
				$tar_content and $task_obj->set_work_comment ( $obj_type, $obj_id, $tar_content, $p_id, '', 'json' );
				break;
		}
		break;
	case "message" : //������Ϣ
		$title = $_lang['send_msg'];
		if ($sbt_edit) {
			$task_obj->send_message($title,$tar_content,$to_uid, $to_username,'','json');
		} else {
			require keke_tpl_class::template ( 'message' );
		}
			die ();
		break;
}
switch ($view) {
	case "work" :
		$search_condit = $task_obj->get_search_condit();
		$date_prv = date("Y-m-d",time());//���ڹ����ظ�ʱ��ʱ��ǰ׺����
		$work_status = $task_obj->get_work_status ();//��ȡ���״̬����
		intval ( $page ) and $p ['page'] = intval ( $page ) or $p ['page']='1';
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p['page_size']='10';
		$p['url'] = $basic_url."&view=work&ut=$ut&page_size=".$p ['page_size']."&page=".$p ['page'];
		$p ['anchor'] = '';
		$w['work_id'] = $work_id;//������
		$w['work_status'] = $st;//���״̬
		$w['user_type']   = $ut;//�û�����  my�Լ�
		$work_arr = $task_obj->get_work_info ($w, " work_id desc ", $p ); //�����Ϣ
		$pages = $work_arr ['pages'];
		$work_info = $work_arr ['work_info'];
		$mark      = $work_arr['mark'];
		///*����Ƿ���������**/
		$has_new  = $task_obj->has_new_comment($p ['page'],$p ['page_size']);
	
		break;
	case "comment" :
	$comment_obj = keke_comment_class::get_instance('task'); 
		$url = $basic_url."&view=comment";
		intval($page) or $page = 1;
		$comment_arr = $comment_obj->get_comment_list($task_id, $url, $page); 
		$comment_data = $comment_arr['data'];
		$comment_page = $comment_arr['pages'];
		$reply_arr = $comment_obj->get_reply_info($task_id);
	
	    switch ($op){
	    	case "reply": //�ظ���������
	    		$comment_arr = array("obj_id"=>$task_id,"origin_id"=>$task_id,"obj_type"=>"task","p_id"=>$pid,
	    		 "uid"=>$uid, "username"=>$username,"content"=>$content,"on_time"=>time()); 
	    		$res = $comment_obj->save_comment($comment_arr,$task_id,1); 
	    		if($res!=3&&$res!=2){
	    			$v1 =  $comment_obj->get_comment_info($res);
	    			$tmp ='replay_comment';
	    			require keke_tpl_class::template ( "task/task_comment_reply" );
	    		}else{
	    			echo $res;
	    		}
	    		die();
	    		break;
	    	case "add": //������������ 
	    		$comment_arr = array("obj_id"=>$task_id,"origin_id"=>$task_id,"obj_type"=>"task",
	    		"uid"=>$uid, "username"=>$username,"content"=>$content,"on_time"=>time());
	    		$res = $comment_obj->save_comment($comment_arr,$task_id); 
	    		if($res!=3&&$res!=2){
	    			$v = $comment_obj->get_comment_info($res);
	    			$tmp ='pub_comment';
	    			require keke_tpl_class::template ( "task/task_comment_reply" );
	    		}else{
	    			echo $res;
	    		}
	    		die();
	    		break;
	    	case "del": 
	    		$comment_info = $comment_obj->get_comment_info($comment_id);
	    		if( $uid ==ADMIN_UID||$user_info['group_id']==7){
	    			//���¸�����Ϣ 
	    			$res = $comment_obj->del_comment($comment_id,$task_id,$comment_info['p_id']);
	    		}else{
	    			kekezu::keke_show_msg("", $_lang['not_priv'],"error","json");
	    		}
	    		$res and kekezu::keke_show_msg("", $_lang['delete_success'],"","json") or kekezu::keke_show_msg("",$_lang['system_is_busy'],"error","json");
	    		break;	
	    } 
		break;
	case "mark":
		$mark_count = $task_obj->get_mark_count();//����ͳ��
		intval ( $page ) and $p ['page'] = intval ( $page ) or $p ['page']='1';
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p['page_size']='10';
		$p['url'] = $basic_url."&view=mark&page_size=".$p ['page_size']."&page=".$p ['page'];
		$p ['anchor'] = '';
		$w['model_code'] = $model_code;//����ģ��
		$w['origin_id']   = $task_id;//����Դ task_id
		$w['mark_status'] = $st;//����״̬
		//$ut=='my' and $w['uid'] = $uid;//�ҵ�����
		$w['mark_type'] = $ut;//���Ե�����
		$mark_arr = keke_user_mark_class::get_mark_info($w,$p,' mark_id desc ',"mark_status>0");
		$mark_info = $mark_arr['mark_info'];
		$pages     = $mark_arr['pages'];
		break;
	case "base" :
	default :
		$task_file = $task_obj->get_task_file (); //���񸽼�
		$kekezu->init_prom();
		$can_prom = $kekezu->_prom_obj->is_meet_requirement ( "bid_task", $task_id );
		if($task_info['task_status']==8){
			$list_work = db_factory::query(' select uid,username from '.TABLEPRE.'witkey_task_work where task_id='.intval($task_id).' and work_status in (1,2,3)');
		}
		if($task_info['task_status']==2&&$task_info['uid']==$uid){
			$item_list= keke_payitem_class::get_payitem_config ( 'employer', null, null, 'item_id' );
		}
}
require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . "/task_info" );
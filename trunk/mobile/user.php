<?php
 defined ( 'IN_KEKE' )&&defined('ISWAP')&&ISWAP or kekezu::echojson ($wap_msg, 0);
$data = array();
 $data['pub_num'] =intval( $user_info['pub_num']);//����
 $data['join_num']= intval($user_info['take_num']);//�μ�
 $e_data = kekezu::get_table_data(" count(task_id) c,task_status","witkey_task"," uid='{$uid}'",'','task_status','','task_status',3600);
 $data['wait_num']= intval($e_data[0]['c']);//����
 $data['adva_num']= intval($e_data[2]['c']+$e_data[3]['c']);//������
 $data['over_num']= intval($e_data[8]['c']);//���
 $f_count = db_factory::get_count(sprintf(" select count(f_id) c from %switkey_favorite where keep_type='task' and uid='%d'",TABLEPRE,$uid),0,'c',3600);
 $data['favor_num']= intval($f_count);
 $m_send = db_factory::get_count(" select count(msg_id) c from ".TABLEPRE."witkey_msg where uid='{$uid}' and msg_pid=0");
 $data['msg_send']= intval($m_send);
 $m_get  = db_factory::get_count(" select count(msg_id) c from ".TABLEPRE."witkey_msg where to_uid='{$uid}' and msg_pid=0");
 $data['msg_get']= intval($m_get);
 kekezu::echojson('',1,$data);die();
  
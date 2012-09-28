<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$std_cache_name = 'task_cache_'.$pub_mode.'_'.$model_id.'_'.$t_id.'_' . substr ( md5 ( $uid ), 0, 6 );
$release_obj = preward_release_class::get_instance ( $model_id ,$pub_mode);

$release_obj->get_task_obj ( $std_cache_name ); //��ȡ������Ϣ����
$release_obj->pub_mode_init($std_cache_name,$init_info);//����ģʽ��ʼ�������Ϣ
$release_info = $release_obj->_std_obj->_release_info; //���񷢲���Ϣ
$task_config = $release_obj->_task_config; //��������
$min =time()+ 24*3600*$task_config['min_day'];
$min = date("Y-m-d",$min); 
$payitem_arr = keke_payitem_class::get_payitem_info('employer','preward'); //��ȡ���������е���ֵ����  
$payitem_standard = keke_payitem_class::payitem_standard (); //�շѱ�׼
$ajax =='check_priv' and $release_obj->check_pub_priv('','json');
switch ($r_step) { //���񷢲�����
	case "step1" :
		
		switch ($ajax) {
			case "getmaxday" : //��ȡ�������
				$release_obj->get_max_day ( $task_cash );
				break;
		}
		if (kekezu::submitcheck($formhash)) {
			$release_info and $_POST = array_merge ( $release_info, $_POST );
			//�����ͽ�ת��
			$_POST['txt_task_cash'] = keke_curren_class::convert($_POST['txt_task_cash'],0,true);
			$release_obj->save_task_obj ( $_POST, $std_cache_name ); //��Ϣ����
			header ( "location:index.php?do=release&pub_mode=$pub_mode&t_id=$t_id&model_id={$model_id}&r_step=step2" );
			die ();
		} else{
			$default_max_day = $release_obj->_default_max_day; //��ǰԤ���µ��������
		}
		
		break;
	case "step2" :		
		if (kekezu::submitcheck($formhash)) {			
			$release_info and $_POST = array_merge ( $release_info, $_POST);
			$_POST['txt_title'] = kekezu::escape($txt_title);
			$_POST['tar_content'] = $tar_content;
			$release_obj->save_task_obj ($_POST, $std_cache_name ); //��Ϣ����
			header ( "location:index.php?do=release&pub_mode=$pub_mode&t_id=$t_id&model_id={$model_id}&r_step=step3" );
			die ();
		} else {
			$release_obj->check_access ( $r_step, $model_id, $release_info ); //ҳ�����Ȩ�޼��
			$kf_info	 = $release_obj->_kf_info; //����ͷ�
			$indus_p_arr = $release_obj->get_bind_indus(); //������ҵ
			$indus_arr   = $release_obj->get_task_indus($release_info ['indus_pid']); //�Ӽ���ҵ
			$ext_types   = kekezu::get_ext_type (); //������������
 
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
			$release_info and $_POST = array_merge ( $release_info, $_POST );
			$release_obj->save_task_obj ( $_POST, $std_cache_name ); //��Ϣ����
			$task_id = $release_obj->pub_task ( ); //�����¼����
			$release_obj->update_task_info ( $task_id, $std_cache_name ); //��ɷ�����������Ϣ
		} else {
			$release_obj->check_access ( $r_step, $model_id, $release_info ); //ҳ�����Ȩ�޼��
			$item_list = keke_payitem_class::get_payitem_config ( 'employer' ,$model_info['model_code']);//������ֵ������
			$standard = keke_payitem_class::payitem_standard ();//��ֵ�����շѱ�׼����
			//$trust_list = kekezu::get_payment_config('','trust','1');//���������б�
			$total_cash = $release_obj->get_total_cash ( $release_info ['txt_task_cash'] ); //�����ܽ��
			$item_info = $release_obj->get_pay_item (); //���񸽼����ȡ
		}
		break;
	case "step4" :
		$release_obj->check_access ( $r_step, $model_id, $release_info,$task_id ); //ҳ�����Ȩ�޼��
// 		$task_info = $release_obj->check_access ( $r_step, $model_id, $release_info,$task_id ); //ҳ�����Ȩ�޼��
// 		$item_list = keke_payitem_class::get_user_payitem($uid,'spend','task',$task_id);
// 		$standard = keke_payitem_class::payitem_standard ();
		break;
}
require keke_tpl_class::template ( 'release' );
		
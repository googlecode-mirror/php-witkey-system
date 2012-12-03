<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$std_cache_name = 'service_cache_'.$model_id.'_' . substr ( md5 ( $uid ), 0, 6 );
$release_obj = service_release_class::get_instance ( $model_id );
$payitem_arr = Sys_payitem::get_payitem_info('employer','service'); //��ȡ���������е���ֵ����  
$payitem_standard = Sys_payitem::payitem_standard (); //�շѱ�׼

$release_obj->get_service_obj ( $std_cache_name ); //��ȡ������Ϣ����
$release_info = $release_obj->_std_obj->_release_info; //���񷢲���Ϣ
$service_config = $release_obj->_service_config; //��������
$ext = '*.jpg;*.jpeg;*.gif;*.png;*.bmp';
switch ($r_step) { //���񷢲�����
	case "step1" :
		if(kekezu::submitcheck($formhash)){
			$release_info and $_POST = array_merge ( $release_info, $_POST );
			$_POST['txt_price'] = Curren::convert($_POST['txt_price'],0,true);
			$release_obj->save_service_obj ( $_POST, $std_cache_name ); //��Ϣ����
			header ( "location:index.php?do=shop_release&model_id={$model_id}&r_step=step2" );
			die ();
		}
		break;
	case "step2" :
		if (kekezu::submitcheck($formhash)) {
			$release_info and $_POST = array_merge ( $release_info, $_POST,$_FILES);
			$_POST['txt_title']  = kekezu::escape($txt_title);
			$_POST['tar_content'] =  $tar_content ;
			$_POST['txt_price'] = Curren::convert($_POST['txt_price'],0,true);
			$release_obj->save_service_obj ( $_POST, $std_cache_name ); //��Ϣ����
			header ( "location:index.php?do=shop_release&model_id={$model_id}&r_step=step3" );
			die ();
		} else {
			$release_obj->check_access ( $r_step, $model_id, $release_info ); //ҳ�����Ȩ�޼��
			$kf_info	 = $release_obj->_kf_info; //����ͷ�
			$indus_p_arr = $release_obj->get_bind_indus(); //������ҵ
			$indus_arr   = $release_obj->get_service_indus($release_info ['indus_pid']); //�Ӽ���ҵ
			$price_unit  = $release_obj->get_price_unit();//�۸�λ
			$service_unit= $release_obj->get_service_unit();//��ʱ��λ
 		}
		break;
	case "step3" :
		switch ($ajax) {
			case "save_payitem" : 
			
				$release_obj->save_pay_item ( $item_id, $item_code, $item_name, $item_cash, $std_cache_name ,$item_num);
				break;
			case "rm_payitem" :	
				$release_obj->remove_pay_item ( $item_id, $std_cache_name );
				break;
		}
		
		if (kekezu::submitcheck($formhash)) {
		//if($formhash){
			$release_info and $_POST = array_merge ( $release_info, $_POST );
			$release_obj->save_service_obj ( $_POST, $std_cache_name ); //��Ϣ����
			$service_id = $release_obj->pub_service();//��������
			$release_obj->update_service_info($service_id, $std_cache_name);
			die ();
		} else {
			$release_obj->check_access ( $r_step, $model_id, $release_info ); //ҳ�����Ȩ�޼��
			$item_list = Sys_payitem::get_payitem_info ('employer',$model_info[model_code] );//������ֵ������
			
			$standard = Sys_payitem::payitem_standard ();//��ֵ�����շѱ�׼����
			$item_info = $release_obj->get_pay_item (); //�������ȡ
			$total_cash = $release_obj->get_payitem_cash ( 0); //�����ܽ��
		}
		break;
	case "step4" :
		$service_info = $release_obj->check_access ( $r_step, $model_id, $release_info,$service_id ); //ҳ�����Ȩ�޼��
		break;
}
require keke_tpl_class::template ( 'shelves' );
		
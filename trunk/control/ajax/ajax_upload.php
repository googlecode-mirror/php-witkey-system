<?php
/**
 * @copyright keke-tech
 * @author jie
 * @version v 2.0
 * 2010-6-5����04:40:43
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
$upload_obj=keke_ajax_upload_class::get_instance($_SERVER['QUERY_STRING']);
switch ($upload_obj->_file_type){
	case 'sys'://ϵͳ�����ϴ�
	case 'editor'://�༭��
	case 'att'://�ϴ�����
		$upload_obj->upload_file();
		break;
	case 'big'://�ϴ����ļ�
		$upload_obj->upload_big_file();
		break;
	case 'service'://�ϴ�ͼƬ�����Զ����гɴ���С
		$upload_obj -> upload_and_resize_pic();
		break;
}
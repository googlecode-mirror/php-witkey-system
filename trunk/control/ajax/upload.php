<?php	defined ( 'IN_KEKE' ) or exit('Access Denied');
/**
 * @copyright keke-tech
 * @author jie
 * @version v 2.0
 * 2010-6-5����04:40:43
 */


class Control_ajax_upload extends Controller{
	/**
	 * �ļ��ϴ�
	 * @example sys����ʾϵͳ�ļ����� 'ad','auth','mark','tools' �⼸��
	 * Ŀ¼�Ƕ�sys����չ��ad ��ʾ���ͼƬ,auth ��ʾ��֤ͼƬ,mark ��ʾ�����ļ�,tools ��ʾ��ֵ����ͼƬ  �⼸��������
	 * task_id Ϊ����ʾ
	 * @see keke_ajax_upload_class::file_info_init()
	 * 
	 */
	function action_index(){
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
	}
	/**
	 * ɾ��ָ�����ļ�,����Ҫ��fid,filepath,��ֹ��ע
	 */
	function action_del(){
		//�ļ���fid
		$_GET = Keke_tpl::chars($_GET);
		$fid = intval($_GET['fid']);
		//һ������ͬһ��ͼƬ�ж��ֳߴ�ʱ����Ҫ����������������ڿ�ѡ����
		$size = $_GET['size'];
		//�ļ�·�� 
		$filepath = $_GET['filepath'];
		//ִ��ɾ�� 
		$res = keke_file_class::del_att_file($fid,$filepath,$size);
		$res and Keke::echojson ( '', 1 ) or Keke::echojson ( '', '0' );
	}
}
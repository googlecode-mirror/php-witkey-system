<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨admin ������
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-08-30 09:51:34
 */
abstract  class Control_admin extends Controller{
	 //����Ȩ���ж�
	 
	/**
	 * ͨ��control+action �õ���̨��ԴID
	 * ͨ����ԴID ���뵱ǰ�û���id���ж��û����Ƿ��в���Ȩ��
	 * ������check_roleȥ����
	 * 
	 */
	function before(){
		$this->check_login();
	}
	
	/**
	 * ����Ƿ��¼
	 */
	function check_login(){
		$jump_url = "<script>window.parent.location.href='".BASE_URL."/index.php/admin/login';</script>";
		if(!$_SESSION['admin_uid']){
			echo $jump_url;
		}
		
	}
	
}

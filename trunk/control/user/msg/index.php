<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-д��
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_index extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'index';
	
	function action_index(){
		
		if(isset ( $_POST['formhash'] ) && ! empty ( $_POST['formhash'] )){
			//��sqlע��
			$_POST=Keke_tpl::chars($_POST);
			//ȡ���û���Ϊ$_POST['txt_to_username'] ��uid
			$user_uid = Keke_user::instance()->get_user_info($_POST['txt_to_username'],'*',0);
			$user_to_uid = $user_uid['uid'];
			//������Ϣ
			Keke_msg::instance()->send_msg($user_to_uid,$_POST['txt_title'],$_POST['txt_content']);
			keke::show_msg('���ͳɹ�',$this->request->uri(),'success','ϵͳ��ʾ',3);
		}
		
		if (isset ( $_GET['check_username'] ) && ! empty ( $_GET['check_username'] )) {
			$res = Keke_user::instance()->get_user_info($_GET['check_username'],'*',0);
			if($res){
				echo true;
			}else{
				echo '�û�������';
			}
			die ();
		}
		
		require Keke_tpl::template('user/msg/index');
	}
}
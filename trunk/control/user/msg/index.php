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
			$_POST=Keke_tpl::chars($_POST);
			Keke_msg::instance()->send_msg($_POST['txt_to_username'],$_POST['txt_title'],$_POST['txt_content']);
			keke::show_msg('���ͳɹ�',$this->request->uri(),'success','ϵͳ��ʾ',3);
		}
		$check_username = $_GET['check_username'];
		if (isset ( $check_username ) && ! empty ( $check_username )) {
			$res = Keke_user::instance()->get_user_info($check_username,'*',0);
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
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
  				
		require Keke_tpl::template('user/msg/index');
	}
	
   function action_check_username(){
       $username = $this->request->param('id');
       $where = "username = '$username'";
       $res = DB::select('count(*)')->from('witkey_member')->where($where)->get_count()->execute();
       if($res > 0){
       	  echo  TRUE;
       }else{
       	  echo 'user_not_exists';
       }
   }
   
   function action_send(){
	   	Keke::formcheck($_POST['formhash']);
	   	//��sqlע��
	   	$_POST=Keke_tpl::chars($_POST);
	   	//ȡ���û���
	   	$user_uid = Keke_user::instance()->get_user_info($_POST['txt_to_username'],'uid',0);
	   	$user_to_uid = $user_uid['uid'];
	   	//������Ϣ
	   	Keke_msg::instance()->send_msg($user_to_uid,$_POST['txt_title'],$_POST['txt_content']);
	   	keke::show_msg('���ͳɹ�',$this->request->uri(),'success','ϵͳ��ʾ',3);
   }
	
	
}
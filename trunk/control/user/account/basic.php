<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-��������
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_basic extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'basic';
	
	function action_index(){
		
		$uindex = new Control_user_account_index($this->request,$this->response);
		
		$uinfo = $uindex->get_user_info();
		
		require Keke_tpl::template('user/account/basic');
	}
	
	/**
	 * �����û�ͷ��
	 */
	function action_avatar(){
		$flash_html = Keke_user::instance()->avatar_flash($_SESSION['uid']);
		require Keke_tpl::template('user/account/avatar');
	}
	//�ʼ����
	function action_unemail(){
		
	}
	//�ֻ����
	function action_unmobile(){
		
	}
	/**
	 * �����ֻ���֤��
	 */
	function action_send_sms(){
		$mobile = $this->request->param('id');
		if(Keke_valid::phone($mobile,11)===FALSE){
			return FALSE;
		}
		$rand_code = Keke::randomkeys(6);
		$sql ="replace into `keke_witkey_auth_mobile`\n".
			  "(uid,username,mobile,valid_code,auth_status,auth_time) \n".
			  "values (:uid,:username,'$mobile','$rand_code',0,:time)";
		
		DB::query($sql,Database::UPDATE)->tablepre(':keke_')
		->param(':uid', $this->uid)->param(':username', $this->username)
		->param(':time', SYS_START_TIME)->execute();
		Keke_msg::instance()->send_sms($mobile,'�ֻ���֤��:'.$rand_code);
	}
	function action_valid_code(){
		$code = $this->request->param('code');
	}
	
}
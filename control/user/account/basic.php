<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理-基本资料
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_basic extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'basic';
	
	function action_index(){
		
		$uindex = new Control_user_account_index($this->request,$this->response);
		
		$uinfo = $uindex->get_user_info();
		
		require Keke_tpl::template('user/account/basic');
	}
	
	/**
	 * 更新用户头象
	 */
	function action_avatar(){
		$flash_html = Keke_user::instance()->avatar_flash($_SESSION['uid']);
		require Keke_tpl::template('user/account/avatar');
	}
	//邮件解绑
	function action_unemail(){
		
	}
	//手机解绑
	function action_unmobile(){
		
	}
	/**
	 * 发送手机认证码
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
		Keke_msg::instance()->send_sms($mobile,'手机验证码:'.$rand_code);
	}
	function action_valid_code(){
		$code = $this->request->param('code');
	}
	
}
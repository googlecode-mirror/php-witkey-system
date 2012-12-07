<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理-账号安全
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_auth extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'auth';
	/**
	 * 实名认证
	 */
	function action_index(){
		
		$auth_info = DB::select()->from('witkey_auth_realname')->where("uid= $this->uid")->get_one()->execute();
		
		
		$gid = DB::select('group_id')->from('witkey_space')->where("uid= $this->uid")->get_count()->execute();
		if($gid==2){
			require Keke_tpl::template('user/account/auth_realname');
		}else{
			$this->action_enter();
		}
	}
	function action_real_save(){
		
		Keke::formcheck($_POST['formhash']);
		
		$realname = $_POST['realname'];
		
		$id_code = $_POST['id_code'];
		//正面图片
		$id_pic = keke_file_class::upload_file('id_pic');
		 
		//反面图片
		$pic = keke_file_class::upload_file('pic');
		
		$sql = "replace into `:keke_witkey_auth_realname`\n".
				"(uid,username,realname,id_code,pic,id_pic,start_time,auth_status) \n".
				"values (:uid,:username,:realname,:id_code,:pic,:id_pic,:start_time,:auth_status)";
		$params = array(':uid'=>$this->uid,':username'=>$this->username,':realname'=>$realname,
				      ':id_code'=>$id_code,':pic'=>$pic,':id_pic'=>$id_pic,
					  ':start_time'=>SYS_START_TIME,':auth_status'=>0);
		
		DB::query($sql,Database::UPDATE)->tablepre(':keke_')->parameters($params)->execute();
		
		Keke::show_msg('提交成功','user/account_auth');
	}
 
	/**
	 * 企业认证
	 */
	function action_enter(){
	
	
		require Keke_tpl::template('user/account/auth_enter');
	}

}
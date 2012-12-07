<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-�˺Ű�ȫ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_auth extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'auth';
	/**
	 * ʵ����֤
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
		//����ͼƬ
		$id_pic = keke_file_class::upload_file('id_pic');
		 
		//����ͼƬ
		$pic = keke_file_class::upload_file('pic');
		
		$sql = "replace into `:keke_witkey_auth_realname`\n".
				"(uid,username,realname,id_code,pic,id_pic,start_time,auth_status) \n".
				"values (:uid,:username,:realname,:id_code,:pic,:id_pic,:start_time,:auth_status)";
		$params = array(':uid'=>$this->uid,':username'=>$this->username,':realname'=>$realname,
				      ':id_code'=>$id_code,':pic'=>$pic,':id_pic'=>$id_pic,
					  ':start_time'=>SYS_START_TIME,':auth_status'=>0);
		
		DB::query($sql,Database::UPDATE)->tablepre(':keke_')->parameters($params)->execute();
		
		Keke::show_msg('�ύ�ɹ�','user/account_auth');
	}
 
	/**
	 * ��ҵ��֤
	 */
	function action_enter(){
	
	
		require Keke_tpl::template('user/account/auth_enter');
	}

}
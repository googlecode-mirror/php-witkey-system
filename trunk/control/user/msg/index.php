<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-写信
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_index extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
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
	   	//防sql注入
	   	$_POST=Keke_tpl::chars($_POST);
	   	//取得用户名
	   	$user_uid = Keke_user::instance()->get_user_info($_POST['txt_to_username'],'uid',0);
	   	$user_to_uid = $user_uid['uid'];
	   	//发送信息
	   	Keke_msg::instance()->send_msg($user_to_uid,$_POST['txt_title'],$_POST['txt_content']);
	   	keke::show_msg('发送成功',$this->request->uri(),'success','系统提示',3);
   }
	
	
}
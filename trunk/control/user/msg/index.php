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
		if(isset ( $_POST['formhash'] ) && ! empty ( $_POST['formhash'] )){
			$_POST=Keke_tpl::chars($_POST);
			Keke_msg::instance()->send_msg($_POST['txt_to_username'],$_POST['txt_title'],$_POST['txt_content']);
			keke::show_msg('发送成功',$this->request->uri(),'success','系统提示',3);
		}
		$check_username = $_GET['check_username'];
		if (isset ( $check_username ) && ! empty ( $check_username )) {
			$res = Keke_user::instance()->get_user_info($check_username,'*',0);
			if($res){
				echo true;
			}else{
				echo '用户不存在';
			}
			die ();
		}
		require Keke_tpl::template('user/msg/index');
	}
}
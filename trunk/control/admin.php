<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台admin 控制器
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-08-30 09:51:34
 */
abstract  class Control_admin extends Controller{
	 //操作权限判断
	 
	/**
	 * 通过control+action 得到后台资源ID
	 * 通过资源ID ，与当前用户级id，判断用户组是否有操作权限
	 * 可以让check_role去死了
	 * 
	 */
	function before(){
		$this->check_login();
	}
	
	/**
	 * 检查是否登录
	 */
	function check_login(){
		$jump_url = "<script>window.parent.location.href='".BASE_URL."/index.php/admin/login';</script>";
		if(!$_SESSION['admin_uid']){
			echo $jump_url;
		}
		
	}
	
}

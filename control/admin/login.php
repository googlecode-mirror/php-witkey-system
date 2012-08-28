<?php
class Control_admin_login extends Controller {
	/**
	 * 判断有没有登录，如果登录了，跳到index.php
	 * 如果没有登录跳到初始化登录页面
	 */
	function action_index(){
       global $_K;
       //group_id > 0 表示是
       if($_SESSION['admin_uid'] and $_K['userinfo']['group_id']>0){
       	     
       }
		
	}

}
//end
 
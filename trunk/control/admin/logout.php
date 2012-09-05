<?php
class Control_admin_logout extends Controller {

	function action_index(){
		$_SESSION ['uid'] = "";
		$_SESSION ['username'] = "";
		$_SESSION ['auid'] = "";
		$_SESSION ['user_info'] = "";
		/* if (isset ( $_COOKIE ['user_login'] )) {
			setcookie ( 'user_login', '' );
		} */
		Cookie::delete('user_login');
		
		$this->request->redirect('/admin');
		
	}
	
	
}

?>
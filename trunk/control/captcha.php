<?php defined ( 'IN_KEKE' ) or exit('Access Denied');
class Control_captcha extends Controller{
   
	
	/**
	 * 初始化验证码图形
	 */
	function action_index(){
	    Keke_captcha::instance()->render(false);
	}
}
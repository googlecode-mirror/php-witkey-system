<?php defined ( 'IN_KEKE' ) or exit('Access Denied');
class Control_captcha extends Controller{
   
	
	/**
	 * ��ʼ����֤��ͼ��
	 */
	function action_index(){
	    Keke_captcha::instance()->render(false);
	}
}
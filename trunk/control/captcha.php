<?php defined ( 'IN_KEKE' ) or exit('Access Denied');
class Control_captcha extends Controller{
   
	
	/**
	 * ��ʼ����֤��ͼ��
	 */
	function action_index(){
		$style = 'basic';
		if(($id = $this->request->param('id'))!==null){
			$style = $id;
		}
		
		if(($ids = $this->request->param('ids'))!==null){
			//ͼƬ����ĸ߿�
			list($w,$h) = explode('/', $ids);
		}
	    Keke_captcha::instance($w,$h,$style)->render(false);
	}
	/**
	 * ���code
	 */
	function action_check(){
		 
		//��֤��
		if(($code = $this->request->param('id'))!==null){
			//�������� Keke_captcah::valid()������SESSION��ˢ�£���������
			//ֻ����֤��ˢ��SESSION;
			echo ( bool ) (sha1 ( strtoupper ( $code ) ) === $_SESSION ['Keke_captcha_response']);
		}
		 
	}
	function action_test(){
		echo true;
	}
}
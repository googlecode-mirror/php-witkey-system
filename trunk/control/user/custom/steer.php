<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�ͷ�������ҳ-�û�����
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_custom_steer extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'custom';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'steer';
	
	function action_index(){
		
		
		$open_url = BASE_URL.'/index.php/user/custom_steer/comment';
		require Keke_tpl::template('user/custom/steer');
	}
	function action_comment(){
	    
		if($_POST){
	    	var_dump($_POST); 
	    	$this->request->redirect('user/custom_steer');
	    }	
		require Keke_tpl::template('user/custom/comment');
	}
	
}
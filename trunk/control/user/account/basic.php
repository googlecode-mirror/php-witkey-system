<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-��������
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_basic extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'basic';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/account/basic');
	}
	/**
	 * �û�ͷ��
	 */
	function action_avatar(){
		
		
		require Keke_tpl::template('user/account/avatar');
	}
}
<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-�˺Ű�ȫ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_auth extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'auth';
	/**
	 * ʵ����֤
	 */
	function action_index(){
		
		require Keke_tpl::template('user/account/auth_realname');
	}
	/**
	 * ���п���֤
	 */
	function action_bank(){
		
		
		require Keke_tpl::template('user/account/auth_bank');
	}
	/**
	 * ��ҵ��֤
	 */
	function action_enter(){
	
	
		require Keke_tpl::template('user/account/auth_enter');
	}

}
<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-�˺Ű�ȫ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_safe extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'safe';
	/**
	 * �޸�����
	 */
	function action_index(){
		
		require Keke_tpl::template('user/account/pwd');
	}
	/**
	 * �޸İ�ȫ��
	 */
	function action_safe(){
		
		
		require Keke_tpl::template('user/account/safe');
	}

}
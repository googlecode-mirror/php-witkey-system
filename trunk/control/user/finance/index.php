<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź�����ҳ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_index extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'index';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/finance/index');
	}
}
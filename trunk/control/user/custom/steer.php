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
		
		
		
		require Keke_tpl::template('user/custom/steer');
	}
}
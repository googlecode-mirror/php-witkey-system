<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-������-��������Ʒ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_seller_mark extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'seller';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'mark';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/seller/mark');
	}
 
}
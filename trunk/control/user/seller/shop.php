<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-������-���̹���
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_seller_shop extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'seller';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'shop';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/seller/shop');
	}
	function action_case(){
	
	
	
		require Keke_tpl::template('user/seller/shop_case');
	}
	function action_member(){
	
	
	
		require Keke_tpl::template('user/seller/shop_member');
	}
}
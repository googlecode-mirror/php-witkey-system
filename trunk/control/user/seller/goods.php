<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-������-��������Ʒ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_seller_goods extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'seller';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'goods';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/seller/goods');
	}
	function action_edit(){
		
		require Keke_tpl::template('user/seller/goods_edit');
	}
}
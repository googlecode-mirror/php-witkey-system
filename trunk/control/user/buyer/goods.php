<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-���-�������Ʒ
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_user_buyer_goods extends Control_user{
    
	
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'buyer';
	/**
	 *
	 * @var �����˵�ѡ����,��ֵ����ѡ��
	 */
	protected static $_left = 'goods';
	function action_index(){
		global $_K,$_lang;
		 
		 
		
		require Keke_tpl::template('user/buyer/goods');
	}
	function action_edit(){
		require Keke_tpl::template('user/buyer/goods_eidt');
	}
}
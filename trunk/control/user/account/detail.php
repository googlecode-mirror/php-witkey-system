<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-��������
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_detail extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'detail';
	/**
	 * ��������
	 */
	function action_index(){
		
		require Keke_tpl::template('user/account/work_list');
	}
	/**
	 * ����֤��
	 */
	function action_skill(){
		
		
		require Keke_tpl::template('user/account/skill');
	}
	/**
	 * ���ܱ�ǩ
	 */
	function action_skill_tag(){

		require Keke_tpl::template('user/account/skill_tag');
	}
}
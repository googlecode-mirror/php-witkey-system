<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-��Ϣ-�ռ���
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_in extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'in';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/msg/in');
	}
	function action_info(){
		
		require Keke_tpl::template('user/msg/info');
	}
	function action_out_info(){
		self::$_left='out';
		
		require Keke_tpl::template('user/msg/info');
	}
}
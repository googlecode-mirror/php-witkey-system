<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-��������
 * @author Michael
 * @version 3.0
   2012-12-11
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
		$where = "uid = $this->uid";
		$works  = DB::select()->from('witkey_member_work')->where($where)->execute();
		require Keke_tpl::template('user/account/work_list');
	}
	/**
	 * ������������
	 */
	function action_work_save(){
		
	}
	/**
	 * ����֤��
	 */
	function action_skill(){
		$where = "uid = $this->uid";
		$certs = DB::select()->from('witkey_member_cert')->where($where)->execute();
		require Keke_tpl::template('user/account/skill');
	}
	/**
	 * ����֤�鱣��
	 */
	function action_skill_save(){
				
	}
	/**
	 * ���ܱ�ǩ
	 */
	function action_skill_tag(){
		$where = "uid = $this->uid";
	    $skills = DB::select('skill_ids')->from('witkey_space')->where($where)->get_count()->execute();	
		$skills = explode(',', $skills);
	    require Keke_tpl::template('user/account/skill_tag');
	}
	/**
	 * ���ܱ�ǩ����
	 */
	function action_tag_save(){
		
	}
	
}
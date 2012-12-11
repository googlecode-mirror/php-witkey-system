<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理-基本资料
 * @author Michael
 * @version 3.0
   2012-12-11
 */

class Control_user_account_detail extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'detail';
	/**
	 * 工作经历
	 */
	function action_index(){
		$where = "uid = $this->uid";
		$works  = DB::select()->from('witkey_member_work')->where($where)->execute();
		require Keke_tpl::template('user/account/work_list');
	}
	/**
	 * 工作经历保存
	 */
	function action_work_save(){
		
	}
	/**
	 * 技能证书
	 */
	function action_skill(){
		$where = "uid = $this->uid";
		$certs = DB::select()->from('witkey_member_cert')->where($where)->execute();
		require Keke_tpl::template('user/account/skill');
	}
	/**
	 * 技能证书保存
	 */
	function action_skill_save(){
				
	}
	/**
	 * 持能标签
	 */
	function action_skill_tag(){
		$where = "uid = $this->uid";
	    $skills = DB::select('skill_ids')->from('witkey_space')->where($where)->get_count()->execute();	
		$skills = explode(',', $skills);
	    require Keke_tpl::template('user/account/skill_tag');
	}
	/**
	 * 技能标签保存
	 */
	function action_tag_save(){
		
	}
	
}
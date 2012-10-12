<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户认证处理
 * @author Michael
 * @version 2.2
   2012-10-12
 */

class Keke_user_auth{
	
	/**
	 * 获取用户认证记录
	 * @param $uid
	 */
	public static function get_auth_by_uid($uid) {
		return DB::select()->from('witkey_member_auth')->where("uid ='$uid'")->get_one()->execute();
		
	}
 
	/**
	 * 获取认证图片
	 * @param $auth_code
	 * @param $uid user id
	 * @return   $img_list
	 */
	public static function get_auth_imghtm($auth_code, $uid) {
		global $_lang;
		$auth_list = self::get_auth_by_uid ( $uid );
		$config_list = self::get_auth_item_list();
		$img_list = '';
		foreach ( $config_list as $c ) {
			if (! $c ['auth_open'])
				continue;
			$str = '';
			$str .= '<img src="';
			$str .= 'data/uploads/' . 'ico/';
			$str .= $auth_list [$c ['auth_code']] ['auth_status'] ? $c ['auth_small_ico'] : $c ['auth_small_n_ico'];
			$str .= '" align="absmiddle" title="' . $c ['auth_title'];
			$str .= $auth_list [$c ['auth_code']] ['auth_status'] ? $_lang['has_pass'] : $_lang['not_pass'];
			$str .= '" width="15">&nbsp;';
			$img_list .= $str;
		}
		return $img_list;
	
	}

	/**
	 * 用户认证记录验证
	 * @param  $auth_code sting or array()认证类型 获取单一或多类
	 * @param  $uid 用户名
	 * @return bool 如果查多个认证返回数组
	 */
	public static function check_auth($auth_code,$uid){
		if(is_string($auth_code)){
			return (bool)DB::select($auth_code)->from('witkey_member_auth')->where("uid = '$uid'")->get_count()->execute();
		}elseif(is_array($auth_code)){
			$c = implode(',', $auth_code);
			return DB::select($c)->from('witkey_member_auth')->where("uid='$uid'")->get_one()->execute();
		}
	}
	/**
	 * 更改认证记录状态
	 * @param  $auth_code 可为数组
	 * @param $uid 认证人id
	 * @param  $status
	 */
	public static function set_auth_status($uid,$auth_code,$status) {
		$where = "uid='$uid'";
		//更新记录状态
		DB::update('witkey_auth_'.$auth_code)->set(array('auth_status'))->value(array($status))->where($where)->execute();
		//如果是个企业认证改变用户角色
		if($auth_code==='enterperise'){
			$action = (bool)$status?'pass':'no_pass';
			self::set_user_role($uid,$action);
		}
		//判断是否有对应的记录
		if(DB::select('uid')->from('witkey_member_auth')->where($where)->get_count()->execute()){
			//is hava else update
		   return (bool)DB::update('witkey_member_auth')->set(array($auth_code))->value(array($status))->where($where)->execute();
		}else{
			//insert
		   return (bool)DB::insert('witkey_member_auth')->set(array('uid',$auth_code))->value(array($uid,$status))->execute();
		}
	}

	/**
	 *认证提示
	 */
	public function auth_lang(){
		global $_lang;
		$lang=array("realname"=>$_lang['realname_auth'],
				"bank"=>$_lang['bank_auth'],
				"email"=>$_lang['email_auth'],
				"enterprise"=>$_lang['enterprise_auth'],
				"mobile"=>$_lang['mobile_auth'],
				"weibo"=>$_lang['weibo_auth']);
		return $lang[$this->_auth_code];
	}
	/**
	 * 删除认证申请--支持批量删除
	 * @param $auth_ids 待删除认证项编号 可以为数组
	 * @see keke_auth_base_class::del_auth()
	*/
	public function del_auth($auth_ids) {
		global $_lang;
	
		is_array($auth_ids) and $ids=implode(",",$auth_ids) or $ids=$auth_ids;//数组连接
		$auth_info=$this->get_auth_info($ids);//获取实名认证表记录
		$size=sizeof($auth_info);
		$size==0 and Keke::admin_show_msg($this->auth_lang(). $_lang['apply_not_exist_delete_fail'],$_SERVER['HTTP_REFERER']);
	
		if($size==1&&$auth_info[0]['auth_status']!='1'){//单条记录。单个删除 通过的记录无法被删除
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			$res = $this->del_auth_record($auth_info[0]['uid']);//删除record记录
			/**企业认证删除时重置用户身份**/
			$this->_auth_code=='enterprise' and $this->set_user_role($auth_info[0][uid],'not_pass');
		}elseif($size>1){//多条记录。多个删除
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			foreach ($auth_info as $v){
				$res = $this->del_auth_record($v['uid']);
				/**企业认证删除时重置用户身份**/
				$this->_auth_code=='enterprise' and $this->set_user_role($v[uid],'not_pass');
			}
		}
		Keke::empty_cache();
		$res && Keke::admin_show_msg($this->auth_lang(). $_lang['apply_delete_success'],$_SERVER['HTTP_REFERER'],3,'','success');
		Keke::admin_show_msg($this->auth_lang(). '删除失败',$_SERVER['HTTP_REFERER'],3,'','warning');
			
	}
	/**
	 * @通过认证审核
	 * @param string array $uid
	 * @param string $auth_code (realname,email,mobile...)
	 * @return bool
	 * @example 支付批量审核，与单条审核，更新认证记录的状态,
	 * 同时更新用户的auth表,以后查用户的认证状态，查member_auth 表就可以了
	 */
	public static function pass($uid,$auth_code){
		if(is_int($uid)){
			//单条更新
		  self::set_auth_status($uid, $auth_code, 1);
		}elseif(is_array($uid)){
			$size = sizeof($uid);
			//批量通过
			for($i=0;$i<$size;$i++){
				self::set_auth_status($uid[$i], $auth_code, 1);
			}
		}
		return TRUE;
	}
	/**
	 * @认证不通过
	 * @param string array $uid
	 * @return bool 
	 */
	public static function no_pass($uid,$auth_code){
		if(is_int($uid)){
			//单条更新
			self::set_auth_status($uid, $auth_code, 0);
		}elseif(is_array($uid)){
			$size = sizeof($uid);
			//批量通过
			for($i=0;$i<$size;$i++){
				self::set_auth_status($uid[$i], $auth_code, 0);
			}
		}
		return TRUE;
	}
	/**
	 * 删除指定的认证记录
	 * @param  int array  $uid
	 * @param unknown_type $auth_code
	 */
	public static function del($uid,$auth_code){
		
	}
	/**
	 * 认证审核   支持批量
	 * @param $item_ids 认证项编号 可以为数组
	 * @param $type 审核类型
	 */
	public function review_auth($auth_ids,$type='pass'){
		global $_lang;
		global $kekezu;
		$kekezu->init_prom();
		$prom_obj = Keke::$_prom_obj;
		is_array($auth_ids) and $auth_ids=implode(",",$auth_ids);//数组连接
	
		$auth_info=$this->get_auth_info($auth_ids);//认证信息获取
	
		$size=sizeof($auth_info);
		$size>0&&$type=='pass' and $status='1' or $status='2';//待变更状态
	
		$size==0 and Keke::admin_show_msg($this->auth_lang(). $_lang['apply_not_exist_audit_fail'],$_SERVER['HTTP_REFERER']);
		if($size==1&&$auth_info[0]['auth_status']!='1'){//已通过的认证无法操作
				
			$this->set_auth_status($auth_info[0][$this->_primary_key],$status);
			$this->set_auth_record_status($auth_info[0]['uid'], $status);
			/**企业认证时修改用户角色**/
			$this->_auth_code=='enterprise' and $this->set_user_role($auth_info[0][uid],$type);
		}elseif($size>1){
			foreach ($auth_info as $v){
				if($v['auth_status']!='1'){//已通过的认证无法操作
					$this->set_auth_record_status($v['uid'], $status);
					$this->set_auth_status($v[$this->_primary_key],$status);
					/**企业认证时修改用户角色**/
					$this->_auth_code=='enterprise' and $this->set_user_role($v[uid],$type);
				}
			}
		}
		switch ($type){
			case "pass":
				Keke::admin_system_log($this->auth_lang(). $_lang['apply_pass'] . "$auth_ids");
				foreach ($auth_info as $v){
					$feed_arr = array(
							"feed_username"=>array("content"=>$v[username],"url"=>"index.php?do=space&member_id=$v[uid]"),
							"action"=>array("content"=>$_lang['has_pass'],"url"=>""),
							"event"=>array("content"=>$this->auth_lang(),"url"=>"")
					);
					Keke::save_feed($feed_arr, $v['uid'],$v['username'],$this->_auth_name );
					/** 注册推广结算*/
					$prom_obj->dispose_prom_event($this->_auth_name,$v['uid'], $v['uid']);
	
					Keke::notify_user ( $this->auth_lang(). $_lang['through'], $_lang['your'].$this->auth_lang().$_lang['has_pass_please_to']."<a href=\'index.php?do=user&view=payitem&op=auth&auth_code=".$this->_auth_code."\'>".$_lang['auth_center']."</a>".$_lang['view_detail'],$v['uid'],$v['username']);
				}
				Keke::empty_cache();
				Keke::admin_show_msg($this->auth_lang().$_lang['apply_audit_success'],$_SERVER['HTTP_REFERER'],3,'','success');
				break;
			case "not_pass":
				Keke::admin_system_log($this->auth_lang().$_lang['apply_not_pass']."$auth_ids");
				foreach ($auth_info as $v){
					Keke::notify_user ( $this->auth_lang().$_lang['fail'], $_lang['your'].$this->auth_lang().$_lang['not_pass_please_to']."<a href=\'index.php?do=user&view=payitem&op=auth&auth_code=".$this->_auth_code."\'>".$_lang['auth_center']."</a>".$_lang['view_detail'], $v['uid'] , $v['username']);
				}
				Keke::empty_cache();
				Keke::admin_show_msg($this->auth_lang().$_lang['apply_audit_not_pass'],$_SERVER['HTTP_REFERER'],3,'','success');
				break;
		}
	}
	/**
	 * 企业认证时更新用户角色
	 * @param $action 动作  pass not_pass
	 * @param $uid  用户ID
	 * @example user_role 1 为普通用户, 2 为企业用户
	 */
	public static  function set_user_role($uid,$action='pass'){
		$action=='pass' and $user_role='2' or $user_role='1';
		Dbfactory::execute(sprintf(" update %switkey_space set user_type='%d' where uid='%d'",TABLEPRE,$user_role,$uid));
	}
}
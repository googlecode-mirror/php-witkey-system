<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û���֤����
 * @author Michael
 * @version 2.2
   2012-10-12
 */

class Keke_user_auth{
	
	/**
	 * ��ȡ�û���֤��¼
	 * @param $uid
	 */
	public static function get_auth_by_uid($uid) {
		return DB::select()->from('witkey_member_auth')->where("uid ='$uid'")->get_one()->execute();
		
	}
 
	/**
	 * ��ȡ��֤ͼƬ
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
	 * �û���֤��¼��֤
	 * @param  $auth_code sting or array()��֤���� ��ȡ��һ�����
	 * @param  $uid �û���
	 * @return bool ���������֤��������
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
	 * ������֤��¼״̬
	 * @param  $auth_code ��Ϊ����
	 * @param $uid ��֤��id
	 * @param  $status
	 */
	public static function set_auth_status($uid,$auth_code,$status) {
		$where = "uid='$uid'";
		//���¼�¼״̬
		DB::update('witkey_auth_'.$auth_code)->set(array('auth_status'))->value(array($status))->where($where)->execute();
		//����Ǹ���ҵ��֤�ı��û���ɫ
		if($auth_code==='enterperise'){
			$action = (bool)$status?'pass':'no_pass';
			self::set_user_role($uid,$action);
		}
		//�ж��Ƿ��ж�Ӧ�ļ�¼
		if(DB::select('uid')->from('witkey_member_auth')->where($where)->get_count()->execute()){
			//is hava else update
		   return (bool)DB::update('witkey_member_auth')->set(array($auth_code))->value(array($status))->where($where)->execute();
		}else{
			//insert
		   return (bool)DB::insert('witkey_member_auth')->set(array('uid',$auth_code))->value(array($uid,$status))->execute();
		}
	}

	/**
	 *��֤��ʾ
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
	 * ɾ����֤����--֧������ɾ��
	 * @param $auth_ids ��ɾ����֤���� ����Ϊ����
	 * @see keke_auth_base_class::del_auth()
	*/
	public function del_auth($auth_ids) {
		global $_lang;
	
		is_array($auth_ids) and $ids=implode(",",$auth_ids) or $ids=$auth_ids;//��������
		$auth_info=$this->get_auth_info($ids);//��ȡʵ����֤���¼
		$size=sizeof($auth_info);
		$size==0 and Keke::admin_show_msg($this->auth_lang(). $_lang['apply_not_exist_delete_fail'],$_SERVER['HTTP_REFERER']);
	
		if($size==1&&$auth_info[0]['auth_status']!='1'){//������¼������ɾ�� ͨ���ļ�¼�޷���ɾ��
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			$res = $this->del_auth_record($auth_info[0]['uid']);//ɾ��record��¼
			/**��ҵ��֤ɾ��ʱ�����û����**/
			$this->_auth_code=='enterprise' and $this->set_user_role($auth_info[0][uid],'not_pass');
		}elseif($size>1){//������¼�����ɾ��
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			foreach ($auth_info as $v){
				$res = $this->del_auth_record($v['uid']);
				/**��ҵ��֤ɾ��ʱ�����û����**/
				$this->_auth_code=='enterprise' and $this->set_user_role($v[uid],'not_pass');
			}
		}
		Keke::empty_cache();
		$res && Keke::admin_show_msg($this->auth_lang(). $_lang['apply_delete_success'],$_SERVER['HTTP_REFERER'],3,'','success');
		Keke::admin_show_msg($this->auth_lang(). 'ɾ��ʧ��',$_SERVER['HTTP_REFERER'],3,'','warning');
			
	}
	/**
	 * @ͨ����֤���
	 * @param string array $uid
	 * @param string $auth_code (realname,email,mobile...)
	 * @return bool
	 * @example ֧��������ˣ��뵥����ˣ�������֤��¼��״̬,
	 * ͬʱ�����û���auth��,�Ժ���û�����֤״̬����member_auth ��Ϳ�����
	 */
	public static function pass($uid,$auth_code){
		if(is_int($uid)){
			//��������
		  self::set_auth_status($uid, $auth_code, 1);
		}elseif(is_array($uid)){
			$size = sizeof($uid);
			//����ͨ��
			for($i=0;$i<$size;$i++){
				self::set_auth_status($uid[$i], $auth_code, 1);
			}
		}
		return TRUE;
	}
	/**
	 * @��֤��ͨ��
	 * @param string array $uid
	 * @return bool 
	 */
	public static function no_pass($uid,$auth_code){
		if(is_int($uid)){
			//��������
			self::set_auth_status($uid, $auth_code, 0);
		}elseif(is_array($uid)){
			$size = sizeof($uid);
			//����ͨ��
			for($i=0;$i<$size;$i++){
				self::set_auth_status($uid[$i], $auth_code, 0);
			}
		}
		return TRUE;
	}
	/**
	 * ɾ��ָ������֤��¼
	 * @param  int array  $uid
	 * @param unknown_type $auth_code
	 */
	public static function del($uid,$auth_code){
		
	}
	/**
	 * ��֤���   ֧������
	 * @param $item_ids ��֤���� ����Ϊ����
	 * @param $type �������
	 */
	public function review_auth($auth_ids,$type='pass'){
		global $_lang;
		global $kekezu;
		$kekezu->init_prom();
		$prom_obj = Keke::$_prom_obj;
		is_array($auth_ids) and $auth_ids=implode(",",$auth_ids);//��������
	
		$auth_info=$this->get_auth_info($auth_ids);//��֤��Ϣ��ȡ
	
		$size=sizeof($auth_info);
		$size>0&&$type=='pass' and $status='1' or $status='2';//�����״̬
	
		$size==0 and Keke::admin_show_msg($this->auth_lang(). $_lang['apply_not_exist_audit_fail'],$_SERVER['HTTP_REFERER']);
		if($size==1&&$auth_info[0]['auth_status']!='1'){//��ͨ������֤�޷�����
				
			$this->set_auth_status($auth_info[0][$this->_primary_key],$status);
			$this->set_auth_record_status($auth_info[0]['uid'], $status);
			/**��ҵ��֤ʱ�޸��û���ɫ**/
			$this->_auth_code=='enterprise' and $this->set_user_role($auth_info[0][uid],$type);
		}elseif($size>1){
			foreach ($auth_info as $v){
				if($v['auth_status']!='1'){//��ͨ������֤�޷�����
					$this->set_auth_record_status($v['uid'], $status);
					$this->set_auth_status($v[$this->_primary_key],$status);
					/**��ҵ��֤ʱ�޸��û���ɫ**/
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
					/** ע���ƹ����*/
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
	 * ��ҵ��֤ʱ�����û���ɫ
	 * @param $action ����  pass not_pass
	 * @param $uid  �û�ID
	 * @example user_role 1 Ϊ��ͨ�û�, 2 Ϊ��ҵ�û�
	 */
	public static  function set_user_role($uid,$action='pass'){
		$action=='pass' and $user_role='2' or $user_role='1';
		Dbfactory::execute(sprintf(" update %switkey_space set user_type='%d' where uid='%d'",TABLEPRE,$user_role,$uid));
	}
}
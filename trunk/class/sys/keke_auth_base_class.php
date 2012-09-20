<?php
/**
 * @author       Administrator
 */
keke_lang_class::load_lang_class('keke_auth_base_class');
abstract class keke_auth_base_class {
	public $_auth_item;
	
	public $_auth_code;
	public $_auth_name;
	public $_auth_obj;
	public $_auth_table_name;

	public $_tab_obj; //keke_table_obj����
	public $_primary_key; //����
	

	public function __construct($auth_code) {
		$this->_auth_code = $auth_code;
		$this->_auth_name = $auth_code . "_auth";
		$this->_auth_table_name = "witkey_auth_" . $auth_code;
	}
	/**
	 *��ȡ��֤������ϸ
	 * @param $auth_code ��֤���� ֧������
	 * @param int $find_str �����ֶ�  ��','������ ���뺬auth_code
	 * @param is_open �Ƿ���
	 * @param $w ��������
	 * @return ���˾�����֤��ʱ�᷵��һά���� 
	 */
	public static  function get_auth_item($auth_code=null,$find_str=null,$is_open=false,$w=null,$cache=true) {
		global $_cache_obj;
			$auth_code&&is_array($auth_code) and $auth_code=implode(",", $auth_code);
			$auth_code and ( is_array($auth_code) and  $where =" auth_code in ('$auth_code') " or $where =" auth_code = '$auth_code'" ) or $where = " 1 = 1";
			$find_str and $fds = $find_str or $fds = '*';
			$is_open and $where .= " and auth_open=1 ";
			$w      and $where.=" and ".$w;
			$cache==true and $c=null or $c=0;
			$auth_item=Keke::get_table_data($fds,"witkey_auth_item", $where,'listorder asc','','','auth_code',$c);
			if($auth_code&&!is_array($auth_code)){
				return $auth_item[$auth_code];
			}else{
				return $auth_item;
			}
				
				
	}
	/**
	 * ������֤��¼״̬
	 * @param  $auth_code ��Ϊ����
	 * @param $uid ��֤��id
	 * @param  $status
	 */
	public function set_auth_record_status($uid,$status) {
		return dbfactory::execute(sprintf(" update %switkey_auth_record set auth_status = '%d' where auth_code= '%s' and uid = '%d'",TABLEPRE,$status,$this->_auth_code,$uid));
	}
	/**
	 * ������֤��ϸ״̬
	 */
	public function set_auth_status($auth_id,$status){ 
		return dbfactory::execute(sprintf(" update %s set auth_status = '$status' where %s = '%d'",TABLEPRE.$this->_auth_table_name,$this->_primary_key,$auth_id));
	}
	
	/**
	 * ����/�༭ ��֤��¼
	 * @param $auth_code ��֤����
	 * @param $uid ��֤�û�
	 * @param $username ��֤�û���
	 * @param $end_time ��֤ʧЧʱ��  0 ����
	 * @param $data ��֤��ϸ����
	 * @param $auth_status Ĭ�ϼ�¼״̬
	 */
	public function add_auth_record($uid,$username, $auth_code,$end_time, $data = array(),$auth_status='0') {
		
		$record_obj  = new Keke_witkey_auth_record_class ();
		$record_info = dbfactory::get_one(sprintf(" select * from %switkey_auth_record where uid = '%d' and auth_code = '%s'",TABLEPRE,$uid,$auth_code));
		
		if ($record_info ['ext_data'] && $data) {//׷����֤data��¼
			$odata  =  unserialize ( $record_info ['ext_data'] );
			$odata and $data = array_merge ( $odata, $data );
		}
		$record_obj->setAuth_code ( $auth_code );
		$record_obj->setUid($uid );
		$record_obj->setUsername($username );
		is_array($data) and $data=serialize($data);
		$data and $record_obj->setExt_data ($data);
		$record_obj->setEnd_time ($end_time);
		
		if ($record_info) {
			$record_obj->setWhere ( 'record_id = ' . $record_info ['record_id'] );
			return $record_obj->edit_keke_witkey_auth_record ();
		} else {
			$record_obj->setAuth_status ($auth_status);
			return $record_obj->create_keke_witkey_auth_record ();
		}
	}
	/**
	 * ɾ����֤��¼
	 * @param $uid ��֤�û�
	 */
	public function del_auth_record($uid) {
		$res=dbfactory::execute(sprintf(" delete from %switkey_auth_record where uid= '%d' and auth_code = '%s'",TABLEPRE,$uid,$this->_auth_code));
	}
	/**
	 * ��֤�������ݴ���
	 * @param $data �����ύ����
	 */
	public function format_auth_apply($data){
		global $kekezu;
	
		$auth_info           = $this->get_auth_item($this->_auth_code,'auth_expir,auth_cash,auth_code','','',false);
		$data['uid']         = Keke::$_userinfo['uid'];
		$data['username']    = Keke::$_userinfo['username'];
		$data['start_time']  = time();
		$data['cash']        = $auth_info['auth_cash'];
		$data['auth_status'] = '0';
		$data['end_time']    = time()+$auth_info ['auth_expir'] * 3600 * 24;
		
		return $data;
	}
	/**
	 * ��ȡ��ϸ��֤��Ϣ
	 * @param $auth_ids ��֤���� ����Ϊ','���ӵ��ַ���
	 */
	public function get_auth_info($auth_ids){
		if(isset($auth_ids)){
			if(!stristr($auth_ids,',')) {
			 	return  dbfactory::query(sprintf(" select * from %s where %s = '%s'",TABLEPRE.$this->_auth_table_name,$this->_primary_key,$auth_ids));
			}else{
				
				return dbfactory::query(sprintf(" select * from %s where %s in (%s) ",TABLEPRE.$this->_auth_table_name,$this->_primary_key,$auth_ids));
			}
		}else{
			return array();
		}
	}
	/**
	 * ��ȡ�û�����������֤��Ϣ
	 * @param $uid �û�id
	 * @param $is_username �Ƿ��û���
	 */
	public function get_user_auth_info($uid,$is_username=0,$show_id=''){
		$sql="select * from ".TABLEPRE.$this->_auth_table_name;
		if($uid){
			$is_username=='0' and $sql.=" where uid = '$uid' " or $sql.=" where username = '$uid' ";
			$show_id and $sql.=" and ".$this->_primary_key."=".$show_id;
			$sql .=" order by $this->_primary_key desc";
			$data = dbfactory::query($sql);
			if(sizeof($data)==1){
				return $data[0];
			}else{
				return $data;
			}
		}else{
			return array();
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
	 * ǰ̨������֤��Ŀ
	 * @param $data �ⲿ������֤����
	 * @param $file_name �ϴ��ļ��� **�뱣�������ݿ��ֶ�һ�µ�����
	 */
	abstract function add_auth($data,$file_name = '');
	/**
	 * ɾ����֤����--֧������ɾ��
	 * @param $auth_ids ��ɾ����֤���� ����Ϊ����
	 * @see keke_auth_base_class::del_auth()
	 */
	public function del_auth($auth_ids) {
		global $_lang;
		
		is_array($auth_ids) and $ids=implode(",",$auth_ids) or $ids=$auth_ids;//��������
		$auth_info=$this->get_auth_info($ids);//��ȡʵ����֤����¼
		$size=sizeof($auth_info);
		$size==0 and Keke::admin_show_msg($this->auth_lang(). $_lang['apply_not_exist_delete_fail'],$_SERVER['HTTP_REFERER']);
		
		if($size==1&&$auth_info[0]['auth_status']!='1'){//������¼������ɾ�� ͨ���ļ�¼�޷���ɾ��
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			$res = $this->del_auth_record($auth_info[0]['uid']);//ɾ��record��¼
			/**��ҵ��֤ɾ��ʱ�����û�����**/		
			$this->_auth_code=='enterprise' and $this->set_user_role($auth_info[0][uid],'not_pass');
		}elseif($size>1){//������¼�����ɾ��
			$this->_tab_obj->del($this->_primary_key,$auth_ids);
			foreach ($auth_info as $v){
				$res = $this->del_auth_record($v['uid']);
				/**��ҵ��֤ɾ��ʱ�����û�����**/		
				$this->_auth_code=='enterprise' and $this->set_user_role($v[uid],'not_pass');
			}
		}
		Keke::empty_cache();
		$res && Keke::admin_show_msg($this->auth_lang(). $_lang['apply_delete_success'],$_SERVER['HTTP_REFERER'],3,'','success');
		Keke::admin_show_msg($this->auth_lang(). 'ɾ��ʧ��',$_SERVER['HTTP_REFERER'],3,'','warning');
			
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
	 */
	public function set_user_role($uid,$action='pass'){
		$action=='pass' and $user_role='2' or $user_role='1';
		dbfactory::execute(sprintf(" update %switkey_space set user_type='%d' where uid='%d'",TABLEPRE,$user_role,$uid));
	}

}
<?php
keke_lang_class::load_lang_class('keke_auth_enterprise_class');
class keke_auth_enterprise_class extends keke_auth_base_class{

	public static function get_instance($auth_code='enterprise') {
		static $obj = null;
		if ($obj == null) {
			$obj = new keke_auth_enterprise_class($auth_code);
		}
		return $obj;
	}
	public function __construct($auth_code='enterprise') {
		parent::__construct($auth_code);
		$this->_primary_key     ='enterprise_auth_id';
		$this->_tab_obj         =keke_table_class::get_instance($this->_auth_table_name);
	}
	public static function get_auth_step($auth_step=null,$auth_info=array()){
		$step='step1';
		if($auth_step){
			$step=$auth_step;
		}elseif($auth_info){
			$auth_status = intval($auth_info['auth_status']);
			if($auth_status==3){
				$step = 'step1';
			}elseif($auth_status==0){
				$step="step3";
			}else{
				$step="step4";
			}
		}
		return $step;
	}
/**
	 * ǰ̨������֤��Ŀ
	 * @param $data �ⲿ������֤����
	 * @param $file_name �ϴ��ļ��� **�뱣�������ݿ��ֶ�һ�µ�����
	 *  * @param $is_jump trueĬ����תҳ�棬false ����ֵ
	 */
	public function add_auth($data,$file_name = '',$is_jump = true) {
		global $kekezu,$user_info,$_lang;
		
		$data=$this->format_auth_apply($data);//��ʽ���ύ����
		$file_name and $licen_pic = keke_file_class::upload_file($file_name);//��֤ͼƬ�ϴ�
		
		if (! $licen_pic || ! $data ['licen_num']) {
			Keke::show_msg ( $this->auth_lang().$_lang['apply_submit_fail'],$_SERVER['HTTP_REFERER'], 3, $this->auth_lang().$_lang['apply_submit_fail_for_info_little'], 'warning' );
		} 
		else {
			$licen_pic and $data[$file_name]=$licen_pic;
			$auth_info=$this->get_user_auth_info($user_info[uid]);
			if($auth_info){
				$success=$this->_tab_obj->save($data,array($this->_primary_key=>$auth_info[$this->_primary_key]));
				$this->set_auth_record_status($user_info['uid'], '0');
				dbfactory::execute(sprintf(" update %switkey_space set user_type='2' where uid='%d'",TABLEPRE,$auth_info[uid]));//�����û���ɫΪ��ҵ
			}else{
				$success=$this->_tab_obj->save($data);
			}
			/**��ҵ�ռ併��**/
			$shop_info=dbfactory::get_one(sprintf(" select shop_id,shop_type,shop_name from %switkey_shop where uid='%d'",TABLEPRE,$user_info[uid]));
			if($shop_info&&$shop_info['shop_type']=='2'){
				$msg=new Keke_msg_class();
				if ($msg->validate ( 'space_change' )) {
					$v=array($_lang['space_name']=>$shop_info['shop_name']);
					$msg->send_message($user_info ['uid'] , $user_info ['username'], 'space_change',$_lang['space_changed'],$v,$user_info [email]);
				}
			}
		}
		if ($success) {//�����¼
			//��֤�շѡ����������¼
			$data['cash'] > 0 and keke_finance_class::cash_out ($data['uid'],$data ['cash'],$this->_auth_name, $data ['cash'], $this->_auth_name, $success );
			
			$data['start_time']==$data['end_time'] and $end_time=$data['end_time'] or $end_time=0;
			$this->add_auth_record($data['uid'], $data['username'], $this->_auth_code,$end_time);//��ӽ�����֤��¼
			if($is_jump){
				Keke::show_msg ( $this->auth_lang().$_lang['apply_submit_success'],"index.php?do=user&view=payitem&op=auth&auth_code=enterprise&auth_step=step3&ver=1#userCenter", 3, $this->auth_lang().$_lang['apply_success_and_wait_audit'] ,'success');
			}else{
				return true;
			}
			
		}
	}
}
?>
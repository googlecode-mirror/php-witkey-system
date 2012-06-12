<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @desc Ȩ�����Ϳ�����
 * @version 2011-08-25 13:27:34
 */
class tender_priv_class extends keke_privission_class{
	
	public static function get_instance($model_id,$user_info=null,$op_code=null,$role=null) {
		static $obj = null;
		if ($obj == null) {
			$obj = new tender_priv_class($model_id,$user_info,$op_code,$role);
		}
		return $obj;
	}
	
	public function __construct($model_id,$user_info=null,$op_code=null,$role=null){
		parent::__construct($model_id);
		$this->_uid       =  $user_info['uid'];
		$this->_user_info =  $user_info;
		$this->_op_code   =  $op_code;
		$this->_role      =  $role;
	}
	/**
	 * ��ȡָ��ģ����ָ�������û��Ĳ���Ȩ��
	 * @param int $task_id ������
	 * @param int $mode_id ģ�ͱ��
	 * @param $user_info �û���Ϣ
	 * @param int $role �û���ɫ   Ĭ��Ϊ1=>����
	 * @return boolean
	 */
	public static function get_priv($task_id,$mode_id,$user_info,$role='1') {
		return parent::get_priv($task_id,$mode_id, $user_info,$role);
	}
}

?>
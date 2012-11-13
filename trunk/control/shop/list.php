<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��̨�б�������
 * @author Michael
 * @version 2.2
   2012-10-21
 */
Keke_lang::loadlang ('list','shop');
abstract class Control_shop_list extends Control_admin{
    
	/**
	 *
	 * @var ����id
	 */
	protected  $_sid ;
	protected  $_base_uri;
	
	function __construct($request, $response){
		parent::__construct($request, $response);
		$this->_sid = intval($_GET['sid']);
		$this->_base_uri  = BASE_URL."/index.php/shop/".$this->_model_code."_admin_list";
	}
    /**
     * �����Ƽ�
     */
    public function set_recommend(){
    	//�ı������is_top Ϊ1
    	$where = "sid = '$this->_sid'";
    	DB::update('witkey_service')->set(array('is_top'))->value(array(1))->where($where)->execute();
    }
    /**
     * ȡ�������Ƽ�
     */
    public function set_unrecommend(){
    	//�ı������is_top Ϊ0
    	$where = "sid = '$this->_sid'";
    	DB::update('witkey_service')->set(array('is_top'))->value(array(0))->where($where)->execute();
    }
    /**
     * �Ƿ����ɾ�� ��Ʒ
     * ��Ʒû�н��׵Ķ�������ɾ��  status(ok,send,confirm,arbitral),��Щ״̬�Ĳ���ɾ��
     * @return bool
     */
    public function has_del($sid=NULL){
    	if($sid===NULL){
    		$sid  = $this->_sid;
    	}
    	$sql = "SELECT c.order_status FROM `:Pwitkey_service` as a \n".
				"LEFT JOIN :Pwitkey_order_detail as b on a.sid = b.obj_id and b.obj_type = 'service'\n".
				"left join :Pwitkey_order as c on b.order_id = c.order_id\n".
				"where a.sid = :sid ";
    	$status = DB::query($sql)->tablepre(":P")->param(":sid", $sid)->get_count()->execute();
    	$status_arr = array('ok','send','confirm','arbitral');
    	return (bool)!in_array($status,$status_arr);
     
    }
     
    
    /**
     * ��ͨ�����
     * ״̬״̬1->10 ���ʧ��
     */
    public function set_no_pass(){
    	//�ı�����״̬
    	$this->set_status(10);
    	$task_info = $this->get_task_info();
    	//�˻������ͽ�
    	Keke::init_model();
    	$model_name = Keke::$_model_list[$task_info['model_id']]['model_name'];
    	$data = array($model_name,$task_info['task_title']);
    	Sys_finance::init_mem('task_fail', $data);
    	//ֻ�˻��ͽ��������ò���
    	Sys_finance::cash_in ( $task_info ['uid'], $task_info ['task_cash'], 0, 'task_fail', 'admin', 'task', $task_info ['sid'] );
    }
    
    
    /**
     * ��ȡ������Ϣ
     * @param int $sid
     * @return Ambigous <string, unknown, Ambigous>
     */
    public function get_service_info($sid = NUll){
    	if($sid == NULL){
    		$where = "sid = '$this->_sid'";
    	}else{
    		$where = "sid = '$sid'";
    	}
    	return  DB::select()->from('witkey_service')->where($where)->get_one()->execute();
    }
    /**
     * ��������״̬
     * @param int $status
     * @param int $sid
     */
    public function set_status($status,$sid=NULL){
    	if($sid===NULL){
    		$where = "sid = '$this->_sid'";
    	}else{
    		$where = "sid = '$sid'";
    	}
    	return (bool)DB::update('witkey_service')->set(array('task_status'))->value(array($status))->where($where)->execute();
    }
    /**
     * ��̨����༭���Բ�������
     * @param int $status ����״̬
     * @return multitype:unknown Ambigous <>
     */
    public static function can_operate($status) {
    	global $_lang;
    	$operate = array ();
    	switch ($status) {
    		case "1" : //�����
    			$operate ['pass'] = $_lang['pass_audit'];
    			$operate ['nopass'] = $_lang['pass_audit'];
    			break;
    		case "2" : //Ͷ��
    		case "3" : //ѡ��
    		case "4" : //ͶƱ
    		case "5" : //��ʾ
    		case "6" : //����
    			$operate ['freeze'] = $_lang['freeze_task'];
    			break;
    		case "7" : //����
    			$operate ['unfreeze'] = $_lang['unfreeze_task'];
    	}
    	return $operate;
    }
    /**
     * ��ȡ����ĸ���
     * @param int $sid
     * @return Ambigous <string, unknown, Ambigous>
     */
    public static  function get_service_file($sid){
    	$where ="obj_type='service' and obj_id = '$sid'";
    	return DB::select()->from('witkey_file')->where($where)->execute();
    }

    /**
     * ɾ��ָ������,Ĭ��ɾ����ǰ����ķ���
     * @return number
     */
    public function del_service($sid = NULL){
    	if($sid===NULL){
    		$sid = $this->_sid;
    	}
    	$where = "sid = '$sid'";
    	
    	//����ĸ���
    	self::del_files_by_service($sid);
    	//Ҫɾ������,�����
    	$sql = "DELETE a,b from :Pwitkey_service as a LEFT JOIN :Pwitkey_comment as b\n".
				"on a.sid = b.obj_id and b.obj_type = 'service' \n".
				"where a.sid = :sid ";
    	
     	return (int)DB::query($sql,Database::DELETE)->param(":sid", $sid)->tablepre(":P")->execute();
    }
    /**
     * ɾ������+����ĸ�����+��¼
     * @param int $sid
     */
    public static function del_files_by_service($sid){
    	$files = (array)self::get_task_file($sid);
    	$file_id = array();
    	foreach ($files as $v){
    		$path = S_ROOT.$v['save_name'];
    		if(is_file($path)){
    			unlink($path);
    		}
    		$file_id[] = $v['file_id'];
    	}
    	if(empty($file_id)){
    		return TRUE;
    	}
    	$ids = implode(',', $file_id);
    	$where = " file_id in($ids) ";
    	return DB::delete('witkey_file')->where($where)->execute();
    }
}
<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.2
 * 2012-10-9 ����17��30
 */
class Control_admin_user_trans extends Control_admin{
	 
	private $_trans_status;
	private $_trans_object;
	
	function __construct($request, $response){
		parent::__construct($request, $response);
		//״̬
		$this->_trans_status =  keke_report_class::get_transrights_status();
		//����
		$this->_trans_object = keke_report_class::get_transrights_obj();
	}
	
	function action_index($type='work'){
		global $_K,$_lang;
		//��Ҫ���б�����ʾ���ֶ�
		$fields = '`report_id`,`origin_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`report_type`,`op_uid`,`op_username`,`report_desc`';
		//�����в�ѯ���ֶ�
		$query_fields = array('report_id'=>$_lang['id'],'report_status'=>'��ǰ״̬','on_time'=>$_lang['time']);
		
		//����uri
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$del_uri = $base_uri.'/del';
		//ͳ�Ƶ�����������д�������ٲ�ѯһ��
		$count = intval($_GET['count']);
		//Ĭ�������ֶ�
		$this->_default_ord_field = 'on_time';
		//��ȡ��ҳ����
		extract($this->get_url($base_uri));
		//��������
		$trans_status = $this->_trans_status;  
		 
		$rp_type =  keke_report_class::get_report_type();
		
		$where .= " and  obj = '$type' ";
		
		//��ѯ���������µ�����
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//��ѯ����������
		$report_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_object;

		require keke_tpl::template('control/admin/tpl/user/trans');
	}
 	/**
	 * �û����飬�ͷ����Իظ�
	 */
	function action_complaint(){
		global $_K,$_lang;
		
		$fields = '`report_id`,`obj`,`username`,`on_time`,`op_uid`,`op_username`,`report_desc`,`report_status`,`op_result`';
		
		$query_fields = array('report_id'=>$_lang['id'],'report_status'=>'��ǰ״̬','on_time'=>$_lang['time']);
		
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$del_uri = $base_uri.'/comment_del';
		//ͳ�Ƶ�����������д�������ٲ�ѯһ��
		$count = intval($_GET['count']);
		//Ĭ�������ֶ�
		$this->_default_ord_field = 'on_time';
		//��ȡ��ҳ����
		extract($this->get_url($base_uri.'/complaint'));
		$where .= " and obj = 'comment'";
		$trans_status = $this->_trans_status;
		//��ѯ���������µ�����
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		 
		require keke_tpl::template('control/admin/tpl/user/steer');
	}
	
	/**
	 * ��Ʒ�ٱ� 
	 */
	function  action_product(){
		 $this->action_index('product');
	}
	
	/**
	 * ���̾ٱ�
	 */
	function action_shop(){
		 $this->action_index('shop');
	}
	/**
	 * �ͷ��ظ��ý���
	 */
	function action_reply(){
		$columns = array('report_status','op_result','op_time','op_uid');
		if(CHARSET == 'gbk'){
			$_POST['data'] = Keke::utftogbk($_POST['data']);
		}
		$values = array(4,$_POST['data'],time(),$_SESSION['admin_uid']);
		$where = "report_id = '{$_GET['report_id']}'";
		DB::update('witkey_report')->set($columns)->value($values)->where($where)->execute();
	} 
	/**
	 * ����ٱ��Ͳ鿴������
	 */
	function action_process(){
		global $_K,$_lang;
		//��ȡ��������type,�û����ض�Ӧ�������б���ٱ��б�
		$type = $_GET['type'];
	 
		//��ȡ���ݹ����Ķ�report_id��������ѯ��Ӧ��report�����Ϣ
		$report_id = $_GET['report_id'];
		//��Ӧ����Ϣ
		$report_info = keke_report_class::get_report_info ( $report_id );
		//�ٱ�����Ϣ
		$user_info = Keke_user::instance()->get_user_info ( $report_info ['uid'] ); 
		//�Է���Ϣ
		$to_userinfo = Keke_user::instance()->get_user_info ( $report_info ['to_uid'] );
		//��ȡprocessҳ�����Ϣ
		$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_object; 
		//��������
		$trans_status = $this->_trans_status;
		$rp_type =  keke_report_class::get_report_type();
		require keke_tpl::template('control/admin/tpl/user/trans_process');
	}
	/**
	 * ����ٱ�
	 */
	function action_save(){
		global $_lang;
		Keke::formcheck($_POST['formhash']);
		$_POST = Keke_tpl::chars($_POST);
		if(isset($_POST['btn_report'])){
			$status = 4;
		}else{
			$status = 3;
		}
		$columns = array('report_status','op_result','op_time','op_uid');
		$values = array($status,$_POST['op_result'],time(),$_SESSION['admin_uid']);
		$where = "report_id = '{$_POST['report_id']}'";
		DB::update('witkey_report')->set($columns)->value($values)->where($where)->execute();
		
		Keke::show_msg($_lang['submit_success'],$this->request->referrer());
		 
	}
	/**
	 * ɾ�������ٱ���Ϣ
	 */
	function action_del(){
		$report_id = $_GET['report_id'];
		if ($report_id){
			$where .=' report_id ='.$report_id;
		}
		echo Model::factory('witkey_report')->setWhere($where)->del();
	}
}


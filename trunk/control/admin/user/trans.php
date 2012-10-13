<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.2
 * 2012-10-9 ����17��30
 */
class Control_admin_user_trans extends Controller{
	private $_action_arr ;
	private $_trans_status;
	private $_trans_object;
	
	function __construct(){
		//��ѯ�ٱ���άȨ��Ͷ������
		$this->_action_arr  = keke_report_class::get_transrights_type();
		//��������
		$this->_trans_status =  keke_report_class::get_transrights_status();
		$this->_trans_object = keke_report_class::get_transrights_obj();
	}
	
	function action_index($type=NULL,$report_status=NULL){
		global $_K,$_lang;
		//��Ҫ���б�����ʾ���ֶ�
		$fields = '`report_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`op_uid`,`op_username`,`report_desc`';
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
		//��ѯ�ٱ���άȨ��Ͷ������
		$action_arr =  $this->_action_arr; //keke_report_class::get_transrights_type();
		//��������
		$trans_status = $this->_trans_status; //keke_report_class::get_transrights_status();
		//��ȡ����Ĳ���type������report��rights��complaint
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'report';
		}
		if($report_status){
			$where .= " and report_status = '$report_status'";
			$uri .="&report_status ='$report_status'"; 
		}
		//������ת��Ϊ���ݿ�洢�ֶ�1,2,3
		$rp_type = $action_arr[$type][0];
		//��Ӳ�ѯ����
		$where .= " and  report_type = '$rp_type' ";
		//���url��������������άȨ��ȥ����Ͷ��
		$uri .= "&type=$type";
		//��ѯ���������µ�����
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//��ѯ����������
		$report_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_object;
// 		var_dump($trans_object);die;
		require keke_tpl::template('control/admin/tpl/user/trans');
	}
	/**
	 * һ�������ֱ��Ǿٱ�������
	 */
	function action_report(){
		$report_status = $_GET['report_status'];
	 	$this->action_index('report',$report_status);
	}
	function action_rights(){
		$report_status = $_GET['report_status'];
		$this->action_index('rights',$report_status);
	}
	function action_complaint(){
		$report_status = $_GET['report_status'];
		$this->action_index('complaint',$report_status);
	} 
	/**
	 * ����ٱ��Ͳ鿴������
	 */
	function action_process(){
		global $_K,$_lang;
		//��ȡ��������type,�û����ض�Ӧ�������б���ٱ��б�
		$type = $_GET['type'];
		//��ѯ�ٱ���άȨ��Ͷ������
		$action_arr = $this->_action_arr; 
		//���͵ĺ��֣��ٱ���άȨ��Ͷ��
		$rep_type_chinese = $action_arr[$_GET['type']][1];
		//��ȡ���ݹ����Ķ�report_id��������ѯ��Ӧ��report�����Ϣ
		$report_id = $_GET['report_id'];
		//��Ӧ����Ϣ
		$report_info = keke_report_class::get_report_info ( $report_id );
		//�ٱ�����Ϣ
		$user_info = keke_user_class::get_user_info ( $report_info ['uid'] ); 
		//�Է���Ϣ
		$to_userinfo = keke_user_class::get_user_info ( $report_info ['to_uid'] );
		//��ȡprocessҳ�����Ϣ
		$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_status; 
		//��������
		$trans_status = $this->_trans_status;
 
		require keke_tpl::template('control/admin/tpl/user/trans_process');
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


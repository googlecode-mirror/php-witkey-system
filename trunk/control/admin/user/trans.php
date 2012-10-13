<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.2
 * 2012-10-9 下午17：30
 */
class Control_admin_user_trans extends Controller{
	private $_action_arr ;
	private $_trans_status;
	private $_trans_object;
	
	function __construct(){
		//查询举报，维权，投诉类型
		$this->_action_arr  = keke_report_class::get_transrights_type();
		//处理的情况
		$this->_trans_status =  keke_report_class::get_transrights_status();
		$this->_trans_object = keke_report_class::get_transrights_obj();
	}
	
	function action_index($type=NULL,$report_status=NULL){
		global $_K,$_lang;
		//需要在列表中显示的字段
		$fields = '`report_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`op_uid`,`op_username`,`report_desc`';
		//搜索中查询的字段
		$query_fields = array('report_id'=>$_lang['id'],'report_status'=>'当前状态','on_time'=>$_lang['time']);
		//基本uri
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$del_uri = $base_uri.'/del';
		//统计的总数，这里写出避免再查询一次
		$count = intval($_GET['count']);
		//默认排序字段
		$this->_default_ord_field = 'on_time';
		//获取分页条件
		extract($this->get_url($base_uri));
		//查询举报，维权，投诉类型
		$action_arr =  $this->_action_arr; //keke_report_class::get_transrights_type();
		//处理的情况
		$trans_status = $this->_trans_status; //keke_report_class::get_transrights_status();
		//获取后面的参数type，包括report，rights，complaint
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'report';
		}
		if($report_status){
			$where .= " and report_status = '$report_status'";
			$uri .="&report_status ='$report_status'"; 
		}
		//将类型转化为数据库存储字段1,2,3
		$rp_type = $action_arr[$type][0];
		//添加查询条件
		$where .= " and  report_type = '$rp_type' ";
		//添加url参数，用来区分维权，去报，投诉
		$uri .= "&type=$type";
		//查询各种类型下的数据
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//查询出来的数据
		$report_list = $data_info['data'];
		//显示分页的页数
		$pages = $data_info['pages'];
		//所属的类别，商品，任务，稿件，订单
		$trans_object = $this->_trans_object;
// 		var_dump($trans_object);die;
		require keke_tpl::template('control/admin/tpl/user/trans');
	}
	/**
	 * 一下三个分别是举报的三类
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
	 * 处理举报和查看处理方案
	 */
	function action_process(){
		global $_K,$_lang;
		//获取穿过来的type,用户返回对应的类型列表，如举报列表
		$type = $_GET['type'];
		//查询举报，维权，投诉类型
		$action_arr = $this->_action_arr; 
		//类型的汉字，举报，维权，投诉
		$rep_type_chinese = $action_arr[$_GET['type']][1];
		//获取传递过来的额report_id，用来查询对应的report表的信息
		$report_id = $_GET['report_id'];
		//对应的信息
		$report_info = keke_report_class::get_report_info ( $report_id );
		//举报方信息
		$user_info = keke_user_class::get_user_info ( $report_info ['uid'] ); 
		//对方信息
		$to_userinfo = keke_user_class::get_user_info ( $report_info ['to_uid'] );
		//获取process页面的信息
		$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
		//所属的类别，商品，任务，稿件，订单
		$trans_object = $this->_trans_status; 
		//处理的情况
		$trans_status = $this->_trans_status;
 
		require keke_tpl::template('control/admin/tpl/user/trans_process');
	}
	/**
	 * 删除单条举报信息
	 */
	function action_del(){
		$report_id = $_GET['report_id'];
		if ($report_id){
			$where .=' report_id ='.$report_id;
		}
		echo Model::factory('witkey_report')->setWhere($where)->del();
	}
}


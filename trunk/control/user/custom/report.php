<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-客服管理首页 --举报
 * @author Michael
 * @version 2.2
   2012-10-25
 */
class Control_user_custom_report extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'custom';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'report';
	/**
	 * 我举报的
	 * @param string $my
	 */
	function action_index($my = NULL){
		//定义全局变量与语言包
		global $_lang , $_K;
		//要查询的字段       要显示的字段,即SQl中SELECT要用到的字段
		//编号 	被举报人 	类型 	原因 	附件 	状态 	时间
		$fields = "`report_id`,`to_username`,`report_type`,`report_desc`,`report_file`,`report_status`,`on_time`,`op_result`";
		//查询字段
		$query_fields = array('report_id'=>'编号','on_time'=>'时间','report_desc'=>'原因');
		//总记录数
		$count = intval($_GET['count']);
		//当前页
		$page = intval($_GET['page']);
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'on_time';
		//基本uri
		$base_uri = BASE_URL.'/index.php/user/custom_report';
		//获取分页条件
		extract($this->get_url($base_uri));
		//我收到的举报
		$m = $my;
		//$m&&$_SESSION['uid'] and $where .= " and to_uid = '1'".$_SESSION['uid'] or $where .=" and uid = ".$_SESSION['uid'];
		$m and $base_uri = BASE_URL.'/index.php/user/custom_report/my';
		//查询     获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$base_uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//处理的情况
		$trans_status = $this->_trans_status;
		//举报类型
		$rp_type =  keke_report_class::get_report_type();
		require Keke_tpl::template('user/custom/report');
	}
	/**
	 * 我收到的
	 */
	function action_my(){
		$this->action_index('my');
	}
	
	
}
<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 系统日志
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24下午03:46:14
 */
class Control_admin_tool_log extends Controller{
	
	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `log_id`,`username`,`log_content`,`log_ip`,`log_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('log_id'=>$_lang['id'],'log_content'=>$_lang['name'],'log_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//tool本来是一个目录，由于没有定义tool为目录的路由,所以这个控制层的文件来too_file So这里不能写为tool/file
		$base_uri = BASE_URL."/index.php/admin/tool_log";
		
		//添加编辑的uri,add这个action 是固定的
		//$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'log_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_system_log')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//用户组
		$group_arr = keke_admin_class::get_user_group ();
		
		require Keke_tpl::template('control/admin/tpl/tool/log');
		
	}
	/**
	 * 日志记录的删除,支持单与多删除
	 */
	function action_del(){
		//删除单条,这里的file_id 是在模板上的请求连接中有的
		if($_GET['log_id']){
			$where = 'log_id = '.$_GET['log_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'log_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_system_log')->setWhere($where)->del();
		
	}
	
	
}
/* 
Keke::admin_check_role (19);
$group_arr = keke_admin_class::get_user_group ();
//初始化对象
$table_obj = keke_table_class::get_instance ( 'witkey_system_log' );
intval ( $page ) or $page = 1;
intval ( $wh ['page_size'] ) or $wh ['page_size'] = 10;
$url = "index.php?do=$do&view=$view&txt_username=$txt_username&txt_start_time=$txt_start_time&txt_log_id=$txt_log_id&txt_end_time=$txt_end_time&page=$page&w[ord]={$w['ord']}&wh[slt_page_size]={$wh['slt_page_size']}";
//删除
if ($ac == 'del') {
	$res = $table_obj->del ( 'log_id', $log_id );
	Keke::admin_system_log($_lang['delete_sys_log'] . $log_id ); //日志记录
	$res and Keke::admin_show_msg ($_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ($_lang['delete_fail'], $url,3,'','warning' );
} elseif ($ac == 'del_all') {
	$sql = sprintf ( "TRUNCATE TABLE %switkey_system_log", TABLEPRE );
	Dbfactory::execute ( $sql );
	Keke::admin_system_log( $_lang['empty_system_log'] );
	Keke::admin_show_msg ( $_lang['empty_system_log_success'], $url,3,'','success' );
} elseif ($sbt_action) {
	$res = $table_obj->del ( 'log_id', $ckb );
	Keke::admin_system_log( $_lang['mulit_delete_log'] );
	$res and Keke::admin_show_msg ($_lang['mulit_operate_success'], $url,3,'','success' ) or Keke::admin_show_msg ($_lang['mulit_operate_fail'], $url,3,'','warning' );

} else {
	//获取查询条件
	$where = " 1 = 1";
	empty ( $txt_log_id ) or $where .= " and log_id =" . intval ( $txt_log_id );
	empty ( $txt_end_time ) or $where .= " and log_time <" . Keke::sstrtotime ( $txt_end_time );
	empty ( $txt_start_time ) or $where .= " and log_time >" . Keke::sstrtotime ( $txt_start_time );
	empty ( $txt_username ) or $where .= " and username like '%$txt_username%'";
	empty ( $txt_content ) or $where .= " and log_content like '%$txt_content%'";
	if(is_array($w['ord'])){
		$where .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	}else{
 
			$where .= " order by log_id desc";
	}
	 

	//查询
	$d = $table_obj->get_grid ( $where, $url, $page, $wh ['page_size'],null,1,'ajax_dom' );
	$log_data = $d ['data'];
	$pages = $d ['pages'];

}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */
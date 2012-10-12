<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 后面实名认证列表页
 * @author Michael
 * @version 2.2
   2012-10-11
 */
class Control_auth_realname_admin_list extends Controller {
	/**
	 * 初始化后台列表页
	 * 显示所有的认证记录，待审核的记录在最前边
	 */
	function action_index(){
	   global $_K,$_lang;
	   /* echo $_K['directory'];
	   echo "<br>";
	   echo $_K['control'];  */
	   $fields = ' `r_id`,`uid`,`username`,`realname`,`id_card`,`id_pic`,`cash`,`start_time`,`auth_status`,`end_time`';
	   //要查询的字段,在模板中显示用的
	   $query_fields = array('r_id'=>$_lang['id'],'realname'=>$_lang['name'],'start_time'=>$_lang['time']);
	   //总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
	   $count = intval($_GET['count']);
	   //基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
	   $base_uri = BASE_URL."/index.php/auth/realname_admin_list";
	   //添加编辑的uri,add这个action 是固定的
	   $add_uri =  $base_uri.'/add';
	   //删除uri,del也是一个固定的，写成其它的，你死定了
	   $del_uri = $base_uri.'/del';
	   //默认排序字段，这里按时间降序
	   $this->_default_ord_field = 'start_time';
	   //这里要口水一下，get_url就是处理查询的条件
	   extract($this->get_url($base_uri));
	 
	   //获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
	   $data_info = Model::factory('witkey_auth_realname')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
	   //列表数据
	   $list_arr = $data_info['data'];
	   //分页数据
	   $pages = $data_info['pages'];
	   
	   require Keke_tpl::template ( 'control/auth/realname/tpl/admin_list' );
	}
	/**
	 * 初始化认证信息页面
	 */
	function action_add(){
		global $_K,$_lang;
		
		require Keke_tpl::template ( 'control/auth/realname/tpl/admin_info' );
	}
	/**
	 * 认证通过
	 */
	function action_pass(){
		 global $_lang;
		 $auth_code = 'realname';
		 if($_GET['u_id']){
		 	$uid = $_GET['u_id'];
		 }else{
		 	$uid = $_POST['ckb'];
		 }
		 Keke_user_auth::pass($uid, $auth_code);
		 Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
	/**
	 * 认证不通过
	 */
	function action_no_pass(){
		global $_lang;
		$auth_code = 'realname';
		if($_GET['u_id']){
			$uid = $_GET['u_id'];
		}else{
			$uid = $_POST['ckb'];
		}
		Keke_user_auth::no_pass($uid, $auth_code);
		Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
	/**
	 * 单条删除与多条删除 
	 */
	function action_del(){
		global $_lang;
		$auth_code = 'realname';
		if($_GET['u_id']){
			$uid = $_GET['u_id'];
		}else{
			$uid = $_POST['ckb'];
		}
		Keke_user_auth::no_pass($uid, $auth_code);
		Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
}

/* $realname_obj = new Keke_witkey_auth_realname_class (); //实例化实名认证表
$url = "index.php?do=" . $do . "&view=" . $view . "&auth_code=" . $auth_code . "&w[page_size]=" . $w [page_size] . "&w[realname_a_id]=" . $w [realname_a_id] . "&w[username]=" . $w [username] . "&w[auth_status]=" . $w [auth_status]; //跳转地址
if (isset ( $ac )) {
	switch ($ac) {
		case "pass" : //单条通过认证操作
			kekezu::admin_system_log($obj.$_lang['pass_realname_auth']);
			$auth_obj->review_auth ( $realname_a_id, 'pass' );
			break;
		case "not_pass" : //单条不通过认证操作
			kekezu::admin_system_log($obj.$_lang['nopass_realname_auth']);
			$auth_obj->review_auth ( $realname_a_id, 'not_pass' );
			break;
			;
		case 'del' : //单条删除认证申请
			kekezu::admin_system_log($obj.$_lang['delete_realname_auth']);
			$auth_obj->del_auth ( $realname_a_id );
			break;
	}
} elseif (isset ( $sbt_action )) {
	$keyids = $ckb;

	switch ($sbt_action) {
		case $_lang['mulit_delete'] : //批量删除
			kekezu::admin_system_log($_lang['mulit_delete_realname_auth']);
			$auth_obj->del_auth ( $keyids );
			break;
			;
		case $_lang['mulit_pass'] : //批量审核
			kekezu::admin_system_log($_lang['mulit_pass_realname_auth']);
			$auth_obj->review_auth ( $keyids, 'pass' );
			break;
			;
		case $_lang['mulit_nopass'] : //批量不审核

			kekezu::admin_system_log($_lang['mulit_nopass_realname']);
			$auth_obj->review_auth ( $keyids, 'not_pass' );
			break;
	}
} else //列表
{
	$where = " 1 = 1 "; //默认查询条件
	($w ['auth_status'] === "0" and $where .= " and auth_status = 0 ") or ($w ['auth_status'] and $where .= " and auth_status = '$w[auth_status]' "); //搜索认证状态
	intval ( $w ['realname_a_id'] ) and $where .= " and realname_a_id = " . intval ( $w ['realname_a_id'] ) . ""; //搜索认证编号
	$w ['username'] and $where .= " and username like '%" . $w ['username'] . "%' "; //搜索认证标题
	$where.=" order by realname_a_id desc ";
	intval ( $w ['page_size'] ) and $page_size = intval ( $w ['page_size'] ) or $page_size = 10; //每页显示多少条，默认10
	$realname_obj->setWhere ( $where ); //查询统计
	$count = $realname_obj->count_keke_witkey_auth_realname ();
	intval ( $page ) or $page = 1 and $page = intval ( $page );
	$kekezu->_page_obj->setAjax(1);
	$kekezu->_page_obj->setAjaxDom("ajax_dom");
	$pages = $kekezu->_page_obj->getPages ( $count, $page_size, $page, $url );
	//查询结果数组
	$realname_obj->setWhere ( $where . $pages [where] );
	$realname_arr = $realname_obj->query_keke_witkey_auth_realname ();
	require $kekezu->_tpl_obj->template ( "auth/" . $auth_dir . "/control/admin/tpl/auth_list" );
} */
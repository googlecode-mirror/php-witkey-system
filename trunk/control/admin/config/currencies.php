<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * 汇率配置
 * @author Michael
 *
 */

class Control_admin_config_currencies extends Controller{
	
	private $_base_uri ;
	
	function __construct(){
		$this->_base_uri = BASE_URL."/index.php/admin/config_currencies";
	}
	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `currencies_id`,`title`,`code`,`symbol_left`,`symbol_right`,`decimal_point`,`thousands_point`,`decimal_places`,`value` ';
		//要查询的字段,在模板中显示用的
		//$query_fields = array('currencies_id'=>$_lang['id'],'title'=>$_lang['name'],'code'=>'币种');
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = $this->_base_uri;
		
		//添加编辑的uri,add这个action 是固定的
		$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//更新uri 
		$update_uri = $base_uri.'/update';
		//默认排序字段，这里按时间降序
		//$this->_default_ord_field = 'on_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_currencies')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('control/admin/tpl/config/currencies');
	}
	/**
	 * 添加汇率
	 */
	function action_add(){
		global $_K,$_lang;
		
		if($_GET['cid']){
			$cid = intval($_GET['cid']);
			$where = " currencies_id = '$cid' ";
			$currency_config = Model::factory('witkey_currencies')->setWhere($where)->query();
			$currency_config = $currency_config[0];
		}
		//定义默认的的默认货币符号
		$default_currency = $_K['currency'];
		require Keke_tpl::template('control/admin/tpl/config/currencies_add');
	}
	/**
	 * 通过goole更新汇率
	 */
	function action_update(){
		global $_K,$_lang;
		$code = $_GET['code'];
		$cur = new keke_curren_class();
		if($code){
			//更新指定的汇率
			$cur->update(FALSE,$code);
		}else{
			//批量更新
			$cur->update(TRUE);
		}
		Keke::show_msg ( $_lang['update_mi_success'], 'index.php/admin/config_currencies','success' );
	}
	/**
	 * 保存汇率配置
	 */
	function action_save(){
		global $_lang;
		//form检查
		Keke::formcheck($_POST['formhash']);
		//数据
		$array = array('title'=>$_POST['title'],
				'code'=>$_POST['code'],
				'symbol_left'=>$_POST['symbol_left'],
				'symbol_right'=>$_POST['symbol_right'],
				'decimal_point'=>$_POST['decimal_point'],
				'thousands_point'=>$_POST['thousands_point'],
				'decimal_places'=>$_POST['decimal_places'],
				'value'=>$_POST['value']);
		if($_POST['default_cur']){
			DB::update('witkey_config')->set(array('v'))->value(array($_POST['default_cur']))->where("k='currency'")->execute();
			//更改默认币种、附带更改当前选择币种
			$_SESSION['currency'] = $_POST['default_cur'];
			Cache::instance()->del('keke_config');
		}
		if($_POST['hdn_cid']){
			//条件
			$where = "currencies_id = '{$_POST['hdn_cid']}'";
			//更新
			Model::factory('witkey_currencies')->setData($array)->setWhere($where)->update();
			//show_msg 跳转的地址
			$url = "?cid=".$_POST['hdn_cid'];
		}else{
			//添加
			Model::factory('witkey_currencies')->setData($array)->create();
			$url = NULL;
		}
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_currencies/add'.$url,'success');
	}
	/**
	 * 删除指定的汇率
	 */
	function action_del(){
		$cid = intval($_GET['cid']);
		echo DB::delete('witkey_currencies')->where('currencies_id='.$cid)->execute();
	}
	
}

/* Keke::admin_check_role ( 2 );
$url = "index.php?do=$do&view=$view";
$default_currency =$Keke->_sys_config['currency'];
$currencies_obj = new keke_table_class('witkey_currencies');
$page and $page=intval ( $page ) or $page = 1;
$slt_page_size and $slt_page_size=intval ( $slt_page_size ) or $slt_page_size = 20;
$cur = new keke_curren_class();
if ($ac == 'del') {
	if ($cid&&($cid!=keke_curren_class::$_default['currencies_id'])) { //不允许删除默认货币
		$res = $currencies_obj->del ( "currencies_id", $cid, $url );
		Keke::admin_system_log ( $_lang['links_delete'].$del_id );
		Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' );die;
	} else {
		Keke::admin_show_msg ( $_lang['delete_fail'], $url ,3,$_lang['del_default'],'warning');die;
	}

}else {
	$where = ' 1 = 1  ';
	$d = $currencies_obj->get_grid ( $where, $url, $page, $slt_page_size,null,1,'ajax_dom');
	$currencies_config = $d [data];
	$pages = $d [pages];
}
//更新汇率
if($ac=='update'){
	if(isset($code)){
		$res = $cur->update(false,$code);
	}else{
		//批量更新
		$res = $cur->update(true);
	}
	$res and Keke::admin_show_msg ( $_lang['update_mi_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['update_mi_fail'], $url,3,'','warning' );
}


require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view ); */

/* $ops = array ('list','edit');

$op = (! empty ( $op ) && in_array ( $op, $ops )) ? $op : 'list';

if (file_exists ( ADMIN_ROOT . 'admin_'.$do.'_' . $view .'_'.$op. '.php' )) {  
	require  ADMIN_ROOT . 'admin_'.$do.'_'. $view .'_'.$op. '.php';  
} else {
	Keke::admin_show_msg ( $_lang['404_page'],'',3,'','warning' );
} */
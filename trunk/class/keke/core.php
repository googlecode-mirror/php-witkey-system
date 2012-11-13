<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );


/**
 * this not free,powered by keke-tech
 * @version 2.0
 * @auther xujie
 * 
 */

include 'base.php';
class Keke_core extends Keke_base {
	protected  static $_core_class = array ();
	protected static $_caching = FALSE;
	protected static $_files_changed = false;
 
	/**
	 * ����ҳ����ת��ʾ
	 *@param $content ��ʾ��Ϣ $_lang['submit_success']�ύ�ɹ�,$_lang['submit_fail']�ύʧ��
	 *@param $url ��תurl 
	 * @param $type string
	 *        success �ɹ�  error ���� info ����/��ʾ  confirm ȷ��
	 *@param $title ���⣬Ĭ��Ϊ��ϵͳ��ʾ��
	 *@param $time ��תҳ��ʾʱ�䣬Ĭ��Ϊ3��        
	 */
	static function show_msg( $content = "", $url = "",  $type = 'success',$title = NULL,$time = 3) {
		global $_K, $basic_config, $username, $uid, $nav_list, $_lang;
		$r = $_REQUEST;
		//$msgtype = $type;
		if($title===NULL){
			$title = $_lang['sys_tips'];
		}
		//û��http����base_url
		if (strpos($url, '://') === FALSE){
			$url = Route::site($url, TRUE, Keke::$_index_file);
		}
		require Keke_tpl::template ( 'show_msg' );
		die ();
	}
	
	/**
	 * ���ȫ�ֻ���
	 */
	static function empty_cache() {
		$file_obj = new keke_file_class ();
		TPL_CACHE and $file_obj->delete_files ( S_ROOT . "/data/tpl_c" );
		IS_CACHE and $file_obj->delete_files ( S_ROOT . "/data/cache" );
	}
	/**
	 * ��ȫ��SESSION����
	 *
	 * @param $verify boolean
	 *        	�Ƿ�����֤
	 */
	static function reset_secode_session($verify) {
		global $uid;
		if ($verify) { // ����֤������֮ǰ�Ƿ���֤����ǿ��������֤
			unset ( $_SESSION ['check_secode_' . $uid] );
			return TRUE;
		} else { // ������֤
			if ($_SESSION ['check_secode_' . $uid]) { //
				return FALSE;
			} else { // ��Ȼ�ⲿָʾ������֤���������ڰ�ȫ��session�����ڡ���ʱǿ������֤
				return TRUE;
			}
		}
	}
	/**
	 * ��ȡshowWindw�ĵ�������
	 */
	static function get_window_url() {
		global $_K;
		$post_url = $_SERVER ['QUERY_STRING'];
		preg_match ( '/(.*)&infloat/U', $post_url, $match );
		return $_K ['siteurl'] . '/index.php?' . $match ['1'];
	}
	
	/**
	 * ��¥����
	 *
	 * @param $int $nodeid
	 *        	-- ������ID��ֵ
	 * @param $arTree array
	 *        	-- ����
	 */
	static function sort_tree($nodeid, $data_arr, $pid = "indus_pid", $id = "indus_id") {
		$res = array ();
		for($i = 0; $i < sizeof ( $data_arr ); $i ++)
			if ($data_arr [$i] ["$pid"] == $nodeid) {
				array_push ( $res, $data_arr [$i] );
				$subres = self::sort_tree ( $data_arr [$i] ["$id"], $data_arr, $pid, $id );
				for($j = 0; $j < sizeof ( $subres ); $j ++)
					array_push ( $res, $subres [$j] );
			}
		return $res;
	}
	
	static function get_format_size($bytes) {
		$units = array (0 => 'B',1 => 'kB',2 => 'MB',3 => 'GB'	);
		$log = log ( $bytes, 1024 );
		$power = ( int ) $log;
		$size = pow ( 1024, $log - $power );
		return round ( $size, 2 ) . ' ' . $units [$power];
	
	}
	/**
	 * ���ܳ�������ת���� xx��
	 *
	 * @param $number int��float..
	 *        	����
	 * @param $unit string
	 *        	��λ
	 */
	static function pretty_format($number, $unit = '') {
		global $_lang;
		$unit == '' && $unit = $_lang ['million'];
		if ($number < 10000) {
			return $number;
		}
		return ((round ( $number / 1000 )) / 10) . $unit; // round�������� ceil��һ��ȡ��
			                                                  // floor��ȥ��ȡ��
	}

	public static function register_autoloader($callback = null) {
		spl_autoload_unregister ( array ('Keke_core','autoload') );
		isset ( $callback ) and spl_autoload_register ( $callback );
		spl_autoload_register ( array (	'Keke_core','autoload') );
	
	}
	public static function keke_require_once($filename, $class_name = null) {
		isset ( $GLOBALS ['class'] [$filename] ) or (($GLOBALS ['class'] [$filename] = 1) and require $filename);
	}
	
	public static function autoload($class_name) {
		try{
		    $class_name = strtolower($class_name);
		    
			$path = str_replace ( '_', '/', $class_name);
			if (strpos ( $class_name, '_class' )) {
				$path = str_replace ( '/class', '_class', $path );
			}
			
			if(($class=Keke::find_file('class', $path.EXT,$class_name.EXT))!=null){
				require $class;
				return true;
			}
		 }catch (Exception $e){
			throw new Keke_exception($e);
		} 
		return false;
 	}
	/**
	 * ������Ҫ����ϵͳ��ʼ���ӵ����ļ�
	 *
	 * @example Keke::cache('name'); ��ȡ����
	 * @example Keke::cache('name',$data); д����
	 * @param string $name    	��������
	 * @param string $data     	��������
	 * @param int $lifetime    	����ʱ��
	 */
	public static function cache($name, $data = NULL, $lifetime = NULL) {
		// �����ļ�
		$file = md5 ( $name ) . '.txt';
		// ����Ŀ¼
		$dir = S_ROOT . 'data/cache' . DIRECTORY_SEPARATOR;
		if ($lifetime === NULL) {
			// Ĭ��60���ӻ���
			$lifetime = 3600;
		}
		if ($data === NULL) {
			if (is_file ( $dir . $file )) {
				if ((time () - filemtime ( $dir . $file )) < $lifetime) {
					return  unserialize ( file_get_contents ( $dir . $file ) );
					
				} else {
					unlink ( $dir . $file );
				}
			}
			return NULL;
		}
		$data = serialize ( $data );
		return ( bool ) file_put_contents ( $dir . $file, $data, LOCK_EX );
	}
	
	/* public static function keke_show_msg($url, $content, $type = 'success', $output = 'normal') {
		global $_lang;
		switch ($output) {
			case "normal" :
				$type == 'success' or $type = 'warning';
				Keke::show_msg ( $_lang ['operate_notice'], $url, '3', $content, $type );
				break;
			case "json" :
				$type == 'error' or $status = '1'; // �Ǵ�����ʾ,����ȷ
				Keke::echojson ( $_lang ['operate_notice'], intval ( $status ), $content );
				die ();
				break;
		}
	} */
	
	/**
	 * $fileds,$where����Ϊ���� , $pkΪ@return�����key , ��Dbfactory -> select()�ĸĽ�,��ӻ���
	 *
	 * @return array($pk => data)
	 */
	public static function get_table_data($fileds = '*', $table, $where = '', $order = '', $group = '', $limit = '', $pk = '', $cachetime = 0) {
		return Dbfactory::get_table_data ( $fileds, $table, $where, $order, $group, $limit, $pk, $cachetime );
	}
	
	/**
	 * ��ȡ��������
	 */
	public static function get_ext_type() {
		global $kekezu;
		$basic_config = Keke::$_sys_config;
		$flie_types = explode ( '|', $basic_config ['file_type'] );
		
		foreach ( $flie_types as $k => $v ) {
			$k and $ext .= ",";
			$ext .= '.' . $v;
		}
		return $ext;
	}
	/**
	 * ��ȡϵͳ��ǰ��ģ��
	 * @return Ambigous <string, unknown, Ambigous, unknown, number, multitype:multitype: >
	 */
	public static function get_tpl() {
		$res =  DB::select('tpl_title,tpl_pic')->from('witkey_template')
		       ->where('is_selected = 1')->limit(0, 1)
		       ->cached(66666,'keke_template')->execute();
		return $res[0];
	}
	static function execute_time() {
		$stime = explode ( ' ', SYS_START_TIME );
		$etime = explode ( ' ', microtime ( 1 ) );
		$ex_time = ($etime [0] - $stime[0]);
		$memory = sprintf ( ' memory usage: %s', self::get_format_size ( memory_get_usage() ) );
		return array (	$ex_time,$memory);
	}
	
	static function lang($key) {
		return Keke_lang::lang ( $key );
	}
	
	// ��ȡ�û�������ʱ��
	static function update_oltime() {
		global $_K, $kekezu;
		$res = null;
		$login_uid = Keke::$_uid;
		$user_oltime = Dbfactory::get_one ( sprintf ( "select last_op_time from %switkey_member_oltime where uid = '%d'", TABLEPRE, $login_uid ) );
		if ((SYS_START_TIME - $user_oltime ['last_op_time']) > $_K ['timespan']) {
			$res = Dbfactory::execute ( sprintf ( "update %switkey_member_oltime set online_total_time = online_total_time+%d,last_op_time = '%d' where uid = '%d'", TABLEPRE, $_K ['timespan'], SYS_START_TIME, $login_uid ) );
		}
		return $res;
	}
	
	/**
	 * �������
	 */
	static function error_handler($code, $error, $file = NULL, $line = NULL) {
		
		if ($code != 8 ) {
			ob_get_level () and ob_clean ();
// 			var_dump($code,$error);die;
			Keke_exception::handler ( new ErrorException ( $error, $code, 0, $file, $line ) );
		}
		return TRUE;
	}
	/**
	 * �쳣����
	 */
	static function shutdown_handler() {
		//if(!Keke::$_inited){
		//	return ;
		//}
		if (self::$_caching === TRUE AND self::$_files_changed === TRUE){
			Keke::cache('loader_class', self::$_core_class);
		}
		
		if (KEKE_DEBUG and $error = error_get_last () and in_array ( $error ['type'], array (
				E_PARSE,
				E_ERROR,
				E_USER_ERROR 
		) )) {
			ob_get_level () and ob_clean ();
			Keke_exception::handler ( new ErrorException ( $error ['message'], $error ['type'], 0, $error ['file'], $error ['line'] ) );
			exit ( 1 );
		}
	}

}
class Keke extends Keke_core {
	//����ļ�ͷ���İ�����ǣ�û�о����
	const FILE_SECURITY = '<?php defined (\'IN_KEKE\' ) or die ( \'Access Denied\' );';
	public static $_inited = false;
	public static $_safe_mode ;
	public static $_magic_quote;
	public static $_log;
	public static $_index_file = 'index.php';
	public static $_sys_config;
	public static $_uid;
	public static $_username;
	public static $_userinfo;
	public static $_template;
	public static $_model_list;
	public static $_nav_list;
	public static $_user_group;
	public static $_tpl_obj;
	public static $_cache_obj;
	public static $_page_obj;
	//�����Ƿ���
	public static $_task_open=0;
	//�̳��Ƿ���
	public static $_shop_open=0; 
	public static $_mark;
 
	public static $_messagecount;
	public static $_indus_p_arr;
	public static $_indus_c_arr;
	public static $_indus_arr;
	public static $_prom_obj;
	public static $_weibo_list;
	public static $_api_open;
	public static $_lang;
	public static $_lang_list;
	public static $_style_path;
	public static $_weibo_attent;
	public static $_attent_api_open;
	public static $_currency;
	public Static $_curr_list;
	//���ͷ
	public static $_expose= true;
	public static $_content_type= 'text/html'; 
	public static $_db;
 	
	protected static $_files = array ();
	public static $_errors = true;
	
	public static function &get_instance() {
		static $obj = null;
		if ($obj === null) {
			$obj = new Keke ();
		}
		return $obj;
	}
	function __construct() {
		$this->init ();
		Keke_lang::loadlang ( 'public', 'public' );
	}
	
	function init() {
		global  $_K, $_lang;
		if(self::$_inited){
			return;
		}
		self::$_inited = true;
		define ( 'LIB', S_ROOT . 'class' . DIRECTORY_SEPARATOR );
		define ( 'EXT', '.php' );
		include (S_ROOT . 'config/config.inc.php');
		define ( 'KEKE_VERSION', '2.1' );
		define ( 'KEKE_RELEASE', '2012-06-2' );
		define ( "P_NAME", 'KPPW' );
		if(Keke::$_caching === true){
			Keke::$_core_class = Keke::cache('loader_class');
		}
		
		self::register_autoloader ();
		
		if (( int ) KEKE_DEBUG == 1) {
			set_exception_handler ( array (	'Keke_exception','handler' ) );
			set_error_handler ( array ('Keke_core','error_handler' ) );
		}
		register_shutdown_function ( array ('Keke_core','shutdown_handler') );
		
		if(ini_get('register_globals')){
			self::globals();
		}
		if(function_exists('mb_internal_encoding')){
			mb_internal_encoding(CHARSET);
		}
		//��ȫģʽ
		Keke::$_safe_mode  = (bool)ini_get('safe_mode');
		Keke::$_magic_quote = (bool)get_magic_quotes_gpc();
		//����ȫ�ֱ���
		$_GET = Keke::k_stripslashes($_GET);
		$_POST = Keke::k_stripslashes($_POST);
		$_COOKIE = Keke::k_stripslashes($_COOKIE);
			// self::$_db = Database::instance ();
		Keke::init_session ();
		$this->init_config ();
		
		//$this->init_user ();
		
		Keke::$_cache_obj = Cache::instance ();
		//Keke::$_tpl_obj = new Keke_tpl();
		//Keke::$_page_obj = new keke_page_class ();

		$this->init_out_put ();
		$this->init_lang ();
		$this->init_curr();
		//$this->init_model();

		self::$_log = log::instance()->attach(new keke_log_file());
		if (!isset($_SESSION['auid']) and Keke::$_sys_config ['is_close'] == 1 && substr ( $_SERVER ['PHP_SELF'], - 24 ) != '/control/admin/index.php') {
			Keke::show_msg ( $_lang ['site_is_close_notice'], 'index.php', 20, $_lang ['site_close_reason_notice'] . Keke::$_sys_config ['close_reason'] . '��', 'warning' );
		}
		
		  
	}
	/**
	 * ��ʼ��������Ϣ
	 */
	function init_config() {
		global $i_model, $_lang, $_K;
		$config_arr = array ();
		if(($config_arr = Cache::instance()->get('keke_config'))==NULL){
			$basic_arr = DB::select('`k`,`v`')->from('witkey_config')->execute();
			$size = sizeof ( $basic_arr );
			for($i = 0; $i < $size; $i ++) {
				$config_arr [$basic_arr [$i] ['k']] = $basic_arr [$i] ['v'];
			}
			Cache::instance()->set('keke_config', $config_arr,60000);
		}
		Keke::$_sys_config = $config_arr ;
		//$template = Keke::get_tpl ();
		Keke::$_template = $config_arr ['template'];
		$map_config = unserialize ( $config_arr ['map_api_open'] );
		$map_api = "baidu";
		$_K ['timestamp'] = $_SERVER['REQUEST_TIME'];
		$_K ['charset'] = CHARSET;
		$_K ['template'] = $config_arr ['template'];
		$_K ['theme'] = $config_arr ['theme'];
		$_K ['sitename'] = $config_arr ['website_name'];
		$_K ['siteurl'] = $config_arr ['website_url'];
		$_K ['inajax'] = 0;
		$_K ['block_search'] = array ();
		$_K ['is_rewrite'] = $config_arr ['is_rewrite'];
		$_K ['map_api'] = $map_api;
		$_K ['google_api'] = $config_arr ['google_api'];
		$_K ['baidu_api'] = $config_arr ['baidu_api'];
		$_K ['timespan'] = '600';
		$_K ['i'] = 0;
		$_K ['refer'] = "index.php";
		$_K ['block_search'] = $_K ['block_replace'] = array ();
		$_lang = array ();
		//is_file ( S_ROOT . '/config/lic.php' ) and include (S_ROOT . '/config/lic.php');
		$config_arr ['seo_title'] and $_K ['html_title'] = $config_arr ['seo_title'] or $_K ['html_title'] = $config_arr ['website_name'];
		define ( 'SKIN_PATH', 'tpl/' . $_K ['template'] );
		define ( 'UPLOAD_RULE', date ( 'Y/m/d/' ) );
		define ( 'UPLOAD_ROOT', S_ROOT . '/data/uploads/' . UPLOAD_RULE ); // ������������·��
		define ( 'UPLOAD_ALLOWEXT', '' . $config_arr ['file_type'] ); // �����ϴ����ļ���׺�������׺�á�|���ָ�
		define ( 'UPLOAD_MAXSIZE', '' . $config_arr ['max_size'] * 1024 * 1024 ); // �����ϴ��ĸ������ֵ
		define ( "CREDIT_NAME", $config_arr ['credit_rename'] ? $config_arr ['credit_rename'] : $_lang ['credit'] );
		define ( "EXP_NAME", $config_arr ['exp_rename'] ? $config_arr ['exp_rename'] : $_lang ['experience'] );
		define ( 'FORMHASH', Keke::formhash () );
		Keke::$_sys_config = $config_arr;
		Keke::$_style_path = $_K ['siteurl'] . "/" . SKIN_PATH;
	
	}
	/**
	 * ��ʼ���û�
	 */
	function init_user() {
		global $_K;
		if (isset ( $_SESSION ['uid'] )) {
			Keke::$_uid = $_SESSION ['uid'];
			Keke::$_username = $_SESSION ['username'];
			Keke::$_userinfo = Keke_user::instance()->get_user_info( Keke::$_uid );
			Keke::$_user_group = Keke::$_userinfo ['group_id'];
			$sql = "select count(msg_id) from %switkey_msg where to_uid = '%d' and view_status=0 and msg_status!=1";
			//Keke::$_messagecount = Dbfactory::get_count ( sprintf ( $sql, TABLEPRE, Keke::$_uid ) );
		} elseif (isset ( $_COOKIE ['user_login'] )) {
			$temp = unserialize ( keke_encrypt_class::decode ( $_COOKIE ['user_login'] ) );
			$_SESSION ['uid'] = $temp ['uid'];
			$_SESSION ['username'] = $temp ['username'];
			unset ( $temp );
		}
	}
	/**
	 * ��ʼ���ƹ�ʵ�����������
	 */
	static function init_prom() {
		Keke::$_prom_obj = keke_prom_class::get_instance ();
	}
	/**
	 * ��ʼ����ҵ,�������
	 */
	static function init_industry() {
		
		Keke::$_indus_p_arr =  Keke::get_table_data ( '*', "witkey_industry", "indus_type=1 and indus_pid = 0 ", "listorder asc ", '', '', 'indus_id', 3600 );
		Keke::$_indus_c_arr = Keke::get_table_data ( '*', 'witkey_industry', 'indus_type=1 and indus_pid >0', 'listorder', '', '', 'indus_id', 3600 );
		Keke::$_indus_arr = Keke::get_table_data ( '*', 'witkey_industry', '', 'listorder', '', '', 'indus_id', 3600 );
	
	}
	/**
	 * ��ʼ������������Ҫ���أ�����core �����
	 */
	static function init_nav(){
		global $_K;
		$nav_list = DB::select('*')->from('witkey_nav')->cached(6000,'keke_nav')->execute();
		$nav_list = Keke::get_arr_by_key($nav_list,'nav_id');
		Keke::$_nav_list = $nav_list; 
		$_K['nav_arr'] = $nav_list;
	}
	/**
	 * ��ʼ��΢����֤����
	 */
	static public  function init_oauth() {
		
		foreach ( Keke::$_basic_arr as $k => $v ) {
			($v ['type'] == 'weibo' || $v ['type'] == 'interface') and Keke::$_weibo_list [$v ['k']] = $v ['v'];
		}
		Keke::$_api_open = unserialize ( Keke::$_sys_config ['oauth_api_open'] );
	
	}
	/**
	 * ��ʼ��,΢����ע������Ҫ����
	 */
	static public function init_weibo_attent() {
		foreach ( Keke::$_basic_arr as $k => $v ) {
			$v ['type'] == 'attention' and Keke::$_weibo_attent [$v ['k']] = $v ['v'];
		}
		Keke::$_attent_api_open = unserialize ( Keke::$_sys_config ['attent_api_open'] );
	}
	//��ʼ������
	function init_lang() {
		Keke::$_lang_list = Keke_lang::lang_type ();
		Keke::$_lang = Keke_lang::get_lang ();
	}
	//��ʼ������
	function init_curr() {
		if ($_SESSION ['currency']) {
			Keke::$_currency = $_SESSION ['currency'];
		} else {
			Keke::$_currency = Keke::$_sys_config ['currency'];
			$_SESSION ['currency'] = Keke::$_sys_config ['currency'];
		}
		Keke::$_curr_list = Keke_lang::get_curr_list ();
	}
	/**
	 * ��ʼ������model,�������
	 */
	static public function init_model() {
		$model_arr = db::select ( '*' )->from ( 'witkey_model' )->order("listorder asc")->cached (3600,'keke_model')->execute ();
		Keke::$_model_list = Keke::get_arr_by_key ( $model_arr, 'model_id' );
		foreach ( Keke::$_model_list as $v ) {
			if ($v ['model_type'] == 'task') {
				Keke::$_task_open = (Keke::$_task_open or $v ['model_status']);
			} else {
				Keke::$_shop_open = (Keke::$_shop_open or $v ['model_status']);
			}
		}
		Keke::nav_filter ();
	}
	/**
	 * ��������
	 */
	public static function nav_filter() {
		global $_K;
		Keke::init_nav();
		$nav_arr = Keke::$_nav_list;
		if ((Keke::$_task_open and  Keke::$_shop_open) == FALSE) {
			foreach ( $nav_arr as $k => $v ) {
				if (Keke::$_task_open == FALSE) {
					if (in_array ( $_K ['action'], array ('task', 'task_list', 'weibo' ) )) {
						unset ( $nav_arr [$k] );
					}
				}
				if (Keke::$_shop_open == FALSE) {
					if (in_array ( $_K ['action'], array ('shop', 'shop_list', 'seller_list' ) )) {
						unset ( $nav_arr [$k] );
					}
				}
				if (Keke::$_shop_open == FALSE  and Keke::$_task_open == FALSE) {
					if ($_K ['action'] == 'case') {
						unset ( $nav_arr [$k] );
					}
				}
			}
		}
		Keke::$_nav_list = $nav_arr;
	}
	 
	public static function init_session() {
		$session = Keke_session::instance();
		$_SESSION = & $session->as_array();
		
	}
	function init_out_put() {
		global  $_K;
		ob_start ();
	}
	/**
	 * ��ָ��Ŀ¼�е��ļ�
	 *
	 * @param string $dir        	
	 * @param string $file        	
	 */
	public static function find_file($dir,$file,$class_name) {
		$path = $dir . DIRECTORY_SEPARATOR . $file;
		//�л��棬ֱ�ӷ���
		if (Keke::$_caching===true and isset ( Keke::$_core_class [$path] )) {
			return Keke::$_core_class [$path];
		}
		$class = S_ROOT . $path ;
		
		$helper =S_ROOT.$dir.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.$class_name;
		$sys =  S_ROOT.$dir.DIRECTORY_SEPARATOR.'sys'.DIRECTORY_SEPARATOR.$class_name;
		$model = S_ROOT.$dir.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.$class_name;
		$control = S_ROOT.$file;
		$models = array ('cache','database');
		$found = false;
		if (is_file ( $class )) {
			$found = $class;
		}elseif(is_file($model)){
			$found = $model;
		}elseif(is_file($sys)){
			$found = $sys;
		}elseif(is_file($helper)){
			$found = $helper;
		}elseif(is_file($control)){
			$found = $control;
		} elseif(isset($models)) {
			foreach ( $models as $d ) {
				$class = S_ROOT . $dir . DIRECTORY_SEPARATOR . $d.DIRECTORY_SEPARATOR.$file ;
			 	if (is_file ( $class )) {
					$found = $class;
					break;
				}
			}
		}
		 
		if(Keke::$_caching===true){
			Keke::$_core_class[$path] = $found;
			Keke::$_files_changed = true;
		}
		return $found;
	
	}
	public function deinit() {
		if (self::$_inited) {
			spl_autoload_unregister ( array (
					'Keke_core',
					'autoload' 
			) );
			if (Keke::$_errors) {
				restore_error_handler ();
				restore_exception_handler ();
			}
			self::$_inited = false;
		}
	}
	/**
	 * �����û���
	 * 
	 */
	public static function check_user_by_name($user, $isusername = 0) {
		global $_K;
		$member_obj = new keke_witkey_member();
		if ($isusername) {
			$member_obj->setWhere ( "username='{$user}'" );
		} else {
			$member_obj->setWhere ( "uid='{$user}'" );
		}
		$user_count = $member_obj->count();
		return $user_count;
	}
	/**
	 * ɾ��ȫ�ֱ���
	 * @return void();
	 */
	public static function globals(){
		if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS'])){
			echo "Global variable overload attack detected! Request aborted.\n";
			exit(1);
		}
		// ��ȡ������ͨ��ȫ�ֱ���
		$global_variables = array_keys($GLOBALS);
	
		// ɾ�����б�׼��ȫ�ֱ���
		$global_variables = array_diff($global_variables, array(
				'_COOKIE','_ENV','_GET','_FILES','_POST','_REQUEST','_SERVER','_SESSION','GLOBALS',
		));
		foreach ($global_variables as $name){
			// ɾ��ȫ�ֱ���
			unset($GLOBALS[$name]);
		}
	}

}

$ipath = dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "install.lck";
file_exists ( $ipath ) == true or header ( "Location: install/index.php" ); 

$kekezu = Keke::get_instance ();

Keke_lang::load_lang_class ( 'keke_core_class' );
// end 
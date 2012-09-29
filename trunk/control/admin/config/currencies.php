<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��������
 * @author Michael
 *
 */

class Control_admin_config_currencies extends Controller{
	
	private $_base_uri ;
	
	function __construct(){
		$this->_base_uri = BASE_URL."/index.php/admin/config_currencies";
	}
	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `currencies_id`,`title`,`code`,`symbol_left`,`symbol_right`,`decimal_point`,`thousands_point`,`decimal_places`,`value` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		//$query_fields = array('currencies_id'=>$_lang['id'],'title'=>$_lang['name'],'code'=>'����');
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = $this->_base_uri;
		
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//����uri 
		$update_uri = $base_uri.'/update';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		//$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_currencies')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('control/admin/tpl/config/currencies');
	}
	/**
	 * ��ӻ���
	 */
	function action_add(){
		global $_K,$_lang;
		
		if($_GET['cid']){
			$cid = intval($_GET['cid']);
			$where = " currencies_id = '$cid' ";
			$currency_config = Model::factory('witkey_currencies')->setWhere($where)->query();
			$currency_config = $currency_config[0];
		}
		//����Ĭ�ϵĵ�Ĭ�ϻ��ҷ���
		$default_currency = $_K['currency'];
		require Keke_tpl::template('control/admin/tpl/config/currencies_add');
	}
	/**
	 * ͨ��goole���»���
	 */
	function action_update(){
		global $_K,$_lang;
		$code = $_GET['code'];
		$cur = new keke_curren_class();
		if($code){
			//����ָ���Ļ���
			$cur->update(FALSE,$code);
		}else{
			//��������
			$cur->update(TRUE);
		}
		Keke::show_msg ( $_lang['update_mi_success'], 'index.php/admin/config_currencies','success' );
	}
	/**
	 * �����������
	 */
	function action_save(){
		global $_lang;
		//form���
		Keke::formcheck($_POST['formhash']);
		//����
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
			//����Ĭ�ϱ��֡��������ĵ�ǰѡ�����
			$_SESSION['currency'] = $_POST['default_cur'];
			Cache::instance()->del('keke_config');
		}
		if($_POST['hdn_cid']){
			//����
			$where = "currencies_id = '{$_POST['hdn_cid']}'";
			//����
			Model::factory('witkey_currencies')->setData($array)->setWhere($where)->update();
			//show_msg ��ת�ĵ�ַ
			$url = "?cid=".$_POST['hdn_cid'];
		}else{
			//���
			Model::factory('witkey_currencies')->setData($array)->create();
			$url = NULL;
		}
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_currencies/add'.$url,'success');
	}
	/**
	 * ɾ��ָ���Ļ���
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
	if ($cid&&($cid!=keke_curren_class::$_default['currencies_id'])) { //������ɾ��Ĭ�ϻ���
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
//���»���
if($ac=='update'){
	if(isset($code)){
		$res = $cur->update(false,$code);
	}else{
		//��������
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
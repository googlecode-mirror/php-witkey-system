<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��������
 * @author Michael
 * 2012-10-01
 */

class Control_admin_config_currencies extends Control_admin{
	
	private $_base_uri ;
	
	function __construct($request, $response){
		parent::__construct($request, $response);
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
		
		$default_currency = $_K['currency'];
		
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
		$cur = new Curren();
		if($code){
			//����ָ���Ļ���
			$cur->update(FALSE,$code);
		}else{
			//��������
			$cur->update(TRUE);
		}
		Keke::show_msg ( $_lang['update_mi_success'], 'admin/config_currencies','success' );
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
		Keke::show_msg($_lang['submit_success'],'admin/config_currencies/add'.$url,'success');
	}
	/**
	 * ɾ��ָ���Ļ���
	 */
	function action_del(){
		$cid = intval($_GET['cid']);
		echo DB::delete('witkey_currencies')->where('currencies_id='.$cid)->execute();
	}
	
}
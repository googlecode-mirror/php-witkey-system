<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_link extends Controller {
	
	//��������
	function action_index() {
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��Ҫ�õ����ֶ�
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('link_id'=>$_lang['id'],'link_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��
		$count = intval($_GET['count']);
		//����uri
		$base_uri = BASE_URL."/index.php/admin/link";
		//��ӱ༭��uri
		$add_uri =  $base_uri.'/add';
		//ɾ��uri
		$del_uri = $base_uri.'/del';
		
	    extract($this->get_url($base_uri));

		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//��ѯ��ǰ��Sql
		//var_dump(Database::instance()->get_last_query());
		//��ѯ����sql����
		//echo Database::instance()->get_query_num();
		
		require Keke_tpl::template('control/admin/tpl/link');
	}
	/**
	 * 
	 * @param string $base_uri
	 * @return multitype:string number
	 */
	function get_url($base_uri){
		$r = array();
		//��ʼ��where��ֵ
		$where = ' 1=1 ';
		$query_uri = '?';
		//�ֶ�������
		if($_GET['slt_fields']  and $_GET['txt_condition']){
			//ʱ��Ĳ�ѯ����
			if(strtotime($_GET['txt_condition'])){
				//�ֶ�ֵΪʱ��ʱ
				$c =  $_GET['txt_condition'];
				//��������ݿ��е�on_time �ֶα�����ʱ���
				$f =  "FROM_UNIXTIME(`{$_GET['slt_fields']}`,'%Y-%m-%d')";
				
			}else{
				//��ʱ�������
				$c = $_GET['txt_condition'];
				$f = "`{$_GET['slt_fields']}`";
			}
			//�����like ������ֵҪ��%
			if($_GET['slt_cond']=='like'){
				$c = "%$c%";
			}
			//ƴ��url�ֶ�
			$where .= "and $f {$_GET['slt_cond']} '$c'";
			
			$query_uri .= "slt_cond={$_GET['slt_cond']}";
			$query_uri .= "&slt_fields={$_GET['slt_fields']}&txt_condition={$_GET['txt_condition']}";
		}
		if($_GET['page_size']){
			$query_uri .= '&page_size='.$_GET['page_size'];
		}
		//ҳ��
		$_GET['page'] and $page = $_GET['page'] or $page = 1;
		
		//�����uri,f��ʾҪ������ֶ�
		if($_GET['f']){
			$query_uri .= '&f='.$_GET['f'].'&ord='.$_GET['ord'];
		}
		//��ѯuri
		$uri = $base_uri.$query_uri;
		//�����ǣ�����js �еı���
		//����
		if(isset($_GET['ord']) and $_GET['ord']==1){
			$ord_tag = 0;
			$ord_char = '��';
			//����
		}elseif(isset($_GET['ord']) and $_GET['ord']==0){
			$ord_tag = 1;
			$ord_char = '��';
		}else{
			//Ĭ�ϲ���ʾ
			$ord_tag = 0;
			$ord_char = '';
		}
		
		
		//���������
		if(isset($_GET['f'])){
			$t = $ord_tag==1?'desc':'asc';
			$order = " order by {$_GET['f']} $t ";
		}
		$r['where'] = $where;
		//$r['query_uri'] =$query_uri;
		$r['uri'] = $uri;
		$r['ord_tag']=$ord_tag;
		$r['ord_char']=$ord_char;
		$r['order'] = $order;
		$r['page']=$page;
		return $r;		
	}
	//�����༭��ʼ��
	function action_add(){
		global $_K,$_lang;
		$link_id = $_GET['link_id'];
		//�����ֵ���ͽ���༭״̬
		if($link_id){
			$link_info = Model::factory('witkey_link')->setWhere('link_id = '.$link_id)->query();
			$link_info = $link_info[0];
			$link_pic = $link_info['link_pic'];
		}
		
		if(strpos($link_pic, 'http')!==FALSE){
			//Զ�̵�ַ
			$mode = 1;
		}else{
			//����ͼƬ
			$mode = 2;
		}
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	//��������
	function action_save(){
		Keke::formcheck($_POST['formhash']);
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		$array = array('link_name'=>$_POST['txt_link_name'],
				       'link_url'=>$_POST['txt_link_url'],
					   'link_pic'=>$link_pic,
					   'listorder' => $_POST['txt_listorder'],				  
				);

		if($_POST['hdn_link_id']){
			Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add?link_id='.$_POST['hdn_link_id'],'�ύ�ɹ�','success');
		}else{
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add','�ύ�ɹ�','success');
		}
		
		
	}
	//ɾ��
	function action_del(){
		//ɾ������
		if($_GET['link_id']){  
			$where = 'link_id = '.$_GET['link_id'];
		//ɾ������	
		}elseif($_GET['link_ids']){
			$where = 'link_id in ('.$_GET['link_ids'].')';
		}
		echo  Model::factory('witkey_link')->setWhere($where)->del();
	}
	
}

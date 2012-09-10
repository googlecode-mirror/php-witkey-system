<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_link extends Controller {
	/**
	 * ���������ʼ��ҳ��
	 * index �Ǳ���Ļ�������·���Ҳ���index������͹��˰�
	 * �ӵ���ע�Ͱ�,���Ǳ���Ҫд��(*_*)!
	 */
	function action_index() {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��Ҫ�õ����ֶ�
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('link_id'=>$_lang['id'],'link_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/link";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
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
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$link_id = $_GET['link_id'];
		//�����ֵ���ͽ���༭״̬
		if($link_id){
			$link_info = Model::factory('witkey_link')->setWhere('link_id = '.$link_id)->query();
			$link_info = $link_info[0];
			$link_pic = $link_info['link_pic'];
		}
		//��http�ľ���url��ַ������ˮ��
		if(strpos($link_pic, 'http')!==FALSE){
			//Զ�̵�ַ
			$mode = 1;
		}else{
			//����ͼƬ
			$mode = 2;
		}
		//����ģ�壬���е��J8��,�����˶�����
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	/**
	 * ����ģ�����ύ�������ݵ����ݿ���
	 * ���acton ��ͨ�õģ���Ҫ��㶨���������
	 * 
	 */
	function action_save(){
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		//������ҵ���ж�,������ͼƬ����url��ַ
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			//�ϴ��ļ��õģ������������˵��ʹ,Ҫ���Ǽ�
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('link_name'=>$_POST['txt_link_name'],
				       'link_url'=>$_POST['txt_link_url'],
					   'link_pic'=>$link_pic,
					   'listorder' => $_POST['txt_listorder'],				  
		);
        //���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_link_id']){
			Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add?link_id='.$_POST['hdn_link_id'],'�ύ�ɹ�','success');
		}else{
		 //��Ҳ��Ȼ�������(insert)�����ݿ���	
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add','�ύ�ɹ�','success');
		}
		
		
	}
	/**
	 * ������ɾ����action ��Ҫ�Ǵ���Ҫ����ɾ��
	 * �����ɾ����
	 * ��أ�ɾ��action������ͳһdel,��Ҫ��Ϊʲô
	 * ����ɾ����������������ֵ�Ϳ���ɾ����
	 * ����ɾ���ģ���ǰ��jsƴ�Ӻõ�ids��������ֵ.js ֻ��ids ��Ӵ����Ҫд����������
	 * 
	 */
	function action_del(){
		//ɾ������,�����link_id ����ģ���ϵ������������е�
		if($_GET['link_id']){  
			$where = 'link_id = '.$_GET['link_id'];
		//ɾ������,���������ͳһΪidsӴ����	
		}elseif($_GET['ids']){
			$where = 'link_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_link')->setWhere($where)->del();
	}
	
}

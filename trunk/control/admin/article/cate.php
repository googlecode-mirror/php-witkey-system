<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��̨��Ѷ���������Ʋ�
 * @author Michael	
 * 2012-09-26
 */
class Control_admin_article_cate extends Control_admin {

	/**
	 * ���·����б�
	 */
	function action_index($type='article') {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `art_cat_id`,`art_cat_pid`,`cat_name`,`cat_type`,`listorder`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('art_cat_id'=>$_lang['id'],'cat_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		if($type=='help'){
			$ac = '/help';
		}else{
			$ac = '/index';
		}
		$base_uri = BASE_URL."/index.php/admin/article_cate";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//�ж������·��࣬���ǰ�������
		$where .= " and cat_type = '$type' ";
		$uri .= "&type=$type";
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_article_category')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']=500);
		//�б�����
		$list_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		$temp_arr = array();
    	Keke::get_tree($list_arr,$temp_arr,'cat',NULL,'art_cat_id','art_cat_pid','cat_name');
		$cate_tree_arr = $temp_arr;
		unset($temp_arr);
		
		
		$cate_index_arr = $this->get_cate_by_index ($type);

		require Keke_tpl::template('control/admin/tpl/article/cate');
	}
	/**
	 * ��ȡ�������������
	 */
	function get_cate_by_index($type=NULL){
		if(!$type){
			$type = 'article';
		}
		$where = " cat_type = '$type'";
		$cate_arr = DB::select()->from('witkey_article_category')->where($where)->execute();
		$cate_index_arr = array();
		foreach ($cate_arr as $k=>$v){
			$cate_index_arr[$v['art_cat_pid']][$v['art_cat_id']] = $v;
		}
		return $cate_index_arr;
		
		
	}
	/**
	 * ���������б�
	 */
	function action_help(){
		$this->action_index($type='help');
	}
	/**
	 * �������
	 */
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang,$kekezu;
		$cate_id = $_GET['art_cat_id'];
		$type=$_GET['type'];
		if($type=='help'){
			$ac = '/help';
		}else{
			$ac = '/index';
		}
		//�����ֵ���ͽ���༭״̬
		if($cate_id){
			$cate_info = Model::factory('witkey_article_category')->setWhere('art_cat_id = '.$cate_id)->query();
			$cate_info = $cate_info[0];
		}
		
	    $cate_arr = Model::factory('witkey_article_category')->setWhere("cat_type='$type'")->query();
		$t_arr = array ();
		
		//����д���pid ��index ����pid
		$index = $_GET['art_cat_pid']?$_GET['art_cat_pid']:$cate_info['art_cat_pid'];
		
 		Keke::get_tree ( $cate_arr, $t_arr, 'option', $index, 'art_cat_id', 'art_cat_pid', 'cat_name' );
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/article/cate_add');
	}
	/**
	 * �����ౣ��
	 */
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		$cat_type=$_POST['hdn_cat_type'];
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('art_cat_pid'=>$_POST['slt_cat_id'],
				'cat_name'=>$_POST['txt_cat_name'],
				'cat_type'=>$cat_type,
				'listorder' => $_POST['txt_listorder'],
				'on_time'=>time(),
		);
		//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_art_cat_id']){
			Model::factory('witkey_article_category')->setData($array)->setWhere("art_cat_id = '{$_POST['hdn_art_cat_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('�ύ�ɹ�','admin/article_cate/add?art_cat_id='.$_POST['hdn_art_cat_id'].'&type='.$cat_type,'success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			$cate_id = Model::factory('witkey_article_category')->setData($array)->create();
			//����art_index
			//$this->update_art_index($cate_id);
			Keke::show_msg('�ύ�ɹ�','admin/article_cate/add?type='.$cat_type,'success');
		}
	}
	/**
	 * �������������Ϣ
	 */
	function action_batch_save(){
		global $_lang;
		Keke::formcheck($_POST['formhash']);
			
		//��ҵ��������,indus_id => indus_name
		$names = $_POST['names'];
		$orders = $_POST['orders'];
		$no = array();
		//�ϲ�Ҫ���µ�����������
		foreach ($names as $k=>$v){
			$no[$k] = array('name'=>$v,'order'=>$orders[$k]);
		}
		//Ҫ���µ����ݲ��뵽���ݿ�
		foreach ($no as $k=>$v){
			$columns = array('cat_name','listorder');
			$values = array($v['name'],$v['order']);
			$where = "art_cat_id = '$k'";
			$res += DB::update('witkey_article_category')->set($columns)->value($values)->where($where)->execute();
		}
		//��������ҵ����
		$add_indus_name_listarr  = $_POST['add_indus_name_listarr'];
		//��������ҵ����
		$add_indus_name_arr = $_POST['add_indus_name_arr'];
		//�ϲ�����������������
		$add_arr = array();
		if($add_indus_name_arr){
			foreach ($add_indus_name_arr as $k=>$v) {
				$t = array();
				foreach ($v as $i=>$j){
					$t[] = array('pid'=>$k,'name'=>$v[$i],'order'=>$add_indus_name_listarr[$k][$i]);
				}
				$add_arr[$k] = $t;
			}
		}
		//���������ݲ��뵽��
		if($add_arr){
			$columns = array('art_cat_pid','cat_name','listorder','cat_type');
			foreach ($add_arr as $k=>$v){
				foreach ($v as $v1){
					$values = array($v1['pid'],$v1['name'],$v1['order'],$_POST['type']);
					DB::insert('witkey_article_category')->set($columns)->value($values)->execute();
				}
			}
		}
		$type = $_POST['type'];
		if($type == 'help'){
			$uri = 'help';
		}
		Keke::show_msg($_lang['submit_success'],'admin/article_cate/'.$uri,'success');
	}
	/**
	 * ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����case_id ����ģ���ϵ������������е�
		if($_GET['art_cat_id']){
			$art_cat_id = $_GET['art_cat_id'];
			$where = 'art_cat_id = '.$_GET['art_cat_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}
		echo  Model::factory('witkey_article_category')->setWhere($where)->del();
		//�������ҵ,�� ɾ�����µ�����ҵ��
		if($_GET['art_cat_pid']<=0){
			DB::delete('witkey_article_category')->where("art_cat_pid = $art_cat_id")->execute();
		}
	}
	 
}
 
 
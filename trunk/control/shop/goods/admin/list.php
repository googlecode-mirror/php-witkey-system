<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��̨�����б�
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_shop_goods_admin_list extends Control_shop_list{
	/**
	 * @var ģ�ʹ���
	 */
	public  $_model_code   = 'goods';
 
	/**
	 * ��Ʒ�б�ҳ
	 */
    function action_index(){
    	global $_K,$_lang;
    	
    	//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
    	$fields = ' `sid`,`title`,`username`,`price`,`unite_price`,`service_time`,`unit_time`,`sale_num`,`status`,`on_time`,`is_top`';
    	//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
    	$query_fields = array('sid'=>$_lang['id'],'title'=>$_lang['name'],'price'=>$_lang['cash']);
    	//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
    	$count = intval($_GET['count']);
    	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
    	$base_uri = $this->_base_uri;
    	//��ӱ༭��uri,add���action �ǹ̶���
    	$add_uri =  $base_uri.'/add';
    	//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
    	$del_uri = $base_uri.'/del';
    	//Ĭ�������ֶΣ����ﰴʱ�併��
    	$this->_default_ord_field = 'sid';
    	//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
    	extract($this->get_url($base_uri));
    	//��ָ�����͵���Ʒ
    	$model_id = DB::select('model_id')->from('witkey_model')->where("model_code='$this->_model_code'")->get_count()->execute();
    	$where  .= " and model_id = $model_id";
    	//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
    	$data_info = Model::factory('witkey_service')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
    	//�б�����
    	$list_arr = $data_info['data'];
    	//��ҳ����
    	$pages = $data_info['pages'];
    	
    	$shop_status = Control_shop_goods_base::get_shop_status();
    
     	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/list');
    }
    /**
     * ��Ʒ�༭
     */
    public function action_add(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	 //��ȡ��Ʒ��Ϣ
        $shop_info = $this->get_shop_info();
         
        $base_uri = $this->_base_uri;
        $process_arr = Control_shop_list::can_operate($shop_info['shop_status']);
        $indus_option_arr = Sys_indus::get_indus_tree($shop_info['indus_id']);
        //������Ʒ״̬
        $status_arr = Control_shop_goods_base::get_shop_status();
        //��ȡ��Ʒ����ֵ��
        $payitem_list = Sys_payitem::get_shop_payitem($this->_shop_id);
        
        $file_list = Control_shop_list::get_shop_file($this->_shop_id);
         
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/edit');
    }
    
    /**
     * ��Ʒ����
     */
    public function action_save(){
    	$shop_id = $_POST['shop_id'];
    	if(!$shop_id){
    		return FALSE;
    	}
    	Keke::formcheck($_POST['formhash']);
    	$array = array('shop_title'=>$_POST['shop_title'],
    			'indus_id'=>$_POST['slt_indus_id'],
    			'shop_desc'=>$_POST['shop_desc']);
    	$where = "shop_id = $shop_id";
    	Model::factory('witkey_shop')->setData($array)->setWhere($where)->update();
    	$this->request->redirect($this->request->referrer());
    	
    }
    
    /**
     * �ϼ�
     */
    public function action_recommend(){
    	 $this->set_recommend();
    }
    /**
     * �¼�
     */
    public function action_unrecommend(){
    	//�ı���Ʒ��is_top Ϊ0
    	$this->set_unrecommend();
    }
     
    /**
     * ɾ����Ʒ�������Ʒû�н����еĶ����������ɾ��
     */
    public function  action_del(){
    	echo $this->del_service();
    }
     
    /**
     * ɾ��ָ�����
     * ɾ�������ͬʱҪɾ��������Ա����������,����
     */
    public function action_work_del(){
    	$work_id = $_GET['work_id'];
    	//ɾ����Ӧ���ļ�
    	$files = DB::select('save_name')->from('witkey_file')->where("obj_id = '$work_id' and obj_type='work'");
    	foreach ($files as $v){
           $path = S_ROOT.$v['save_name'];
           if(file_exists($path)){
           	  unlink($path);
           } 		
    	}
    	//ɾ�����������ű�
    	$sql = "delete a,b,c from ".TABLEPRE."witkey_shop_work as a \n".
				"left join ".TABLEPRE."witkey_comment as b\n".
				"on b.obj_id = a.work_id and b.obj_type='work'\n".
				"left join ".TABLEPRE."witkey_file as c \n".
				"on a.work_id = c.obj_id and c.obj_type='work'\n".
				"where a.work_id = '$work_id'";
		echo DB::query($sql,Database::DELETE);				
    }
    /**
     * ��Ʒ�����б�ҳ
     */
    public function action_comment(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	$base_uri = $this->_base_uri;
    	//��ȡ��Ʒ��Ϣ
    	$comments = DB::select()->from('witkey_comment')->where("obj_id = '$shop_id' and obj_type='shop' ")->execute(); 
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/shop_comment');
    }
    /**
     * ɾ����Ʒ����
     */
    public function action_comment_del(){
    	$comment_id = $_GET['comment_id'];
    	echo DB::delete('witkey_comment')->where("comment_id = '$comment_id'")->execute();
    }
    /**
     * ��Ʒ�����б�ҳ
     */
    public function action_mark(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	$base_uri = $this->_base_uri;
    	//��ȡ��Ʒ��Ϣ
    	//$shop_info = $this->get_shop_info();
    	$where = "model_code = '$this->_model_code' and origin_id = '$shop_id'";
    	$marks = DB::select()->from('witkey_mark')->where($where)->execute();
    	//����״̬
    	$mark_status = Keke_user_mark::get_mark_status();
    	//������
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/shop_mark');
    }
    
  

    
}
<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ���ͺ�̨�����б�
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_task_mreward_admin_list extends Control_task_list{
	/**
	 * @var ģ�ʹ���
	 */
	public  $_model_code   = 'mreward';
	/**
	 * �����б�ҳ
	 */
    function action_index(){
    	global $_K,$_lang;
    	
    	//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
    	$fields = ' `task_id`,`task_title`,`username`,`task_cash`,`model_id`,`task_status`,`indus_id`,`work_num`,`contact`,`start_time`,`is_top`';
    	//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
    	$query_fields = array('task'=>$_lang['id'],'task_title'=>$_lang['name'],'task_cash'=>$_lang['cash']);
    	//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
    	$count = intval($_GET['count']);
    	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
    	$base_uri = BASE_URL."/index.php/task/".$this->_model_code."_admin_list";
    	//��ӱ༭��uri,add���action �ǹ̶���
    	$add_uri =  $base_uri.'/add';
    	//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
    	$del_uri = $base_uri.'/del';
    	//Ĭ�������ֶΣ����ﰴʱ�併��
    	$this->_default_ord_field = 'task_id';
    	//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
    	extract($this->get_url($base_uri));
    	//��ָ�����͵�����
    	$model_id = DB::select('model_id')->from('witkey_model')->where("model_code='$this->_model_code'")->get_count()->execute();
    	$where  .= " and model_id = $model_id";
    	//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
    	$data_info = Model::factory('witkey_task')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
    	//�б�����
    	$list_arr = $data_info['data'];
    	//��ҳ����
    	$pages = $data_info['pages'];
    	
    	$task_status = Control_task_mreward_task::get_task_status();
     	require Keke_tpl::template('control/task/'.$this->_model_code.'/tpl/admin/list');
    }
    /**
     * ����༭
     */
    public function action_add(){
    	
    }
    /**
     * ���񱣴�
     */
    public function action_save(){
    	
    }
    /**
     * �����Ƽ�
     */
    public function action_recommend(){
    	
    }
    /**
     * ���񶳽�
     */
    public function action_freeze(){
    	
    }
    /**
     * ����ⶳ
     */
    public function action_unfreeze(){
    	
    }
    
}
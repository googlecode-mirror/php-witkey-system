<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-���-����
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_user_buyer_mark extends Control_user{
    
	
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'buyer';
	/**
	 *
	 * @var �����˵�ѡ����,��ֵ����ѡ��
	 */
	protected static $_left = 'mark';
	public  static $_mark = array(1=>'����',2=>'����',3=>'����');
	protected $_uri ;
	protected $_ord_tag;
	protected $_ord_char;
	
	function action_index(){
		
		
		//��ѯ�ֶ�
		$query_fields = array ('by_uid' => 'UID', 'by_username' => '�û���');
		
		$status = (int)$_GET['status'];
		
		$data = $this->seller_mark_data($status);
		
		$uri = $this->_uri;
		
		$ord_tag = $this->_ord_tag;
		
		$ord_char = $this->_ord_char;
		// �б�����
		$list_arr = $data ['data'];
//  		explode($delimiter, $string)
		// ��ҳ����
		$pages = $data ['pages'];
		
		require Keke_tpl::template('user/buyer/mark');
	}
	/**
	 * ���Է����̵Ļ�������
	 */
	function seller_mark_data($status=NULL){
		
		$sql = "SELECT a.mark_id,a.mark_status,a.mark_time,a.by_uid,a.by_username,a.mark_content,\n".
				"if(e.model_type='task',c.task_title,d.title) as obj_title,a.origin_id,\n".
				"if(e.model_type='task','����','��Ʒ') as obj_type , e.model_name,\n".
				"group_concat(b.aid_name) as aid_name ,a.aid_star \n".
				"FROM `:keke_witkey_mark` a left join :keke_witkey_mark_aid b \n".
				"on FIND_IN_SET(b.aid,a.aid) \n".
				"LEFT JOIN :keke_witkey_model e \n".
				"on a.model_code = e.model_code \n".
				"left join :keke_witkey_task c\n".
				"on a.origin_id = c.task_id and e.model_type = 'task'\n".
				"LEFT JOIN :keke_witkey_service d\n".
				"on a.origin_id =  d.sid and e.model_type = 'service'\n";
		
		$sql = DB::query($sql)->tablepre(':keke_')->compile(Database::instance());
		
		// �ܼ�¼��,��ҳ�õ�
		$count = intval ( $_GET ['count'] );
		 
		$base_uri = PHP_URL . "/user/buyer_mark";
		if($status !==NULL AND $status > 0){
			$base_uri .= '/?status='.$status;
		}
		
		// Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'mark_time';
		
		// ����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract ( $this->get_url ( $base_uri ) );
		
		$where .= " and a.mark_status >0 and a.mark_type = 1 and a.uid = $this->uid ";
		
		$group = " group by a.mark_id";
		if($status >0 ){
			$where .= " and a.mark_status=".intval($status);
		}
		$this->_uri = $uri;
		$this->_ord_tag = $ord_tag;
		$this->_ord_char = $ord_char;
		return Model::sql_grid($sql,$where,$uri,$order,$group,$page,$count,$_GET['page_size']);
	}
	
}

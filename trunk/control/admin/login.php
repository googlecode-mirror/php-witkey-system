<?php
class Control_admin_login extends Controller {
	/**
	 * �ж���û�е�¼�������¼�ˣ�����index.php
	 * ���û�е�¼������ʼ����¼ҳ��
	 */
	function action_index(){
       global $_K;
       //group_id > 0 ��ʾ��
       if($_SESSION['admin_uid'] and $_K['userinfo']['group_id']>0){
       	     
       }
		
	}

}
//end
 
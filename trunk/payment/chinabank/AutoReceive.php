<?php  define ( "IN_KEKE", true );

require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

$pay_config = Sys_payment::factory('chinabank')->get_pay_config();

$key=$pay_config['key'];

$v_oid     =trim($_POST['v_oid']);      
$v_pmode   =trim($_POST['v_pmode']);      
$v_pstatus =trim($_POST['v_pstatus']);      
$v_pstring =trim($_POST['v_pstring']);      
$v_amount  =trim($_POST['v_amount']);     
$v_moneytype  =trim($_POST['v_moneytype']);     
$remark1   =trim($_POST['remark1' ]);     
$remark2   =trim($_POST['remark2' ]);     
$v_md5str  =trim($_POST['v_md5str' ]);     
/**
 * ���¼���md5��ֵ
 */
                           
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //ƴ�ռ��ܴ�
Keke::$_log->add(Log::DEBUG, '������̨�ص�')->write();
if ($v_md5str==$md5string)
{
	Keke::$_log->add(Log::DEBUG, '������̨�ص�״̬�ɹ�')->write();	
   if($v_pstatus=="20")
	{
		list ($uid, $order_id ,$rid ) = explode ( '-', $v_oid, 3 );
		//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
		if(Sys_payment::set_recharge_status($uid,$rid, '', $v_amount,'��������')===FALSE){
			return false;
		}
		
		if($order_id>0){
			//��������Ϣ
		}
		
	}
  echo "ok";
	
}else{
	echo "error";
}
Keke::$_log->add(Log::DEBUG, '������̨�ص�����')->write();
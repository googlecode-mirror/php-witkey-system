<?php  define ( "IN_KEKE", true );


require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

$pay_config = Sys_payment::factory('chinabank')->get_pay_config();

$key=$pay_config['key'];	
$v_oid     =trim($_POST['v_oid']);       // �̻����͵�v_oid�������   
$v_pmode   =trim($_POST['v_pmode']);    // ֧����ʽ���ַ�����   
$v_pstatus =trim($_POST['v_pstatus']);   //  ֧��״̬ ��20��֧���ɹ�����30��֧��ʧ�ܣ�
$v_pstring =trim($_POST['v_pstring']);   // ֧�������Ϣ �� ֧����ɣ���v_pstatus=20ʱ����ʧ��ԭ�򣨵�v_pstatus=30ʱ,�ַ������� 
$v_amount  =trim($_POST['v_amount']);     // ����ʵ��֧�����
$v_moneytype  =trim($_POST['v_moneytype']); //����ʵ��֧������    
$remark1   =trim($_POST['remark1' ]);      //��ע�ֶ�1
$remark2   =trim($_POST['remark2' ]);     //��ע�ֶ�2
$v_md5str  =trim($_POST['v_md5str' ]);   //ƴ�պ��MD5У��ֵ  

/**
 * ���¼���md5��ֵ
 */
                           
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

/**
 * �жϷ�����Ϣ�����֧���ɹ�������֧��������ţ�������һ���Ĵ���
 */


if ($v_md5str==$md5string)
{
	if($v_pstatus=="20")
	{
		
		list ($uid, $order_id ,$rid ) = explode ( '-', $v_oid, 3 );
		//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
		if(Sys_payment::set_recharge_status($uid,$rid, '', $v_amount,'��������')===FALSE){
			Keke::show_msg('��Ҫ�ظ�ˢ��',Cookie::get('last_page'),'error');
		}
		
		if($order_id>0){
			//��������Ϣ
		}
		
		//�̻�ϵͳ���߼����������жϽ��ж�֧��״̬�����¶���״̬�ȵȣ�......
		Keke::show_msg('��ʱ����֧���ɹ�,�����'.$v_amount,Cookie::get('last_page'));
	}else{
		//echo "֧��ʧ��";
		Keke::show_msg('��ʱ����֧��ʧ��',Cookie::get('last_page'),'error');
	}
}else{
	Keke::show_msg('У��ʧ��,���ݿ���',Cookie::get('last_page'),'error');
}

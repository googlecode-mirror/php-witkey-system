<?php  define ( "IN_KEKE", true );
require (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_boot.php');

$yeepay = Sys_payment::factory('yeepay');
 
#	�������ز���.
$return = $yeepay->getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = $yeepay->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.

$total_fee = Curren::output($r3_Amt);

#	У������ȷ.
if($bRet===FALSE){
	echo "������Ϣ���۸�";
}
	if($r1_Code=="1"){
		
		if($r9_BType=="1"){
			
			list ($uid, $order_id ,$rid ) = explode ( '-', $r8_MP, 3 );
			//�ı��ֵ��¼,���ж���������û�д����������д�������򷵻أ��������
			//var_dump($r3_Amt);//die;
			if(Sys_payment::set_recharge_status($uid,$rid, '', $r3_Amt,'�ױ�֧��')===FALSE){
				Keke::show_msg('��Ҫ�ظ�ˢ��',Cookie::get('last_page'),'error');
			}
				
			if($order_id>0){
				//��������Ϣ
			}
			
			//�̻�ϵͳ���߼����������жϽ��ж�֧��״̬�����¶���״̬�ȵȣ�......
			Keke::show_msg('��ʱ����֧���ɹ�,�����'.$total_fee,Cookie::get('last_page'));
			
			
		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			echo "success";
			echo "<br />���׳ɹ�";
			echo  "<br />����֧������������";
			
			
		}
	}
 

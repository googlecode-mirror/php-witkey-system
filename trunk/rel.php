<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

//��ֵ�ж�


 
Keke_sms::instance()->send('13545368115', '�������������Ѿ����');
die;
 

 
//$user =  Keke_sms::instance()->get_userinfo();
//http://www.kekezu.com/control/admin/index.php?do=comment&view=sms_list
//$time = date('Y-m-d H:i:s',time());
//Keke_sms::instance()->send('13545368115', '�人�Ϳͣ�����ƽ̨�ѿ�ͨ,����ʱ�䣺'.$time);

 

 
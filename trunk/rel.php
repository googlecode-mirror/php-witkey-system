<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

//��ֵ�ж�


 
$sql = "select * from :Pwitkey_task a LEFT join :Nwitkey_task_work b
on a.task_id = b.task_id
where a.task_id = :task_id";
 
$res =   DB::query($sql,Database::SELECT)->tablepre(':P')->tablepre(':N')->param(':task_id', '129')->execute();

var_dump($res);

 
//$user =  Keke_sms::instance()->get_userinfo();
//http://www.kekezu.com/control/admin/index.php?do=comment&view=sms_list
//$time = date('Y-m-d H:i:s',time());
//Keke_sms::instance()->send('13545368115', '�人�Ϳͣ�����ƽ̨�ѿ�ͨ,����ʱ�䣺'.$time);

die;

 
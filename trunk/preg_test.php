<?php

define('IN_KEKE', TRUE);

include 'app_boot.php';
 
$arr = array('{������}'=>'1231','{�������}'=>'�������test',
		'{��������}'=>'<a href="">test</a>',
		'{����״̬}'=>'ѡ����','{��ʼʱ��}'=>'2012-11-05','{Ͷ�����ʱ��}'=>'20130905',
		'{ѡ�����ʱ��}'=>'2013-01-2'
		);

//վ���Ų��� 
Keke_msg::instance()->set_tpl('task_pub')->set_var($arr)->to_user(1)->send();


 

<?php 
/**
 * �Ϳ��ƹ����ˣ���ʼ��������
 * @var unknown_type
 */
global $config;

$config['keke_app_id'] = '786237';
//�û����������Ӧ��secret
$config['keke_app_secret'] = 'e11dfd6d2bdecfa0ad7313c153d315bc';

//Ĭ��ǩ����ʽ
$config['sign_type'] = 'MD5';
//Ĭ���ַ�����
$config['_input_charset'] = strtoupper(CHARSET);
//ͬ���ص���ַ
$config['return_url'] = $_K['siteurl'].'/keke_client/keke/return.php';
//�첽�ص���ַ
$config['notify_url'] = $_K['siteurl'].'/keke_client/keke/notify.php';
<?php 
/**
 * �Ϳ��ƹ����ˣ���ʼ��������
 * @var unknown_type
 */
global $config,$_K;

$config['keke_app_id'] = '';//
//�û����������Ӧ��secret
$config['keke_app_secret'] = '';//

//Ĭ��ǩ����ʽ
$config['sign_type'] = 'MD5';
//Ĭ���ַ�����
$config['_input_charset'] = strtoupper(CHARSET);
//ͬ���ص���ַ
$config['return_url'] = $_K['siteurl'].'/client/keke/return.php';
//�첽�ص���ַ
$config['notify_url'] = $_K['siteurl'].'/client/keke/notify.php';
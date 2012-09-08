<?php
define ( "IN_KEKE", TRUE );
spl_autoload_register ( 'module' ); 
define ( "ISWAP", True ); //wap_base_class::is_wrap()
include 'app_comm.php'; 

$wrap_msg = array (//wrap��ȫ��msg��ʾ���飬ͨѶʧ�����������
'a' => 'forbidden', //֪ͨ�ͻ��˵ĺ�������
//forbidden��ֹ����ֹ����������Android��ʧ�ܺ�Ĭ��Ϊ��ֹ״̬�����е���ֹ����ʱ��a��������ȱʡ
//relogin����������
'r' => 'Access Denied' );//����ʧ��ԭ��
//Access Denied û�з���Ȩ��
//Connection timed out ���ӳ�ʱ 
ISWAP or kekezu::echojson ( $wrap_msg, 0 );
 
$dos = array ('config','check_version','get_indus','user', 'index', 'login', 'register', 'logout', 'upload', 'test', 'task', 'msg', 'release','search');
 
(! empty ( $do ) && in_array ( $do, $dos )) and $do=$do or $do = 'index'; 
$uid = intval($kekezu->_uid);
$username = $kekezu->_username;
$user_info = $kekezu->_userinfo;
 
function module($class_name) {
	try {
		$file = S_ROOT . '/lib/wap/' . $class_name . '.php';
		if (is_file ( $file )) {
			kekezu::keke_require_once ( $file, $class_name );
			return class_exists ( $file, false ) || interface_exists ( $file, false );
		}
	} catch ( Exception $e ) {
		keke_exception::handler ( $e );
	}
	return true;
} 
include S_ROOT . 'mobile/' . $do . '.php';



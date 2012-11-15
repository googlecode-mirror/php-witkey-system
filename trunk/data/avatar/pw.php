<?php  define('IN_KEKE', 1);

include '../../app_boot.php';
include S_ROOT.'config/config_pw.php';
if($_GET){
	$uri = "action=uploadicon&step=2&&url=images/facebg.jpg&winuid=1&&filename={$_GET['filename']}";
	$data = $_SERVER['HTTP_RAW_POST_DATA'] ? $_SERVER['HTTP_RAW_POST_DATA'] : file_get_contents('php://input');
	/* if($data){
		var_dump($data);die;
	} */
	
	$url = UC_API.'/job.php?'.$uri;
	
	/* echo gettype($data);
	die; */
	if ($data)
	{
		$body = $data;
	}
	//var_dump($_GET,$body);die;
	// Set the content length
	header('content-length', (string) strlen($body));
	
	
	// Create the context
	$options = array(
			strtolower('http') => array(
					'method'     => 'get',
					'header'     => 'Content-type: application/octet-stream',
					'content'    => $body
			)
	);
	
	// Create the context stream
	$context = stream_context_create($options);
	
	stream_context_set_option($context, $options);
	
 	$stream = fopen($url, 'r', FALSE, $context);
	
	$meta_data = stream_get_meta_data($stream);
	$content = stream_get_contents($stream);
	print_r($content);
	fclose($stream);
	
	//Keke::curl_request($url,1,'post',array('data'=>$data));
	//Keke_Request::current()->redirect('user/account_basic/avatar');
} 
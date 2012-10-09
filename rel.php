<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

// Create the context
/* $options = array(
		'http' => array(
				'method'     => 'get',
				'header'     => array("Accept-Language:zh-CN,zh;q=0.8",
						'User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4')
		)
);
 */
// Create the context stream
//$context = stream_context_create($options);

//stream_context_set_option($context, $options);
//http://www.baidu.com/s?wd=ssdsss
$uri = 'http://www.baidu.com/s/wd?=ssss';
$query = array();//array('wd'=>'ssdsss');


function do_post_request($url, $postdata)
{
	$data = "";
	$boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

	//Collect Postdata
	foreach($postdata as $key => $val)
	{
		$data .= "--$boundary\n";
		$data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
	}

	$data .= "--$boundary\n";


	$params = array('http' => array(
	'method' => 'GET',
	'header' => 'Content-Type: Content-Type:text/html; ',
	'content' => $data
	));

   $ctx = stream_context_create($params);
	$fp = fopen($url, 'rb', false, $ctx);

	if (!$fp) {
	throw new Exception("Problem with $url, $php_errormsg");
	}
  
	$response = @stream_get_contents($fp);
	if ($response === false) {
	throw new Exception("Problem reading data from $url, $php_errormsg");
	fclose($fp);
}
return $response;
}
echo do_post_request($uri, $query);
die;
 
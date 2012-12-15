<?php define ( "IN_KEKE", TRUE );

include 'app_boot.php';


 

$subject = '<a href="/kppw_google/index.php/user/account_detail/skill_del?cid=9&pic=data/uploads/2012/12/12/2430650c82f6653cae.jpg" onclick="return kdel(this)">É¾³ý</a>
';

$preg_searchs [] = '/\<a\s*href\=\"\.*\/(index)\.php\/.*\"/ie';
$s[] = '/\<a\s*href=\"\/kppw_google\/(index.php)\.*\"/ie';

$preg_replaces [] = 'index.html';
 

// $c =  str_replace('index.php/', 'index.html/', $subject);
// $c = strtr($subject, array('index.php'=>'index.html'));

//var_dump();




die;




//$a = DB::select()->from('witkey_config')->execute();
 


//echo Keke_user_register::instance('keke')->gen_secode('123456','7sghrm');
//ÓïÑÔ°ü¼ÓÔØ²âÊÔ

 
		
/* $models = DB::select()->from('witkey_model')->where('1=1')->execute();
 
$xml = Xml::array2xml($models);
$arr = Xml::xml2array($xml);
var_dump($arr); */
die;

//$url =  Sys_payment::factory()->get_pay_url('order', '200', 'test', 1212);
/* $detail_data = DB::select('wid,bank_account,bank_username,cash')->from('witkey_withdraw')->limit(0, 10)->execute();
echo Sys_payment::factory()->get_batch_html($detail_data);
 *///Request::factory()->redirect($url);


<?php
//$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';


$img =  Keke_captcha::instance()->render();

 
 
if($_POST){
	Keke_captcha::valid($code);
}
 
/*  $b = array('ad_type','ad_name');
 $a  = array('9','update_name');
 $c = array_combine($b, $a);
 var_dump($c);
die(); 
 */
///$res = Model::factory('witkey_ad')->setData(array('ad_name'=>'sdsdsd','ad_content'=>'content'))->create();

//var_dump($res);
//对象对删除测试
// $res = DB::delete()->table('witkey_ad')->where('ad_id = :id')->param(":id", "261")->execute();
//对象化更新
/* $res = DB::update()->table('witkey_ad')
		->set(array('ad_type','ad_name'))
	  	->value(array('19','name'))
		->where('ad_id = 264')->execute(); */


//对象化插入
//$res = DB::insert()->into('witkey_ad')->set(array('ad_name','ad_content'))->value(array('10','ad_insert'))->execute();


//$res = DB::delete('witkey_ad')->where('ad_id=266')->execute();






/* $aas=new keke_witkey_ad();
$aas->setAd_content($value)->setAd_file($value)->setAd_name($value)->create(); */

// Cache::instance()->generate_id($id)->set(null, $val);

//DB::query($sql)->param($param, $value)->cached()->execute();

// DB::select()->from($table)->where($where)->execute();

/* Keke::$_log->add(Log::STRACE, 'debug_test')->write(); */


//var_dump($res);



/* $end = microtime(true);
echo $end-$star; */
//var_dump ( $end-$star,Keke::execute_time() );
//require keke_tpl_class::template('en');

// $end = microtime(true);
 //var_dump ( $end-$star,Keke::execute_time() );
require keke_tpl_class::template('en');


 
<?php
$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_comm.php';



///$res = Model::factory('witkey_ad')->setData(array('ad_name'=>'sdsdsd','ad_content'=>'content'))->create();

//var_dump($res);
Dbfactory::

/* $aas=new keke_witkey_ad();
$aas->setAd_content($value)->setAd_file($value)->setAd_name($value)->create(); */

// Cache::instance()->generate_id($id)->set(null, $val);

//DB::query($sql)->param($param, $value)->cached()->execute();

// DB::select()->from($table)->where($where)->execute();

/* Keke::$_log->add(Log::STRACE, 'debug_test')->write(); */


//var_dump($res);


$end = microtime(true);
// var_dump ( $end-$star,Keke::execute_time() );
//require keke_tpl_class::template('en');

var_dump(apache_response_headers());

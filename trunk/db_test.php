<?php
$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_comm.php';

//header('Cache-Control: max-age=8000');
 
//var_dump(md5('keke123456'));
$end = microtime(true);
var_dump ( $end-$star,kekezu::execute_time() );
//require keke_tpl_class::template('en');


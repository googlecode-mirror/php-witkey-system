<?php
$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_comm.php';


 
$end = microtime(true);
var_dump ( $end-$star,kekezu::execute_time() );

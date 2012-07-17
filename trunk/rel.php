<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

 
new Control_test();

var_dump(Keke::execute_time()); 
 
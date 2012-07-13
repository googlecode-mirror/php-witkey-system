<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

class a{
	static function b($c='b'){
		echo $c;
	}
}
$r = new ReflectionMethod('a', 'b');
echo $r->invokeArgs(null,array('cccccc'));

<?php define ( "IN_KEKE", TRUE );
 
include 'app_boot.php';

function t(){
	echo 't';
}
 
class a {
	function b(){
		echo 'b';
		t();
	}
}


$a = new a;
$a->b();

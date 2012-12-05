<?php  define ( "IN_KEKE", TRUE );

include 'app_boot.php';


$request = Request::factory ();

$_K ['control'] = $request->initial ()->controller ();
$_K ['action'] = $request->initial ()->action ();
$_K ['directory'] = $request->initial ()->directory ();


$_K['directory'] or $_K['directory'] = 'index';

 
Keke_lang::loadlang ( $_K ['control'] ,$_K['directory'] );


echo $request->execute()->send_headers(TRUE);

die;






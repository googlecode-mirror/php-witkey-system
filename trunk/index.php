<?php  define ( "IN_KEKE", TRUE );

include 'app_boot.php';

isset ( $m ) && $m == "user" and $do = "avatar";

$request = Request::factory ();
$_K ['control'] = $request->initial ()->controller ();
$_K ['action'] = $request->initial ()->action ();
$_K ['directory'] = $request->initial ()->directory ();
keke_lang_class::package_init ( "index" );
keke_lang_class::loadlang ( $_K ['control'] );

$request->execute ();



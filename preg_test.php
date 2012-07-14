<?php

define('IN_KEKE', TRUE);

include 'app_boot.php';


$str = '<!--{include header}-->';

//$m = strtr($str, array($str=>"Keke_tpl::readtemplate('\\1')"));
// mbereg_replace($pattern, $replacement, $string, $option);//

// '/\<\!\-\-\{include\s+([\d_\/]+)\}\-\-\>/ie', "Keke_tpl::readtemplate('\\1')", $template )

//$m =preg_replace ( '/\<\!\-\-\{include\s+([\w_\/]+)\}\-\-\>/ie', "Keke_tpl::readtemplate('\\1')", $str );
// preg_match($par, $str,$matches);


var_dump($m);

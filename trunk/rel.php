<?php 
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';


$apc  = Keke_cache::instance('apc');
/* if($value =$apc->get('test_apc') == null){
	$apc->set('test_apc', 'apc_vale');
}
var_dump($value);
 */
//.$apc->set('test_apc', 'apc_vale');
echo $apc->get('test_apc');
 
list($s,$t) = Keke::execute_time(); 
var_dump($s,$t); 
<?php
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08����02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
$_K['is_rewrite'] = 0 ;

$views = array('ajax','upload','indus','score','code','share','menu','message','file','task');
 
in_array($view,$views) or $view ="ajax";

require 'ajax/ajax_'.$view.'.php';
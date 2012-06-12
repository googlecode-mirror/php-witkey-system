<?php
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2012-01-10下午02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
$ops = array ('auth', 'toolbox','promotion','payitem_task');

in_array ( $op, $ops ) or $op = "auth";
/**
 * 子集菜单
 */
$sub_nav =array(
			array("auth"=>array(kekezu::lang("auth"),"document"),
			   "toolbox"=>array(kekezu::lang("toolbox"),"icon16 box"),
			   "promotion"=>array(kekezu::lang("prom_make_money"),"layers-1"))
			);
			
 $auth_item_list or  $op = 'toolbox';
require 'user_' . $op . '.php';
<?php
/**
 * @copyright keke-tech
 * @author shangk
 * @version v 2.0
 * 2010-5-17обнГ02:29:58
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
include S_ROOT.'/keke_client/keke/config.php';
require Keke_tpl::template ( "control/admin/tpl/admin_{$do}_{$view}" );

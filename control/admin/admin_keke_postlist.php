<?php
/**
 * @author hr
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role(136);
include S_ROOT.'/keke_client/keke/config.php';
require Keke_tpl::template ( "control/admin/tpl/admin_{$do}_{$view}" );
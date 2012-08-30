<?php
/**
 * @author hr
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role(136);
include S_ROOT.'/keke_client/keke/config.php';
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
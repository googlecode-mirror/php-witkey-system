<?php
/**
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * 2012-2-17����
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(136);
include S_ROOT.'/keke_client/keke/config.php';
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
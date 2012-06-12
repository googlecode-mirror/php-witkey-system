<?php
/**
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2010-8-5обнГ04:55:01
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$str = kekezu::check_secode ( $txt_code );
echo $str;
die ();
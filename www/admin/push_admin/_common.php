<?php
define('G5_IS_ADMIN', true);
include_once ('../../common.php');
include_once(G5_ADMIN_PATH.'/admin.lib.php');
include_once(MS_ADMIN_PATH."/admin_access.php");
include_once(MS_ADMIN_PATH."/admin_head.php");
include_once(MS_ADMIN_PATH."/admin_menu.php");
include_once(MS_PLUGIN_PATH.'/wz.chargepoint/config.php');
include_once(MS_PLUGIN_PATH.'/wz.chargepoint/lib/function.lib.php');
include_once(MS_PLUGIN_PATH.'/wz.chargepoint/lib/core.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/style.css">', 10);
//add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/font-awesome.min.css">', 10);
//add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/magnific-popup.css?v=170202">', 12);
//add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/jquery.magnific-popup.min.js"></script>', 12);
//add_javascript('<script type="text/javascript" src="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/js/common.js"></script>', 12);

add_stylesheet('<link rel="stylesheet" href="'.MS_ADMIN_URL.'/wz_chargepoint_admin/style.css?v=191004">', 10);
add_javascript('<script type="text/javascript" src="'.MS_ADMIN_URL.'/wz_chargepoint_admin/js/common.js?v=191004"></script>', 12);
?>
<?php
define('TB_IS_ADMIN', true);
include_once ('../../common.php');
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");
include_once(TB_ADMIN_PATH."/admin_menu.php");

include_once(TB_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(TB_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.TB_ADMIN_URL.'/wz_bookingC_prm_admin/style.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/font-awesome.min.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/magnific-popup.css?v=170202">', 12);
add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/jquery.magnific-popup.min.js"></script>', 12);
add_javascript('<script type="text/javascript" src="'.TB_ADMIN_URL.'/wz_bookingC_prm_admin/js/common.js"></script>', 12);

?>
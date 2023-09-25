<?php
include_once('./_common.php');
include_once(MS_ADMIN_PATH."/admin_access.php");
include_once(MS_ADMIN_PATH."/admin_head.php");
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/style.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/font-awesome.min.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/magnific-popup.css?v=170202">', 12);
add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/jquery.magnific-popup.min.js"></script>', 12);
add_javascript('<script type="text/javascript" src="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/js/common.js"></script>', 12);

$pg_title = ADMIN_MENU11;
$pg_num = 11;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

if($code == "wzb_booking_list")			$pg_title2 = ADMIN_MENU11_01;
if($code == "wzb_booking_status")				$pg_title2 = ADMIN_MENU11_02;
if($code == "wzb_booking_calendar")				$pg_title2 = ADMIN_MENU11_03;
if($code == "wzb_room_list")			$pg_title2 = ADMIN_MENU11_04;
if($code == "wzb_room_status")	$pg_title2 = ADMIN_MENU11_05;
if($code == "wzb_price_list")				$pg_title2 = ADMIN_MENU11_06;
if($code == "wzb_room_option_list")			$pg_title2 = ADMIN_MENU11_07;
if($code == "wzb_holiday_list")			$pg_title2 = ADMIN_MENU11_08;
if($code == "wzb_pay_list")				$pg_title2 = ADMIN_MENU11_09;
if($code == "wzb_popup_list")			$pg_title2 = ADMIN_MENU11_10;
if($code == "wzb_config")			$pg_title2 = ADMIN_MENU11_11;

include_once(MS_ADMIN_PATH."/admin_topmenu.php");
?>
<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(MS_ADMIN_PATH."/wz_bookingC_prm_admin/{$code}.php");
	?>
</div>

<?php
include_once(MS_ADMIN_PATH."/admin_tail.php");
?>
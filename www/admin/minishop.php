<?php
include_once("./_common.php");
include_once(MS_ADMIN_PATH."/admin_access.php");
include_once(MS_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU2;
$pg_num = 2;
$snb_icon = "<i class=\"fa fa-handshake-o\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
    alert("접근권한이 없습니다.");
}

if($code == "pform")		$pg_title2 = ADMIN_MENU2_01;
if($code == "pbasic")		$pg_title2 = ADMIN_MENU2_02;
if($code == "anewlist")		$pg_title2 = ADMIN_MENU2_03;
if($code == "termlist")		$pg_title2 = ADMIN_MENU2_04;
if($code == "plist")		$pg_title2 = ADMIN_MENU2_05;
if($code == "pcenter")		$pg_title2 = ADMIN_MENU2_19;
if($code == "paylist")		$pg_title2 = ADMIN_MENU2_06;
if($code == "balancelist")	$pg_title2 = ADMIN_MENU2_07;
if($code == "payrun")		$pg_title2 = ADMIN_MENU2_08;
if($code == "payhistory")	$pg_title2 = ADMIN_MENU2_09;
if($code == "leave")		$pg_title2 = ADMIN_MENU2_10;
if($code == "tree")			$pg_title2 = ADMIN_MENU2_11;
if($code == "orgchart")		$pg_title2 = ADMIN_MENU2_12;
if($code == "matchlist")	$pg_title2 = ADMIN_MENU2_14;
if($code == "linelist")		$pg_title2 = ADMIN_MENU2_15;
if($code == "point")		$pg_title2 = ADMIN_MENU1_07;
if($code == "sphistory")		$pg_title2 = ADMIN_MENU2_16;
if($code == "sprun")		$pg_title2 = ADMIN_MENU2_17;
if($code == "lphistory")		$pg_title2 = ADMIN_MENU2_18;

include_once(MS_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(MS_ADMIN_PATH."/minishop/mini_{$code}.php");
	?>
</div>

<?php 
include_once(MS_ADMIN_PATH."/admin_tail.php");
?>
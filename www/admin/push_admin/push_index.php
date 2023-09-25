<?php
include_once('./_common.php');

$g5['title'] = 'PUSH 관리';

$pg_title = ADMIN_MENU13;
$pg_num = 13;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

if($code == "push_list")			$pg_title2 = ADMIN_MENU13_01;
if($code == "push_form")				$pg_title2 = ADMIN_MENU13_02;
if($code == "config")				$pg_title2 = ADMIN_MENU13_03;

include_once(MS_ADMIN_PATH."/admin_topmenu.php");
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?code=order_list" class="ov_listall">전체목록</a>';
?>
<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	    include_once(MS_ADMIN_PATH."/push_admin/{$code}.php");
	?>
</div>
<?php
include_once(MS_ADMIN_PATH."/push_admin/admin_tail_config.php");
?>
<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/minishop_reg.php');
}

if(!$config['minishop_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', MS_URL);
}

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '복지몰 분양신청';
include_once("./_head.php");

if($partner['mb_id']) {
	Theme::get_theme_part(MS_THEME_PATH,'/minishop_reg_result.skin.php');
} else {
	$token = md5(uniqid(rand(), true));
	set_session("ss_token", $token);

	$from_action_url = MS_HTTPS_BBS_URL.'/minishop_reg_update.php';
	Theme::get_theme_part(MS_THEME_PATH,'/minishop_reg.skin.php');
}

include_once("./_tail.php");
?>
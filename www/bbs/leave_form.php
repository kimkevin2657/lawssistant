<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/leave_form.php');
}

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '회원탈퇴';
include_once("./_head.php"); 

$form_action_url = MS_HTTPS_BBS_URL.'/leave_form_update.php';
Theme::get_theme_part(MS_THEME_PATH,'/leave_form.skin.php');

include_once("./_tail.php");
?>
<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '회원정보 수정';
include_once("./_head.php");

$readonly = "readonly style='background-color:#ddd;'";

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$register_action_url = MS_HTTPS_BBS_URL.'/register_mod_update.php';

Theme::get_theme_part(MS_THEME_PATH,'/register_mod.skin.php');
include_once("./_tail.php");
?>
<?php
include_once("./_common.php");

if($is_member) {
    alert_close("이미 로그인중입니다.");
}

$ms['title'] = '회원정보 찾기';
include_once(MS_PATH.'/head.sub.php');

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_BBS_URL."/password_lost2.php";
Theme::get_theme_part(MS_THEME_PATH,'/password_lost.skin.php');

include_once(MS_PATH."/tail.sub.php");
?>
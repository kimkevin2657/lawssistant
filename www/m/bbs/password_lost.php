<?php
include_once("./_common.php");

if($is_member) {
    alert("이미 로그인중입니다.");
}

$ms['title'] = '회원정보 찾기';
include_once("./_head.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_MBBS_URL."/password_lost2.php";
Theme::get_theme_part(MS_MTHEME_PATH,'/password_lost.skin.php');

include_once("./_tail.php");
?>
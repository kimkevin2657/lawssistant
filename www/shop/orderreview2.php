<?php
include_once("./_common.php");

if(!$is_member) {
    alert_close("로그인 후 작성 가능합니다.");
}

$ms['title'] = '구매후기 작성';
include_once(MS_PATH."/head.sub.php");

$gs = get_goods($gs_id);

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_SHOP_URL.'/orderreview2_update.php';
Theme::get_theme_part(MS_THEME_PATH,'/orderreview2.skin.php');

include_once(MS_PATH."/tail.sub.php");
?>
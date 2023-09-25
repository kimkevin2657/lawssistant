<?php
include_once("./_common.php");

if(!$config['gift_yes']) {
    alert("쿠폰사용이 중지 되었습니다.");
}

if(!$is_member) {
	goto_url(MS_MBBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = "쿠폰인증";
include_once("./_head.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$sql = " select * from shop_gift where mb_id = '$member[id]' order by no desc ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$form_action_url = MS_HTTPS_MSHOP_URL.'/gift_update.php';
Theme::get_theme_part(MS_MTHEME_PATH,"/gift.skin.php");

include_once("./_tail.php");
?>
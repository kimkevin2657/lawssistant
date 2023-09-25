<?php
include_once("./_common.php");

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', MS_URL);
}

$up_id = get_sub_domain();


if(is_admin()) {
	alert('관리자는 신청을 하실 수 없습니다.');
}

if(is_seller($member['id'])) {
	goto_url(MS_MYPAGE_URL.'/page.php?code=seller_main');
}

$ms['title'] = '온라인 입점신청';
include_once("./_head.php");

if($seller['mb_id'] && !$seller['state']) {
	Theme::get_theme_part(MS_THEME_PATH,'/seller_reg_result.skin.php');
} else {
	$token = md5(uniqid(rand(), true));
	set_session("ss_token", $token);

	$config['seller_reg_agree'] = preg_replace("/\\\/", "", $config['seller_reg_agree']);

	$from_action_url = MS_HTTPS_BBS_URL.'/seller_reg_plus_form_update.php';
	Theme::get_theme_part(MS_THEME_PATH,'/seller_reg_plus_form.skin.php');
}

include_once("./_tail.php");
?>
<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/seller_reg.php');
}

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', MS_URL);
}

$ms['title'] = '판매재 가입하기';
include_once("./_head.php");
Theme::get_theme_part(MS_THEME_PATH,'/seller_reg.skin.php');
include_once("./_tail.php");
?>
<?php
include_once('./common.php');

// 모바일접속인가?
if(TB_IS_MOBILE) {
	goto_url(TB_MURL);
}

define('_INDEX_', true);

// 인트로를 사용중인가?
if(!$is_member && $config['shop_intro_yes']) {
	Theme::get_theme_part(TB_THEME_PATH,'/intro.skin.php');
    return;
}

include_once(TB_PATH.'/head.php'); // 상단
Theme::get_theme_part(TB_THEME_PATH,'/main.skin.php'); // 메인
include_once(TB_PATH.'/tail.php'); // 하단
?>
<?php
################################################################################################
################################################################################################
################################################################################################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
################################################################################################
################################################################################################
################################################################################################
include_once('./common.php');


// 모바일접속인가?
if(MS_IS_MOBILE) {
	goto_url(MS_MURL);
}


$r = new ReflectionClass('Theme');
//echo $r->getFileName();

define('_INDEX_', true);

// 인트로를 사용중인가?

if(!$is_member && $config['shop_intro_yes']) {
	Theme::get_theme_part(MS_THEME_PATH,'/intro.skin.php');
    return;
}

include_once(MS_PATH.'/head.php'); // 상단
Theme::get_theme_part(MS_THEME_PATH,'/main.skin.php'); // 메인
include_once(MS_PATH.'/tail.php'); // 하단
?>
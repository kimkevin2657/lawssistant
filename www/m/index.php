<?php
define('_MINDEX_', true);
include_once("./_common.php");
//session_cache_expire();  
// 인트로를 사용중인지 검사

if(!$is_member && $config['shop_intro_yes']) {
	Theme::get_theme_part(MS_THEME_PATH,'/intro.skin.php');
    return;
}

include_once(MS_MPATH."/_head.php"); // 상단
include_once(MS_MPATH."/popup.inc.php"); // 팝업
Theme::get_theme_part(MS_MTHEME_PATH,'/main.skin.php'); // 팝업레이어
include_once(MS_MPATH."/_tail.php"); // 하단
?>
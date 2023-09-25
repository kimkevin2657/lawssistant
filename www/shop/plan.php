<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/plan.php');
}

$ms['title'] = '복지관';
include_once("./_head.php");
Theme::get_theme_part(MS_THEME_PATH,'/plan.skin.php');
include_once("./_tail.php");
?>
<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/faq.php');
}

$ms['title'] = '자주묻는 질문';
include_once("./_head.php"); 
Theme::get_theme_part(MS_THEME_PATH,'/faq.skin.php');
include_once("./_tail.php");
?>
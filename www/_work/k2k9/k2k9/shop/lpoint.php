<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/point.php');
}

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '라인점수조회';
include_once("./_head.php");

Theme::get_theme_part(TB_THEME_PATH,'/lpoint.skin.php');

include_once("./_tail.php");
?>
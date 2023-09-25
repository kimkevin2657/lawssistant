<?php
include_once("./_common.php");

if(!$is_member)
	goto_url(MS_MBBS_URL.'/login.php?url='.$urlencode);

$ms['title'] = '추천점수조회';
include_once("./_head.php");

Theme::get_theme_part(MS_MTHEME_PATH,'/lpoint.skin.php');

include_once("./_tail.php");
?>
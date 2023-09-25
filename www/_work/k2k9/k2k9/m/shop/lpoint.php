<?php
include_once("./_common.php");

if(!$is_member)
	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);

$tb['title'] = '라인점수조회';
include_once("./_head.php");

Theme::get_theme_part(TB_MTHEME_PATH,'/lpoint.skin.php');

include_once("./_tail.php");
?>
<?php
include_once("./_common.php");

$co	= sql_fetch("select * from shop_content where co_id = '$co_id'");
if(!$co["co_id"]){
	alert('자료가 없습니다.', MS_MURL);
}

$ms['title'] = $co['co_subject'];
include_once("./_head.php");

if(!$co['co_mobile_content']) {
	$co['co_mobile_content'] = $co['co_content'];
}

Theme::get_theme_part(MS_MTHEME_PATH,'/content.skin.php');

include_once("./_tail.php");
?>
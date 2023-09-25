<?php
include_once("./_common.php");

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', MS_MURL);
}

$ms['title'] = '온라인 입점안내';
include_once("./_head.php");
Theme::get_theme_part(MS_MTHEME_PATH,'/seller_reg.skin.php');
include_once("./_tail.php");
?>
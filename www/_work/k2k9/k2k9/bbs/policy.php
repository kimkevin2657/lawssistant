<?php
include_once("./_common.php");

$tb['title'] = '개인정보처리방침';
include_once("./_head.php");
Theme::get_theme_part(TB_THEME_PATH,'/policy.skin.php');
include_once("./_tail.php");
?>
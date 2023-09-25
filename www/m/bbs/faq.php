<?php
include_once("./_common.php");

$ms['title'] = '자주묻는 질문';
include_once("./_head.php");
Theme::get_theme_part(MS_MTHEME_PATH,'/faq.skin.php');
include_once("./_tail.php");
?>
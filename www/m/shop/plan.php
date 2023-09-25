<?php
include_once("./_common.php");

$ms['title'] = '복지관';
include_once("./_head.php");
Theme::get_theme_part(MS_MTHEME_PATH,'/plan.skin.php');
include_once("./_tail.php");
?>
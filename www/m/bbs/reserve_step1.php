<?php
include_once("./_common.php");

$ms['title'] = '예약하기';
include_once("./_head.php");
Theme::get_theme_part(MS_MTHEME_PATH,'/reserve_step1.skin.php');
include_once("./_tail.php");
?>
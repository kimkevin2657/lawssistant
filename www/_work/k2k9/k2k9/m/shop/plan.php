<?php
include_once("./_common.php");

$tb['title'] = '기획전';
include_once("./_head.php");
Theme::get_theme_part(TB_MTHEME_PATH,'/plan.skin.php');
include_once("./_tail.php");
?>
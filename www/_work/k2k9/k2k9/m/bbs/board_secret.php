<?php
include_once("./_common.php");

$tb['title'] = '비밀번호 확인';
include_once("./_head.php");
Theme::get_theme_part(TB_MTHEME_PATH,'/board_secret.skin.php');
include_once("./_tail.php");
?>
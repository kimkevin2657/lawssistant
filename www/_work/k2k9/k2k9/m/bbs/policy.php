<?php
include_once("./_common.php");

$tb['title'] = "개인정보처리방침";
include_once(TB_MPATH."/head.sub.php");
Theme::get_theme_part(TB_MTHEME_PATH,'/policy.skin.php');
include_once(TB_MPATH."/tail.sub.php");
?>
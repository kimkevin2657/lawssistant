<?php
include_once("./_common.php");

$ms['title'] = "개인정보처리방침";
include_once(MS_MPATH."/head.sub.php");
Theme::get_theme_part(MS_MTHEME_PATH,'/policy.skin.php');
include_once(MS_MPATH."/tail.sub.php");
?>
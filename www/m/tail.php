<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

Theme::get_theme_part(MS_MTHEME_PATH,'/tail.skin.php'); // 하단

// BODY 내부 메시지
if($config['tail_script']) {
	echo $config['tail_script'].PHP_EOL;
}

include_once(MS_MPATH."/tail.sub.php");
?>
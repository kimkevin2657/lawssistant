<?php
if(!defined('_MALLSET_')) exit;

Theme::get_theme_part(MS_THEME_PATH,'/tail.skin.php'); // 하단

// BODY 내부 메시지
if($config['tail_script']) {
	echo $config['tail_script'].PHP_EOL;
}

include_once(MS_PATH."/tail.sub.php");
?>
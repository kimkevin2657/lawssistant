<?php
include_once('./common.php');
ini_set("display_errors", 1);
error_reporting(E_ALL^E_NOTICE^E_DEPRECATED^E_WARNING);
$pop_no = $_REQUEST['pop_no'];
$popup  = sql_fetch("SELECT * FROM shop_popup WHERE index_no = '{$pop_no}'");
include_once(MS_PATH.'/head.php'); // 상단
if( $popup ) {
?><div><?php  echo conv_content($popup['memo'], 1);  ?></div><?php
}
include_once(MS_PATH.'/tail.php'); // 하단
?>

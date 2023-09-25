<?php
include_once("./_common.php");

$ms['title'] = '출석포인트';
include_once("./_head.php");

$row = sql_fetch("select * from shop_roulette where no='1'");
$buff_lotto = array(); // 로또공

function init_lotto2(){
global $buff_lotto, $row;
		$point1 = $row['point1'];
		$point2 = $row['point2'];
		$point3 = $row['point3'];
		$point4 = $row['point4'];
		$point5 = $row['point5'];
		$point6 = $row['point6'];
		$point7 = $row['point7'];
		$point8 = $row['point8'];
		$point_per1 = $row['point_per1'];
		$point_per2 = $row['point_per2'];
		$point_per3 = $row['point_per3'];
		$point_per4 = $row['point_per4'];
		$point_per5 = $row['point_per5'];
		$point_per6 = $row['point_per6'];
		$point_per7 = $row['point_per7'];
		$point_per8 = $row['point_per8'];
	$lotto = Array($point1=>$point_per1, $point2=>$point_per2, $point3=>$point_per3, $point4=>$point_per4, $point5=>$point_per5, $point6=>$point_per6, $point7=>$point_per7, $point8=>$point_per8); // 1=>20%, 2=>20%, 3=>5%, 4=>15%, 5=>50%
	foreach($lotto as $key=>$value){
		$buff_lotto = array_merge($buff_lotto, array_fill(0,$value,$key));
	}
}

function get_lotto2(){
global $buff_lotto;
shuffle($buff_lotto);
return end($buff_lotto);
}

Theme::get_theme_part(MS_MTHEME_PATH,'/wheel.skin.php');

include_once("./_tail.php");
?>
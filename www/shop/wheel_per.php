<?php
include_once("./_common.php");
if($_POST["mode"]=='per') {//룰렛 포인트
	$row = sql_fetch("select * from shop_roulette where no='1'");
	$buff_lotto = array(); // 로또공

	function init_lotto(){
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

	function get_lotto(){
	global $buff_lotto;
	shuffle($buff_lotto);
	return end($buff_lotto);
	}

	init_lotto(); // 공준비
	$gong = get_lotto(); // 하나 뽑기

	if($gong == $row['point1']){
		$deg = rand(0, 45)+720;
	}elseif($gong == $row['point2']){
		$deg = rand(45, 90)+720;
	}elseif($gong == $row['point3']){
		$deg = rand(91, 135)+720;
	}elseif($gong == $row['point4']){
		$deg = rand(136, 180)+720;
	}elseif($gong == $row['point5']){
		$deg = rand(181, 225)+720;
	}elseif($gong == $row['point6']){
		$deg = rand(226, 270)+720;
	}elseif($gong == $row['point7']){
		$deg = rand(271, 315)+720;
	}elseif($gong == $row['point8']){
		$deg = rand(316, 360)+720;
	}
	// 한면에 deg 값은 0~60 까지이다. 6개의 항목이라면 360 즉 360도를 나타낸다. 30+720
	// 40을 맞추고 싶다면 180~240 까지 이므로 임의의 약 190에 2바퀴를 추가로 돌린다 가정하면 360x2 720에 +190을 한 910을 입력해주면 된다.
	//$deg = rand(180, 240)+720;
	echo $deg;
}
?>
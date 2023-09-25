<?php
$sub_menu = "600100";
include_once("./_common.php");

$w = $_POST['w'];
$pu_id = $_POST['pu_id'];
$sido = $_POST['sido'];
$gugun = $_POST['gugun'];
$pu_subject = $_POST['pu_subject'];
$pu_content = $_POST['pu_content'];
$pu_link = $_POST['pu_link'];
$pu_sex = $_POST['pu_sex'];
$pu_age = $_POST['pu_age'];
$pu_grade = $_POST['pu_grade'];
$set_addr = $_POST['set_addr'];

$set_lang = $_POST['set_lang']; // 위도
$set_lng = $_POST['set_lng']; // 경도
$set_meter = $_POST['set_meter']; // 거리

$category_name = $_POST['category_name']; // 거리

$address = $sido." ".$gugun;

/* $sql = sql_query("SELECT * FROM shop_member where mb_addr like '{$address}%' ");
while($row = sql_fetch_array($sql)){

	push_send($pu_subject, $pu_content, '', '', '');
} */

if($w == "u"){
	
	sql_query("UPDATE push_data SET pu_subject = '{$pu_subject}', pu_content = '{$pu_content}', pu_datetime = NOW(), pu_sido = '{$sido}', pu_gugun = '{$gugun}', pu_link = '{$pu_link}', pu_sex = '{$pu_sex}', pu_age = '{$pu_age}', pu_grade = '{$pu_grade}', set_addr = '{$set_addr}', set_lang = '{$set_lang}', set_lng = '{$set_lng}', set_meter = '{$set_meter}', category_name = '{$category_name}' where pu_id = '{$pu_id}' ");

	//echo "UPDATE push_data SET pu_subject = '{$pu_subject}', pu_content = '{$pu_content}', pu_datetime = NOW(), pu_sido = '{$sido}', pu_gugun = '{$gugun}', pu_link = '{$pu_link}', pu_sex = '{$pu_sex}', pu_age = '{$pu_age}', pu_grade = '{$pu_grade}', set_addr = '{$set_addr}', set_lang = '{$set_lang}', set_lng = '{$set_lng}', set_meter = '{$set_meter}', category_name = '{$category_name}' where pu_id = '{$pu_id}' "; 

	$msg = "내용이 수정되었습니다.";

}else{

	sql_query("INSERT INTO push_data SET pu_subject = '{$pu_subject}', pu_content = '{$pu_content}', pu_datetime = NOW(), pu_sido = '{$sido}', pu_gugun = '{$gugun}', pu_link = '{$pu_link}', pu_sex = '{$pu_sex}', pu_age = '{$pu_age}', pu_grade = '{$pu_grade}', set_addr = '{$set_addr}', set_lang = '{$set_lang}', set_lng = '{$set_lng}', set_meter = '{$set_meter}', category_name = '{$category_name}' ");

	$msg = "내용이 저장되었습니다.";
}


alert($msg);
?>
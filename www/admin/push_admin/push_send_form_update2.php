<?php
$sub_menu = "600100";
include_once("./_common.php");

$w = $_GET['w'];
$pu_id = $_GET['pu_id'];

$pu_data = sql_fetch("SELECT * FROM push_data where pu_id = '{$pu_id}' ");

$sido = $pu_data['pu_sido'];
$gugun = $pu_data['pu_gugun'];

$pu_age = $pu_data['pu_age'];
$pu_sex = $pu_data['pu_sex'];
$pu_grade = $pu_data['pu_grade'];

$set_meter = $pu_data['set_meter']; //km 반경
$set_lang = $pu_data['set_lang']; //위도
$set_lng = $pu_data['set_lng']; //경도

$address = $pu_data['set_addr'];
$category_name = $pu_data['category_name']; //분류명

$sql_search = " where (1) ";

if($sido != "" && $gugun != ""){
	$sql_search .= " and mb_addr like '{$address}%' ";
}

if($pu_age){
	$sql_search .= " and age = '{$pu_age}' ";
}

if($pu_sex){
	$sql_search .= " and gender = '{$pu_sex}' ";
}

if($pu_grade != 0){
	$sql_search .= " and grade = '{$pu_grade}' ";
}

if($category_name != ""){
	$sql_search .= " and mb_category = '{$category_name}' ";
}

if($set_meter > 0 ){

	$sql = sql_query("SELECT * , ( 6371 * ACOS( COS( RADIANS( {$set_lang} ) ) * COS( RADIANS( mb_lat ) ) * COS( RADIANS( mb_lng ) - RADIANS( {$set_lng} ) ) + SIN( RADIANS( {$set_lang} ) ) * SIN( RADIANS( mb_lat ) ) ) ) AS distance FROM shop_member {$sql_search} having distance <= {$set_meter} ");

	//echo "SELECT * , ( 6371 * ACOS( COS( RADIANS( {$set_lang} ) ) * COS( RADIANS( mb_lat ) ) * COS( RADIANS( mb_lng ) - RADIANS( {$set_lng} ) ) + SIN( RADIANS( {$set_lang} ) ) * SIN( RADIANS( mb_lat ) ) ) ) AS distance FROM shop_member {$sql_search} having distance <= {$set_meter} "; 

}else{

	$sql = sql_query("SELECT * FROM shop_member {$sql_search} ");

	//echo "SELECT * FROM shop_member {$sql_search} ";

}



while($row = sql_fetch_array($sql)){

	push_send($row['id'], $pu_data['pu_subject'], $pu_data['pu_content'], $pu_data['pu_link'], '', '');
}


alert("PUSH가 발송되었습니다.");
?>
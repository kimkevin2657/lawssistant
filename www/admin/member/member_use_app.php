<?php
include_once("./_common.php");

ajax_admin_token();

$mb = get_member($mb_id);
if($mb['use_app'] == '0')
	$t_value = '1';
else
	$t_value = '0';

$pt = get_member($mb['pt_id']);


if($pt['grade'] == '3' or $pt['grade'] == '4' or $pt['grade'] == '6'){
	if($mb['use_app'] == '0'){
		$grade = '7';
	}else{
		$grade = '9';
	}
}elseif($pt['grade'] == '5'){
	if($mb['use_app'] == '0'){
		$grade = '7';
	}else{
		$grade = '9';
	}
}

 if( is_admin($member['grade'])){
	if($mb['use_app'] == '0'){
		$grade = '7';
	}else{
		$grade = '9';
	}
 }



$sql = " update shop_member set use_app='$t_value',grade='$grade' where id='$mb_id' ";
$result = sql_query($sql);

if($result) 
	die("{\"error\":\"\"}"); // 정상
else 
	die("{\"error\":\"일시적인 오류\"}");
?>
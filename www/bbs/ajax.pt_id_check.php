<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(strlen($prt_id) >= 1) {
	$pt = get_member_pt($prt_id);
    if($pt['id']) {
        echo '<strong>'.$pt['name'].' 소속으로 확인되셨습니다.</strong>';
    } else {
	$ptr = get_member_pt_name($prt_id);
		if($ptr['id']) {
			echo '<strong>'.$ptr['name'].' 소속으로 확인되셨습니다.</strong>';
		} else {
			echo '';
		}
        echo '';
    }
}
/*
if(preg_match("/[^0-9a-z_]+/i", $prt_id)) {
    echo '<strong>영문자, 숫자, _ 만 입력하세요.</strong>';
} else if(strlen($prt_id) < 3) {
    echo '<strong>최소 3자이상 입력하세요.</strong>';
} else {
	$pt = get_member_pt($prt_id);
    if($pt['id']) {
        echo '<strong>'.$pt['id'].'님으로 확인되셨습니다.</strong>';
    } else {
        echo '';
    }
}*/
?>
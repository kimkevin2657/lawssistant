<?php
include_once("../../../common.php");

if(!$bo_table || !$wr_id || !$seq || !$current) {
	die("Error");
};

$bo_table = $_POST['bo_table'];
$wr_id	= $_POST['wr_id']; // 글번호
$seq	= $_POST['seq']; // 결재선 번호
$current= $_POST['current']; // 상태값 ( 0 - 대기, 1 -  승인, 2 - 반려, 3 - 보류,  99 - 승인취소 )
$ti		= G5_TIME_YMDHIS;

// 승인 취소
if($current == 99) {
	$current = 0;
	$current_sub = 99;
}

switch($seq) {
	case "1" : // 결재순번 1
		$sql_update = "wr_3 = '{$current}|{$ti}' ";
		break;
	case "2" : // 결재순번 2
		$sql_update = "wr_5 = '{$current}|{$ti}' ";
		break;
	case "3" : // 결재순번 3
		$sql_update = "wr_7 = '{$current}|{$ti}' ";
		break;
	case "4" : // 결재순번 4
		$sql_update = "wr_9 = '{$current}|{$ti}' ";
		break;
	
}

// 상태변경
$sql = "update
			{$write_table}
		set
			{$sql_update}
		where
			wr_id = '{$wr_id}' ";

//echo "approval_upadete.php".$sql;

sql_query($sql, true);

// 승인 취소
if($current_sub == 99) {
	$current = 99;
}

// 로그 기록
$sql = "INSERT INTO
			{$write_table}_log
		SET
			`wr_id`		= '{$wr_id}',
			`mb_id`		= '{$member['id']}',
			`current`	= '{$current}',
			`memo`		= '{$memo}',
			`datetime`	= '{$ti}' ";
			
sql_query($sql, true);

$mode = $sql_update."|".$seq;
echo json_encode($mode);
?>


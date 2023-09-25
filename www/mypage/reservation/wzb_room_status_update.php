<?php
$sub_menu = '790320';
include_once('./_common.php');

$cp_ix              = isset($_POST['sch_cp_ix'])        ? (int)($_POST['sch_cp_ix'])            : "";
$rm_ix              = isset($_POST['rm_ix'])            ? trim($_POST['rm_ix'])                 : "";
$rmc_date           = isset($_POST['rmc_date'])         ? trim($_POST['rmc_date'])              : "";

$rm_ix              = (int)$rm_ix;
$rmc_date           = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $rmc_date) ? $rmc_date : '';

if (!$rm_ix) { 
    die('{"rescd":"99","restx":"이용서비스 정보가 전달되지 않았습니다."}');
} 
else if (!$rmc_date) { 
    die('{"rescd":"97","restx":"일자 정보가 전달되지 않았습니다."}');
} 

$rmc_year   = substr($rmc_date,0,4);
$rmc_month  = substr($rmc_date,5,2);
$rmc_day    = substr($rmc_date,8);

$query = "select rmc_ix from {$g5['wzb_room_close_table']} where cp_ix = '{$cp_ix}' and rm_ix = '{$rm_ix}' and rmc_date = '{$rmc_date}' ";
$rmc = sql_fetch($query);
if ($rmc['rmc_ix']) { // 이미 존재하면 삭제
    
    $query = "delete from {$g5['wzb_room_close_table']} where rmc_ix = '{$rmc['rmc_ix']}' ";
    sql_query($query);
    die('{"rescd":"00","restx":"해제되었습니다."}');
}
else { // 아니면 새로 저장.

    $query = "insert into {$g5['wzb_room_close_table']} set 
                    cp_ix = '$cp_ix', 
                    rm_ix = '$rm_ix', 
                    rmc_year = '$rmc_year', 
                    rmc_month = '$rmc_month', 
                    rmc_day = '$rmc_day', 
                    rmc_date = '$rmc_date'
            ";
    sql_query($query, true);

    die('{"rescd":"00","restx":"차단되었습니다."}');
}
<?php
$sub_menu = '790500';
include_once('./_common.php');

check_admin_token();

$cp_ix              = isset($_POST['sch_cp_ix'])       ? (int)($_POST['sch_cp_ix'])           : "";
$hd_subject         = isset($_POST['hd_subject'])      ? trim($_POST['hd_subject'])           : "";
$hd_date            = isset($_POST['hd_date'])         ? trim($_POST['hd_date'])              : "";
$hd_loop_year       = isset($_POST['hd_loop_year'])    ? trim($_POST['hd_loop_year'])         : "";

$hd_date            = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $hd_date) ? $hd_date : '';
$hd_loop_year       = $hd_loop_year;

if (!$hd_date) { 
    die('{"rescd":"97","restx":"일자 정보가 전달되지 않았습니다."}');
} 

$hd_year   = substr($hd_date,0,4);
$hd_month  = substr($hd_date,5,2);
$hd_day    = substr($hd_date,8);

$query = "select hd_ix from {$g5['wzb_holiday_table']} where cp_ix = '{$cp_ix}' and (hd_date = '{$hd_date}' or (hd_loop_year = 1 and hd_month = '{$hd_month}' and hd_day = '{$hd_day}')) ";
$hd = sql_fetch($query);
if ($hd['hd_ix']) { // 이미 존재하면 업데이트
    $query = "update {$g5['wzb_holiday_table']} set hd_subject = '$hd_subject', hd_loop_year = '$hd_loop_year' where hd_ix = '{$hd['hd_ix']}' and cp_ix = '{$cp_ix}' ";
    sql_query($query);
    die('{"rescd":"00","restx":"수정되었습니다.","resmo":"edit","hd_ix": "'.$hd['hd_ix'].'"}');
} 
else { // 아니면 새로 저장.
    $query = "insert into {$g5['wzb_holiday_table']} set 
                    cp_ix = '$cp_ix', 
                    hd_year = '$hd_year', 
                    hd_month = '$hd_month', 
                    hd_day = '$hd_day', 
                    hd_date = '$hd_date', 
                    hd_subject = '$hd_subject', 
                    hd_loop_year = '$hd_loop_year' 
            ";
    $result = sql_query($query);
    if ($result) { 
        $hd_ix = (!defined('G5_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
        die('{"rescd":"00","restx":"저장되었습니다.","resmo":"new","hd_ix": "'.$hd_ix.'"}');        
    } 
    else {
        die('{"rescd":"96","restx":"정상적으로 실행되지 않았습니다."}');
    }
}
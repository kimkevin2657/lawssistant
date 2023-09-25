<?php
$sub_menu = '790500';
include_once('./_common.php');

check_admin_token();

$cp_ix             = isset($_POST['sch_cp_ix'])       ? (int)($_POST['sch_cp_ix'])           : "";
$hd_ix             = isset($_POST['hd_ix'])           ? trim($_POST['hd_ix'])                : "";
$hd_ix             = (int)$hd_ix;

if (!$hd_ix) { 
    die('{"rescd":"99","restx":"잘못된 접근입니다."}');
} 

$query = "delete from {$g5['wzb_holiday_table']} where hd_ix = '{$hd_ix}' and cp_ix = '{$cp_ix}' ";
sql_query($query);

die('{"rescd":"00","restx":"초기화 처리 되었습니다."}');
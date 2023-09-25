<?php
$sub_menu = '790310';
include_once('./_common.php');

check_admin_token();

$cp_ix              = isset($_POST['sch_cp_ix'])        ? (int)($_POST['sch_cp_ix'])            : "";
$rm_ix              = isset($_POST['rm_ix'])            ? trim($_POST['rm_ix'])                 : "";
$rmp_price          = isset($_POST['rmp_price'])        ? trim($_POST['rmp_price'])             : "";
$rmp_date           = isset($_POST['rmp_date'])         ? trim($_POST['rmp_date'])              : "";
$rmp_time           = isset($_POST['rmp_time'])         ? trim($_POST['rmp_time'])              : "";
$rmp_loop_year      = isset($_POST['rmp_loop_year'])    ? trim($_POST['rmp_loop_year'])         : "";

$rm_ix              = (int)$rm_ix;
$rmp_price          = (int)$rmp_price;
$rmp_date           = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $rmp_date) ? $rmp_date : '';
$rmp_time           = preg_match("/([0-9]{2}):([0-9]{2})/", $rmp_time) ? $rmp_time : '';
$rmp_loop_year      = $rmp_loop_year;

if (!$rm_ix) { 
    die('{"rescd":"99","restx":"이용서비스정보가 전달되지 않았습니다."}');
} 
else if (!$rmp_price) { 
    die('{"rescd":"98","restx":"요금정보는 1원 이상 입력이 되어야 합니다."}');
} 
else if (!$rmp_date) { 
    die('{"rescd":"97","restx":"일자 정보가 전달되지 않았습니다."}');
} 
else if (!$rmp_time) { 
    die('{"rescd":"96","restx":"시간 정보가 전달되지 않았습니다."}');
} 

$rmp_year   = substr($rmp_date,0,4);
$rmp_month  = substr($rmp_date,5,2);
$rmp_day    = substr($rmp_date,8);

$query = "select rmp_ix from {$g5['wzb_room_extend_price_table']} where cp_ix = '{$cp_ix}' and rm_ix = '{$rm_ix}' and ((rmp_date = '{$rmp_date}' and rmp_time = '{$rmp_time}') or (rmp_loop_year = 1 and rmp_month = '{$rmp_month}' and rmp_day = '{$rmp_day}' and rmp_time = '{$rmp_time}')) ";
$rmp = sql_fetch($query);
if ($rmp['rmp_ix']) { // 이미 존재하면 업데이트
    $query = "update {$g5['wzb_room_extend_price_table']} set rmp_price = '$rmp_price', rmp_loop_year = '$rmp_loop_year' where rmp_ix = '{$rmp['rmp_ix']}' and cp_ix = '{$cp_ix}' ";
    sql_query($query);
    die('{"rescd":"00","restx":"수정되었습니다.","resmo":"edit","rmp_ix": "'.$rmp['rmp_ix'].'"}');
} 
else { // 아니면 새로 저장.
    $query = "insert into {$g5['wzb_room_extend_price_table']} set 
                    cp_ix = '$cp_ix', 
                    rm_ix = '$rm_ix', 
                    rmp_year = '$rmp_year', 
                    rmp_month = '$rmp_month', 
                    rmp_day = '$rmp_day', 
                    rmp_date = '$rmp_date', 
                    rmp_time = '$rmp_time', 
                    rmp_price = '$rmp_price', 
                    rmp_loop_year = '$rmp_loop_year' 
            ";
    $result = sql_query($query);
    if ($result) { 
        $rmp_ix = (!defined('G5_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
        die('{"rescd":"00","restx":"저장되었습니다.","resmo":"new","rmp_ix": "'.$rmp_ix.'"}');
    } 
    else {
        die('{"rescd":"96","restx":"정상적으로 실행되지 않았습니다."}');
    }
}
?>
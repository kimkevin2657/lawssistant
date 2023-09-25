<?php
$sub_menu = '790310';
include_once('./_common.php');


$cp_ix          = preg_replace('/[^0-9]/', '', $_POST['sch_cp_ix']);
$rmp_ix         = preg_replace('/[^0-9]/', '', $_POST['rmp_ix']);

if (!$rmp_ix) { 
    die('{"rescd":"99","restx":"잘못된 접근입니다."}');
} 

$rmp_price = 0;
$query = "select rm_ix, rmp_date, rmp_time from {$g5['wzb_room_extend_price_table']} where rmp_ix = '{$rmp_ix}' ";
$rmp = sql_fetch($query);
$rm_ix    = $rmp['rm_ix']; 
$rmp_date = $rmp['rmp_date']; 
$rmp_time = $rmp['rmp_time']; 

$query = "delete from {$g5['wzb_room_extend_price_table']} where rmp_ix = '{$rmp_ix}' ";
sql_query($query);

$prc  = wz_calculate_price($rm_ix, $rmp_date, $rmp_time);   
$rmp_price = $prc['price'];

die('{"rescd":"00","restx":"초기화 처리 되었습니다.", "rmp_price":"'.$rmp_price.'"}');
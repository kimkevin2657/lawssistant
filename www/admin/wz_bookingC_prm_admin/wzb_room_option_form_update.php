<?php
$sub_menu = '790530';
include_once('./_common.php');

check_admin_token();

$mode = isset($_REQUEST['mode']) ? trim($_REQUEST['mode']) : "";
$rmo_ix = isset($_REQUEST['rmo_ix']) ? (int)($_REQUEST['rmo_ix']) : "";
$cp_ix = isset($_REQUEST['cp_ix']) ? (int)($_REQUEST['cp_ix']) : "";
$rmo_name = isset($_REQUEST['rmo_name']) ? trim($_REQUEST['rmo_name']) : "";
$rmo_unit = isset($_REQUEST['rmo_unit']) ? trim($_REQUEST['rmo_unit']) : "";
$rmo_cnt = isset($_REQUEST['rmo_cnt']) ? (int)($_REQUEST['rmo_cnt']) : "";
$rmo_person_max = isset($_REQUEST['rmo_person_max']) ? (int)($_REQUEST['rmo_person_max']) : "";
$rmo_memo = isset($_REQUEST['rmo_memo']) ? trim($_REQUEST['rmo_memo']) : "";
$rmo_price = isset($_REQUEST['rmo_price']) ? (int)($_REQUEST['rmo_price']) : "";
$rmo_required = isset($_REQUEST['rmo_required']) ? (int)($_REQUEST['rmo_required']) : "";
$rmo_sort = isset($_REQUEST['rmo_sort']) ? (int)($_REQUEST['rmo_sort']) : "";
$rmo_use = isset($_REQUEST['rmo_use']) ? (int)($_REQUEST['rmo_use']) : "";

$mode = clean_xss_tags($mode);
$rmo_ix = clean_xss_tags($rmo_ix);
$rmo_name = clean_xss_tags($rmo_name);
$rmo_unit = clean_xss_tags($rmo_unit);
$rmo_cnt = clean_xss_tags($rmo_cnt);
$rmo_person_max = clean_xss_tags($rmo_person_max);
$rmo_memo = clean_xss_tags($rmo_memo);
$rmo_price = clean_xss_tags($rmo_price);
$rmo_required = clean_xss_tags($rmo_required);
$rmo_sort = clean_xss_tags($rmo_sort);
$rmo_use = clean_xss_tags($rmo_use);
$store_mb_id = $_POST['store_mb_id'];
$design_idx = $_POST['design_idx'];
$rm_subject = $_POST['rm_subject'];

$sql_common = " cp_ix = '{$cp_ix}',  
                rmo_name = '{$rmo_name}',
                rmo_unit = '{$rmo_unit}',
                rmo_cnt = '{$rmo_cnt}',
                rmo_memo = '{$rmo_memo}',
                rmo_price = '{$rmo_price}',
                rmo_required = '{$rmo_required}',
                rmo_sort = '{$rmo_sort}',
                rmo_use = '{$rmo_use}',
                store_mb_id = '{$store_mb_id}',
                design_idx = '{$design_idx}',
                design_name = '{$rm_subject}'
            ";

$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_name=".$sch_name."&sch_unit=".$sch_unit."&sch_required=".$sch_required."&sch_use=".$sch_use;

if ($mode == 'new' || $mode == '') { 

    //auth_check($auth[$sub_menu], "w");

    $query = "insert into {$g5['wzb_room_option_table']} set {$sql_common} ";
    sql_query($query);
    
    goto_url('./wzb_booking_list2.php?code=wzb_room_option_list');

} 
else if ($mode == 'edit') { 

    //auth_check($auth[$sub_menu], "w");
    
    $query = "update {$g5['wzb_room_option_table']} set {$sql_common} where rmo_ix = '{$rmo_ix}' ";
    sql_query($query);

    goto_url('./wzb_room_option_form.php?code=wzb_room_option_list&mode=edit&rmo_ix='.$rmo_ix.$qstr);

} 
else if ($mode == 'del') { 

    //auth_check($auth[$sub_menu], "d");
    
    $query = "delete from {$g5['wzb_room_option_table']} where rmo_ix = '{$rmo_ix}' ";
    sql_query($query);

    goto_url('./wzb_booking_list2.php?code=wzb_room_option_list'.$qstr);

}
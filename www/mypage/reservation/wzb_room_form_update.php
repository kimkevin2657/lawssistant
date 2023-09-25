<?php
$sub_menu = '790300';
include_once('./_common.php');

//check_admin_token();

$rm_ix        = preg_replace('/[^0-9]/', '', $_REQUEST['rm_ix']);
$cp_ix        = preg_replace('/[^0-9]/', '', $_REQUEST['cp_ix']);

if ($sch_cp_ix) {
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
}
if ($sch_subject) {
    $qstr .= "&sch_subject=".$sch_subject;
}

if($w == 'd') {

    //auth_check($auth[$sub_menu], 'd');

    sql_query(" delete from {$g5['wzb_room_table']} where rm_ix = '{$rm_ix}' ");

    goto_url('./wzb_booking_list2.php?code=wzb_room_list'.$qstr);

} 
else {

    //auth_check($auth[$sub_menu], 'w');
   
    if (!$cp_ix) { 
        alert('업체를 등록해주세요.');
    } 

    $file_save_dir  = '/wzb_room/';
    $file_save_path = TB_DATA_PATH.$file_save_dir;
    
    //echo $file_save_path;

    @mkdir($file_save_path, G5_DIR_PERMISSION);
    @chmod($file_save_path, G5_DIR_PERMISSION);

    
    $rm_subject     = isset($_POST['rm_subject'])       ? trim($_POST['rm_subject'])        : '';
    $rm_desc        = isset($_POST['rm_desc'])          ? trim($_POST['rm_desc'])           : '';
    $rm_link_url    = isset($_POST['rm_link_url'])      ? trim($_POST['rm_link_url'])       : '';
    $rm_holiday_use = isset($_POST['rm_holiday_use'])   ? (int)($_POST['rm_holiday_use'])   : '0';
    $rm_sort        = isset($_POST['rm_sort'])          ? (int)($_POST['rm_sort'])          : '0';
    $rm_use         = isset($_POST['rm_use'])           ? (int)($_POST['rm_use'])           : '0';
    $rm_week0       = isset($_POST['rm_week0'])         ? (int)($_POST['rm_week0'])         : '0';
    $rm_week1       = isset($_POST['rm_week1'])         ? (int)($_POST['rm_week1'])         : '0';
    $rm_week2       = isset($_POST['rm_week2'])         ? (int)($_POST['rm_week2'])         : '0';
    $rm_week3       = isset($_POST['rm_week3'])         ? (int)($_POST['rm_week3'])         : '0';
    $rm_week4       = isset($_POST['rm_week4'])         ? (int)($_POST['rm_week4'])         : '0';
    $rm_week5       = isset($_POST['rm_week5'])         ? (int)($_POST['rm_week5'])         : '0';
    $rm_week6       = isset($_POST['rm_week6'])         ? (int)($_POST['rm_week6'])         : '0';
    $rm_level       = isset($_POST['rm_level'])         ? (int)($_POST['rm_level'])         : '0';

    $sql_common = " 
                    cp_ix               = '".$cp_ix."',
                    store_mb_id         = '".$_POST['store_mb_id']."',
                    rm_subject          = '".$rm_subject."',
                    rm_desc             = '".$rm_desc."',
                    rm_link_url         = '".$rm_link_url."',
                    rm_holiday_use      = '".$rm_holiday_use."',
                    rm_sort             = '".$rm_sort."',
                    rm_use              = '".$rm_use."',
                    rm_week0            = '".$rm_week0."',
                    rm_week1            = '".$rm_week1."',
                    rm_week2            = '".$rm_week2."',
                    rm_week3            = '".$rm_week3."',
                    rm_week4            = '".$rm_week4."',
                    rm_week5            = '".$rm_week5."',
                    rm_week6            = '".$rm_week6."',
                    rm_level            = '".$rm_level."'
                    ";
}

if($w == '' || $w == 'u') {
	
	if($w == '') {
	
        $sql = " insert into {$g5['wzb_room_table']}
                set $sql_common  ";
        sql_query($sql);

        $rm_ix = (!defined('G5_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
    }
    else if($w == 'u') {

        $sql = " update {$g5['wzb_room_table']}
                    set $sql_common
                    where rm_ix = '{$rm_ix}' ";
        sql_query($sql);

        //echo $sql; 

    }

    // 등록된 객실이미지 삭제
    foreach ($_POST['rmp_ix'] as $key => $value) {
        $rmp_ix = (int)trim($value);
        if ($rmp_ix) { 

            $query = "select * from {$g5['wzb_room_photo_table']} where rmp_ix = '$rmp_ix'";
            $rmp = sql_fetch($query);
            @unlink(TB_DATA_PATH.$file_save_dir.$rmp['rmp_photo']);

            $query = "delete from {$g5['wzb_room_photo_table']} where rmp_ix = '$rmp_ix'";
            sql_query($query);
        } 
    }
    
    // 객실이미지 등록
    $file = wz_file_upload($file_save_path, (1048576 * 30), "rmp_photo", "gif|jpeg|png|tiff|jpg|bmp", "edit");
/*     echo "file";
    print_r($_FILES); */
    if (is_array($file)) {
        foreach ($file as $key => $value) {
            $file_name = $value["filename"];
            $file_name_org = $value["filename_org"];
            $file_size = $value["filesize"];

            if ($file_name) { 
                $sql = "insert into {$g5['wzb_room_photo_table']} set rm_ix = '$rm_ix', rmp_photo = '$file_name', rmp_photo_name = '$file_name_org', rmp_photo_size = '$file_size' ";

                //echo $sql; 

                sql_query($sql);
            }
        }
    }

    // 등록된 이용시간 삭제
    foreach ($_POST['rmt_ix_del'] as $key => $value) {
        $rmt_ix = (int)trim($value);
        if ($rmt_ix) { 
            $query = "delete from {$g5['wzb_room_time_table']} where rmt_ix = '$rmt_ix'";
            sql_query($query);
        } 
    }
    
    // 이용시간 등록
    foreach ($_POST['rmt_time_h'] as $key => $value) {
        
        $rmt_time       = $value .':'. $_POST['rmt_time_m'][$key];
        $rmt_price      = (int)$_POST['rmt_price'][$key];
        $rmt_price_type = $_POST['rmt_price_type'][$key]; // 요금과금방식
        $rmt_max_cnt    = (int)$_POST['rmt_max_cnt'][$key];
        $rmt_ix         = (int)$_POST['rmt_ix'][$key];

        if ($rmt_ix) { 
            $sql = "update {$g5['wzb_room_time_table']} set rmt_time = '$rmt_time', rmt_price = '$rmt_price', rmt_price_type = '$rmt_price_type', rmt_max_cnt = '$rmt_max_cnt' where rmt_ix = '".$rmt_ix."' ";
            sql_query($sql);
        } 
        else {
            $sql = "insert into {$g5['wzb_room_time_table']} set rm_ix = '$rm_ix', rmt_time = '$rmt_time', rmt_price_type = '$rmt_price_type', rmt_price = '$rmt_price', rmt_max_cnt = '$rmt_max_cnt' ";
            sql_query($sql);
        }

    }

    if ($w == '') {
        goto_url('../rpage.php?code=wzb_booking_list'.$qstr);
    }
    else if ($w == 'u') {
        goto_url('../rpage.php?code=wzb_room_form&w=u&rm_ix='.$rm_ix.'&'.$qstr);
	}

}
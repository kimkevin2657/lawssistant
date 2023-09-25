<?php
$sub_menu = '790400';
include_once('./_common.php');

//check_admin_token();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_room=".$sch_room."&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_frdate2=".$sch_frdate2."&sch_todate2=".$sch_todate2."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

if ($_POST['act_button'] == "선택삭제") {

    //auth_check($auth[$sub_menu], "d");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $bkix = (int)$_POST['bk_ix'][$k];
        
        // 객실상태정보 삭제
        $sql = " delete from {$g5['wzb_room_status_table']} where bk_ix = '".$bkix."' ";
        sql_query($sql);
        
        // 객실예약룸정보 삭제
        $sql = " delete from {$g5['wzb_booking_room_table']} where bk_ix = '".$bkix."' ";
        sql_query($sql);

        // 옵션선택정보 삭제
        $sql = " delete from {$g5['wzb_booking_option_table']} where bk_ix = '".$bkix."' ";
        sql_query($sql);

        // 객실예약정보 삭제
        $sql = " delete from {$g5['wzb_booking_table']} where bk_ix = '".$bkix."' ";
        sql_query($sql);
    }

}
else if ($_POST['act_button'] == "선택예약완료") {

    //auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $bkix = (int)$_POST['bk_ix'][$k];

        // 객실상태정보 변경
        $sql = " update {$g5['wzb_room_status_table']} set rms_status = '완료' where bk_ix = '".$bkix."' ";
        sql_query($sql, true);

        // 객실예약정보 변경
        $sql = " update {$g5['wzb_booking_table']} set bk_status = '완료' where bk_ix = '".$bkix."' ";
        sql_query($sql, true);
    }

}
else if ($_POST['act_button'] == "선택예약취소") {

    //auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $bkix = (int)$_POST['bk_ix'][$k];
        
        // 객실상태정보 변경
        $sql = " update {$g5['wzb_room_status_table']} set rms_status = '취소' where bk_ix = '".$bkix."' ";
        sql_query($sql);

        // 객실예약정보 변경
        $sql = " update {$g5['wzb_booking_table']} set bk_status = '취소', bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'admin' where bk_ix = '".$bkix."' ";
        sql_query($sql);
    }

}
else if ($_POST['act_button'] == "선택예약대기") {

    //auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $bkix = (int)$_POST['bk_ix'][$k];
        
        // 객실상태정보 변경
        $sql = " update {$g5['wzb_room_status_table']} set rms_status = '대기' where bk_ix = '".$bkix."' ";
        sql_query($sql);

        // 객실예약정보 변경
        $sql = " update {$g5['wzb_booking_table']} set bk_status = '대기' where bk_ix = '".$bkix."' ";
        sql_query($sql);
    }

}

if ($_POST['act_button'] == "선택예약완료" || $_POST['act_button'] == "선택예약취소" || $_POST['act_button'] == "선택예약대기") { 
    
    include_once(G5_SMS5_PATH.'/sms5.lib.php');
    include_once(WZB_PLUGIN_PATH.'/lib/sms.lib.php');

    include_once(G5_LIB_PATH.'/mailer.lib.php');
    
    //echo G5_SMS5_PATH.'/sms5.lib.php';

    include_once(WZB_PLUGIN_PATH.'/lib/mail.lib.php');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $bkix = (int)$_POST['bk_ix'][$k];

        // sms 발송
        $wzsms = new wz_sms($bkix);
        $wzsms->wz_send();
        
        // mail 발송
        $wzmail = new wz_mail($bkix);
        $wzmail->wz_send();
        
    }
} 

goto_url(TB_URL.'/mypage/rpage.php?code=wzb_booking_list'.$qstr);
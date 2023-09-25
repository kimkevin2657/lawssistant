<?php
$sub_menu = '790400';
include_once('./_common.php');

check_admin_token();

$_POST = array_map('trim', $_POST);
if (isset($_REQUEST['bk_ix'])) {
    $bk_ix = (int)$_REQUEST['bk_ix'];
} else {
    $bk_ix = '';
}

$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_room=".$sch_room."&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_frdate2=".$sch_frdate2."&sch_todate2=".$sch_todate2."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

if ($mode == 'pay') {

    if (!$bk_ix) {
        alert("잘못된 접근입니다.");
    }

    $bk_status          = isset($_POST['bk_status'])        ? trim($_POST['bk_status'])             : "";
    $bk_misu            = isset($_POST['bk_misu'])          ? trim($_POST['bk_misu'])               : "";
    $bk_receipt_price   = isset($_POST['bk_receipt_price']) ? trim($_POST['bk_receipt_price'])      : "";
    $bk_cancel          = isset($_POST['bk_cancel'])        ? trim($_POST['bk_cancel'])             : "";

    $bk_misu            = (int)$bk_misu;
    $bk_receipt_price   = (int)$bk_receipt_price;

    $sql_common = " bk_status           = '{$bk_status}',
                    bk_misu             = '{$bk_misu}',
                    bk_receipt_price    = '{$bk_receipt_price}',
                    bk_receipt_time     = '{$bk_receipt_time}'
                    ";

    $query = "update {$g5['wzb_room_status_table']} set rms_status = '{$bk_status}' where bk_ix = '{$bk_ix}' ";
    sql_query($query);

    $sql = " select * from {$g5['wzb_booking_table']} where bk_ix = '$bk_ix' ";
    $bk = sql_fetch($sql);
    $tno   = $bk['bk_tno'];
    $od_id = $bk['od_id'];

    $store = sql_fetch("SELECT * FROM shop_member where id = '{$bk['store_mb_id']}' ");
    $time = explode(" ", $bk['bk_receipt_time']);

    if($bk_status == "대기"){

$subject_1 = '예약대기';
$message_1 = $bk['bk_name'].'님!
'.$store['name'].'의 예약/결제가 신청되었습니다.

예약번호 : '.$bk['od_id'].'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk['bk_reserv_price']).'
입금계좌 : '.$bk['bk_bank_account'].'

이용해주셔서 감사합니다.';

aligo_sms('TE_4912', $bk['bk_hp'], $bk['bk_name'], $subject_1, $message_1);

    }else if($bk_status == "완료")){

$subject_1 = '예약완료';
$message_1 = $bk['bk_name'].'님!
'.$store['name'].'의 예약/결제가 완료되었습니다.

예약번호 : '.$bk['od_id'].'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk['bk_reserv_price']).'

정상적으로 결제 되었습니다.

이용해주셔서 감사합니다.';
    
aligo_sms('TE_4903', $bk['bk_hp'], $bk['bk_name'], $subject_1, $message_1);

    }else{ //취소

$subject_1 = '예약취소';
$message_1 = $bk['bk_name'].'님!
'.$store['name'].'의 예약/결제가 완료되었습니다.

예약번호 : '.$bk['od_id'].'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk['bk_reserv_price']).'

정상적으로 취소 되었습니다.

이용해주셔서 감사합니다.';
    
aligo_sms('TE_4904', $bk['bk_hp'], $bk['bk_name'], $subject_1, $message_1);
    }

    // pg 결제 승인취소 처리.
    if ($bk_cancel) {

        include_once(WZB_PLUGIN_PATH.'/gender/'.$bk['bk_pg'].'/pg_hub_cancel_adm.php');

        if($pg_res_cd == '') {
            $sql_common .= ", bk_pg_price = 0, bk_pg_cancel = 1, bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'admin' ";
        }
        else {
            die($pg_res_msg);
        }
    }

    $sql = " update {$g5['wzb_booking_table']}
                set $sql_common
                where bk_ix = '{$bk_ix}' ";
    sql_query($sql);

    // SMS BEGIN --------------------------------------------------------
    if ($is_sms_send) {
        include_once(G5_SMS5_PATH.'/sms5.lib.php');
        include_once(WZB_PLUGIN_PATH.'/lib/sms.lib.php');
        $wzsms = new wz_sms($bk_ix);
        $wzsms->wz_send();
    }
    // SMS END   --------------------------------------------------------

    // MAIL BEGIN -------------------------------------------------------
    if ($is_mail_send) {
        include_once(G5_LIB_PATH.'/mailer.lib.php');
        include_once(WZB_PLUGIN_PATH.'/lib/mail.lib.php');
        $wzmail = new wz_mail($bk_ix);
        $wzmail->wz_send();
    }
    // MAIL BEGIN -------------------------------------------------------


}
else if ($mode == 'info') {

    if (!$bk_ix) {
        alert("잘못된 접근입니다.");
    }

    $bk_name            = isset($_POST['bk_name'])          ? trim($_POST['bk_name'])               : "";
    $bk_hp              = isset($_POST['bk_hp'])            ? trim($_POST['bk_hp'])                 : "";
    $bk_email           = isset($_POST['bk_email'])         ? trim($_POST['bk_email'])              : "";
    $bk_memo            = isset($_POST['bk_memo'])          ? trim($_POST['bk_memo'])               : "";

    $bk_email           = wz_get_email_address($bk_email);
    $bk_birthday        = clean_xss_tags($bk_birthday);
    $bk_arrival_time    = clean_xss_tags($bk_arrival_time);

    $sql_common = " bk_name             = '{$bk_name}',
                    bk_hp               = '{$bk_hp}',
                    bk_email            = '{$bk_email}',
                    bk_memo             = '{$bk_memo}'
                    ";

    $sql = " update {$g5['wzb_booking_table']}
                set $sql_common
                where bk_ix = '{$bk_ix}' ";
    sql_query($sql);

}
else if($mode == 'kd') { // 객실개별정보 삭제

    $bkr_ix = (int)$_GET['bkr_ix'];

    $query    = "select * from {$g5['wzb_booking_room_table']} where bkr_ix = '$bkr_ix'";
    $bkr      = sql_fetch($query);
    $bkr_misu = $bkr['bkr_price'];
    $bk_ix    = $bkr['bk_ix'];

    $query = "select bk_receipt_price, bk_misu, bk_status from {$g5['wzb_booking_table']} where bk_ix = '$bk_ix'";
    $bk = sql_fetch($query);
    $bk_receipt_price   = $bk['bk_receipt_price'];
    $bk_misu            = $bk['bk_misu'];
    $bk_status          = $bk['bk_status'];

    if ($bk_misu > 0 && $bkr_misu) { // 삭제하였으므로 미수금이 존재할경우 삭제한만큼 미수금을 차감처리.
        $bk_misu = $bk_misu - $bkr_misu;
    }

    // 해당 예약객실의 예약상태 삭제처리.
    $query = "delete from {$g5['wzb_room_status_table']} where bk_ix = '{$bkr['bk_ix']}' and rm_ix = '{$bkr['rm_ix']}' and rms_time = '{$bkr['bkr_time']}' ";
    sql_query($query);

    // 예약객실정보 삭제처리.
    $query = "delete from {$g5['wzb_booking_room_table']} where bkr_ix = '{$bkr['bkr_ix']}' ";
    sql_query($query);

    // 삭제 후 남아있는 이용서비스의 금액과 인원수 재계산.
    $query = "select sum(bkr_price) as bkr_price, count(1) as cnt, bkr_subject from {$g5['wzb_booking_room_table']} where bk_ix = '$bk_ix'";
    $bkr = sql_fetch($query);

    // 옵션금액 재 계산.
    $query = "select ifnull(sum(odo_price), 0) as odo_price from {$g5['wzb_booking_option_table']} where bk_ix = '$bk_ix'";
    $odo = sql_fetch($query);

    $bk_price = (int)$bkr['bkr_price'] + ($odo['odo_price'] ? $odo['odo_price'] : 0);
    $bk_reserv_price = round(($bk_price / 100) * ($wzpconfig['pn_reserv_price_avg'] ? $wzpconfig['pn_reserv_price_avg'] : 100));

    $query = "update {$g5['wzb_booking_table']} set
                    bk_subject       = '".$bkr['bkr_subject']. ($bkr['cnt']>1 ? ' 외'.($bkr['cnt']-1).'건' : '') ."',
                    bk_price         = '".$bk_price."',
                    bk_reserv_price  = '".$bk_reserv_price."',
                    bk_misu          = '".$bk_misu."'
            where bk_ix = '{$bk_ix}' ";
    sql_query($query);

}

goto_url('./wzb_booking_view.php?bk_ix='.$bk_ix.$qstr);
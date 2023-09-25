<?php
include_once('./_common.php');
include_once('./config.php');
include_once('./lib/function.lib.php');

$uid    = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : "";
//$od_id  = (int)$_REQUEST['od_id'];
$od_id  = $_REQUEST['od_id'];
$od_id  = preg_match("/^[0-9]+$/", $od_id) ? $od_id : '';

if (!$od_id)
    die('{"rescd":"99","restx":"잘못된 접근입니다."}');

if ($mode == 'cancel') { // 예약정보취소

    if (!$is_member) {
        if (get_session('ss_orderview_uid') != $uid)
            die('{"rescd":"98","restx":"잘못된 접근입니다."}');
    }

    $sql = "select * from {$g5['wzb_booking_table']} where od_id = '$od_id' ";
    if($is_member)
        $sql .= " and mb_id = '{$member['mb_id']}' ";
    $bk = sql_fetch($sql);
    if (!$bk['od_id'] || (!$is_member && md5($bk['od_id'].$bk['bk_time'].$bk['bk_ip']) != get_session('ss_orderview_uid'))) {
        die('{"rescd":"97","restx":"조회하실 예약정보가 없습니다."}');
    }

    if ($bk['bk_status'] == '완료') { 
        die('{"rescd":"96","restx":"예약이 완료된 정보이므로 취소가 불가능합니다."}');
    } 
    else {

        // 객실예약정보 변경
        $query = " update {$g5['wzb_booking_table']} set bk_status = '취소', bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'self' where bk_ix = '{$bk['bk_ix']}' ";
        sql_query($query);

        // 객실상태정보 변경
        $query = " update {$g5['wzb_room_status_table']} set rms_status = '취소' where bk_ix = '{$bk['bk_ix']}' ";
        sql_query($query);

    }

    $time = explode(" ", $bk['bk_time']);
    $store = sql_fetch("SELECT * FROM shop_member where id = '{$bk['store_mb_id']}' ");
$subject_1 = '예약취소';
$message_1 = $bk['bk_name'].'님!
'.$store['name'].'의 예약/결제가 취소되었습니다.

예약번호 : '.$bk['od_id'].'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk['bk_reserv_price']).'

정상적으로 취소 되었습니다.

이용해주셔서 감사합니다.';

    aligo_sms('TE_4904', $bk['bk_hp'], $bk['bk_name'], $subject_1, $message_1);

} 

die('{"rescd":"00","restx":""}');
?>
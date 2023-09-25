<?php
$sub_menu = '795100';
include_once('./_common.php');

check_admin_token();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

$qstr .= "&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], "d");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $odid = preg_replace('/[^0-9]/i', '', $_POST['od_id'][$k]);

        $od = sql_fetch(" select mb_id from {$g5['wpot_order_table']} where od_id = '".$odid."' ");

        // 충전내역 삭제
        delete_point($od['mb_id'], '@wzchargepoint', $od['mb_id'], $odid);

        // 충전정보 삭제
        $sql = " delete from {$g5['wpot_order_table']} where od_id = '".$odid."' ";
        sql_query($sql);
    }

}
else if ($_POST['act_button'] == "선택충전완료") {

    auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $odid = preg_replace('/[^0-9]/i', '', $_POST['od_id'][$k]);

        $od = sql_fetch(" select bk_price from {$g5['wpot_order_table']} where od_id = '".$odid."' ");

        // 충전정보 변경
        $sql = " update {$g5['wpot_order_table']} set bk_status = '완료', bk_receipt_time = '".G5_TIME_YMDHIS."', bk_receipt_price = '".$od['bk_price']."' where od_id = '".$odid."' ";
        sql_query($sql, true);

$mb2 = sql_fetch("SELECT * FROM shop_member where id = '{$od['mb_id']}' ");
$mp = (int)$mb2['point']+(int)$od['bk_charge_point'];
$subject_1 = '블링페이 충전완료 안내';
$message_1 = $mb2['name'].'님!
블링페이 포인트가 충전되었습니다.

결제금액 : '.number_format($od['bk_price']).'
충전포인트 : '.$od['bk_charge_point'].'
누적포인트 : '.$mp.'

이용해주셔서 감사합니다.';

aligo_sms('TE_4910', $od['bk_hp'], $mb2['name'], $subject_1, $message_1);

        wz_point_update($odid); // 포인트충전처리
    }

}
else if ($_POST['act_button'] == "선택충전취소") {

    auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $odid = preg_replace('/[^0-9]/i', '', $_POST['od_id'][$k]);

        // 충전정보 변경
        $sql = " update {$g5['wpot_order_table']} set bk_receipt_time = '0000-00-00 00:00:00', bk_receipt_price = 0, bk_status = '취소', bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'admin' where od_id = '".$odid."' ";
        sql_query($sql, true);

        $od = sql_fetch(" select bk_price from {$g5['wpot_order_table']} where od_id = '".$odid."' ");

$mb2 = sql_fetch("SELECT * FROM shop_member where id = '{$od['mb_id']}' ");
$mp = (int)$mb2['point']+(int)$od['bk_charge_point'];
$subject_1 = '블링페이 충전취소 안내';
$message_1 = $mb2['name'].'님!
블링페이 포인트가 취소되었습니다.

충전포인트 : '.$od['bk_charge_point'].'
결제금액 : '.number_format($od['bk_price']).'

이용해주셔서 감사합니다.';

aligo_sms('TE_4909', $od['bk_hp'], $mb2['name'], $subject_1, $message_1);

        wz_point_update($odid); // 포인트충전처리
    }

}
else if ($_POST['act_button'] == "선택충전대기") {

    auth_check($auth[$sub_menu], "w");

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $odid = preg_replace('/[^0-9]/i', '', $_POST['od_id'][$k]);

        // 충전정보 변경
        $sql = " update {$g5['wpot_order_table']} set bk_status = '대기' where od_id = '".$odid."' ";
        sql_query($sql);

        wz_point_update($odid); // 포인트충전처리
    }

}

if ($_POST['act_button'] == "선택충전완료" || $_POST['act_button'] == "선택충전취소" || $_POST['act_button'] == "선택충전대기") {

    include_once(G5_SMS5_PATH.'/sms5.lib.php');
    include_once(WPOT_PLUGIN_PATH.'/lib/sms.lib.php');

    include_once(G5_LIB_PATH.'/mailer.lib.php');
    include_once(WPOT_PLUGIN_PATH.'/lib/mail.lib.php');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $odid = preg_replace('/[^0-9]/i', '', $_POST['od_id'][$k]);

        // sms 발송
        $wzsms = new wz_sms($odid);
        $wzsms->wz_send();

        // mail 발송
        $wzmail = new wz_mail($odid);
        $wzmail->wz_send();
    }
}

goto_url('./order_list.php?'.$qstr);
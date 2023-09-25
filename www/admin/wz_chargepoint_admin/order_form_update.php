<?php
include_once('./_common.php');

$od_id = preg_replace('/[^0-9]/i', '', $_POST['od_id']);

$qstr .= "&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

if (!$od_id) {
    alert("잘못된 접근입니다.");
}

$bk_status          = isset($_POST['bk_status'])        ? trim($_POST['bk_status'])             : "";
$bk_cancel          = isset($_POST['bk_cancel'])        ? trim($_POST['bk_cancel'])             : "";

$bk_misu            = (int)$bk_misu;
$bk_receipt_price   = (int)$bk_receipt_price;

$sql_common = " bk_status           = '{$bk_status}'
                ";

$sql = " select * from {$g5['wpot_order_table']} where od_id = '$od_id' ";
$bk = sql_fetch($sql);
$tno   = $bk['bk_tno'];
$bk_price   = $bk['bk_price'];

if ($bk_status == '완료') {
    $sql_common .= ", bk_receipt_time = '".G5_TIME_YMDHIS."', bk_receipt_price = '".$bk_price."' ";

$mb2 = sql_fetch("SELECT * FROM shop_member where id = '{$bk['mb_id']}' ");
$mp = (int)$mb2['point']+(int)$bk['bk_charge_point'];
$subject_1 = '블링페이 충전완료 안내';
$message_1 = $mb2['name'].'님!
블링페이 포인트가 충전되었습니다.

결제금액 : '.number_format($bk['bk_price']).'
충전포인트 : '.$bk['bk_charge_point'].'
누적포인트 : '.$mp.'

이용해주셔서 감사합니다.';

aligo_sms('TE_4910', $bk['bk_hp'], $mb2['name'], $subject_1, $message_1);

}
else {

    $sql_common .= ", bk_receipt_time = '0000-00-00 00:00:00', bk_receipt_price = 0 ";

    // pg 결제 승인취소 처리.
    if ($bk_cancel) {

$mb2 = sql_fetch("SELECT * FROM shop_member where id = '{$bk['mb_id']}' ");
$mp = (int)$mb2['point']+(int)$bk['bk_charge_point'];
$subject_1 = '블링페이 충전취소 안내';
$message_1 = $mb2['name'].'님!
블링페이 포인트가 취소되었습니다.

충전포인트 : '.$bk['bk_charge_point'].'
결제금액 : '.number_format($bk['bk_price']).'

이용해주셔서 감사합니다.';

aligo_sms('TE_4909', $bk['bk_hp'], $mb2['name'], $subject_1, $message_1);

        include_once(WPOT_PLUGIN_PATH.'/gender/'.$bk['bk_pg'].'/pg_hub_cancel_adm.php');

        if($pg_res_cd == '') {
            $sql_common .= ", bk_pg_price = 0, bk_pg_cancel = 1, bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'admin' ";
        }
        else {
            die($pg_res_msg);
        }
    }

}

$sql = " update {$g5['wpot_order_table']}
            set $sql_common
            where od_id = '{$od_id}' ";
sql_query($sql);

wz_point_update($od_id); // 포인트충전처리

// SMS BEGIN --------------------------------------------------------
if ($is_sms_send) {
    include_once(G5_SMS5_PATH.'/sms5.lib.php');
    include_once(WPOT_PLUGIN_PATH.'/lib/sms.lib.php');
    $wzsms = new wz_sms($od_id);
    $wzsms->wz_send();
}
// SMS END   --------------------------------------------------------

// MAIL BEGIN -------------------------------------------------------
if ($is_mail_send) {
    include_once(G5_LIB_PATH.'/mailer.lib.php');
    include_once(WPOT_PLUGIN_PATH.'/lib/mail.lib.php');
    $wzmail = new wz_mail($od_id);
    $wzmail->wz_send();
}
// MAIL BEGIN -------------------------------------------------------


goto_url('./point_list.php?code=order_view&od_id='.$od_id.'&'.$qstr);
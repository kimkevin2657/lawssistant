<?php
include_once('./_common.php');
include_once('./config.php');
include_once('./lib/function.lib.php');
include_once('./lib/core.lib.php');

if (isset($_POST['sch_day']) && $_POST['sch_day']) {
    $sch_day = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $_POST['sch_day']) ? $_POST['sch_day'] : "";
}

if (!$sch_day) {
    alert('잘못된 접근입니다.', WZB_STATUS_URL);
}

if (!$is_admin && (!isset($_POST['agree1']) || !$_POST['agree1'])) {
    alert('이용규정에 동의하셔야 예약 하실 수 있습니다.');
}

if (!$is_admin && (!isset($_POST['agree2']) || !$_POST['agree2'])) {
    alert('개인정보 활용에 동의하셔야 예약 하실 수 있습니다.');
}

//포인트 차감
$mb = get_member($member['id']);
$use_point = $_POST['use_point'];

if(($use_point < 0) && ($use_point * (-1) > $mb['point'])){
    alert("쇼핑포인트를 차감하는 경우 현재 쇼핑포인트보다 작으면 안됩니다.");
}

$po_point = ($use_point * (-1));
$po_content = "예약 포인트 사용";

insert_point($mb['id'], $po_point, $po_content, '@passive', $mb['id'], $member['id'].'-'.uniqid(''));

$bk_name            = isset($_POST['bk_name'])          ? trim($_POST['bk_name'])               : "";
$bk_hp              = isset($_POST['bk_hp'])            ? trim($_POST['bk_hp'])                 : "";
$bk_email           = isset($_POST['bk_email'])         ? trim($_POST['bk_email'])              : "";
$bk_memo            = isset($_POST['bk_memo'])          ? trim($_POST['bk_memo'])               : "";
$bk_payment         = isset($_POST['bk_payment'])       ? trim($_POST['bk_payment'])            : "";
$bk_deposit_name    = isset($_POST['bk_deposit_name'])  ? trim($_POST['bk_deposit_name'])       : "";
$bk_bank_account    = isset($_POST['bk_bank_account'])  ? trim($_POST['bk_bank_account'])       : "";
$adm_status         = isset($_POST['adm_status'])       ? trim($_POST['adm_status'])            : "";
$store_mb_id    = isset($_POST['store_mb_id'])  ? trim($_POST['store_mb_id'])       : "";
$rm_ix         = isset($_POST['rm_ix'])       ? trim($_POST['rm_ix'])            : "";

$bk_email           = wz_get_email_address($bk_email);
$bk_hp              = clean_xss_tags($bk_hp);
$bk_memo            = clean_xss_tags($bk_memo);
$bk_payment         = clean_xss_tags($bk_payment);
$bk_deposit_name    = clean_xss_tags($bk_deposit_name);
$bk_bank_account    = clean_xss_tags($bk_bank_account);
$adm_status         = clean_xss_tags($adm_status); // 관리자 강제설정

$error_msg      = '';
$bk_cnt_room    = $error = $bk_price = $bk_receipt_price = $bk_misu = $total_room = $total_option = 0;
$bk_subject     = $bk_receipt_time = '';
unset($arr_room);
unset($rms_ix);
unset($bkr_ix);
unset($odo_ix);
$arr_room   = array();
$rms_ix     = array();
$bkr_ix     = array();
$odo_ix     = array();

// 선택이용정보.
unset($arr_room);
$arr_room   = wz_calculate_room($_POST);
$bk_subject = $arr_room[0]['rm_subject'];
$cnt_room   = count($arr_room);

// 선택옵션정보.
unset($arr_option);
$arr_option = wz_calculate_option($_POST);
$cnt_option = count($arr_option);

$rms_year   = substr($sch_day, 0, 4);
$rms_month  = substr($sch_day, 5, 2);
$rms_day    = substr($sch_day, 8);
$now_time   = date('H:i', MS_SERVER_TIME); // 현재시간

$insertroom = $insertstatus = $insertoption = array();

if ($cnt_room > 0) {

    sql_query("LOCK TABLES {$g5['wzb_room_status_table']} WRITE, {$g5['wzb_booking_room_table']} WRITE, {$g5['wzb_room_time_table']} READ;", true);

    foreach ($arr_room as $k => $v) {

        $rm_ix          = $v['rm_ix'];
        $rm_subject     = $v['rm_subject'];
        $rm_time        = $v['rm_time'];
        $rmt_price      = $v['rmt_price'];
        $rmt_price_type = $v['rmt_price_type'];
        $rm_person_max  = 0; // 유효성검증을 위해 core.lib.php 에서 처리된 값을 테이블을 잠근상태에서 다시 초기화
        $rm_person_cnt  = $v['rm_person_cnt'];
        $price_room     = $rmt_price;

        if ($sch_day <= MS_TIME_YMD && $rm_time < $now_time) {
            $error_msg .= '\"'.$rm_subject.'\" 의 '.wz_get_hangul_date($sch_day).' '.wz_get_hangul_time_hm($rm_time).' 은 지난 시간이므로 예약이 불가합니다.\\n';
            $error++;
        }

        // 2019-08-01 : 예약이 가능한 최대수량 다시 확인
        $query = " select rmt_max_cnt from {$g5['wzb_room_time_table']} where rm_ix = '".$rm_ix."' and rmt_time = '".$rm_time."' ";
        $row = sql_fetch($query);
        if ($row['rmt_max_cnt']) {
            $rm_person_max = $row['rmt_max_cnt'];
        }

        // 2019-08-01 : 잔여수량 확인
        $query = " select ifnull(sum(rms_cnt), 0) as rms_cnt from {$g5['wzb_room_status_table']} where rm_ix = '".$rm_ix."' and rms_date = '".$sch_day."' and rms_time = '".$rm_time."' and rms_status <> '취소' group by rm_ix ";
        $row = sql_fetch($query);
        $rm_person_max = $rm_person_max - $row['rms_cnt'];

        // 2019-08-01 : 정해진 수량미달이면 예약가능처리.
        if ($rm_person_max < $rm_person_cnt) { // 예약가능 수량보다 선택한 수량이 많을경우
            $error_msg .= '\"'.$rm_subject.'\" 의 '.wz_get_hangul_date($sch_day).' '.wz_get_hangul_time_hm($rm_time).' 예약 가능 인원보다 초과되어 예약이 불가능합니다.\\n확인 후 다시 예약바랍니다.\\n';
            $error++;
        }

        // 2019-09-09 : 예약인원이 1 미만 일경우 예약불가처리
        if ($rm_person_cnt < 1) {
            $error_msg .= '\"예약 인원은 1명 이상이어야 합니다.\\n확인 후 다시 예약바랍니다.\\n';
            $error++;
        }

        if ($rmt_price_type == '인당') {
            $price_room = $rmt_price * $rm_person_cnt;
        }

        if (!$error) {

            // 이용정보
            $query = "insert into {$g5['wzb_booking_room_table']} set
                        cp_ix       = '".$wzdc['cp_ix']."',
                        rm_ix       = '". $rm_ix."',
                        bkr_subject = '". $rm_subject."',
                        bkr_price   = '". $price_room."',
                        bkr_date    = '". $sch_day."',
                        bkr_time    = '". $rm_time."',
                        bkr_cnt     = '". $rm_person_cnt."'";
            $result = sql_query($query, true);
            $bkr_ix[] = (!defined('MS_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
            if (!$result) {
                $error_msg .= '이용시간정보 등록오류.\\n';
                $error++;
            }
        }

        if (!$error) {

            // 상태정보
            $query = "insert into {$g5['wzb_room_status_table']} set
                        cp_ix       = '".$wzdc['cp_ix']."',
                        rm_ix       = '". $rm_ix."',
                        rms_year    = '". $rms_year."',
                        rms_month   = '". $rms_month."',
                        rms_day     = '". $rms_day."',
                        rms_date    = '". $sch_day."',
                        rms_time    = '". $rm_time."',
                        rms_cnt     = '". $rm_person_cnt."'";
            $result = sql_query($query, true);
            $rms_ix[] = (!defined('MS_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
            if (!$result) {
                $error_msg .= '이용상태정보 등록오류.\\n';
                $error++;
            }
        }

        $total_room += $price_room;
        $bk_cnt_room++;

    }

    sql_query("UNLOCK TABLES ", true);

}
else {
    $error_msg .= '예약 가능한 정보가 존재하지 않습니다.\\n';
    $error++;
}


if (!$error) {

    if ($cnt_option > 0 && !$error) {
        foreach ($arr_option as $k => $v) {

            // 옵션정보
            $insertoption[] = array(
                'rmo_ix'        => $v['rmo_ix'],
                'odo_name'      => $v['rmo_name'],
                'odo_price'     => $v['price'],
                'odo_cnt'       => $v['cnt'],
                'odo_unit'      => $v['rmo_unit'],
                'odr_memo'      => $v['rmo_memo'],
            );

            $total_option += $v['price'];
        }
    }

    // 실제결제되어야할 금액.
    $bk_price           = $total_room + $total_option;
    $bk_reserv_price    = round(($bk_price / 100) * ($wzpconfig['pn_reserv_price_avg'] ? $wzpconfig['pn_reserv_price_avg'] : 100));

    $od_pay_price       = $bk_reserv_price; // pg 단에서 결제금액 일치여부확인을 위한 변수
    $bk_subject         = $bk_subject . ($bk_cnt_room>1 ? ' 외'.($bk_cnt_room-1).'건' : '');
    $bk_status          = '대기';
    $bk_pg_price        = 0; // pg를 통해 결제된 금액.
    $bk_tno = $bk_app_no = '';

    if (!$wzpconfig['pn_is_pay']) { // 결제기능을 사용하지 않을경우 기본 예약상태 처리
        $bk_status = $wzpconfig['pn_result_state'];
    }

    $store = sql_fetch("SELECT * FROM shop_member where id = '{$store_mb_id}' ");

    if ($bk_payment == '무통장') {
        $bk_misu            = $bk_price;
        $bk_receipt_price   = 0;
        $bk_receipt_time    = '0000-00-00 00:00:00';

        $time = explode(" ", MS_TIME_YMDHIS);
$subject_1 = '예약대기';
$message_1 = $bk_name.'님!
'.$store['name'].'의 예약/결제가 신청되었습니다.

예약번호 : '.$od_id.'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk_reserv_price).'
입금계좌 : '.$bk_bank_account.'

이용해주셔서 감사합니다.';
    
        aligo_sms('TE_4912', $bk_hp, $bk_name, $subject_1, $message_1);

    }

    if ($wzpconfig['pn_is_pay']) { // 결제기능사용
        @include_once(WZB_PLUGIN_PATH.'/gender/pg.pay_exec.php');
    }

    if ($is_admin && $adm_status === '완료') { // 관리자 강제설정 (관리자는 예약상태를 강제로 설정합니다.)
        $bk_receipt_price   = $bk_reserv_price;
        $bk_misu            = $bk_price - $bk_receipt_price;
        $bk_receipt_time    = MS_TIME_YMDHIS;
        $bk_status          = $adm_status;

        $time = explode(" ", MS_TIME_YMDHIS);
$subject_1 = '예약대기';
$message_1 = $bk_name.'님!
'.$store['name'].'의 예약/결제가 완료되었습니다.

예약번호 : '.$od_id.'
예약일자 : '.$time[0].'
예약시간 : '.$time[1].'
결제금액 : '.number_format($bk_reserv_price).'

정상적으로 결제 되었습니다.

이용해주셔서 감사합니다.';
    
        aligo_sms('TE_4903', $bk_hp, $bk_name, $subject_1, $message_1);

    }

    if (MS_IS_MOBILE)
        $bk_mobile = '1';
    else
        $bk_mobile = '0';

    $od_id = get_session('ss_order_id');

    $od = sql_fetch("select bk_ix from {$g5['wzb_booking_table']} where od_id = '".$od_id."'");
    if ($od['bk_ix']) {
        alert("이미 예약 처리가 완료된 건 입니다.");
    }

    $query = "insert into {$g5['wzb_booking_table']} set
				cp_ix               = '{$wzdc['cp_ix']}',
                od_id               = '{$od_id}',
                store_mb_id         = '{$store_mb_id}',
                mb_id               = '{$member['id']}',
                bk_name             = '{$bk_name}',
                bk_subject          = '{$bk_subject}',
                bk_hp               = '{$bk_hp}',
                bk_email            = '{$bk_email}',
                bk_memo             = '{$bk_memo}',
                bk_payment          = '{$bk_payment}',
                bk_deposit_name     = '{$bk_deposit_name}',
                bk_bank_account     = '{$bk_bank_account}',
                bk_price            = '{$bk_price}',
                bk_reserv_price     = '{$bk_reserv_price}',
                bk_receipt_price    = '{$bk_receipt_price}',
                bk_pg_price         = '{$bk_pg_price}',
                bk_misu             = '{$bk_misu}',
                bk_receipt_time     = '{$bk_receipt_time}',
                bk_mobile           = '{$bk_mobile}',
                bk_time             = '".MS_TIME_YMDHIS."',
                bk_ip               = '{$_SERVER['REMOTE_ADDR']}',
                bk_pg               = '{$wzpconfig['pn_pg_service']}',
                bk_tno              = '{$bk_tno}',
                bk_app_no           = '{$bk_app_no}',
                bk_status           = '{$bk_status}'
    ";
    $result = sql_query($query, true);
    if (!$result) {
        $error_msg .= '예약정보 등록오류.\\n';
        $error++;
    }
    else {
        $bk_ix = (!defined('MS_MYSQLI_USE') ? mysql_insert_id() : sql_insert_id());
    }

    if (!$error) {

        if (is_array($bkr_ix)) { // 이용정보 예약키 적용.
            $bkr_ix_list = implode(',', $bkr_ix);
            $query = "update {$g5['wzb_booking_room_table']} set bk_ix = '$bk_ix' where bkr_ix in (".$bkr_ix_list.") ";
            sql_query($query);
        }

        if (is_array($rms_ix)) { // 상태정보에 예약키 적용.
            $rms_ix_list = implode(',', $rms_ix);
            $query = "update {$g5['wzb_room_status_table']} set bk_ix = '$bk_ix', rms_status = '$bk_status' where rms_ix in (".$rms_ix_list.") ";
            sql_query($query);
        }

        if (is_array($insertoption)) { //옵션선택정보 등록.
            foreach ($insertoption as $row) {
                $query_common = "";
                foreach ($row as $k => $v) {
                    $query_common .= ", ". $k ." = '". $v ."'";
                }
                $query = "insert into {$g5['wzb_booking_option_table']} set cp_ix = '{$wzdc['cp_ix']}', bk_ix = '$bk_ix' ".$query_common;
                sql_query($query);
            }
        }

        // SMS BEGIN --------------------------------------------------------
        include_once(MS_SMS5_PATH.'/sms5.lib.php');
        include_once(WZB_PLUGIN_PATH.'/lib/sms.lib.php');
        $wzsms = new wz_sms($bk_ix);
        $wzsms->wz_send();
        // SMS END   --------------------------------------------------------

        // MAIL BEGIN -------------------------------------------------------
        include_once(MS_LIB_PATH.'/mailer.lib.php');
        include_once(WZB_PLUGIN_PATH.'/lib/mail.lib.php');
        $wzmail = new wz_mail($bk_ix);
        $wzmail->wz_send();
        // MAIL BEGIN -------------------------------------------------------

        $uid = md5($od_id.MS_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
        set_session('ss_orderview_uid', $uid);
        goto_url(WZB_STATUS_URL.'&mode=step3&od_id='.$od_id.'&amp;uid='.$uid);

    }
}

if ($error) {

    $cancel_msg = '예약정보 등록오류';
    @include_once(WZB_PLUGIN_PATH.'/gender/pg.pay_cancel.php');

    if (is_array($bkr_ix)) { // 이용정보 삭제.
        $bkr_ix_list = implode(',', $bkr_ix);
        $query = "delete from {$g5['wzb_booking_room_table']} where bkr_ix in ('{$bkr_ix_list}') ";
        sql_query($query);
    }
    if (is_array($rms_ix)) { // 상태정보 삭제.
        $rms_ix_list = implode(',', $rms_ix);
        $query = "delete from {$g5['wzb_room_status_table']} where rms_ix in ('{$rms_ix_list}') ";
        sql_query($query);
    }

    alert($error_msg, WZB_STATUS_URL);
}
?>

<html>
    <head>
        <title>예약정보 기록</title>
        <script>
            // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
            function noRefresh()
            {
                /* CTRL + N키 막음. */
                if ((event.keyCode == 78) && (event.ctrlKey == true))
                {
                    event.keyCode = 0;
                    return false;
                }
                /* F5 번키 막음. */
                if(event.keyCode == 116)
                {
                    event.keyCode = 0;
                    return false;
                }
            }

            document.onkeydown = noRefresh ;
        </script>
    </head>
</html>
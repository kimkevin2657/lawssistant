<?php
include_once('./_common.php');
include_once('./config.php');
include_once('./lib/function.lib.php');
include_once('./lib/core.lib.php');

$token = get_session('WPOT_token');
set_session('WPOT_token', '');

if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
    alert('올바른 방법으로 이용해 주십시오.', G5_URL);

if ($wzcnf['cf_con_refund']) {
    if (!$is_admin && (!isset($_POST['agree1']) || !$_POST['agree1'])) {
        alert('환불규정에 동의하셔야 충전 하실 수 있습니다.');
    }
}

$cfp_ix             = isset($_POST['cfp_ix'])           ? trim($_POST['cfp_ix'])                : '';
$bk_hp              = isset($_POST['bk_hp'])            ? trim($_POST['bk_hp'])                 : '';
$bk_email           = isset($_POST['bk_email'])         ? trim($_POST['bk_email'])              : '';
$bk_payment         = isset($_POST['bk_payment'])       ? trim($_POST['bk_payment'])            : '';
$bk_deposit_name    = isset($_POST['bk_deposit_name'])  ? trim($_POST['bk_deposit_name'])       : '';
$bk_bank_account    = isset($_POST['bk_bank_account'])  ? trim($_POST['bk_bank_account'])       : '';
$bk_price           = isset($_POST['bk_price'])         ? trim($_POST['bk_price'])              : '0';
$bk_charge_point    = isset($_POST['bk_charge_point'])  ? trim($_POST['bk_charge_point'])       : '0';

$bk_email           = wz_get_email_address($bk_email);
$cfp_ix             = preg_replace('/[^0-9]/i', '', $cfp_ix);
$bk_hp              = clean_xss_tags($bk_hp);
$bk_payment         = clean_xss_tags($bk_payment);
$bk_deposit_name    = clean_xss_tags($bk_deposit_name);
$bk_bank_account    = clean_xss_tags($bk_bank_account);
$price              = (int)clean_xss_tags($bk_price);
$point              = (int)clean_xss_tags($bk_charge_point);

$error_msg = '';
$bk_misu = $error = 0;
$bk_subject = $bk_receipt_time = '';

if ($wzcnf['cf_point_pay_type'] == '1' || $wzcnf['cf_point_pay_type'] == '2') {
    if ($cfp_ix) {
        $cfp = sql_fetch("select * from {$g5['wpot_config_point_table']} where cfp_ix = '".$cfp_ix."'");
        $price = (int)$cfp['cfp_price'];
        $point = (int)$cfp['cfp_point'];
    }
}

if ($price < 0) {
    $cancel_msg = '잘못된 충전금액입니다.';
    $error++;
}
if ($point < 0) {
    $cancel_msg = '잘못된 충전페이머니입니다.';
    $error++;
}
if (!$is_member) {
    $cancel_msg = '회원만 충전이 가능합니다';
    $error++;
}

if (!$error) {

    // 실제결제되어야할 금액.
    $bk_price           = $price;
    $od_pay_price       = $price; // pg 단에서 결제금액 일치여부확인을 위한 변수
    $bk_charge_point    = $point; // 결제금액에 따른 충전예정포인트
    $bk_subject         = number_format($bk_charge_point).' '.WPOT_POINT_TEXT.' 충전';
    $bk_status          = '대기';
    $bk_pg_price        = 0; // pg를 통해 결제된 금액.
    $bk_tno = $bk_app_no = '';

    if ($bk_payment == '무통장') {
        $bk_misu            = $bk_price;
        $bk_receipt_price   = 0;
        $bk_receipt_time    = '0000-00-00 00:00:00';

    }else{

        $mp = (int)$member['point']+(int)$bk_charge_point;
$subject_1 = '블링페이머니 충전완료 안내';
$message_1 = $member['name'].'님!
블링페이머니가 충전되었습니다.

결제금액 : '.number_format($bk_price).'
충전포인트 : '.$bk_charge_point.'
누적포인트 : '.$mp.'

이용해주셔서 감사합니다.';
    
        aligo_sms('TE_4910', $bk_hp, $member['name'], $subject_1, $message_1);
    }

    @include_once(WPOT_PLUGIN_PATH.'/gender/pg.pay_exec.php');

    if (G5_IS_MOBILE)
        $bk_mobile = '1';
    else
        $bk_mobile = '0';

    $od_id = get_session('ss_order_id');

    $od = sql_fetch("select od_id from {$g5['wpot_order_table']} where od_id = '".$od_id."'");
    if ($od['od_id']) {
        alert("이미 예약 처리가 완료된 건 입니다.");
    }

    $query = "insert into {$g5['wpot_order_table']} set
                od_id               = '{$od_id}',
                mb_id               = '{$member['id']}',
                bk_subject          = '{$bk_subject}',
                bk_hp               = '{$bk_hp}',
                bk_email            = '{$bk_email}',
                bk_payment          = '{$bk_payment}',
                bk_deposit_name     = '{$bk_deposit_name}',
                bk_bank_account     = '{$bk_bank_account}',
                bk_price            = '{$bk_price}',
                bk_charge_point     = '{$bk_charge_point}',
                bk_chargepoint_term = '{$wzcnf['cf_point_term']}',
                bk_receipt_price    = '{$bk_receipt_price}',
                bk_pg_price         = '{$bk_pg_price}',
                bk_receipt_time     = '{$bk_receipt_time}',
                bk_mobile           = '{$bk_mobile}',
                bk_time             = '".G5_TIME_YMDHIS."',
                bk_ip               = '{$_SERVER['REMOTE_ADDR']}',
                bk_pg               = '{$wzcnf['cf_pg_service']}',
                bk_tno              = '{$bk_tno}',
                bk_app_no           = '{$bk_app_no}',
                bk_status           = '{$bk_status}',
                bo_table            = '{$bo_table}'
    ";
    $result = sql_query($query, true);
    if (!$result) {
        $error_msg .= '충전정보 등록오류.\\n';
        $error++;
    }

    if (!$error) {

        wz_point_update($od_id); // 포인트충전처리

        // SMS BEGIN --------------------------------------------------------
        include_once(G5_SMS5_PATH.'/sms5.lib.php');
        include_once(WPOT_PLUGIN_PATH.'/lib/sms.lib.php');
        $wzsms = new wz_sms($od_id);
        $wzsms->wz_send();
        // SMS END   --------------------------------------------------------

        // MAIL BEGIN -------------------------------------------------------
        include_once(G5_LIB_PATH.'/mailer.lib.php');
        include_once(WPOT_PLUGIN_PATH.'/lib/mail.lib.php');
        $wzmail = new wz_mail($od_id);
        $wzmail->wz_send();
        // MAIL BEGIN -------------------------------------------------------

        $uid = md5($od_id.G5_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
        set_session('WPOT_orderview_uid', $uid);
        goto_url(WPOT_STATUS_URL.'&mode=result&od_id='.$od_id.'&amp;uid='.$uid);

    }
}

if ($error) {

    $cancel_msg = '충전정보 등록오류';
    @include_once(WPOT_PLUGIN_PATH.'/gender/pg.pay_cancel.php');

    alert($error_msg, WPOT_STATUS_URL);
}
?>

<html>
    <head>
        <title>충전정보 기록</title>
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
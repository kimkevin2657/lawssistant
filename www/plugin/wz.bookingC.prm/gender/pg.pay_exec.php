<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if ($bk_payment == '신용카드') {

    include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub.php';

    $bk_tno             = $tno;
    $bk_app_no          = $app_no;
    $bk_receipt_price   = $amount;
    $bk_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $bk_deposit_name    = '';
    $bk_bank_account    = $card_name;
    $pg_price           = $amount;
    $bk_pg_price        = $amount;
    $bk_misu            = $bk_price - $bk_receipt_price;
    $bk_status          = '완료';

}
else if ($bk_payment == '계좌이체') {

    include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub.php';
    $bank_name  = iconv("cp949", "utf-8", $bank_name);

    $bk_tno             = $tno;
    $bk_receipt_price   = $amount;
    $bk_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $bk_bank_account    = $bk_payment;
    $bk_deposit_name    = $bk_name;
    $bk_bank_account    = $bank_name;
    $pg_price           = $amount;
    $bk_pg_price        = $amount;
    $bk_misu            = $bk_price - $bk_receipt_price;
    $bk_status          = '완료';

}
else if ($bk_payment == '가상계좌') {

    include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub.php';
    $bankname   = iconv("cp949", "utf-8", $bankname);
    $depositor  = iconv("cp949", "utf-8", $depositor);

    $bk_tno             = $tno;
    $bk_app_no          = $app_no;
    $bk_receipt_price   = 0;
    $bk_bank_account    = $bankname.' '.$account;
    $bk_deposit_name    = $depositor;
    $pg_price           = $amount;
    $bk_misu            = $bk_price - $bk_receipt_price;

}
else if ($bk_payment == '휴대폰') {

    include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub.php';
    $bankname   = iconv("cp949", "utf-8", $bankname);
    $depositor  = iconv("cp949", "utf-8", $depositor);

    $bk_tno             = $tno;
    $bk_receipt_price   = $amount;
    $bk_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $bk_bank_account    = $commid . ($commid ? ' ' : '').$mobile_no;
    $pg_price           = $amount;
    $bk_pg_price        = $amount;
    $bk_misu            = $bk_price - $bk_receipt_price;
    $bk_status          = '완료';

}

if($tno) {
    if((int)$od_pay_price !== (int)$pg_price) {
        $cancel_msg = '결제금액 불일치';
        include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub_cancel.php';
        die($cancel_msg);
    }
}
?>
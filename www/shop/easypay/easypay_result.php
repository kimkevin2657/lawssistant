<?php
if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가
include_once(MS_SHOP_PATH.'/settle_easypay.inc.php');

$resultMap = get_session('PAYREQ_MAP');

if( strcmp('0000', $resultMap['res_cd']) == 0 ) {
    //최종결제요청 결과 성공 DB처리
    $tno        = $resultMap['cno'];
    $amount     = $resultMap['amount'];
    $app_time   = $resultMap['tran_date'];
    $pay_method = $resultMap['pay_type'];
    $pay_type   = $EP_PAYMETHOD_REVERT[$pay_method];
    $depositor  = $resultMap['deposit_nm'];
    $commid     = '';
    $mobile_no  = $resultMap['mobile_no'];
    $app_no     = $resultMap['auth_no'];
    $card_name  = $resultMap['issuer_nm'];
    switch($pay_type) {
        case '계좌이체':
            $bank_name = $resultMap['bank_nm'];
            if($default['de_escrow_use'] == 1)
                $escw_yn         = 'Y';
            break;
        case '가상계좌':
            $bankname  = $resultMap['bank_nm'];
            $account   = $resultMap['account_no'].' '.$resultMap['deposit_nm'];
            $app_no    = $resultMap['account_no'];
            if($default['de_escrow_use'] == 1)
                $escw_yn         = 'Y';
            break;
        default:
            break;
    }
} else {
    die($resultMap['res_msg'].' 코드 : '.$resultMap['res_cd']);
}
?>

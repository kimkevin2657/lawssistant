<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(WPOT_PLUGIN_PATH.'/gender/inicis/config.php');

$is_mobile_pay = is_mobile();

if (!$is_mobile_pay) { // 데스크탑 환경일경우

    $PAYMETHOD = array(
        'VCard'      => '신용카드',
        'Card'       => '신용카드',
        'DirectBank' => '계좌이체',
        'HPP'        => '휴대폰',
        'VBank'      => '가상계좌'
    );

    $resultMap = get_session('resultMap');

    if( strcmp('0000', $resultMap['resultCode']) == 0 ) {
        //최종결제요청 결과 성공 DB처리
        $tno        = $resultMap['tid'];
        $amount     = $resultMap['TotPrice'];
        $app_time   = $resultMap['applDate'].$resultMap['applTime'];
        $pay_method = $resultMap['payMethod'];
        $pay_type   = $PAYMETHOD[$pay_method];
        $depositor  = $resultMap['VACT_InputName'];
        $commid     = '';
        $mobile_no  = $resultMap['HPP_Num'];
        $app_no     = $resultMap['applNum'];
        $card_name  = $CARD_CODE[$resultMap['CARD_Code']];
        switch($pay_type) {
            case '계좌이체':
                $bank_name = $BANK_CODE[$resultMap['ACCT_BankCode']];
                break;
            case '가상계좌':
                $bankname  = $BANK_CODE[$resultMap['VACT_BankCode']];
                $account   = $resultMap['VACT_Num'].' '.$resultMap['VACT_Name'];
                $app_no    = $resultMap['VACT_Num'];
                break;
            default:
                break;
        }
    } else {
        die($resultMap['resultMsg'].' 코드 : '.$resultMap['resultCode']);
    }
}
else { // 모바일환경일 경우

    $PAYMETHOD = array(
        'ISP'    => '신용카드',
        'CARD'   => '신용카드',
        'BANK'   => '계좌이체',
        'MOBILE' => '휴대폰',
        'VBANK'  => '가상계좌'
    );

    // 세션비교
    $hash = md5(get_session('P_TID').$wzcnf['cf_pg_mid'].get_session('P_AMT'));
    if($hash != $_POST['P_HASH'])
        alert('결제 정보가 일치하지 않습니다. 올바른 방법으로 이용해 주십시오.');

    //최종결제요청 결과 성공 DB처리
    $tno             = get_session('P_TID');
    $amount          = get_session('P_AMT');
    $app_time        = $_POST['P_AUTH_DT'];
    $pay_method      = $_POST['P_TYPE'];
    $pay_type        = $PAYMETHOD[$pay_method];
    $depositor       = $_POST['P_UNAME'];
    $commid          = $_POST['P_HPP_CORP'];
    $mobile_no       = $_POST['P_APPL_NUM'];
    $app_no          = $_POST['P_AUTH_NO'];
    $card_name       = $_POST['P_CARD_ISSUER'];

    switch($pay_type) {
        case '계좌이체':
            $bank_name = $_POST['P_VACT_BANK'];
            break;
        case '가상계좌':
            $bankname  = $_POST['P_VACT_BANK'];
            $account   = $_POST['P_VACT_NUM'].' '.$_POST['P_VACT_NAME'];
            $app_no    = $_POST['P_VACT_NUM'];
            break;
        default:
            break;
    }

    // 세션 초기화
    set_session('P_TID',  '');
    set_session('P_AMT',  '');
    set_session('P_HASH', '');

}
?>
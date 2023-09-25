<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

if($default['de_card_test']) {
    // 일반결제 테스트
    //$default['de_easypay_mid'] = 'T0009696';//'T'.$default['de_easypay_mid'];// 'T5102001';
    //$default['de_easypay_mid'] = 'T'.substr($default['de_easypay_mid'], 1, 7);
}
else {
    if($default['de_escrow_use'] == 1) {
        // 에스크로결제
        $useescrow = ':useescrow';
    }
    else {
        // 일반결제
        $useescrow = '';
    }
}

$EP_CASNOTEURL = MS_MSHOP_URL.'/settle_easypay_common.php';

$EP_PAYMETHOD = ['신용카드'=>'11', '무통장입금'=>'22', '계좌이체'=>'21', '휴대폰'=>'31','선불결제'=>'50','간편결제'=>'60'];
$EP_PAYMETHOD_REVERT = [];
foreach($EP_PAYMETHOD as $key=>$val){
    $EP_PAYMETHOD_REVERT[$val] = $key;
}
$EP_WINTYPE = ['iframe', 'popup'];
$EP_CERTTYPE= ['일반'=>'','인증'=>'0','비인증'=>'1'];

$EP_RETURNURL = "http://".$_SERVER['HTTP_HOST']."/m/shop/easypay/easypay_res.php";
$EP_NOTIURL   = "http://".$_SERVER['HTTP_HOST']."/m/shop/easypay/easypay_noti.php";
$EP_POPUPURL  = "http://".$_SERVER['HTTP_HOST']."/m/shop/easypay/popup_reg.php";
$EP_IFRAMEURL = "http://".$_SERVER['HTTP_HOST']."/m/shop/easypay/iframe_reg.php";
$EP_REQUESTURL= "http://".$_SERVER['HTTP_HOST']."/m/shop/easypay/request.php";

/* -------------------------------------------------------------------------- */
/* ::: 처리구분 설정                                                          */
/* -------------------------------------------------------------------------- */
$TRAN_CD_NOR_PAYMENT    = "00101000";   // 승인(일반, 에스크로)
$TRAN_CD_NOR_MGR        = "00201000";   // 변경(일반, 에스크로)

/* -------------------------------------------------------------------------- */
/* ::: 지불정보 설정                                                          */
/* -------------------------------------------------------------------------- */
if( $default['de_card_test']) :
    $g_gw_url  = "testgw.easypay.co.kr";               // Gateway URL ( test )
else:
    $g_gw_url  = "gw.easypay.co.kr";                   // Gateway URL ( real )
endif;
$g_gw_port   = "80";                                           // 포트번호(변경불가)

/* -------------------------------------------------------------------------- */
/* ::: 지불 데이터 셋업 (업체에 맞게 수정)                                    */
/* -------------------------------------------------------------------------- */
/* ※ 주의 ※                                                                 */
/* cert_file 변수 설정                                                        */
/* - pg_cert.pem 파일이 있는 디렉토리의  절대 경로 설정                       */
/* log_dir 변수 설정                                                          */
/* - log 디렉토리 설정                                                        */
/* log_level 변수 설정                                                        */
/* - log 레벨 설정 (1 to 99(높을수록 상세))                                   */
/* -------------------------------------------------------------------------- */

$g_home_dir   = __DIR__.'/easypay/';
$g_cert_file  = $g_home_dir.'/cert/pg_cert.pem';
$g_log_dir    = $g_home_dir."/log";
$g_log_level  = "1";

include_once(__DIR__.'/easypay/easypay_client.php');

<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($wzpconfig['pn_pg_test']) {
    $wzpconfig['pn_pg_mid'] = "T0000";
    $wzpconfig['pn_pg_site_key'] = '3grptw1.zW0GSo4PQdaGvsF__';
    $g_conf_js_url = 'https://testpay.kcp.co.kr/plugin/payplus_web.jsp';
    $g_receipt_url_bill = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
    $g_receipt_url_cash = 'https://testadmin8.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW';
    $g_wsdl = "KCPPaymentService.wsdl";
}
else {
    $g_conf_js_url = 'https://pay.kcp.co.kr/plugin/payplus_web.jsp';
    $g_receipt_url_bill = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
    $g_receipt_url_cash = 'https://admin.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW';
    $g_wsdl = "real_KCPPaymentService.wsdl";
}

if ($is_mobile_pay) { 
    $g_conf_js_url = WZB_PLUGIN_URL.'/gender/kcp/pg_mobile_approval_key.js';
} 

$g_conf_home_dir  = WZB_PLUGIN_PATH.'/gender/kcp';
$g_conf_key_dir   = '';
$g_conf_log_dir   = '/home100/kcp'; // 존재하지 않는 경로를 입력하여 로그 파일 생성되지 않도록 함.

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
    $g_conf_key_dir   = WZB_PLUGIN_PATH.'/gender/kcp/bin/pub.key';
}

$g_conf_site_cd  = $wzpconfig['pn_pg_mid'];
$g_conf_site_key = $wzpconfig['pn_pg_site_key'];

// 테스트 결제 때 PAYCO site_cd, site_key 재설정
if($wzpconfig['pn_pg_test'] && isset($_POST['bk_payment']) && $_POST['bk_payment'] == '간편결제') {
    $g_conf_site_cd = 'S6729';
    $g_conf_site_key = '';
}

if (preg_match("/^T000/", $g_conf_site_cd) || $wzpconfig['pn_pg_test']) {
    $g_conf_gw_url  = "testpaygw.kcp.co.kr";                    // real url : paygw.kcp.co.kr , test url : testpaygw.kcp.co.kr
}
else {
    $g_conf_gw_url  = "paygw.kcp.co.kr";
}

$g_conf_site_name = $config['cf_title'];
$g_conf_log_level = '3';           // 변경불가
$g_conf_gw_port   = '8090';        // 포트번호(변경불가)
$module_type      = '01';          // 변경불가
$ipgm_date        = date("Ymd", (G5_SERVER_TIME + 86400 * 5)); // 결제등록 요청시 사용할 입금마감일
$tablet_size      = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)
?>
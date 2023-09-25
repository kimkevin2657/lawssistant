<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($wzcnf['cf_pg_test']) {
    $wzcnf['cf_pg_mid'] = 'INIpayTest';
    $wzcnf['cf_pg_site_key'] = '1111';
    $wzcnf['cf_pg_sign_key'] = 'SU5JTElURV9UUklQTEVERVNfS0VZU1RS';
    $stdpay_js_url = 'https://stgstdpay.inicis.com/stdjs/INIStdPay.js';
}
else {
    $stdpay_js_url = 'https://stdpay.inicis.com/stdjs/INIStdPay.js';
}


if ($is_mobile_pay) {

    if (!function_exists('curl_init')) {
        alert('cURL 모듈이 설치되어 있지 않습니다.\\n상점관리자에게 문의해 주십시오.');
    }

    $noti_url   = WPOT_PLUGIN_URL.'/gender/inicis/pg_mobile_settle_common.php';
    $next_url   = WPOT_PLUGIN_URL.'/gender/inicis/pg_mobile_approval.php';
    $return_url = WPOT_PLUGIN_URL.'/gender/inicis/pg_mobile_return.php?bo_table='.$bo_table.'&oid=';

}
else {

    /**************************
     * 1. 라이브러리 인클루드 *
     **************************/
    require_once(WPOT_PLUGIN_PATH.'/gender/inicis/libs/INILib.php');
    require_once(WPOT_PLUGIN_PATH.'/gender/inicis/libs/INIStdPayUtil.php');
    require_once(WPOT_PLUGIN_PATH.'/gender/inicis/libs/sha256.inc.php');

    $mid = $wzcnf['cf_pg_mid'];
    $signKey = $wzcnf['cf_pg_sign_key'];

    $home_dir = WPOT_PLUGIN_PATH.'/gender/inicis';

    // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
    @mkdir($home_dir.'/log', 0777);
    @chmod($home_dir.'/log', 0777);

    /***************************************
     * 2. INIpay50 클래스의 인스턴스 생성  *
     ***************************************/
    $inipay = new INIpay50;

    $inipay->SetField("inipayhome", $home_dir); // 이니페이 홈디렉터리(상점수정 필요)
    $inipay->SetField("debug", "false");

    if( ! function_exists('mcrypt_encrypt')) {      // mcrypt 관련 함수가 없다면 취소시 openssl로 합니다.
        $inipay->SetField("encMethod", "openssl");
    }

    $util = new INIStdPayUtil();

    $timestamp = $util->getTimestamp();   // util에 의해서 자동생성

    $cardNoInterestQuota = '';  // 카드 무이자 여부 설정(가맹점에서 직접 설정)
    $cardQuotaBase = '2:3:4:5:6:7:8:9:10:11:12';  // 가맹점에서 사용할 할부 개월수 설정

    $useescrow = '';
    $acceptmethod = 'HPP(2):no_receipt:vbank('.date('Ymd', strtotime("+3 days", G5_SERVER_TIME)).'):below1000'.$useescrow;

    /* 기타 */
    $siteDomain = WPOT_PLUGIN_URL.'/gender/inicis'; //가맹점 도메인 입력
    // 페이지 URL에서 고정된 부분을 적는다.
    // Ex) returnURL이 http://localhost:8082/demo/INIpayStdSample/INIStdPayReturn.php 라면
    //                 http://localhost:8082/demo/INIpayStdSample 까지만 기입한다.

    $returnUrl = $siteDomain.'/INIStdPayReturn.php';
    $closeUrl  = $siteDomain.'/close.php';
    $popupUrl  = $siteDomain.'/popup.php';
}

$g_conf_site_name = $config['cf_title'];
$ipgm_date        = date("Ymd", (G5_SERVER_TIME + 86400 * 5)); // 결제등록 요청시 사용할 입금마감일

$BANK_CODE = array(
    '03' => '기업은행',
    '04' => '국민은행',
    '05' => '외환은행',
    '07' => '수협중앙회',
    '11' => '농협중앙회',
    '20' => '우리은행',
    '23' => 'SC 제일은행',
    '31' => '대구은행',
    '32' => '부산은행',
    '34' => '광주은행',
    '37' => '전북은행',
    '39' => '경남은행',
    '53' => '한국씨티은행',
    '71' => '우체국',
    '81' => '하나은행',
    '88' => '신한은행',
    'D1' => '동양종합금융증권',
    'D2' => '현대증권',
    'D3' => '미래에셋증권',
    'D4' => '한국투자증권',
    'D5' => '우리투자증권',
    'D6' => '하이투자증권',
    'D7' => 'HMC 투자증권',
    'D8' => 'SK 증권',
    'D9' => '대신증권',
    'DA' => '하나대투증권',
    'DB' => '굿모닝신한증권',
    'DC' => '동부증권',
    'DD' => '유진투자증권',
    'DE' => '메리츠증권',
    'DF' => '신영증권'
);

$CARD_CODE = array(
    '01' => '외환',
    '03' => '롯데',
    '04' => '현대',
    '06' => '국민',
    '11' => 'BC',
    '12' => '삼성',
    '14' => '신한',
    '15' => '한미',
    '16' => 'NH',
    '17' => '하나 SK',
    '21' => '해외비자',
    '22' => '해외마스터',
    '23' => 'JCB',
    '24' => '해외아멕스',
    '25' => '해외다이너스'
);

if (!function_exists('wz_fwrite_log')) {
    function wz_fwrite_log($log_dir, $error) {
        $log_file = fopen($log_dir, "a");
        fwrite($log_file, $error."\r\n");
        fclose($log_file);
    }
}
?>
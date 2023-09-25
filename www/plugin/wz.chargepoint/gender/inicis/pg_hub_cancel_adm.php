<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(WPOT_PLUGIN_PATH.'/gender/inicis/config.php');
$cancel_msg = iconv_euckr('운영자 승인 취소');

/*********************
 * 3. 취소 정보 설정 *
 *********************/
$inipay->SetField("type",      "cancel");                        // 고정 (절대 수정 불가)
$inipay->SetField("mid",       $wzcnf['cf_pg_mid']);       // 상점아이디
/**************************************************************************************************
 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
 **************************************************************************************************/
$inipay->SetField("admin",     $wzcnf['cf_pg_site_key']); //비대칭 사용키 키패스워드
$inipay->SetField("tid",       $tno);                   // 취소할 거래의 거래아이디
$inipay->SetField("cancelmsg", $cancel_msg);                     // 취소사유

/****************
 * 4. 취소 요청 *
 ****************/
$inipay->startAction();

/****************************************************************
 * 5. 취소 결과                                           	*
 *                                                        	*
 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
 * (현금영수증 발급 취소시에만 리턴됨)                          *
 ****************************************************************/

$res_cd  = $inipay->getResult('ResultCode');
$res_msg = $inipay->getResult('ResultMsg');

if($res_cd != '00') {
    $pg_res_cd = $res_cd;
    $pg_res_msg = $res_msg;
}
?>
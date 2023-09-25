<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(WZB_PLUGIN_PATH.'/gender/kcp/config.php');
require_once(WZB_PLUGIN_PATH.'/gender/kcp/pg_hub_lib.php');

// locale ko_KR.euc-kr 로 설정
setlocale(LC_CTYPE, 'ko_KR.euc-kr');

$c_PayPlus = new C_PP_CLI_T;

$c_PayPlus->mf_clear();

$tran_cd = '00200000';
$g_conf_home_dir  = WZB_PLUGIN_PATH.'/gender/kcp';
$g_conf_key_dir   = '';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
    $g_conf_log_dir   = WZB_PLUGIN_PATH.'/gender/kcp/log';
    $g_conf_key_dir   = WZB_PLUGIN_PATH.'/gender/kcp/bin/pub.key';
}

if (preg_match("/^T000/", $g_conf_site_cd) || $wzpconfig['pn_pg_test']) {
    $g_conf_gw_url  = "testpaygw.kcp.co.kr";
} else {
    $g_conf_gw_url  = "paygw.kcp.co.kr";
}
$cancel_msg = iconv_euckr('운영자 승인 취소');
$cust_ip = $_SERVER['REMOTE_ADDR'];
$bSucc_mod_type = "STSC";

$c_PayPlus->mf_set_modx_data( "tno",      $tno                         );  // KCP 원거래 거래번호
$c_PayPlus->mf_set_modx_data( "mod_type", $bSucc_mod_type              );  // 원거래 변경 요청 종류
$c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip                     );  // 변경 요청자 IP
$c_PayPlus->mf_set_modx_data( "mod_desc", $cancel_msg );  // 변경 사유

$c_PayPlus->mf_do_tx( $tno,  $g_conf_home_dir, $g_conf_site_cd,
                      $g_conf_site_key,  $tran_cd,    "",
                      $g_conf_gw_url,  $g_conf_gw_port,  "payplus_cli_slib",
                      $ordr_idxx, $cust_ip, "3" ,
                      0, 0, $g_conf_key_dir, $g_conf_log_dir);

$res_cd  = $c_PayPlus->m_res_cd;
$res_msg = $c_PayPlus->m_res_msg;

if($res_cd != '0000') {
    $pg_res_cd = $res_cd;
    $pg_res_msg = iconv_utf8($res_msg);
}

// locale 설정 초기화
setlocale(LC_CTYPE, '');
?>
<?php
define("_MALLSET_", TRUE);

define('WPOT_VER', '1.1');

$g5['wpot_config_table']        = 'wpot_config'; // 기본정보 테이블
$g5['wpot_config_point_table']  = 'wpot_config_point'; // 기본정보 포인트 테이블
$g5['wpot_order_table']         = 'wpot_order'; // 충전정보 테이블
$g5['wpot_order_data_table']    = 'wpot_order_data'; // 충전정보 임시 테이블
$g5['wpot_order_inicis_log_table']    = 'wpot_order_inicis_log'; // 이니시스 결제 임시 테이블

$wzcnf = sql_fetch(" select * from {$g5['wpot_config_table']} ", false);

if($is_mobile){
    define('WPOT_STATUS_URL',        MS_MBBS_URL.'/board.php?bo_table='.$bo_table); // 상태페이지 URL
}else{
    define('WPOT_STATUS_URL',        MS_BBS_URL.'/board.php?bo_table='.$bo_table); // 상태페이지 URL
}
//define('WPOT_STATUS_URL',        MS_BBS_URL.'/board.php?bo_table='.$bo_table); // 상태페이지 URL
define('WPOT_STATUS_HTTPS_URL',  MS_HTTPS_BBS_URL.'/board.php?bo_table='.$bo_table); // 상태페이지 URL (보안서버)
define('WPOT_PLUGIN_URL',        MS_PLUGIN_URL.'/wz.chargepoint');
define('WPOT_PLUGIN_PATH',       MS_PLUGIN_PATH.'/wz.chargepoint');

define('WPOT_POINT_TEXT',       'Oh!포인트');

$config['cf_write_pages'] = 5; // 반응형 처리로 5까지로만 설정.
?>
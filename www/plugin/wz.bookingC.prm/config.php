<?php

define('WZB_STATUS_VER', '1.12');

$g5['wzb_config_table']             = 'g5_wzb3_config'; // 기본정보 테이블
$g5['wzb_corp_table']               = 'g5_wzb3_corp'; // 업체정보 테이블
$g5['wzb_booking_table']            = 'g5_wzb3_booking'; // 예약정보 테이블
$g5['wzb_booking_room_table']       = 'g5_wzb3_booking_room'; // 예약시설서비스정보 테이블
$g5['wzb_booking_data_table']       = 'g5_wzb3_booking_data'; // 예약정보 임시 테이블
$g5['wzb_booking_option_table']     = 'g5_wzb3_booking_option'; // 예약옵션정보 테이블
$g5['wzb_room_table']               = 'g5_wzb3_room'; // 시설정보 테이블
$g5['wzb_room_photo_table']         = 'g5_wzb3_room_photo'; // 시설정보사진 테이블
$g5['wzb_room_close_table']         = 'g5_wzb3_room_close'; // 시설정보차단 테이블
$g5['wzb_room_status_table']        = 'g5_wzb3_room_status'; // 시설정보상태 테이블
$g5['wzb_room_time_table']          = 'g5_wzb3_room_time'; // 시설정보시간 테이블
$g5['wzb_room_option_table']        = 'g5_wzb3_room_option'; // 시설옵션정보 테이블
$g5['wzb_room_extend_price_table']  = 'g5_wzb3_room_extend_price'; // 시설개별요금정보 테이블 (시설요금 최우선순위적용)
$g5['wzb_holiday_table']            = 'g5_wzb3_holiday'; // 공휴일 테이블
$g5['wzb_corp_popup_table']         = 'g5_wzb3_corp_popup'; // 업체팝업배너 테이블

$wzpconfig = sql_fetch(" select * from {$g5['wzb_config_table']} ", false);

$cp_code = $_REQUEST['cp_code'];
$cp_code = preg_match("/^([A-Za-z0-9_]{1,20})$/", $cp_code) ? $cp_code : "";

if ($cp_code) {
    $qstr .= '&amp;cp_code='.$cp_code;
}

// 업체기본정보 설정.
$wzdc = sql_fetch(" select * from {$g5['wzb_corp_table']} where cp_ix = '1' ", false);
if($is_mobile){
    define('WZB_STATUS_URL',        MS_MBBS_URL.'/board.php?bo_table='.$bo_table.'&cp_code='.$cp_code); // 예약상태페이지 URL
    define('WZB_STATUS_HTTPS_URL',  MS_HTTPS_MBBS_URL.'/board.php?bo_table='.$bo_table.'&cp_code='.$cp_code); // 예약상태페이지 URL (보안서버)
}else{
    define('WZB_STATUS_URL',        MS_BBS_URL.'/board.php?bo_table='.$bo_table.'&cp_code='.$cp_code); // 예약상태페이지 URL
    define('WZB_STATUS_HTTPS_URL',  MS_HTTPS_BBS_URL.'/board.php?bo_table='.$bo_table.'&cp_code='.$cp_code); // 예약상태페이지 URL (보안서버)
}

define('WZB_PLUGIN_URL',        MS_PLUGIN_URL.'/wz.bookingC.prm');
define('WZB_PLUGIN_PATH',       MS_PLUGIN_PATH.'/wz.bookingC.prm');
define('WZB_BO_SKIN',           'booking.pensionC');
define('WZB_BO_MOBILE_SKIN',    'booking.pensionC');

$config['cf_write_pages'] = 5; // 반응형 처리로 5까지로만 설정.
?>
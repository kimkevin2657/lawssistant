<?php
if(!defined('_TUBEWEB_')) exit;

//auth_check($auth[$sub_menu], "w");

$pg_title = '환경설정';

$db_reload = false;

// 환경설정 생성
if(!sql_query(" DESCRIBE {$g5['wzb_config_table']} ", false)) {
    sql_query(" CREATE TABLE {$g5['wzb_config_table']} (
                    `pn_ix` INT(11) NOT NULL AUTO_INCREMENT,
                    `pn_bank_info` TEXT NOT NULL COMMENT '무통장입금계좌번호',
                    `bo_table` VARCHAR(20) NOT NULL COMMENT '연결게시판',
                    `pn_con_notice` TEXT NOT NULL COMMENT '공지사항',
                    `pn_con_info` TEXT NOT NULL COMMENT '예약안내',
                    `pn_con_checkinout` TEXT NOT NULL COMMENT '이용안내',
                    `pn_con_refund` TEXT NOT NULL COMMENT '환불안내',
                    `cps_sms_receive` VARCHAR(255) NOT NULL COMMENT '관리자수신번호',
                    `cps_sms1_con_user` TEXT NOT NULL COMMENT '예약대기(예약자)',
                    `cps_sms2_con_user` TEXT NOT NULL COMMENT '예약완료(예약자)',
                    `cps_sms3_con_user` TEXT NOT NULL COMMENT '예약취소(예약자)',
                    `cps_sms1_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약대기사용여부(예약자)',
                    `cps_sms2_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약완료사용여부(예약자)',
                    `cps_sms3_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약취소사용여부(예약자)',
                    `cps_sms1_con_adm` TEXT NOT NULL COMMENT '예약대기(관리자)',
                    `cps_sms2_con_adm` TEXT NOT NULL COMMENT '예약완료(관리자)',
                    `cps_sms3_con_adm` TEXT NOT NULL COMMENT '예약취소(관리자)',
                    `cps_sms4_con_adm` TEXT NOT NULL COMMENT '입금요청(관리자)',
                    `cps_sms1_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약대기사용여부(관리자)',
                    `cps_sms2_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약완료사용여부(관리자)',
                    `cps_sms3_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약취소사용여부(관리자)',
                    `cps_sms4_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '입금요청사용여부(관리자)',
                    `pn_max_booking_expire` SMALLINT(6) NOT NULL DEFAULT '90' COMMENT '예약가능최대일',
                    `pn_wating_time` SMALLINT(6) NOT NULL DEFAULT '48' COMMENT '예약대기시간',
                    `pn_bank_use` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '무통장결제사용여부',
                    `pn_onstore_use` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '현장결제여부',
                    `pn_reserv_price_avg` TINYINT(4) NOT NULL DEFAULT '50' COMMENT '예약금비율',
                    `pn_pg_service` VARCHAR(20) NOT NULL COMMENT '결제대행사',
                    `pn_pg_card_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '신용카드사용',
                    `pn_pg_dbank_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '계좌이체사용',
                    `pn_pg_vbank_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '가상계좌사용',
                    `pn_pg_hp_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '휴대폰결제사용',
                    `pn_pg_test` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '결제테스트',
                    `pn_pg_mid` VARCHAR(100) NOT NULL,
                    `pn_pg_site_key` VARCHAR(255) NOT NULL,
                    `pn_is_pay` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '결제기능사용여부',
                    `pn_result_state` VARCHAR(20) NOT NULL DEFAULT '대기' COMMENT '결제사용안함시 기본예약상태',
                    `pn_start_type` VARCHAR(4) NOT NULL DEFAULT 'date' COMMENT '예약방식',
                    `pn_over_time` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '같은시간중복예약허용여부',
                    PRIMARY KEY (`pn_ix`),
                    INDEX `bo_table` (`bo_table`)
                )
                COMMENT='환경설정'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    sql_query(" INSERT INTO {$g5['wzb_config_table']} 
                    SET `pn_ix`                 = 1, 
                        `pn_bank_info`          = '신한은행1 333-333-33333 홍길동\r\n신한은행2 333-333-33333 홍길동', 
                        `pn_con_notice`         = '공지글을 테스트합니다. - 관리자화면에서 에디터로 수정가능', 
                        `pn_con_info`           = '기본예약안내&nbsp;- 관리자화면에서 에디터로 수정가능', 
                        `pn_con_checkinout`     = '이용안내&nbsp;- 관리자화면에서 에디터로 수정가능',
                        `pn_con_refund`         = '환불규정&nbsp;- 관리자화면에서 에디터로 수정가능'
            ", true);
    $db_reload = true;
}

// 업체정보 생성
if(!sql_query(" DESCRIBE {$g5['wzb_corp_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_corp_table']}` (
                    `cp_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '업체키',
                    `cp_title` VARCHAR(120) NOT NULL COMMENT '업체명',
                    `cp_code` VARCHAR(20) NOT NULL COMMENT '업체코드',
                    `cp_sort` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '정렬번호',
                    `cp_status` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '시스템접속허용',
                    `cp_photo_name` VARCHAR(255) NOT NULL COMMENT '대표이미지파일명',
                    `cp_desc` TEXT NOT NULL COMMENT '업체간단설명',
                    `cp_term_day` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '예약차단일설정',
                    PRIMARY KEY (`cp_ix`),
                    INDEX `cp_code` (`cp_code`)
                )
                COMMENT='업체정보'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);

    sql_query(" INSERT INTO {$g5['wzb_corp_table']} 
                    SET `cp_ix`                 = 1, 
                        `cp_title`              = '".$config['cf_title']."', 
                        `cp_status`             = 1
            ", true);

    $db_reload = true;
}

// 시설정보 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_room_table']}` (
                    `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                    `rm_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설키',
                    `rm_subject` VARCHAR(255) NOT NULL COMMENT '시설명',
                    `rm_desc` TEXT NOT NULL COMMENT '시설간단설명',
                    `rm_link_url` VARCHAR(255) NOT NULL COMMENT '상세정보링크주소',
                    `rm_holiday_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '공휴일예약허용',
                    `rm_sort` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '정렬번호',
                    `rm_use` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '사용여부',
                    `rm_week0` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '일',
                    `rm_week1` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '월',
                    `rm_week2` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '화',
                    `rm_week3` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '수',
                    `rm_week4` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '목',
                    `rm_week5` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '금',
                    `rm_week6` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '토',
                    PRIMARY KEY (`rm_ix`),
                    INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='시설정보'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설정보사진 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_photo_table']} ", false)) {
    sql_query(" CREATE TABLE {$g5['wzb_room_photo_table']} (
                    `rmp_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설이미지키',
                    `rm_ix` INT(11) NOT NULL COMMENT '시설키',
                    `rmp_photo` VARCHAR(255) NOT NULL,
                    `rmp_photo_name` VARCHAR(255) NOT NULL,
                    `rmp_photo_size` INT(11) NOT NULL DEFAULT '0',
                    `rmp_order` TINYINT(4) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`rmp_ix`),
                    INDEX `rm_ix` (`rm_ix`)
                )
                COMMENT='시설정보사진정보'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설정보시간 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_time_table']} ", false)) {
    sql_query(" CREATE TABLE {$g5['wzb_room_time_table']} (
                    `rmt_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설시간키',
                    `rm_ix` INT(11) NOT NULL COMMENT '시설키',
                    `rmt_time` VARCHAR(5) NOT NULL,
                    `rmt_price` INT(11) NOT NULL DEFAULT '0' COMMENT '이용요금',
                    `rmt_price_type` ENUM('인당','시간당') NOT NULL DEFAULT '인당' COMMENT '이용요금과금방식',
                    `rmt_max_cnt` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '예약허용인원',
                    PRIMARY KEY (`rmt_ix`),
                    INDEX `rm_ix` (`rm_ix`)
                )
                COMMENT='시설정보시간'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설정보차단 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_close_table']} ", false)) {
    sql_query(" CREATE TABLE {$g5['wzb_room_close_table']} (
                    `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                    `rmc_ix` INT(11) NOT NULL COMMENT '시설키' AUTO_INCREMENT COMMENT '시설차단키',
                    `rm_ix` INT(11) NOT NULL COMMENT '시설키',
                    `rmc_year` CHAR(4) NOT NULL COMMENT '적용년도(yyyy)',
                    `rmc_month` CHAR(2) NOT NULL COMMENT '적용월(mm)',
                    `rmc_day` CHAR(2) NOT NULL COMMENT '적용일(dd)',
                    `rmc_date` DATE NOT NULL COMMENT '일자(yyyy-mm-dd)',
                    PRIMARY KEY (`rmc_ix`),
                    INDEX `rm_ix` (`rm_ix`),
                    INDEX `rmp_rm` (`rmc_year`, `rmc_month`),
                    INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='시설정보차단'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설정보상태 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_status_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_room_status_table']}` (
                    	`cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `rms_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설상태키',
                        `rm_ix` INT(11) NOT NULL COMMENT '시설키',
                        `bk_ix` INT(11) NOT NULL COMMENT '예약정보키',
                        `rms_year` CHAR(4) NOT NULL COMMENT '년도(yyyy)',
                        `rms_month` CHAR(2) NOT NULL COMMENT '월(mm)',
                        `rms_day` CHAR(2) NOT NULL COMMENT '일(dd)',
                        `rms_date` DATE NOT NULL COMMENT '일자',
                        `rms_time` VARCHAR(5) NOT NULL COMMENT '시간',
                        `rms_status` VARCHAR(10) NOT NULL DEFAULT '대기' COMMENT '상태',
                        `rms_loop_year` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '매년반복:1',
                        `rms_cnt` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '예약수',
                        PRIMARY KEY (`rms_ix`),
                        INDEX `rm_ix` (`rm_ix`),
                        INDEX `rms_date` (`rms_date`),
                        INDEX `rms_ym` (`rms_year`, `rms_month`),
                        INDEX `bk_ix` (`bk_ix`),
                        INDEX `rms_status` (`rms_status`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='시설정보상태'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설옵션 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_option_table']} ", false)) {
        sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_room_option_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `rmo_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설옵션키',
                        `rmo_name` VARCHAR(100) NOT NULL COMMENT '옵션명',
                        `rmo_unit` VARCHAR(20) NOT NULL COMMENT '옵션단위',
                        `rmo_cnt` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '옵션수량',
                        `rmo_memo` VARCHAR(255) NULL DEFAULT NULL COMMENT '옵션한줄설명',
                        `rmo_price` INT(11) NOT NULL DEFAULT '0' COMMENT '금액',
                        `rmo_required` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '필수여부',
                        `rmo_photo_name` INT(11) NULL DEFAULT NULL COMMENT '이미지파일명',
                        `rmo_photo_size` INT(11) NOT NULL DEFAULT '0' COMMENT '이미지파일사이즈',
                        `rmo_sort` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '순서',
                        `rmo_use` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '사용여부',
                        PRIMARY KEY (`rmo_ix`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='시설옵션'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 시설개별요금정보 생성
if(!sql_query(" DESCRIBE {$g5['wzb_room_extend_price_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_room_extend_price_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `rmp_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '시설개별요금키',
                        `rm_ix` INT(11) NOT NULL COMMENT '시설키',
                        `rmp_year` CHAR(4) NOT NULL COMMENT '적용년도(yyyy)',
                        `rmp_month` CHAR(2) NOT NULL COMMENT '적용월(mm)',
                        `rmp_day` CHAR(2) NOT NULL COMMENT '적용일(dd)',
                        `rmp_date` DATE NOT NULL COMMENT '일자(yyyy-mm-dd)',
                        `rmp_time` VARCHAR(5) NOT NULL COMMENT '시간(hh:mm)',
                        `rmp_price` INT(11) NOT NULL DEFAULT '0' COMMENT '시설요금',
                        `rmp_loop_year` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '매년적용',
                        PRIMARY KEY (`rmp_ix`),
                        INDEX `rm_ix` (`rm_ix`),
                        INDEX `rmp_rm` (`rmp_year`, `rmp_month`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='시설개별요금정보'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 예약정보 생성
if(!sql_query(" DESCRIBE {$g5['wzb_booking_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_booking_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `bk_ix` INT(11) NOT NULL AUTO_INCREMENT,
                        `od_id` BIGINT(20) NOT NULL,
                        `mb_id` VARCHAR(255) NOT NULL,
                        `bk_name` VARCHAR(20) NOT NULL,
                        `bk_subject` VARCHAR(255) NOT NULL,
                        `bk_hp` VARCHAR(20) NOT NULL,
                        `bk_email` VARCHAR(100) NOT NULL,
                        `bk_memo` TEXT NOT NULL,
                        `bk_payment` VARCHAR(10) NOT NULL,
                        `bk_deposit_name` VARCHAR(20) NOT NULL,
                        `bk_bank_account` VARCHAR(255) NOT NULL,
                        `bk_price` INT(11) NOT NULL DEFAULT '0',
                        `bk_reserv_price` INT(11) NOT NULL DEFAULT '0',
                        `bk_receipt_price` INT(11) NOT NULL DEFAULT '0',
                        `bk_pg_price` INT(11) NOT NULL DEFAULT '0',
                        `bk_pg_cancel` INT(11) NOT NULL DEFAULT '0',
                        `bk_misu` INT(11) NOT NULL DEFAULT '0',
                        `bk_receipt_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `bk_mobile` TINYINT(4) NOT NULL DEFAULT '0',
                        `bk_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `bk_ip` VARCHAR(20) NOT NULL,
                        `bk_status` VARCHAR(20) NOT NULL DEFAULT '대기',
                        `bk_log` VARCHAR(255) NOT NULL,
                        `bk_pg` VARCHAR(20) NOT NULL,
                        `bk_tno` VARCHAR(255) NOT NULL,
                        `bk_app_no` VARCHAR(100) NOT NULL,
                        `bk_cancel_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `bk_cancel_ip` VARCHAR(20) NOT NULL,
                        `bk_cancel_pos` VARCHAR(10) NOT NULL,
                        PRIMARY KEY (`bk_ix`),
                        INDEX `od_id` (`od_id`),
                        INDEX `mb_id` (`mb_id`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='예약정보'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 예약시설정보 생성
if(!sql_query(" DESCRIBE {$g5['wzb_booking_room_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_booking_room_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `bkr_ix` INT(11) NOT NULL AUTO_INCREMENT,
                        `bk_ix` INT(11) NOT NULL,
                        `rm_ix` INT(11) NOT NULL,
                        `bkr_subject` VARCHAR(255) NOT NULL,
                        `bkr_price` INT(11) NOT NULL DEFAULT '0',
                        `bkr_date` DATE NOT NULL COMMENT '일자(yyyy-mm-dd)',
                        `bkr_time` VARCHAR(5) NOT NULL COMMENT '시간(hh:mm)',
                        `bkr_cnt` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '예약수',
                        PRIMARY KEY (`bkr_ix`),
                        INDEX `rm_ix` (`rm_ix`),
                        INDEX `bk_ix` (`bk_ix`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='예약시설'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 예약옵션 생성
if(!sql_query(" DESCRIBE {$g5['wzb_booking_option_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_booking_option_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `odo_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '예약옵션키',
                        `bk_ix` INT(11) NULL DEFAULT NULL COMMENT '예약정보키',
                        `rmo_ix` INT(11) NOT NULL COMMENT '시설옵션키',
                        `odo_name` VARCHAR(100) NOT NULL COMMENT '옵션명',
                        `odo_price` INT(11) NOT NULL DEFAULT '0' COMMENT '옵션금액',
                        `odo_cnt` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '옵션수량',
                        `odo_unit` VARCHAR(20) NOT NULL COMMENT '옵션단위',
                        `odr_memo` VARCHAR(255) NOT NULL COMMENT '옵션한줄설명',
                        PRIMARY KEY (`odo_ix`),
                        INDEX `bk_ix` (`bk_ix`),
                        INDEX `rmo_ix` (`rmo_ix`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='예약옵션'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 예약정보임시 생성
if(!sql_query(" DESCRIBE {$g5['wzb_booking_data_table']} ", false)) {
        sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_booking_data_table']}` (
                        `od_id` BIGINT(20) UNSIGNED NOT NULL,
                        `mb_id` VARCHAR(20) NOT NULL DEFAULT '',
                        `dt_pg` VARCHAR(255) NOT NULL DEFAULT '',
                        `dt_data` TEXT NOT NULL,
                        `dt_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        INDEX `od_id` (`od_id`)
                )
                COMMENT='예약정보임시'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 공휴일 테이블 생성
if(!sql_query(" DESCRIBE {$g5['wzb_holiday_table']} ", false)) {
        sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_holiday_table']}` (
                        `cp_ix` INT(11) NOT NULL COMMENT '구분키',
                        `hd_ix` INT(11) NOT NULL AUTO_INCREMENT COMMENT '공휴일키',
                        `hd_subject` VARCHAR(100) NOT NULL COMMENT '특정일명',
                        `hd_date` DATE NOT NULL COMMENT '일자(yyyy-mm-dd)',
                        `hd_loop_year` TINYINT(4) NOT NULL DEFAULT '0',
                        `hd_year` CHAR(4) NOT NULL COMMENT '적용년도(yyyy)',
                        `hd_month` CHAR(2) NOT NULL COMMENT '적용월(mm)',
                        `hd_day` CHAR(2) NOT NULL COMMENT '적용일(dd)',
                        PRIMARY KEY (`hd_ix`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='공휴일'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 팝업창 테이블 생성
if(!sql_query(" DESCRIBE {$g5['wzb_corp_popup_table']} ", false)) {
        sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wzb_corp_popup_table']}` (
                        `nw_id` INT(11) NOT NULL AUTO_INCREMENT,
                        `cp_ix` INT(11) NOT NULL DEFAULT '0',
                        `nw_device` VARCHAR(10) NOT NULL DEFAULT 'both',
                        `nw_begin_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `nw_end_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `nw_disable_hours` INT(11) NOT NULL DEFAULT '0',
                        `nw_left` INT(11) NOT NULL DEFAULT '0',
                        `nw_top` INT(11) NOT NULL DEFAULT '0',
                        `nw_height` INT(11) NOT NULL DEFAULT '0',
                        `nw_width` INT(11) NOT NULL DEFAULT '0',
                        `nw_subject` TEXT NOT NULL,
                        `nw_content` TEXT NOT NULL,
                        `nw_content_html` TINYINT(4) NOT NULL DEFAULT '0',
                        `nw_division` VARCHAR(10) NOT NULL DEFAULT 'both',
                        PRIMARY KEY (`nw_id`),
                        INDEX `cp_ix` (`cp_ix`)
                )
                COMMENT='팝업창'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 문자내용컬럼 사이즈 변경
sql_query("ALTER TABLE  {$g5['wzb_config_table']} 
            CHANGE COLUMN `cps_sms1_con_user`  `cps_sms1_con_user` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms2_con_user`  `cps_sms2_con_user` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms3_con_user`  `cps_sms3_con_user` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms1_con_adm`  `cps_sms1_con_adm` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms2_con_adm`  `cps_sms2_con_adm` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms3_con_adm`  `cps_sms3_con_adm` TEXT NOT NULL,
            CHANGE COLUMN `cps_sms4_con_adm`  `cps_sms4_con_adm` TEXT NOT NULL
", true);

// wetoz : 2018-10-02 : 주간최대가능예약횟수, 예약취소가능일, 이용서비스별 예약권한
$query = "show columns from `{$g5['wzb_room_table']}` like 'rm_level' ";
$res = sql_fetch($query);
if (empty($res)) {
    sql_query(" ALTER TABLE `{$g5['wzb_room_table']}` 
                    ADD `rm_level` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '예약권한'
                    ; ", true);
    $db_reload = true;
}

if ($db_reload) { 
    alert("DB를 갱신합니다.", './wzb_config.php'); 
} 

// 예약스킨으로 등록되어 있는 게시판이 존재하는지 체크
$query = "select gr_id, bo_table, bo_subject from {$g5['board_table']} where bo_table = '".$wzpconfig['bo_table']."'";
$bd = sql_fetch($query);
if (!$bd['bo_table'] && !$_GET['bo_table_check']) { // 등록된 테이블이 없다면 공백처리. 
    alert("연결된 게시판이 존재하지 않습니다. 게시판 연결설정항목에서 연결하실 게시판을 설정해주세요.", './wzb_config.php?bo_table_check=1#board_set');
    $query = "update {$g5['wzb_config_table']} set bo_table = ''";
    sql_query($query);
} 
include_once("./admin_head.sub.php");
//include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_EDITOR_LIB);
?>

<form name="frm" id="frm" action="./reservation/wzb_config_update.php" method="post" enctype="multipart/form-data" onsubmit="return getAction(document.forms.frm);">

<h2 class="h2_frm">환경설정</h2>
<div class="btn_fixed_top" style="text-align: right;margin-bottom: 10px;margin-right:20px;">
    <input type="submit" value="수정" class="btn_submi btn btn_01" accesskey="s">
</div>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>환경설정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">예약가능최대일</th>
        <td>
            최대 <input type="text" name="pn_max_booking_expire" value="<?php echo $wzpconfig['pn_max_booking_expire']; ?>" id="pn_max_booking_expire" required class="frm_input required" size="5">
            일 까지 예약 가능.
        </td>
    </tr>
    <tr>
        <th scope="row">예약대기시간설정</th>
        <td>
            <?php echo help('입력된 시간이 경과되면 자동으로 예약대기건은 취소처리 됩니다.<br />단 예약화면에 불특정한 접속이 이루어 질 경우에만 파일이 실행되어 해당시간이 지난 예약건들을 자동 취소 처리 합니다.') ?>
            예약대기건은 <input type="text" name="pn_wating_time" value="<?php echo $wzpconfig['pn_wating_time']; ?>" id="pn_wating_time" required class="frm_input required" size="3"> 시간이 지나면 자동으로 취소처리.
        </td>
    </tr>
    <tr>
        <th scope="row">예약차단일설정</th>
        <td>
            <?php echo help('오늘이 3월5일 이고 3월8일 부터 예약이 가능하게 할경우 "3" 을 입력합니다. 사용하지 않을경우 0 을 입력하시면 당일예약으로 설정됩니다.') ?>
            예약당일 날짜기준 <input type="text" name="cp_term_day" value="<?php echo $wzdc['cp_term_day']; ?>" id="cp_term_day" required class="frm_input required numeric" size="3">일 이후 부터 예약가능.
        </td>
    </tr>
    <tr>
        <th scope="row">결제기능사용여부</th>
        <td>
            <?php echo help("예약시 결제정보를 받아야하는경우 사용으로 선택해주세요."); ?>
            <input type="radio" name="pn_is_pay" value="0" <?php echo $wzpconfig['pn_is_pay']==0?"checked":""; ?> id="pn_is_pay1">
            <label for="pn_is_pay1">사용안함 </label>
            <span id="bx_pn_result_state" style="<?php echo ($wzpconfig['pn_is_pay'] ? 'display:none;' : '');?>">
                (예약이 완료될 경우 예약정보를 
                <select name="pn_result_state" id="pn_result_state">
                    <option value="대기" <?php echo $wzpconfig['pn_result_state'] == '대기' ? 'selected=selected' : '' ; ?>>대기</option>
                    <option value="완료" <?php echo $wzpconfig['pn_result_state'] == '완료' ? 'selected=selected' : '' ; ?>>완료</option>
                </select> 처리 합니다.)
            </span>
            <input type="radio" name="pn_is_pay" value="1" <?php echo $wzpconfig['pn_is_pay']==1?"checked":""; ?> id="pn_is_pay2">
            <label for="pn_is_pay2">사용</label>
        </td>
    </tr>
    <tr>
        <th scope="row">공지</th>
        <td>
            <?php echo editor_html('pn_con_notice', get_text($wzpconfig['pn_con_notice'], 0)); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">기본예약안내</th>
        <td>
            <?php echo editor_html('pn_con_info', get_text($wzpconfig['pn_con_info'], 0)); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">이용안내</th>
        <td>
            <?php echo editor_html('pn_con_checkinout', get_text($wzpconfig['pn_con_checkinout'], 0)); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">환불규정</th>
        <td>
            <?php echo editor_html('pn_con_refund', get_text($wzpconfig['pn_con_refund'], 0)); ?>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<a name="board_set"></a>
<h2 class="h2_frm">게시판연결설정 <?php echo ($wzpconfig['bo_table'] != $bd['bo_table'] ? '(수정버튼을 클릭하셔야 정상적으로 적용이 됩니다.)' : '');?></h2>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>게시판연결설정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="gr_id">그룹<strong class="sound_only">필수</strong></label></th>
        <td>
            <?php echo get_group_select('gr_id', $bd['gr_id'], 'required'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="bo_table">게시판TABLE<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="text" name="bo_table" value="<?php echo $bd['bo_table'] ?>" id="bo_table" required class="frm_input required" maxlength="20">
            영문자, 숫자, _ 만 가능 (공백없이 20자 이내)
            <input type="hidden" name="bo_table_before" id="bo_table_before" value="<?php echo $bd['bo_table'];?>" />
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="bo_subject">게시판 제목<strong class="sound_only">필수</strong></label></th>
        <td colspan="2">
            <input type="text" name="bo_subject" value="<?php echo get_text($bd['bo_subject']) ?>" id="bo_subject" required class="required frm_input" size="80" maxlength="120">
        </td>
    </tr>
    </tbody>
    </table>
</div>

<h2 class="h2_frm">결제설정</h2>
<div class="tbl_frm01 tbl_wrap" id="pay-input-yes" style="<?php echo ($wzpconfig['pn_is_pay'] ? '' : 'display:none;');?>">
    <table>
    <caption>결제설정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">예약금설정</th>
        <td>
            결제금액의 <input type="text" name="pn_reserv_price_avg" value="<?php echo $wzpconfig['pn_reserv_price_avg']; ?>" id="pn_reserv_price_avg" required class="frm_input required" style="text-align:center;" size="5"> % 예약금.
        </td>
    </tr>
    <tr>
        <th scope="row">무통장입금사용</th>
        <td>
            <?php echo help("예약시 무통장으로 입금을 가능하게 할것인지를 설정합니다.\n사용할 경우 은행계좌번호를 반드시 입력하여 주십시오.", 50); ?>
            <select id="pn_bank_use" name="pn_bank_use">
                <option value="0" <?php echo get_selected($wzpconfig['pn_bank_use'], 0); ?>>사용안함</option>
                <option value="1" <?php echo get_selected($wzpconfig['pn_bank_use'], 1); ?>>사용</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">입금계좌정보</th>
        <td>
            <div style="margin:5px 0">엔터로 구분 등록해주세요.</div>
            <textarea name="pn_bank_info" id="pn_bank_info" style="height:60px;"><?php echo $wzpconfig['pn_bank_info']; ?></textarea>
        </td>
    </tr>

    <tr>
        <th scope="row">현장결제사용</th>
        <td>
            <?php echo help("예약시 예약금없이 당일 현장에서 바로 결제를 받습니다. 현장결제를 선택하고 예약 할 경우 바로 예약완료처리 됩니다.", 50); ?>
            <select id="pn_onstore_use" name="pn_onstore_use">
                <option value="0" <?php echo get_selected($wzpconfig['pn_onstore_use'], 0); ?>>사용안함</option>
                <option value="1" <?php echo get_selected($wzpconfig['pn_onstore_use'], 1); ?>>사용</option>
            </select>
        </td>
    </tr>
    
    <?php
    @include_once(WZB_PLUGIN_PATH.'/gender/pg.setting.1.php');
    ?>

    </tbody>
    </table>
</div>
<div id="pay-input-no" style="<?php echo ($wzpconfig['pn_is_pay'] ? 'display:none;' : '');?>">
    결제기능 사용을 원하시면 결제기능사용여부 항목에서 사용으로 선택해주세요.
</div>


<h2 class="h2_frm">문자정보 (예약자발송용)</h2>

<div class="tbl_frm01 tbl_wrap">

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약대기 (예약자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms1_use_user" id="cps_sms1_use_user" value="1" <?php echo $wzpconfig['cps_sms1_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms1_con_user" name="cps_sms1_con_user" wrap="virtual" onkeyup="byte_check('cps_sms1_con_user', 'byte1', 'byte1_max');" class="sms-con"><?php echo $wzpconfig['cps_sms1_con_user'];?></textarea>
            </p>		
            <p class="important">
                <span id="byte1">0</span> / <span id="byte1_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약완료 (예약자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms2_use_user" id="cps_sms2_use_user" value="1" <?php echo $wzpconfig['cps_sms2_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms2_con_user" name="cps_sms2_con_user" wrap="virtual" onkeyup="byte_check('cps_sms2_con_user', 'byte2', 'byte2_max');" class="sms-con"><?php echo $wzpconfig['cps_sms2_con_user'];?></textarea>
            </p>	
            <p class="important">
                <span id="byte2">0</span> / <span id="byte2_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약취소 (예약자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms3_use_user" id="cps_sms3_use_user" value="1" <?php echo $wzpconfig['cps_sms3_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms3_con_user" name="cps_sms3_con_user" wrap="virtual" onkeyup="byte_check('cps_sms3_con_user', 'byte3', 'byte3_max');" class="sms-con"><?php echo $wzpconfig['cps_sms3_con_user'];?></textarea>
            </p>	
            <p class="important">
                <span id="byte3">0</span> / <span id="byte3_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

</div>

<hr class="section-division"></hr>

<h2 class="h2_frm">문자정보 (관리자발송용)</h2>

<div class="tbl_frm01 tbl_wrap">

    <div style="margin:5px 0">
        <table cellpadding=0 cellspacing=0 border=0 style="width:476px;">
            <caption></caption>
            <tbody>
            <tr>
                <th width="30%" scope="col">관리자 수신번호</th>
                <td width="70%">
                    <?php echo help('여러개의 번호일경우 컴마 , 단위로 입력해주세요. <br />(예: 0102222222,0103333333,0104444444)');?>
                    <input type="text" name="cps_sms_receive" id="cps_sms_receive" value="<?php echo $wzpconfig['cps_sms_receive'];?>" class="frm_input" style="width:100%;" maxlength="170" />
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약대기 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms1_use_adm" id="cps_sms1_use_adm" value="1" <?php echo $wzpconfig['cps_sms1_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms1_con_adm" name="cps_sms1_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms1_con_adm', 'byte4', 'byte4_max');" class="sms-con"><?php echo $wzpconfig['cps_sms1_con_adm'];?></textarea>
            </p>		
            <p class="important">
                <span id="byte4">0</span> / <span id="byte4_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약완료 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms2_use_adm" id="cps_sms2_use_adm" value="1" <?php echo $wzpconfig['cps_sms2_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms2_con_adm" name="cps_sms2_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms2_con_adm', 'byte5', 'byte5_max');" class="sms-con"><?php echo $wzpconfig['cps_sms2_con_adm'];?></textarea>
            </p>	
            <p class="important">
                <span id="byte5">0</span> / <span id="byte5_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">			
            <p>예약취소 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms3_use_adm" id="cps_sms3_use_adm" value="1" <?php echo $wzpconfig['cps_sms3_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>	
        </div>	
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms3_con_adm" name="cps_sms3_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms3_con_adm', 'byte6', 'byte6_max');" class="sms-con"><?php echo $wzpconfig['cps_sms3_con_adm'];?></textarea>
            </p>
            <p class="important">
                <span id="byte6">0</span> / <span id="byte6_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <hr class="section-division"></hr>

    <div class="helpguide">
        <div class="help_section"> 
        <table cellspacing="0" border="1" summary="안내 도움말 영역"> 
        <caption>안내 도움말 영역 표</caption>
        <colgroup> 
            <col style="width:88px"> 
            <col> 
            <col style="width:88px">
            <col> 
        </colgroup> 
        <tbody>
        <tr>
            <th scope="row"><div class="inner"><p class="h_tx h_tx1">안내</p></div></th>
            <td colspan="3">
                <div class="inner"> 
                    <ul class="faq_lst">
                        <li>문자내용에 예약자명 발송을 원하시면 내용에 {예약자명} 을 입력해주세요.</li>
                        <li>문자내용에 예약정보 발송을 원하시면 내용에 {예약정보} 을 입력해주세요. (문자발송유형이 LMS 일경우에만 발송이 됩니다.)</li>
                        <li>문자내용에 예약금 발송을 원하시면 내용에 {예약금} 을 입력해주세요.</li>
                        <li>사용에 체크되어 있지 않으면 발송되지 않습니다.</li>
                    </ul> 
                </div>
            </td> 
        </tr>
        </tbody>
        </table>
        </div>
    </div>

</div>

</form>
<script type="text/javascript">
<!--
    function getAction(f) {

        <?php echo get_editor_js('pn_con_notice'); ?>
        <?php echo get_editor_js('pn_con_info'); ?>
        <?php echo get_editor_js('pn_con_checkinout'); ?>
        <?php echo get_editor_js('pn_con_refund'); ?>

        if ((f.cps_sms1_use_adm.checked || f.cps_sms2_use_adm.checked || f.cps_sms2_use_adm.checked) && !f.cps_sms_receive.value)
        {
            alert("관리지 수신번호가 입력되어야 합니다.");
            f.cps_sms_receive.focus();
            return false;
        }

        return true;
    }

    function byte_check(wr_message, sms_bytes, sms_max_bytes)
    {
        var conts = document.getElementById(wr_message);
        var bytes = document.getElementById(sms_bytes);
        var max_bytes = document.getElementById(sms_max_bytes);

        var i = 0;
        var cnt = 0;
        var exceed = 0;
        var ch = '';

        for (i=0; i<conts.value.length; i++)
        {
            ch = conts.value.charAt(i);
            if (escape(ch).length > 4) {
                cnt += 2;
            } else {
                cnt += 1;
            }
        }

        bytes.innerHTML = cnt;

        <?php if($config['cf_sms_type'] == 'LMS') { ?>
        if(cnt > 90)
            max_bytes.innerHTML = 1500;
        else
            max_bytes.innerHTML = 90;

        if (cnt > 1500)
        {
            exceed = cnt - 1500;
            alert('메시지 내용은 1500바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
            var tcnt = 0;
            var xcnt = 0;
            var tmp = conts.value;
            for (i=0; i<tmp.length; i++)
            {
                ch = tmp.charAt(i);
                if (escape(ch).length > 4) {
                    tcnt += 2;
                } else {
                    tcnt += 1;
                }

                if (tcnt > 1500) {
                    tmp = tmp.substring(0,i);
                    break;
                } else {
                    xcnt = tcnt;
                }
            }
            conts.value = tmp;
            bytes.innerHTML = xcnt;
            return;
        }
        <?php } else { ?>
        if (cnt > 80)
        {
            exceed = cnt - 80;
            alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
            var tcnt = 0;
            var xcnt = 0;
            var tmp = conts.value;
            for (i=0; i<tmp.length; i++)
            {
                ch = tmp.charAt(i);
                if (escape(ch).length > 4) {
                    tcnt += 2;
                } else {
                    tcnt += 1;
                }

                if (tcnt > 80) {
                    tmp = tmp.substring(0,i);
                    break;
                } else {
                    xcnt = tcnt;
                }
            }
            conts.value = tmp;
            bytes.innerHTML = xcnt;
            return;
        }
        <?php } ?>
    }

    byte_check('cps_sms1_con_user', 'byte1', 'byte1_max');
    byte_check('cps_sms2_con_user', 'byte2', 'byte2_max');
    byte_check('cps_sms3_con_user', 'byte3', 'byte3_max');
    byte_check('cps_sms1_con_adm', 'byte4', 'byte4_max');
    byte_check('cps_sms2_con_adm', 'byte5', 'byte5_max');
    byte_check('cps_sms3_con_adm', 'byte6', 'byte6_max');

    jQuery(document).ready(function () {
        $(document).on("click", "input[name=pn_is_pay]", function() {
            var pn_is_pay = $(":input:radio[name=pn_is_pay]:checked").val();
            if (pn_is_pay == '0') {
                $('#pay-input-yes').hide();
                $('#pay-input-no').show();
                $('#bx_pn_result_state').show();
            }
            else {
                $('#pay-input-yes').show();
                $('#pay-input-no').hide();
                $('#bx_pn_result_state').hide();
            }
        });
    });


//-->
</script>


<?php
include_once("./admin_tail.sub.php");
//include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
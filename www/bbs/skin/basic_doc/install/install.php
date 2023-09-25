<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//지출결의서 - 상세내역 테이블
@sql_query(
	" CREATE TABLE IF NOT EXISTS `{$write_table}_sub` (
	`id_no` int(11) NOT NULL AUTO_INCREMENT,
	`wr_id` int(11) NOT NULL,
	`mb_id` varchar(20) NOT NULL,
	`doc_sub` varchar(255) NOT NULL,
	`doc_standard` varchar(50) NOT NULL,
	`doc_cnt` int(11) NOT NULL,
	`doc_unit` int(11) NOT NULL,
	`doc_cost` int(11) NOT NULL,
	`doc_etc` varchar(255) NOT NULL,
	PRIMARY KEY (`id_no`),
	KEY `wr_id` (`wr_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8", FALSE
);

// 직원테이블
@sql_query(
	" CREATE TABLE IF NOT EXISTS `{$write_table}_member` (
	`id_no` int(11) NOT NULL AUTO_INCREMENT,
	`mb_section` int(1) NOT NULL,
	`mb_kind` varchar(50) NOT NULL,
	`mb_position` varchar(20) NOT NULL,
	`mb_birth` date NOT NULL,
	`mb_id` varchar(50) NOT NULL,
	`mb_name` varchar(50) NOT NULL,
	`mb_hp` varchar(20) NOT NULL,
	`mb_tel` varchar(20) NOT NULL,
	`mb_fax` varchar(20) NOT NULL,
	`mb_email` varchar(50) NOT NULL,
	`mb_addr1` varchar(255) NOT NULL,
	`mb_addr2` varchar(255) NOT NULL,
	`mb_addr3` varchar(255) NOT NULL,
	`mb_zip` varchar(5) NOT NULL,
	`car_no` varchar(20) NOT NULL,
	`car_name` varchar(50) NOT NULL,
	`car_holiday` varchar(20) NOT NULL,
	`mb_area` int(11) NOT NULL,
	`join_date` date NOT NULL,
	`retire_date` date NOT NULL,
	`mb_status` varchar(10) NOT NULL,
	`mb_content` text NOT NULL,
	PRIMARY KEY (`id_no`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8", FALSE
);

// 문서 결재 로그
@sql_query(
	"CREATE TABLE `{$write_table}_log` (
	`id_no` int(11) NOT NULL AUTO_INCREMENT,
	`wr_id` int(11) NOT NULL,
	`mb_id` varchar(20) NOT NULL,
	`current` tinyint(1) NOT NULL,
	`memo` text NOT NULL,
	`datetime` datetime NOT NULL,
	PRIMARY KEY (`id_no`),
	KEY `wr_id` (`wr_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8", FALSE
);

// 개인별 결재선 관리
@sql_query(
	"CREATE TABLE `{$write_table}_line` (
	`id_no` int(11) NOT NULL AUTO_INCREMENT,
	`mb_id` varchar(20) NOT NULL,
  	`approval` varchar(255) NOT NULL,
  	PRIMARY KEY (`id_no`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8", FALSE
);



// 게시판 여분필드 확장 (wr_11 ~ wr_15)
@sql_query(
	"ALTER TABLE `{$write_table}` 
	ADD `wr_11` TEXT NOT NULL AFTER `wr_10`, 
	ADD `wr_12` TEXT NOT NULL AFTER `wr_11`, 
	ADD `wr_13` TEXT NOT NULL AFTER `wr_12`, 
	ADD `wr_14` TEXT NOT NULL AFTER `wr_13`, 
	ADD `wr_15` TEXT NOT NULL AFTER `wr_14` ");

// 게시판 기본환경설정 값 변경
@sql_query(
	"update
		{$g5['board_table']}
	set 
		bo_category_list = '지출결의서1|지출결의서2|지출결의서3|기안서|품의서',
		bo_1 = 'doc_01|doc_02|doc_03|doc_04|doc_05'
	where
		bo_table = '{$bo_table}' ");
goto_url("?bo_table={$bo_table}");
?>
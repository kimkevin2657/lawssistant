select * from shop_member; 
select * from shop_config;

select * from shop_member_grade;

SELECT @@GLOBAL.sql_mode;
-- STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
SET @@GLOBAL.sql_mode = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';


DROP TABLE shop_partner_matching;
CREATE TABLE shop_partner_matching
(
	index_no int not null auto_increment primary key comment '일련번호',
	pt_id varchar(100) not null comment '후원인ID',
	mb_id varchar(100) not null comment '회원ID',
	reg_price int not null comment '금액',
	grade int not null default 0 comment '가맹등급',
	match_id varchar(100) null comment '매칭수당ID',
	line_id varchar(200) null comment '매칭수당ID',
	reg_dt datetime not null default curtime() comment '등록일시',
	upd_dt datetime not null default curtime() comment '수정일시'
) engine=InnoDB;

-- create table shop_partner_match
-- (
-- 	match_id varchar(100) not null comment '매칭ID',
-- 	mb_id varchar(20) not null comment '회원ID',
-- 	pt_id varchar(20) not null comment '후원ID',
-- 	reg_dt datetime not null default '0000-00-00 00:00:00' comment '등록일시',
-- 	upt_dt datetime not null default '0000-00-00 00:00:00' comment '수정일시',
-- 	KEY(match_id, mb_id)
-- 	) Engine=InnoDB comment '매칭관리';

-- match_id : mb_id+mb_id
-- line_id  : match_id:match_id
-- line_

alter table shop_goods
add column buy_partner_grade tinyint not null default 0 comment '가맹상품' after buy_only;

alter table shop_order
    add buy_partner_grade int not null default 0 comment '구매가맹등급' after shop_id,
    add buy_partner_type enum ('upgrade', 'anew', 'none') not null default 'none' after buy_partner_grade;

alter table shop_order
    add buy_partner_type enum ('upgrade', 'anew', 'none') not null default 'none' after buy_partner_grade;

create table shop_partner_lineing
(
	pt_id varchar(20) not null comment '후원인ID',
	mb_id varchar(20) not null comment '회원ID',
	match_id varchar(100) not null comment '매칭ID',
	line_id varchar(200) null comment '라인ID(mb_id>match_id:mb_id>match_id)',
	reg_dt datetime not null default '0000-00-00 00:00:00' comment '등록일시',
	upt_dt datetime not null default '0000-00-00 00:00:00' comment '수정일시',
	KEY(mb_id, match_id)
	) Engine=InnoDB comment '라인로그';

create table shop_partner_line
(
	line_id varchar(200) not null comment '라인ID(line_id:line_id)',
	mb_id varchar(20) not null comment '회원ID',
	reg_dt datetime not null default '0000-00-00 00:00:00',
	upd_dt datetime not null default '0000-00-00 00:00:00',
	KEY(line_id, mb_id)
	) engine=InnoDB comment '라인관리';
ALTER TABLE shop_config
ADD COLUMN `pf_anew_match_use` tinyint(4) NOT NULL DEFAULT 0 COMMENT '매칭수당 사용여부',
ADD COLUMN `pf_anew_match_per` tinyint(4) NOT NULL DEFAULT 2 COMMENT '매칭수당 지급 건당',
ADD COLUMN `pf_anew_match_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '매칭수당 지급유형',
ADD COLUMN `pf_anew_match_pay` int(11) NOT NULL DEFAULT 20000 COMMENT '매칭수당 지급비율/금액';

ALTER TABLE shop_config
ADD COLUMN `pf_anew_match_pay_2` int(11) NOT NULL default 0 COMMENT '2레벨 매칭수당' AFTER `pf_anew_match_pay`,
ADD COLUMN `pf_anew_match_pay_3` int(11) NOT NULL default 0 COMMENT '3레벨 매칭수당' AFTER `pf_anew_match_pay_2`,
ADD COLUMN `pf_anew_match_pay_4` int(11) NOT NULL default 0 COMMENT '4레벨 매칭수당' AFTER `pf_anew_match_pay_3`,
ADD COLUMN `pf_anew_match_pay_5` int(11) NOT NULL default 0 COMMENT '5레벨 매칭수당' AFTER `pf_anew_match_pay_4`,
ADD COLUMN `pf_anew_match_pay_6` int(11) NOT NULL default 0 COMMENT '6레벨 매칭수당' AFTER `pf_anew_match_pay_5`
;

ALTER TABLE shop_config
DROP COLUMN `pf_anew_match_pay_2`,
DROP COLUMN `pf_anew_match_pay_3`,
DROP COLUMN `pf_anew_match_pay_4`,
DROP COLUMN `pf_anew_match_pay_5`,
DROP COLUMN `pf_anew_match_pay_6`
;

ALTER TABLE shop_config
DROP COLUMN `pf_anew_match_use1`,
DROP COLUMN `pf_anew_match_per1`,
DROP COLUMN `pf_anew_match_type1`,
DROP COLUMN `pf_anew_match_pay1`;

ALTER TABLE shop_config
ADD COLUMN `pf_sale_benefit_9` varchar(255) NOT NULL DEFAULT '' COMMENT '9레벨 판매수수료' AFTER `pf_sale_benefit_6`,
ADD COLUMN `pf_anew_benefit_9` varchar(255) NOT NULL DEFAULT '' COMMENT '9레벨 후원수수료' AFTER `pf_anew_benefit_6`,
ADD COLUMN `pf_sale_benefit_8` varchar(255) NOT NULL DEFAULT '' COMMENT '8레벨 판매수수료' AFTER `pf_sale_benefit_6`,
ADD COLUMN `pf_anew_benefit_8` varchar(255) NOT NULL DEFAULT '' COMMENT '8레벨 후원수수료' AFTER `pf_anew_benefit_6`,
ADD COLUMN `pf_sale_benefit_7` varchar(255) NOT NULL DEFAULT '' COMMENT '7레벨 판매수수료' AFTER `pf_sale_benefit_6`,
ADD COLUMN `pf_anew_benefit_7` varchar(255) NOT NULL DEFAULT '' COMMENT '7레벨 후원수수료' AFTER `pf_anew_benefit_6`
;

ALTER TABLE shop_config
DROP COLUMN `pf_sale_benefit_9`,
DROP COLUMN `pf_anew_benefit_9`,
DROP COLUMN `pf_sale_benefit_8`,
DROP COLUMN `pf_anew_benefit_8`,
DROP COLUMN `pf_sale_benefit_7`,
DROP COLUMN `pf_anew_benefit_7`
;

begin;
insert into shop_partner_matching(pt_id, mb_id, reg_price) values('a11111111', 'hsi3461', '380000');
select * from shop_partner_matching;
rollback;
select * from shop_member;

-- 후원ID(pt_id) 외 라인상위ID(up_id), 그룹ID(본인원ID)(grp_id)
-- 후원ID, 라인상위ID는 동일한 grade여야 한다. ( 최초는 admin )
ALTER TABLE shop_member
	ADD COLUMN `up_id` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '라인상위ID' AFTER pt_id,
	ADD COLUMN `grp_id` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '그룹ID(정산ID)' AFTER up_id
;

ALTER TABLE shop_member
	ADD COLUMN od_id VARCHAR(20) NOT NULL DEFAULT '' COMMENT '가입주문ID'  AFTER up_id;

ALTER TABLE shop_member DROP COLUMN ord_id;

ALTER TABLE shop_member
	ADD COLUMN `jumin6` VARCHAR(255) NOT NULL default '' COMMENT '주민번호6' AFTER point,
	ADD COLUMN `jumin7` VARCHAR(255) NOT NULL default '' COMMENT '주민번호7' AFTER jumin6,
  ADD COLUMN `marketing_yn` TINYINT NOT NULL DEFAULT 0 COMMENT '마케팅활용동의' AFTER jumin7
;

ALTER TABLE shop_member
DROP COLUMN grp_id,
DROP COLUMN up_id;

-- 매칭수, 직라인수(block)
ALTER TABLE shop_member
	ADD COLUMN match_cnt INT NOT NULL COMMENT '매칭수' AFTER point,
	ADD COLUMN line_cnt INT NOT NULL DEFAULT 0 COMMENT '라인수' AFTER match_cnt,
	ADD COLUMN total_line_cnt INT NOT NULL DEFAULT 0 COMMENT '하위라인수' AFTER line_cnt,
	ADD COLUMN job_title VARCHAR(20) NOT NULL DEFAULT '' COMMENT '직급' AFTER total_line_cnt
;

-- 라인 점수 대상 구하기
SELECT *
FROM (
			 SELECT a.match_id
					 ,
							a.line_id
					 ,
							count(1) cnt
					 ,
							case
								when b.pt_id = @pt_id and a.pt_id = @mb_id then @rownum := @rownum + 1
								else
									@rownum := 1
								end    rnum
					 ,
							case
								when b.pt_id <> @pt_id then
									@pt_id := b.pt_id
								else b.pt_id
								end    pt_id
					 ,
							case
								when a.pt_id <> @mb_id then
									@mb_id := a.pt_id
								else a.pt_id
								end    mb_id
			 FROM shop_partner_matching a,
						shop_member b,
						(
							select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
						) c
			 WHERE a.pt_id = b.id
				 AND a.match_id is not null
				 AND b.pt_id = '01035891744'
			 GROUP BY b.pt_id, a.pt_id, a.match_id
			 ORDER BY b.pt_id, a.pt_id, case when a.line_id is null then 1 else 0 end, a.line_id
		 ) A
WHERE rnum = 1
	and line_id is null
	limit 0, 1
;


create table shop_partner_line_point
(
	line_id  varchar(255) not null COMMENT '라인ID',
	mb_id    varchar(20) not null COMMENT '회원ID',
	point    int          not null COMMENT '쇼핑포인트',
	memo     varchar(255) not null COMMENT '메모',
	reg_date datetime not null default '0000-00-00 00:00:00' COMMENT '등록일시',
	key( line_id, mb_id )
	) engine = InnoDB COMMENT '라인점수로그';


ALTER TABLE shop_member_grade
	ADD COLUMN gb_line_depth TINYINT NOT NULL DEFAULT 0 COMMENT '라인UP단계' AFTER gb_no;

ALTER TABLE shop_member
DROP COLUMN match_cnt,
DROP COLUMN line_cnt,
DROP COLUMN total_line_cnt;

SELECT * FROM shop_partner;
ALTER TABLE shop_member
DROP COLUMN grp_id,
DROP COLUMN up_id;

-- CREATE TABLE shop_partner_block
-- (
--
-- ) Engine=InnoDB COMMENT '라인구성';

SELECT * FROM shop_member;
SELECT * FROM shop_partner;
SELECT * FROM shop_order;
SELECT * FROM shop_cart;

ALTER TABLE shop_order
ADD COLUMN up_id varchar(20) NULL COMMENT '추천ID' AFTER pt_id;


ALTER TABLE shop_cart
ADD COLUMN pt_id varchar(20) NULL COMMENT '후원ID' AFTER mb_id,
ADD COLUMN up_id varchar(20) NULL COMMENT '추천ID' AFTER pt_id;

create table shop_partner_line_point
(
	line_id  varchar(255) not null COMMENT '라인ID',
	mb_id    varchar(20) not null COMMENT '회원ID',
	point    int          not null COMMENT '쇼핑포인트',
	memo     varchar(255) not null COMMENT '메모',
	reg_date datetime not null default '0000-00-00 00:00:00' COMMENT '등록일시',
	key( line_id, mb_id )
	) engine = InnoDB COMMENT '라인점수로그';

select DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), '%Y-%m-%d'), DATE_FORMAT(DATE_ADD(now(), INTERVAL -1 DAY), '%Y-%m-%d');
select a.*
                                   from shop_member a
                                  inner join shop_partner b
                                     on a.id = b.mb_id
                                    and (a.term_date is null or a.term_date > now() )
                                    and a.grade between 2 and 6
                                    and a.match_cnt > 0
                                    and DATE_FORMAT(a.anew_date,'%Y-%m-%d') between DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), '%Y-%m-%d') AND DATE_FORMAT(DATE_ADD(now(), INTERVAL -1 DAY), '%Y-%m-%d')
                                  order by a.index_no desc;

SELECT * FROM shop_partner_bonus_title;
DROP TABLE shop_partner_bonus_title;
CREATE TABLE shop_partner_bonus_title
(
  job_no int not null auto_increment unique key comment '일련번호',
  job_title VARCHAR(100) NOT NULL PRIMARY KEY COMMENT '직급',
  benefit FLOAT NOT NULL DEFAULT 0 COMMENT '수익분배율',
  benefit_type VARCHAR(20) NOT NULL DEFAULT 'anew' COMMENT '수익분배유형'
) Engine=InnoDB COMMENT '직급';
INSERT INTO shop_partner_bonus_title(job_no, job_title, benefit, benefit_type)
VALUES (1, '-', 0, 'anew'),
       (2,'팀장(대리점)', 0, 'anew'),
       (3,'과장(특약점)', 3, 'anew'),
       (4,'부장(지사)', 2, 'anew'),
       (5,'이사(총판)', 1, 'anew'),
       (6,'TOP(지점장)', 5, 'sale');

SELECT * FROM shop_partner_bonus_title;
DROP TABLE shop_partner_bonus;
CREATE TABLE shop_partner_bonus
(
	up_point INT NOT NULL PRIMARY KEY COMMENT '달성점수',
  bonus_pay   INT NOT NULL COMMENT '보너스',
  job_no int not null comment '직급NO',
  benefits VARCHAR(255) NULL COMMENT '회사지원',
  remark VARCHAR(255) NULL COMMENT '수익보전PLAN',
  bonus_pb INT NOT NULL DEFAULT 0 COMMENT 'PB상품후원권',
  stay_monthly_rate INT NOT NULL DEFAULT 15 COMMENT '유지실적',
  stay_shopping_balance INT NOT NULL DEFAULT 0 COMMENT '유지매출',
  use_yn ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT '사용어부',
	vi_cnt INT NOT NULL DEFAULT  0 COMMENT '누적달성수'
) engine=InnoDB COMMENT '보상플랜1';
INSERT INTO shop_partner_bonus (up_point, bonus_pay, job_no, benefits, remark, bonus_pb, stay_monthly_rate, stay_shopping_balance, use_yn, vi_cnt)
VALUES (64   ,    50000, 1, '', '', 0,15,0,'Y', 0),
       (128  ,   200000, 2, '', '', 0,15,0,'Y', 0),
       (200  ,   500000, 3, '', '', 0,15,0,'Y', 0),
       (500  ,  1000000, 3, '', '', 0,15,0,'Y', 0),
       (1000 ,  1200000, 4, '', '', 0,15,0,'Y', 0),
       (3000 ,  1500000, 4, '', '', 0,15,0,'Y', 0),
       (6000 ,  3000000, 4, '', '', 0,15,0,'Y', 0),
       (10000, 10000000, 5, '', '', 0,15,0,'Y', 0),
       (14000, 15000000, 5, '', '', 0,15,0,'Y', 0),
       (20000, 20000000, 6, '', '', 0,15,0,'Y', 0);
      select * from shop_partner_bonus;
SELECT SUM(bonus_pay) FROM shop_partner_bonus;
SELECT * FROM shop_partner_bonus;
UPDATE shop_partner_bonus SET bonus_pb = 0 where 1;
select IFNULL(sum(line_point),0) total from shop_member where use_app = 1 and job_title= '과장(특약점)'; --and (term_date is null or term_date > now()) ;
CREATE TABLE shop_partner_bonus_history
(
  mb_id varchar(60) not null comment '회원ID',
  up_point int not null comment '달성점수',
  bonus_at datetime not null comment '달성일시',
  primary key(mb_id, up_point)
) Engine=InnoDB COMMENT '보상히스토리';
CREATE TABLE shop_partner_bonus_share
(
  share_date date not null comment '지급일자',
  share_at datetime not null comment '지급일시',
  primary key(share_date)
) engine=InnoDB comment '수익공유';

SELECT * FROM shop_partner_rollup;
SELECT * FROM shop_partner_bonus;
SELECT * FROM shop_partner_bonus;
-- select * from shop_partner_block;
select * from shop_member where line_point > 0 ;
INSERT INTO shop_partner_bonus
(up_point, bonus_pay, job_title, benefits, remark, bonus_pb, stay_monthly_rate, stay_shopping_balance, use_yn, vi_cnt)
VALUES
(300 ,  1000000, '팀장'     , '', '', 0,15,0,'Y', 0),
(500 ,  5000000, '부장'     , '', '', 0,15,0,'Y', 0),
(1000, 10000000, '본부장'    , '공동 사무실 및 전산지원\n[ 회의실 및 개별 책상 구성 ]', '', 0,15,0,'Y', 0),
(2000, 20000000, '그룹장지점장', '공동 사무실 및 전산지원\n[ 회의실 및 개별 책상 구성 ]', '1PB 상품후원권', 1,15,0,'Y', 0),
(3000, 30000000, '지사장'    , '공동 사무실 및 전산지원\n[ 회의실 및 개별 책상 구성 ]', '2PB 상품후원권', 2,15,0,'Y', 0),
(4000, 40000000, '총괄본부장' , '10평 개인사무실', '3PB 상품후원권', 3,15,0,'Y', 0),
(5000, 50000000, '사장'     , '20평 개별 사무실 지원', '프랜차이즈 독립', 0,15,0,'Y', 0);

SELECT receipt_price FROm shop_partner;
select count(1), sum(line_point) total from shop_member where use_app = 1 and (term_date is null or term_date > now()) and job_title= 'TOP';
select IFNULL(sum(line_point),0) total from shop_member where use_app = 1 and (term_date is null or term_date > now()) and job_title= 'TOP';
select sum(line_point) total from shop_member where use_app = 1 and (term_date is null or term_date > now()) and job_title= 'TOP';
CREATE TABLE shop_partner_yearly_bonus
(
	up_point INT NOT NULL PRIMARY KEY COMMENT '달성점수',
	bonus_plan VARCHAR(255) NULL COMMENT '여행플랜',
	use_yn ENUM('Y', 'N') NOT NULL DEFAULT 'Y' COMMENT '사용여부'
) Engine=InnoDB COMMENT '보상플랜2';

INSERT INTO shop_partner_yearly_bonus
(up_point, bonus_plan, use_yn)
VALUES
(500, '일본여행 중국여행', 'Y'),
(1000, '동남아여행', 'Y'),
(2000, '러시아여행', 'Y'),
(3000, '호주여행', 'Y'),
(4000, '유럽여행', 'Y'),
(5000, '미국여행', 'Y');

-- type no 와 grade 가 상이 함 주의
-- type 4 = grade 5
-- type 5 = grade 4
UPDATE shop_goods
SET buy_partner_grade = 5,
		goods_price = 150000
WHERE index_no in ( select gs_id from shop_goods_type where it_type4 = 1 );

UPDATE shop_goods
SET buy_partner_grade = 4,
		goods_price = 400000
WHERE index_no in ( select gs_id from shop_goods_type where it_type5 = 1 );

UPDATE shop_member SET up_id = pt_id WHERE (up_id = '' or up_id is null) and pt_id <> '' and pt_id is not null;
-- 쇼핑 공급사
UPDATE shop_member SET grade = 7 WHERE id = 'shopping';

select * from shop_partner_matching where line_id = '01035891768≫01035891770∥01035891771§01035891769≫01035891772∥01035891773';
use ianart_k2k9;

select * from shop_partner_matching;
select * from shop_partner_line;
select * from shop_partner;
SELECT a.pt_id, c.gb_line_point gb_line_point, IFNULL(e.gb_line_point, 0) gb_line_point_top FROM shop_partner_matching a INNER JOIN shop_member b ON a.pt_id = b.id INNER JOIN shop_member_grade c ON b.grade = c.gb_no LEFT JOIN shop_member d ON b.pt_id = d.id LEFT jOIN shop_member_grade e ON d.grade = e.gb_no WHERE a.line_id = '01035891768≫01035891770∥01035891771§01035891769≫01035891772∥01035891773' GROUP BY a.pt_id, c.gb_line_point, e.gb_line_point;
--
-- create table shop_partner_match
-- (
-- 	match_id varchar(100) not null comment '매칭ID',
-- 	mb_id varchar(20) not null comment '회원ID',
-- 	pt_id varchar(20) not null comment '후원ID',
-- 	reg_dt datetime not null default '0000-00-00 00:00:00' comment '등록일시',
-- 	upt_dt datetime not null default '0000-00-00 00:00:00' comment '수정일시',
-- 	KEY(match_id, mb_id)
-- 	) Engine=InnoDB comment '매칭관리';
--

CREATE TABLE shop_partner_matching
(
	index_no int not null auto_increment primary key comment '일련번호',
	pt_id varchar(100) not null comment '후원인ID',
	mb_id varchar(100) not null comment '회원ID',
	reg_price int not null comment '금액',
	grade int not null comment '가맹등급',
	match_id varchar(100) null comment '매칭ID',
	line_id  varchar(200) null comment '라인ID',
	reg_dt datetime not null default curtime() comment '등록일시',
	upd_dt datetime not null default curtime() comment '수정일시',
	key(pt_id, mb_id)
	) engine=InnoDB comment '매칭로그';

ALTER TABLE shop_partner_matching
	-- CHANGE COLUMN pay_id match_id varchar(100) not null comment '매칭ID';
	ADD COLUMN grade int not null default 0 comment '가맹등급' after reg_price;
ADD COLUMN line_id varchar(100) null comment '라인ID' after match_id;
select state from shop_partner where mb_id = '01035891745';
select * from shop_member;
UPDATE shop_partner
SET mb_id = replace(mb_id, '-', '')
WHERE mb_id like '%-%';
SELECT * FROM shop_partner;
SELECT * FROM shop_partner_matching;
update `shop_partner_matching` set match_id = null where match_id = '';
select * from shop_partner_matching where match_id is null;
select * from shop_partner_matching where length(trim(match_id)) = 0;
select * from shop_partner_matching where length(trim(line_id)) = 0;
UPDATE shop_partner
SET state = 0
WHERE state = 1
	AND index_no > 11;
UPDATE shop_partner_matching
SET pt_id = '01035891740'
WHERE pt_id = 'a0000';
UPDATE shop_partner_matching
SET mb_id = '01035891740'
WHERE mb_id = 'a0000';
UPDATE shop_partner_matching
SET mb_id = '01035891741'
WHERE mb_id = 'a0001';
UPDATE shop_partner_matching
SET mb_id = '01035891742'
WHERE mb_id = 'a0002';
UPDATE shop_partner_matching
SET mb_id = '01035891743'
WHERE mb_id = 'a0005';
UPDATE shop_partner
SET receipt_price = 150000
WHERE receipt_price = 0;

-- 이건 아니다!
select 1 depth, pt_id, count(distinct pay_id)
from
	shop_partner_matching
where pay_id <> '' group by pt_id
union all
select 2 depth, pt_id, count(distinct pay_id)
from
	shop_partner_matching
where pay_id <> ''
	and pt_id in ( select pt_id from shop_member )
group by pt_id
union all
select 3 depth, pt_id, count(distinct pay_id)
from
	shop_partner_matching
where pay_id <> ''
	and pt_id in ( select pt_id from shop_member
								 where id in (select pt_id from shop_member))
group by pt_id
union all
select 4 depth, pt_id, count(distinct pay_id)
from
	shop_partner_matching
where pay_id <> ''
	and pt_id in ( select pt_id from shop_member
								 where id in (select pt_id from shop_member
															where id in (select pt_id from shop_member))
)
group by pt_id
union all
select 5 depth, pt_id, count(distinct pay_id)
from
	shop_partner_matching
where pay_id <> ''
	and pt_id in ( select pt_id from shop_member
								 where id in (select pt_id from shop_member
															where id in (select pt_id from shop_member
																					 where id in (select pt_id from shop_member)))
)
group by pt_id
;

select * from shop_member;
SELECT * FROM shop_order;
select * from shop_order_data;

update shop_order
set mb_id = '01035891740',
		pt_id = '01035891740'
where mb_id = 'a0000';

select * from shop_partner;
select * from shop_cart;

UPDATE shop_cart
SET mb_id = '01035891740'
WHERE mb_id = 'a0000';



select * from shop_cart;
UPDATE shop_cart
SET mb_id = replace(mb_id, '-', '')
WHERE mb_id like '%-%';

SELECT * FROM shop_goods;

SELECT od_id FROM shop_member WHERE od_id = '18112922123009' and '01035891745';
SELECT od_id FROM shop_member WHERE od_id = '18112922123009' and '01035891745';
SELECT * FROM shop_member;
SELECT * FROM shop_partner;
SELECT MAX(id) max_id FROM shop_member WHERE id LIKE '01035891745%';

UPDATE shop_member a INNER JOIN (
	select a.id, min(b.od_id) min_od_id, max(b.od_id) max_od_id from shop_member a, shop_order b where a.id = b.mb_id group by a.id
	) B
ON a.id = b.id
SET a.od_id = b.min_od_id;

select grade, min(id) top_id from shop_member where id like '0103589175%' group by grade;

SELECT min(id), grade FROM shop_member WHERE id LIKE '01035891745%' AND grade IN (4,5) AND use_app <> 0 GROUP BY grade;


-- match_id : mb_id+mb_id
-- line_id  : match_id:match_id

select * from shop_goods;

alter table shop_goods
	add column buy_partner_grade tinyint not null default 0 comment '가맹상품' after buy_only;
select * from shop_member_grade;
-- create table shop_partner_lineing
-- (
-- 	pt_id varchar(20) not null comment '후원인ID',
-- 	mb_id varchar(20) not null comment '회원ID',
-- 	match_id varchar(100) not null comment '매칭ID',
-- 	line_id varchar(200) null comment '라인ID(mb_id>match_id:mb_id>match_id)',
-- 	reg_dt datetime not null default '0000-00-00 00:00:00' comment '등록일시',
-- 	upt_dt datetime not null default '0000-00-00 00:00:00' comment '수정일시',
-- 	KEY(mb_id, match_id)
-- 	) Engine=InnoDB comment '라인로그';

-- create table shop_partner_line
-- (
-- 	line_id varchar(200) not null comment '라인ID(line_id:line_id)',
-- 	mb_id varchar(20) not null comment '회원ID',
-- 	reg_dt datetime not null default '0000-00-00 00:00:00',
-- 	upd_dt datetime not null default '0000-00-00 00:00:00',
-- 	KEY(line_id, mb_id)
-- 	) engine=InnoDB comment '라인관리';


SELECT b.pt_id, a.pt_id mb_id, a.match_id, count(1)
FROM shop_partner_matching a, shop_member b
WHERE a.pt_id = b.id and a.line_id IS NULL GROUP BY a.pt_id, a.match_id, b.pt_id;

create table shop_partner_line_point
(
	line_id  varchar(255) not null COMMENT '라인ID',
	mb_id    varchar(20) not null COMMENT '회원ID',
	point    int          not null COMMENT '쇼핑포인트',
	memo     varchar(255) not null COMMENT '메모',
	reg_date datetime not null default '0000-00-00 00:00:00' COMMENT '등록일시',
	key( line_id, mb_id )
	) engine = InnoDB COMMENT '라인점수로그';

SELECT * FROM (
								SELECT a.match_id
										, a.line_id
										, count(1) cnt
										, case when b.pt_id = @pt_id and a.pt_id = @mb_id then @rownum := @rownum + 1
													 else
														 @rownum := 1
												 end rnum
										, case when b.pt_id <> @pt_id then
														 @pt_id := b.pt_id
													 else b.pt_id
												 end pt_id
										, case when a.pt_id <> @mb_id then
														 @mb_id := a.pt_id
													 else a.pt_id
												 end mb_id
								FROM shop_partner_matching a, shop_member b, (
									select @rownum := 0 rnum, @pt_id :='', @mb_id := ''
								) c
								WHERE a.pt_id = b.id
									AND a.match_id is not null
									--   AND a.line_id IS NULL
									--   AND b.pt_id = '01035891744'
								GROUP BY b.pt_id, a.pt_id, a.match_id
								ORDER BY b.pt_id, a.pt_id, case when a.line_id is null then 1 else 0 end, a.line_id
							) A
WHERE rnum = 1
-- and line_id is null
-- limit 0, 2
;

SELECT *
FROM (
			 SELECT a.match_id
					 ,  a.line_id
					 ,  count(1) cnt
					 ,  case
								when a.pt_id = @pt_id and a.mb_id = @mb_id then @rownum := @rownum + 1
								else
									@rownum := 1
								end    rnum
					 ,  case
								when a.pt_id <> @pt_id then
									@pt_id := a.pt_id
								else a.pt_id
								end    pt_id
					 ,  case
								when a.mb_id <> @mb_id then
									@mb_id := a.mb_id
								else a.mb_id
								end    mb_id
			 FROM shop_partner_matching a,
						(
							select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
						) c
			 WHERE 1 = 1
				 AND a.match_id is not null
				 AND a.pt_id = '01035891745'
			 GROUP BY a.mb_id, a.match_id
			 ORDER BY a.mb_id, case when a.line_id is null then 1 else 0 end, a.line_id
		 ) A
WHERE rnum = 1
	and line_id is null
-- limit 0, 1
;
SELECT *
FROM (
			 SELECT a.match_id
					 ,
							a.line_id
					 ,
							count(1) cnt
					 ,
							case
								when b.pt_id = @pt_id and a.pt_id = @mb_id then @rownum := @rownum + 1
								else
									@rownum := 1
								end    rnum
					 ,
							case
								when b.pt_id <> @pt_id then
									@pt_id := b.pt_id
								else b.pt_id
								end    pt_id
					 ,
							case
								when a.pt_id <> @mb_id then
									@mb_id := a.pt_id
								else a.pt_id
								end    mb_id
			 FROM shop_partner_matching a,
						shop_member b,
						(
							select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
						) c
			 WHERE a.pt_id = b.id
				 AND a.match_id is not null
				 AND b.pt_id = '01035891745'
			 GROUP BY b.pt_id, a.pt_id, a.match_id
			 ORDER BY b.pt_id, a.pt_id, case when a.line_id is null then 1 else 0 end, a.line_id
		 ) A
WHERE rnum = 1
	and line_id is null
	limit 0, 2;

SELECT *
FROM (
			 SELECT a.match_id
					 ,
							a.line_id
					 ,
							count(1) cnt
					 ,
							case
								when b.pt_id = @pt_id and a.pt_id = @mb_id then @rownum := @rownum + 1
								else
									@rownum := 1
								end    rnum
					 ,
							case
								when b.pt_id <> @pt_id then
									@pt_id := b.pt_id
								else b.pt_id
								end    pt_id
					 ,
							case
								when a.pt_id <> @mb_id then
									@mb_id := a.pt_id
								else a.pt_id
								end    mb_id
			 FROM shop_partner_matching a,
						shop_member b,
						(
							select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
						) c
			 WHERE a.pt_id = b.id
				 AND a.match_id is not null
				 AND b.pt_id = '01035891744'
			 GROUP BY b.pt_id, a.pt_id, a.match_id
			 ORDER BY b.pt_id, a.pt_id, case when a.line_id is null then 1 else 0 end, a.line_id
		 ) A
WHERE rnum = 1
		--  and line_id is null
	limit 0, 2;
SELECT * FROM `shop_partner_matching` WHERE LINE_ID IS NOT NULL;


ALTER TABLE shop_partner_matching
	ADD COLUMN match_at datetime not null default '0000-00-00 00:00:00' comment '매칭완료일' after match_id,
	ADD COLUMN line_at datetime not null default '0000-00-00 00:00:00' comment '라인생성일' after line_id;

select * from `shop_partner_line_point`;



begin;

UPDATE shop_partner_matching
SET line_id = replace(line_id, 'match_', '')
WHERE line_id LIKE '%match_%';

UPDATE shop_partner_matching
SET match_id = replace(match_id, '-', ':')
		, line_id  = replace(line_id, '-', ':');

UPDATE shop_partner_matching
SET match_id = replace(match_id, '|', '⊙')
		, line_id  = replace(line_id, '|', '⊙');

UPDATE shop_partner_matching
SET match_id = replace(match_id, '●', '◎')
		, line_id  = replace(line_id, '●', '◎');
rollback;


ALTER TABLE shop_partner_matching
	CHANGE COLUMN line_id line_id VARCHAR(200) NULL COMMENT '라인ID';

select 2 a
union all
select null a
union all
select 1 a
union all
select 'a' a
order by case when a is null then 1 else 0 end, a;

SELECT *
FROM shop_member
WHERE od_id in ( select od_id from shop_order where mb_id = '01035891744_0001');
SELECT *
FROM shop_order where od_id not in ( select od_id from shop_member );

select * from shop_goods where gcode = '1543139517';

UPDATE shop_partner_matching
SET match_id = 'match_01035891750:010358951'
where pt_id = '01035891745'
	AND mb_id IN ('01035891750', '01035891751')
	AND match_id IS NULL;


SELECT b.pt_id, a.pt_id mb_id, a.match_id
FROM shop_partner_matching a, shop_member b
WHERE a.pt_id = b.id and a.line_id is not null;

SELECT * FROM shop_partner_matching;
UPDATE shop_partner_matching
SET match_id = null
WHERE match_id = '';

ALTER TABLE shop_partner_matching
	CHANGE COLUMN line_id line_id VARCHAR(200) NULL COMMENT '라인ID';

ALTER TABLE shop_partner_line_point
  ADD COLUMN index_no int not null AUTO_INCREMENT UNIQUE KEY comment '일련번호' FIRST;

ALTER TABLE shop_member
	ADD UNIQUE INDEX unique_id (id);

ALTER TABLE shop_partner
	ADD UNIQUE INDEX unique_mb_id (mb_id);



-- 라인 내역
SELECT * FROM (
								select a.*, b.*
								from shop_member a,
										 (select c.pt_id              match_mb_id,
														 line_id,
														 min(match_id)        min_mb_id,
														 max(match_id)        max_mb_id,
														 max(match_reg_price) match_reg_price,
														 max(lime_at)         lime_at,
														 count(1)             lining_cnt
											from
												(select pt_id                                  match_mb_id,
																match_id,
																line_id,
																min(mb_id) as                          min_mb_id,
																max(mb_id) as                          max_mb_id,
																max(reg_price)                         match_reg_price,
																date_format(max(match_at), '%Y-%m-%d') match_at,
																date_format(max(line_at), '%Y-%m-%d')  lime_at,
																count(1)                               matching_cnt
												 from shop_partner_matching
												 group by pt_id, line_id
												 having count(1) > 1) b,
												shop_member c
											WHERE b.match_mb_id = c.id
											group by c.pt_id, line_id
											having count(1) > 1
										 ) b
								where a.id = b.match_mb_id
									and a.grade IN (SELECT gb_no FROM shop_member_grade WHERE gb_no BETWEEN 2 and 6 AND gb_line_depth = 2)
								UNION ALL
								select a.*, b.*
								from shop_member a,
										 (select pt_id                                 match_mb_id,
														 line_id,
														 min(mb_id) as                         min_mb_id,
														 max(mb_id) as                         max_mb_id,
														 max(reg_price)                        match_reg_price,
														 date_format(max(line_at), '%Y-%m-%d') line_at,
														 count(1)                              lining_cnt
											from shop_partner_matching
											group by pt_id
											having count(1) > 1) b
								where a.id = b.match_mb_id
									and a.grade IN (SELECT gb_no FROM shop_member_grade WHERE gb_no BETWEEN 2 and 6 AND gb_line_depth = 1)
							) A;

ALTER TABLE shop_member MODIFY COLUMN grp_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_seller_cal MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_partner_match MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_member MODIFY COLUMN id varchar(60)  NOT NULL ;
ALTER TABLE shop_member MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_member MODIFY COLUMN up_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_login MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_partner_term MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_leave_log MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_leave_log MODIFY COLUMN new_id varchar(60)  NOT NULL ;
ALTER TABLE shop_leave_log MODIFY COLUMN old_id varchar(60)  NOT NULL ;
ALTER TABLE shop_order MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_order MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_order MODIFY COLUMN up_id varchar(60)  NULL  DEFAULT NULL;
ALTER TABLE shop_order MODIFY COLUMN shop_id varchar(60)  NOT NULL ;
ALTER TABLE shop_order MODIFY COLUMN seller_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_type MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_visit_sum MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_banner MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_gift MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_qa MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_qa MODIFY COLUMN replyer varchar(60)  NOT NULL ;
ALTER TABLE shop_seller MODIFY COLUMN mb_id varchar(60)  NOT NULL ;

ALTER TABLE shop_logo MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_order_data MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_partner_line_point MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_member_leave MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_review MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_review MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_review MODIFY COLUMN seller_id varchar(60)  NOT NULL ;

ALTER TABLE shop_board_13 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_board_36 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_board_22 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_popup MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_board_21 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_qa MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_goods_qa MODIFY COLUMN seller_id varchar(60)  NOT NULL ;
ALTER TABLE shop_coupon_log MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_board_20 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_visit MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_keyword MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_board_41 MODIFY COLUMN pt_id varchar(60)  NOT NULL ;
ALTER TABLE shop_cart MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_cart MODIFY COLUMN pt_id varchar(60)  NULL  DEFAULT NULL;
ALTER TABLE shop_cart MODIFY COLUMN up_id varchar(60)  NULL  DEFAULT NULL;
ALTER TABLE shop_partner_payrun MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_brand MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_wish MODIFY COLUMN mb_id varchar(60)  NOT NULL ;
ALTER TABLE shop_partner MODIFY COLUMN mb_id varchar(60)  NOT NULL ;

ALTER TABLE shop_partner_pay MODIFY COLUMN pp_rel_action varchar(60) NOT NULL DEFAULT '';
ALTER TABLE shop_partner_pay MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_partner_pay MODIFY COLUMN pp_rel_id varchar(60)  NOT NULL  DEFAULT '';

ALTER TABLE shop_point MODIFY COLUMN po_rel_action varchar(60) NOT NULL DEFAULT '';
ALTER TABLE shop_point MODIFY COLUMN mb_id varchar(60)  NOT NULL  DEFAULT '';
ALTER TABLE shop_point MODIFY COLUMN po_rel_id varchar(60)  NOT NULL  DEFAULT '';

ALTER TABLE shop_member
ADD COLUMN grp_no varchar(10) not null default '0000' comment '그룹INDEX' after grp_id;

CREATE TABLE shop_partner_rollup
(
  rollup_no int not null auto_increment unique key comment '일련번호',
  rollup_date date not null default '0000-00-00' primary key comment '정산일자',
  rollup_at datetime not null default '0000-00-00 00:00:00' comment '실행일시'
) Engine=InnoDB comment '롤업15/30';


ALTER TABLE shop_member_grade
ADD COLUMN gb_line_point tinyint not null default 0 comment '라인점수' after gb_line_depth;

ALTER TABLE shop_member
ADD COLUMN line_point int not null default 0 comment '라인점수' after point;

select * from shop_member;

select * from shop_partner_pay;

select * from shop_partner_line_point;

select * from shop_partner_matching;

select * from shop_partner_bonus;

select * from shop_partner_yearly_bonus;

select * from shop_partner_rollup;

truncate table shop_partner_matching;
truncate table shop_partner_line_point;
truncate table shop_partner_pay;
update shop_member set line_point = 0, match_cnt = 0, pay = 0 where 1;
update shop_order set dan = 1 where dan = 5;

select a.mb_id, a.reg_price, b.grade
from shop_partner_matching a, shop_member b
where a.pt_id = b.id
 and a.pt_id = 'Y1lQTklmYWFyN1pKN3FmMjNKcWV1UT09'
 and a.mb_id <> 'cGRqSzdnRUdVajJyZlNyT2o0bE5udz09'
 and a.match_id is null limit 0, 1;


select * from shop_partner_yearly_bonus;
select * from shop_partner_line_point;
select * from shop_partner_bonus;

select * from shop_plan;

select * from shop_partner_matching;
select * from shop_partner_line_point;

select * from shop_cart order by index_no desc;
select * from shop_member where id = 'VlAzdFkwcG9qb2VTd3NVbmpmUG1mZz09';


select * from shop_partner_matching;

SELECT a.pt_id, c.gb_line_point gb_line_point, IFNULL(e.gb_line_point, 0) gb_line_point_top
FROM shop_partner_matching a
       INNER JOIN shop_member b ON a.pt_id = b.id
       INNER JOIN shop_member_grade c ON b.grade = c.gb_no
       LEFT JOIN shop_member d ON b.pt_id = d.id
       LEFT jOIN shop_member_grade e ON d.grade = e.gb_no
WHERE a.line_id = '01012341235≫01012341236∥01012341235'
GROUP BY a.pt_id, c.gb_line_point, e.gb_line_point;

select * from shop_member_grade;

alter table shop_member_grade
  add column gb_line_point_gold_matched tinyint not null default 0 comment '하위골드회원매칭시추가점수' after gb_line_point,
  add column gb_pf_hosting tinyint not null default 0 comment '호스팅비용' after gb_promotion,
  add column gb_pf_sp_point int not null default 0 comment '쇼핑페이지급'  after gb_pf_hosting,
  add column gb_pf_up_sp_point int not null default 0 comment '추천쇼핑페이' after gb_pf_sp_point,
  add column gb_pf_per_sp_point int not null default 0 comment '쇼핑페이적립%' after gb_pf_up_sp_point,
  add column gb_pf_per_up_pay int not null default 0 comment '영업수수료%' after gb_pf_per_sp_point,
  add column gb_pf_per_match_pay float not null default 0 comment '관리수수료%' after gb_pf_per_up_pay;

alter table shop_member_grade
  add column gb_line_point_rollup_level tinyint not null default 1 comment '롤업단계' after gb_line_point,
  add column gb_line_point_gold_matched_rollup_level tinyint not null default 1 comment '롤업단계' after gb_line_point_gold_matched;

alter table shop_member_grade
  add column gb_pf_point int not null default 0 comment '쇼핑포인트지급'  after gb_pf_hosting,
  add column gb_pf_up_point int not null default 0 comment '추천쇼핑포인트' after gb_pf_point,
  add column gb_pf_per_point int not null default 0 comment '쇼핑포인트적립%' after gb_pf_up_point;

  select * from shop_uniqid;

select * from shop_partner_bonus;
select * from shop_partner_bonus_history;
select * from shop_partner_bonus_title;
select * from shop_partner_bonus_share;
select distinct sp_rel_table from shop_partner_shopping_pay;
select * from shop_point where po_rel_table in ('anew', 'member', 'order_reward');
select * from shop_partner_shopping_pay where sp_rel_table in ('anew', 'member', 'ordre_reward');
select sum(sp_price) from shop_partner_shopping_pay;
drop table shop_partner_shopping_pay;
create table shop_partner_shopping_pay
(
  sp_id int not null auto_increment primary key comment '일련번호',
  mb_id varchar(60) not null comment '회원ID',
  sp_datetime datetime not null comment '등록일시',
  sp_content varchar(255) not null comment '쇼핑페이내역',
  sp_price int not null default 0 comment '쇼핑페이지급',
  sp_use_price int not null default 0 comment '쇼펭페이사용',
  sp_balance int not null default 0 comment '누계',
  sp_rel_table varchar(30) not null comment '관련 테이블',
  sp_rel_id varchar(60) not null comment '관련 일련번호',
  sp_rel_action varchar(60) not null comment '관련 행위',
  sp_referer text comment '접속레퍼럴',
  sp_agent varchar(255) comment '접속브라우저'
) Engine=InnoDB comment '쇼핑페이';

create table shop_partner_shopping_payrun
(
  index_no int not null auto_increment primary key comment '일련번호',
  mb_id varchar(60) not null comment '회원ID',
  state tinyint not null default 0 comment '상태',
  balance int not null default 0 comment '환전요청페이',
  paytax int not null default 0 comment '수수료/세액공제',
  paynet int not null default 0 comment '실환전페이',
  bank_name varchar(255) comment '은행',
  bank_account varchar(255) comment '계좌',
  bank_holder varchar(255) comment '예금주',
  reg_time datetime not null
) Engine=InnodDB comment '쇼핑페이환전';

select * from shop_member;
select * from shop_partner_line_point;
drop table shop_partner_line_point;
create table shop_partner_line_point
(
  lp_id int not null auto_increment primary key comment '일련번호',
  mb_id varchar(60) not null comment '회원ID',
  lp_datetime datetime not null comment '등록일시',
  lp_content varchar(255) not null comment '라인쇼핑포인트내역',
  lp_point int not null default 0 comment '라인보인트지급',
  lp_use_point int not null default 0 comment '라인쇼핑포인트사용',
  lp_balance int not null default 0 comment '누계',
  lp_rel_table varchar(30) not null comment '관련 테이블',
  lp_rel_id varchar(60) not null comment '관련 일련번호',
  lp_rel_action varchar(60) not null comment '관련 행위',
  lp_referer text comment '접속레퍼럴',
  lp_agent varchar(255) comment '접속브라우저'
) Engine=InnoDB comment '라인쇼핑포인트내역';

select * from shop_partner;
drop table shop_partner_type;
create table shop_partner_type
(
  biz_no int not null auto_increment unique key comment '일련번호',
  biz_type varchar(10) not null primary key comment '타법인가입유형',
  biz_type_name varchar(20) not null comment '가입유형명',
  biz_anew_price int not null default 0 comment '가맹점개설비',
  biz_grade_5_to int not null comment '정회원전환등급',
  biz_grade_4_to int not null comment 'VIP회원전환등급',
  use_good_register tinyint not null default 1 comment '제품등록여부',
  use_partner_pay tinyint not null default 1 comment '수수료여부(추천/매칭)',
  use_share_bonus tinyint not null default 1 comment '유지보너스여부',
  use_point_bonus tinyint not null default 1 comment '가맹점수보너스여부',
) Engine=InnoDB comment '타법인가입유형';

-- insert into shop_partner_type(biz_type, biz_type_name, anew_price, use_good_register, use_partner_pay, use_share_bonus, use_point_bonus) values('fb_none', '신규회원', 1, 1,1,1,1);

insert into shop_partner_type(biz_no,biz_type, biz_type_name, biz_anew_price, biz_grade_5_to, biz_grade_4_to, use_good_register, use_partner_pay, use_share_bonus, use_point_bonus)
       values(1,'fb_198', '타법인회원', 198000,4,4,1,1,1,1);

insert into shop_partner_type(biz_no,biz_type, biz_type_name, biz_anew_price, biz_grade_5_to, biz_grade_4_to, use_good_register, use_partner_pay, use_share_bonus, use_point_bonus)
       values(2,'fb_33', '타법인~9/30가입회원', 33000,5,4,0,0,1,1);

insert into shop_partner_type(biz_no,biz_type, biz_type_name, biz_anew_price, biz_grade_5_to, biz_grade_4_to, use_good_register, use_partner_pay, use_share_bonus, use_point_bonus)
       values(3,'fb_0', '타법인10/1~가입회원',0,5,4,0,0,1,1);

update shop_partner_type set use_partner_pay = 0 where biz_no = 2;

SELECT * FROM shop_partner_matching;
select * from shop_partner_bonus_title;
select * from shop_partner_bonus;
alter table shop_partner
  add column from_biz_type enum('fb_none','fb_198','fb_33', 'fb_0') null comment '타법인가입유형' after anew_grade,
  add column from_biz_name varchar(30) null comment '타법인명' after from_biz_type,
  add column from_biz_id varchar(40) null comment '타법인ID' after from_biz_name,
  add column from_biz_job_title varchar(30) null comment '타법인직급' after from_biz_id,
  add column from_biz_grade varchar(30) null comment '타법인등급' after from_biz_job_title,
  add column from_biz_confirmed tinyint not null default 0 comment '타법인승인' after from_biz_grade
;
alter table shop_partner
  add column pay_bank_name varchar(255) comment '페이명' after bank_holder,
  add column pay_bank_account varchar(255) comment '계좌번호' after pay_bank_name,
  add column pay_bank_holder varchar(255) comment '계좌주' after pay_bank_account;

alter table shop_partner
  drop column from_biz_type,
  drop column from_biz_name,
  drop column from_biz_id,
  drop column from_biz_job_title,
  drop column from_biz_grade,
  drop column from_biz_confirmed
;
select max(up_lv) from shop_partner_hierarchy_pt;
select * from shop_partner_hierarchy_pt where up_lv = 63;

select * from shop_member where name = 'k2k9';
select * from shop_member where id = 'UlZWQy9pSy9mYUF2dkxtZTJZOExUUT09';

select * from shop_member where id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
select d.name, d.cellphone, d.id, p.name, p.cellphone, p.id, a.up_lv from shop_partner_hierarchy_pt a, shop_member d, shop_member p where a.dn_id = d.id and a.pt_id = p.id and a.dn_id = 'b2o4Q2N4bUdTZExRak90OEdnUFoydz09' order by a.up_lv;

select d.name, d.cellphone, d.id, p.name, p.cellphone, p.id, a.up_lv from shop_partner_hierarchy_pt a, shop_member d, shop_member p where a.dn_id = d.id and a.pt_id = p.id and a.pt_id = 'd3N1b3RodHdzQUVjK2pObU03RzZ4dz09' order by a.up_lv;
select * from shop_partner_hierarchy_pt where pt_id = 'd3N1b3RodHdzQUVjK2pObU03RzZ4dz09';
select count(1) from shop_partner_hierarchy_pt where pt_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
select up_lv, count(1) from shop_partner_hierarchy_pt where pt_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09' group by up_lv;


select * from shop_dp_label where shop_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
alter table shop_member
add column sp_point int not null comment '쇼핑페이' after point;

alter table shop_order add column use_sp_point int not null comment '쇼핑페이사용' after use_point;
alter table shop_order add column sum_sp_point int not null comment '쇼핑페이적립' after sum_point;

select * from shop_order order by index_no desc;
select use_sp_point from shop_order;
select * from shop_point;
select * from shop_partner_line_point;
select * from shop_partner_shopping_pay;

select * from shop_partner_matching;
select * from shop_partner_line_point;



select buy_only, buy_level from shop_goods;
update shop_goods set buy_level = 10 where 1;
select sum_point from shop_order;


select * from shop_partner_type order by biz_no;

alter table shop_member
add column reg_level tinyint not null default 0 comment '호스팅',
add column reg_price int not null default 0 comment '호스팅비용';

select pf_anew_benefit_type from shop_config;

select * from shop_member_grade;

select * from shop_partner_bonus_history;
select * from shop_partner_bonus;


select * from shop_partner;

select count(1), max(up_lv) from shop_partner_hierarchy_pt;
select count(1), max(up_lv) from shop_partner_hierarchy_up;
select * from shop_partner_hierarchy_pt where pt_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
select a.id, a.pt_id, b.id, b.pt_id, a.name, b.name, a.anew_date, b.anew_date, a.index_no, b.index_no from shop_member a, shop_member b where a.pt_id = b.id and a.index_no < b.index_no;
select a.id, a.pt_id, b.id, b.pt_id, a.name, b.name, a.anew_date, b.anew_date, a.index_no, b.index_no from shop_member a, shop_member b where a.pt_id = b.id and a.anew_date< b.anew_date;

select * from shop_member where pt_id is null;
select * from shop_member where pt_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
select id from shop_member where pt_id in (select id from shop_member where pt_id in (select id from shop_member where pt_id in (select id from shop_member where pt_id in (select id from shop_member where pt_id in ( select id from shop_member where pt_id in ( select id from shop_member where pt_id in (select id from shop_member where pt_id in (select id from shop_member where pt_id in (select id from shop_member where pt_id in ( select id from shop_member where pt_id in ( select id from shop_member where pt_id in ( select id from shop_member where pt_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09' ))))))))))));
;

select a.reg_time, a.anew_date, a.index_no, a.id, a.grade
  from shop_member a left join shop_member p on a.pt_id = p.id
 where a.use_app = 1
   and a.grade between 2 and 6
 order by
   case when a.anew_date < p.anew_date then 1
        else 0 end asc,
   a.anew_date asc, a.index_no asc;

select * from shop_member

select a.*
  from shop_member a
 where 1 = 1
       start with a.id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09'
       connect by prior a.id = a.pt_id;

select * from shop_partner_hierarchy_pt where up_lv = 19;
select count(1) from shop_partner_hierarchy_pt;
select max(up_lv) from shop_partner_hierarchy_pt;
select * from shop_member where id = 'cUwzNGM4ZGZYdTRIRjVVTmUwanhuUT09';
select * from shop_member where id = 'Y0c5aktybUY1bzJ5Tm5CWldNZXRpdz09';
select * from shop_member where id = 'c3hYajY0eE9maDhNMUNuNHBydWF4dz09';
select * from shop_member where pt_id = 'cUwzNGM4ZGZYdTRIRjVVTmUwanhuUT09';
select * from shop_member where pt_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';
select * from shop_member where name = 'k2k9';
select count(1) from shop_partner_hierarchy_pt where pt_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09';
select count(1) from shop_member;
select * from shop_partner_hierarchy_pt where dn_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09';

CREATE TABLE parts(part_id INTEGER NOT NULL PRIMARY KEY,
  part_name VARCHAR(60) NOT NULL);

CREATE TABLE components(comp_id INTEGER NOT NULL PRIMARY KEY,
  comp_name VARCHAR(60),
  comp_count INTEGER NOT NULL,
  comp_part INTEGER NOT NULL,
  comp_partof INTEGER,
  FOREIGN KEY(comp_part) REFERENCES parts(part_id));
ALTER TABLE components ADD FOREIGN KEY(comp_partof) REFERENCES components(comp_id);

INSERT INTO parts VALUES(1, 'Car');
INSERT INTO parts VALUES(2, 'Bolt');
INSERT INTO parts VALUES(3, 'Nut');
INSERT INTO parts VALUES(4, 'V8 engine');
INSERT INTO parts VALUES(5, '6-cylinder engine');
INSERT INTO parts VALUES(6, '4-cylinder engine');
INSERT INTO parts VALUES(7, 'Cylinder block');
INSERT INTO parts VALUES(8, 'Cylinder');
INSERT INTO parts VALUES(9, 'Piston');
INSERT INTO parts VALUES(10, 'Camshaft');
INSERT INTO parts VALUES(11, 'Camshaft bearings');
INSERT INTO parts VALUES(12, 'Body');
INSERT INTO parts VALUES(13, 'Gearbox');
INSERT INTO parts VALUES(14, 'Chassie');
INSERT INTO parts VALUES(15, 'Rear axle');
INSERT INTO parts VALUES(16, 'Rear break');
INSERT INTO parts VALUES(17, 'Wheel');
INSERT INTO parts VALUES(18, 'Wheel bolts');

INSERT INTO components VALUES(1, '320', 1, 1, NULL);
INSERT INTO components VALUES(2, NULL, 1, 6, 1);
INSERT INTO components VALUES(3, NULL, 1, 7, 2);
INSERT INTO components VALUES(4, NULL, 4, 8, 3);
INSERT INTO components VALUES(5, NULL, 4, 9, 3);
INSERT INTO components VALUES(6, NULL, 1, 10, 3);
INSERT INTO components VALUES(7, NULL, 3, 11, 6);
INSERT INTO components VALUES(8, NULL, 1, 12, 1);
INSERT INTO components VALUES(9, NULL, 1, 14, 1);
INSERT INTO components VALUES(10, NULL, 1, 15, 9);
INSERT INTO components VALUES(11, NULL, 2, 16, 10);

INSERT INTO components VALUES(12, '323 i', 1, 1, NULL);
INSERT INTO components VALUES(13, NULL, 1, 5, 12);

SELECT LPAD('-', level, '-')||'>' level_text, comp_count, NVL(comp_name, part_name) name
FROM components c, parts p
WHERE c.comp_part = p.part_id
START WITH c.comp_name = '320'
CONNECT BY PRIOR c.comp_id = c.comp_partof;

drop table shop_partner_hierarchy;
create table shop_partner_hierarchy
(
  dn_id varchar(60) not null comment '하위ID',
  pt_id varchar(60) not null comment '상위ID',
  up_lv int not null comment '상윈DEPTH',
  up_idx int not null comment '하위순위',
  reg_date datetime comment '생성일시',
  primary key (dn_id, pt_id, up_lv)
) Engine=InnoDB comment '가맹계층';

drop table if exists shop_partner_hierarchy_pt;
create table shop_partner_hierarchy_pt
(
  dn_id varchar(60) not null comment '하위ID',
  pt_id varchar(60) not null comment '상위ID',
  up_lv int not null comment '상윈DEPTH',
  up_idx int not null comment '하위순위',
  reg_date datetime comment '생성일시',
  primary key (dn_id, pt_id, up_lv)
) Engine=InnoDB comment '가맹후원계층';

create table shop_partner_hierarchy_up
(
  dn_id varchar(60) not null comment '하위ID',
  up_id varchar(60) not null comment '상위ID',
  up_lv int not null comment '상윈DEPTH',
  up_idx int not null comment '하위순위',
  reg_date datetime comment '생성일시',
  primary key (dn_id, up_id, up_lv)
) Engine=InnoDB comment '가맹추천계층';

select * from shop_partner_hierarchy;
select * from shop_partner_hierarchy_pt;
select * from shop_partner_hierarchy_up;



select * from shop_partner_bonus_share;
select * from shop_partner_rollup;

alter table shop_partner_pay
  add column pp_due_date date comment '지급예정일' after pp_datetime;

select * from shop_partner_pay;

update shop_partner_pay
   set pp_due_date = pp_datetime
 where pp_due_date is null;

select * from shop_partner_pay;

select * from shop_partner_hierarchy;

select gb_anew_price receipt_price from shop_member_grade;
select biz_anew_price receipt_price from shop_partner_type;

select distinct receipt_price from (select gb_anew_price receipt_price from shop_member_grade union all select biz_anew_price receipt_price from shop_partner_type) a order by receipt_price desc;

select distinct receipt_price from (select gb_anew_price receipt_price from shop_member_grade union all select biz_anew_price receipt_price from shop_partner_type) a order by receipt_price desc;

select * from shop_partner_type;
select * from shop_partner;
select gb_no, gb_name, gb_anew_price from shop_member_grade where gb_no between 2 and 6;
select id, grade from shop_member;
update shop_partner set from_biz_type = 'fb_none' where from_biz_type is null;
select a.from_biz_type, a.receipt_price, a.anew_grade
  from shop_partner a
 order by a.receipt_price desc;

update shop_member a inner join shop_partner b on a.id = b.mb_id set b.from_biz_type = 'fb_33',
                        b.from_biz_name = '타법인~9/30가입회원',
                        b.from_biz_id   = a.id,
                        b.from_biz_job_title = '',
                        b.from_biz_grade= '정회원',
                        a.grade = 5,
                        b.anew_grade=5,
                        b.receipt_price = 33000
                    where a.grade = 3;

update shop_member a inner join shop_partner b on a.id = b.mb_id set b.from_biz_type = 'fb_33',
                        b.from_biz_name = '타법인~9/30가입회원',
                        b.from_biz_id   = a.id,
                        b.from_biz_job_title = '',
                        b.from_biz_grade= 'VIP회원',
                        a.grade  = 4,
                        a.anew_grade = 4,
                        b.receipt_price = 33000
                    where a.grade = 2;
update shop_partner set receipt_price = 33000 where receipt_price = 33000396;
update shop_partner set receipt_price = 33000 where receipt_price = 33000198;
update shop_partner set anew_grade = 4 where anew_grade = 2;
update shop_partner set anew_grade = 5 where anew_grade = 3;

update shop_member_grade
   set gb_name = '일반회원',
       gb_line_point_gold_matched = 0,
       gb_pf_hosting = 0,
       gb_anew_price = 0,
       gb_pf_sp_point=10000,
       gb_pf_up_sp_point=10000,
       gb_pf_per_sp_point=10,
       gb_pf_per_up_pay=30,
       gb_pf_per_match_pay=7.5,
       gb_line_point_rollup_level = 0,
       gb_line_point_gold_matched_rollup_level=0
 where gb_no = 6;

update shop_member_grade
   set gb_name = '정회원',
       gb_line_point_gold_matched = 1,
       gb_pf_hosting = 6,
       gb_anew_price = 198000,
       gb_pf_sp_point = 180000,
       gb_pf_up_sp_point = 90000,
       gb_pf_per_sp_point = 50,
       gb_pf_per_up_pay=30,
       gb_pf_per_match_pay=7.5,
       gb_line_point_rollup_level = 10,
       gb_line_point_gold_matched_rollup_level=10
 where gb_no = 5;

update shop_member_grade
set gb_name = '골드회원',
    gb_line_point_gold_matched = 2,
    gb_pf_hosting = 12,
    gb_anew_price = 396000,
    gb_pf_sp_point = 360000,
    gb_pf_up_sp_point = 180000,
    gb_pf_per_sp_point= 80,
    gb_pf_per_up_pay=30,
    gb_pf_per_match_pay=7.5,
       gb_line_point_rollup_level = 10,
       gb_line_point_gold_matched_rollup_level=10
where gb_no = 4;

update shop_member_grade
set gb_name = '',
    gb_line_point_gold_matched = 0,
    gb_pf_hosting = 0,
    gb_anew_price = 0,
    gb_pf_sp_point = 0,
    gb_pf_up_sp_point = 0,
    gb_pf_per_sp_point= 0
where gb_no = 3;

update shop_member_grade
set gb_name = '',
    gb_line_point_gold_matched =0,
    gb_pf_hosting = 0,
    gb_anew_price = 0,
    gb_pf_sp_point =0,
    gb_pf_up_sp_point = 0,
    gb_pf_per_sp_point= 0
where gb_no = 2;

truncate table shop_partner_matching;
truncate table shop_partner_pay;
truncate table shop_partner_shopping_pay;
truncate table shop_partner_line_point;
truncate table shop_partner_hierarchy;
update shop_member set total_line_cnt = 0, match_cnt = 0, line_point = 0, sp_point = 0, pay = 0 where 1;

select * from shop_partner_matching;
select * from shop_partner_pay;
select * from shop_partner_shopping_pay;
select * from shop_partner_line_point;
select * from shop_point;
select * from shop_partner_payrun;
select * from shop_partner_hierarchy;
select * from shop_partner_bonus_history;

select anew_date
  from shop_member
 where anew_date IS NOT NULL
 order by anew_date;

select a.reg_time, a.anew_date, a.index_no, a.id, a.grade, point, sp_point, line_point, pay, match_cnt, total_line_cnt
  from shop_member a, shop_partner b
 where a.id = b.mb_id
   and a.use_app = 1
   and IFNULL(a.anew_date, '') <> ''
 order by a.anew_date asc;

select a.reg_time, a.anew_date, a.index_no, a.id, a.grade, point, sp_point, line_point, pay, match_cnt, total_line_cnt
                  from shop_member a, shop_partner b
                 where a.id = b.mb_id
                   and a.use_app = 1
                   and IFNULL(a.anew_date, '') <> ''
                 order by a.anew_date asc;

select IFNULL(max(up_idx),0) + 1 nt_idx from shop_partner_hierarchy where pt_id ='cUQrSTNqcEMwRCsyL1paeWJjcSt3UT09' and up_lv = 1;

select * from shop_partner_hierarchy;
select number_format(sum(receipt_price), 0, ',','.') from shop_partner;
select * from shop_partner_pay;
select * from shop_partner_shopping_pay;
select * from shop_partner_line_point;
select * from shop_point;
select * from shop_partner_matching;

select * from shop_partner_bonus;
-- select job_title from shop_member order by job_title asc;
SELECT a.*, b.job_title FROM shop_partner_bonus a, shop_partner_bonus_title b
 WHERE a.job_no = b.job_no and a.up_point <= 200 and a.up_point > 0 ORDER BY a.up_point;

alter table shop_member
add column job_no int not null comment '타이블NO' after job_title;


select job_title from shop_member where job_title is not null;

select * from shop_partner_bonus_title;


CREATE TABLE shop_partner_crond_log
(
  exec_no int not null auto_increment unique key comment '일련번호',
  job_name varchar(60) not null comment '작업명',
  exec_date date not null default '0000-00-00' primary key comment '실행일자',
  exec_at datetime not null default '0000-00-00 00:00:00' comment '실행일시'
) Engine=InnoDB comment '배치로그';
alter table shop_member
add column pc_no int null comment '센터NO' after up_id;
create table shop_partner_center
(
  pc_no int not null auto_increment primary key comment '센터NO',
  pc_nm varchar(30) not null comment '센터명',
  pc_cc_no int not null comment '센터장',
  pc_state tinyint not null comment '운영상태',
  reg_at datetime comment '등록일시',
  upd_at datetime comment '수정일시'

) Engine=InnoDB comment='지점';

select * from shop_partner_bonus_share;
select * from shop_partner_bonus_title;
select * from shop_leave_log;
select * from shop_point;
select replace(po_content, '의 추천인', '의 후원인') from shop_point where po_content LIKE '%의 추천인%';


select a.id, ceil(b.amount * 0.2 / c.total_line_point * a.line_point)
  from shop_member a,
       ( select sum(use_price) * 0.2 amount from shop_order where dan in ( 5, 8 ) ) b,
       ( select sum(line_point) total_line_point from shop_member ) c;

select * from shop_seller;
select distinct(length(mb_id)) from shop_goods;
select * from shop_goods where mb_id = 'admin';

alter table shop_seller
  modify column seller_code varchar(60) comment '공급사 코드';

select * from shop_seller;
create table shop_seller_code
(
  seller_no int not null auto_increment primary key comment '공급사 일련번호',
  reg_at datetime not null
) Engine=InnoDB comment '공급사코드';

insert into shop_seller_code(seller_no, reg_at) values(1, now());
insert into shop_seller_code(seller_no, reg_at) values(2, now());
insert into shop_seller_code(seller_no, reg_at) values(3, now());
insert into shop_seller_code(seller_no, reg_at) values(4, now());

select * from shop_seller_code;

select * from shop_seller_cal;

select seller_code from shop_seller where state = 1;

select distinct from_biz_name from shop_partner;

SELECT IFNULL(max(up_point),0) up_point FROM shop_partner_bonus_history where mb_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09';
select line_point from shop_member where id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09';
select sum(lp_point) from shop_partner_line_point where mb_id = 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09';
SELECT * FROM shop_partner_bonus_history;
SELECT a.*, b.job_title FROM shop_partner_bonus a, shop_partner_bonus_title b WHERE a.job_no = b.job_no and a.up_point <= 906 and a.up_point > 500 ORDER BY a.up_point;

select * from shop_goods order by point_pay_max desc;


drop table shop_api_auth;
create table shop_api_auth
(
  client_id varchar(60) not null primary key comment '클라이언트ID',
  client_secret varchar(60) not null comment '클라이언트Secret',
  remote_addr varchar(20) not null comment 'REMOTE ADDR',
  expire_at datetime not null default '2100-12-31 23:59:59' comment '만료일',
  reg_at datetime not null comment '등록일시'
) Engine=InnoDB comment 'API 인증';


insert into shop_api_auth(client_id, client_secret, remote_addr, expire_at, reg_at) values('MzBiNHBMdjlSZ1VSQU1NY1p0Wlg2UT09', 'U1FGck5RRHNoV1FDTXV0UUxwaCtjUT09', '127.0.0.1', '2100-12-31 23:59:59', now());

drop table shop_api_token;
create table shop_api_token
(
  token varchar(60) not null primary key comment 'TOKEN',
  client_id varchar(60) not null comment '클라이언트ID',
  expire_at datetime not null comment '만료일'
) Engine=InnoDB comment 'API 토큰';
select date_add(now(), interval 1 hour);

select * from shop_api_token;
select * from shop_api_auth;
SELECT a.client_id, a.client_secret FROM shop_api_auth a, shop_api_token b WHERE a.client_id =b.client_id AND b.token = 'WkJDb0dzOG53M2VyaU9vRFhkMzVNZz09';
SELECT a.client_id, a.client_secret FROM shop_api_auth a, shop_api_token b WHERE a.client_id =b.client_id AND b.token = '19022223303670';
SELECT a.client_id, a.client_secret FROM shop_api_auth a, shop_api_token b WHERE a.client_id =b.client_id AND b.token = '19022223375305';
select sum(po_point) as sum_po_point from shop_point where mb_id = 'Y0pwOWxRNVYyQnJLMnRGb0JTYm9GQT09';

-- create table mig_id_columns as select table_name, column_name, 1 is_id_column, now() reg_at
select table_name, column_name, column_comment, 1 is_id_column, now() reg_at
 from information_schema.columns where TABLE_SCHEMA = 'k2k9company' AND DATA_TYPE = 'varchar' AND COLUMN_TYPE = 'varchar(60)';
select * from mig_id_columns where is_id_column = 0;
select * from mig_id_columns where is_id_column = 1;
select * from shop_joincheck;

drop table shop_member_change_id;
create table shop_member_change_id
(
src_id varchar(60) not null comment '원본ID',
trg_id varchar(60) not null comment '변경ID',
reg_at datetime not null comment '변경일시',
primary key(src_id, trg_id)
) Engine=InnoDB comment '아이디변경로그';

alter table shop_member_change_id
  add column mng_id varchar(60) not null comment '변경자ID',
  add column remote_addr varchar(60) not null comment '변경자IP';

select * from shop_member where id = 'SWhZdkgyK29QbW9XdUF6elIyYmdXQT09'; -- a0017
select * from shop_member where id = 'VFc5RENnS1ZLZjdrTFlneDVvcGYxdz09'; -- 01032490905
update shop_member set id = 'SWhZdkgyK29QbW9XdUF6elIyYmdXQT09' where id = 'VFc5RENnS1ZLZjdrTFlneDVvcGYxdz09';


select a.*, b.receipt_price, c.pc_nm from shop_partner b
 inner join shop_member a on a.id = b.mb_id
 left join shop_partner_center c on a.pc_no = c.pc_no
 where 1 and a.id like '%a0017%'
 order by a.index_no desc limit 0, 30;


select * from shop_partner_line_point;
select * from shop_default;
alter table shop_default add column de_easypay_mid varchar(20) comment '이지페이아이디' after de_escrow_use;

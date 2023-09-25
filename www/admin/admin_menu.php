<?php
define("_MALLSET_", TRUE);

define('ADMIN_MENU1',		'회원관리');
define('ADMIN_MENU1_01',	'회원 정보관리');
define('ADMIN_MENU1_02',	'회원 레벨관리');
define('ADMIN_MENU1_03',	'회원 등록하기');
define('ADMIN_MENU1_04',	'회원 일괄등록');
define('ADMIN_MENU1_05',	'월별 가입통계분석');
define('ADMIN_MENU1_06',	'일별 가입통계분석');
define('ADMIN_MENU1_07',	'쇼핑포인트적립 현황');

define('ADMIN_MENU2',		'미니샵관리');
define('ADMIN_MENU2_01',	'미니샵 환경설정');
define('ADMIN_MENU2_02',	'미니샵 수수료정책');
define('ADMIN_MENU2_03',	'미니샵 신규신청');
define('ADMIN_MENU2_04',	'미니샵 연장신청');
define('ADMIN_MENU2_05',	'미니샵 전체목록');
define('ADMIN_MENU2_06',	'미니샵 수수료보기');
define('ADMIN_MENU2_07',	'미니샵 수수료정산');
define('ADMIN_MENU2_08',	'미니샵 수수료정산요청');
define('ADMIN_MENU2_09',	'미니샵 수수료내역');
define('ADMIN_MENU2_10',	'추천인변경 로그');
define('ADMIN_MENU2_11',	'미니샵 트리구조');
// 조직도 메뉴 추가
define('ADMIN_MENU2_12',	'미니샵 조직도');
define('ADMIN_MENU2_13',	'미니샵 조직도 트리');
define('ADMIN_MENU2_14',	'미니샵 분류관리');
// 미니샵 매칭 관리
// 미니샵 라인 관리
if( defined('ZEN_USE_MATCHING_ADMIN') && ZEN_USE_MATCHING_ADMIN )
    define('ADMIN_MENU2_14',	'미니샵 매칭내역');
if( defined('USE_LINE_UP') && USE_LINE_UP && defined('ZEN_USE_LINE_ADMIN') && ZEN_USE_LINE_ADMIN)
    define('ADMIN_MENU2_15',    '미니샵 라인내역');

define('ADMIN_MENU2_16', '미니샵 쇼핑페이 내역');
define('ADMIN_MENU2_17', '미니샵 쇼핑페이 환전');
define('ADMIN_MENU2_18', '미니샵 점수 내역');
define('ADMIN_MENU2_19', '미니샵 지점');

define('ADMIN_MENU3',		'공급사관리');
define('ADMIN_MENU3_01',	'공급사 신청목록');
define('ADMIN_MENU3_02',	'공급사 신규등록');
define('ADMIN_MENU3_03',	'공급사 일괄등록');
define('ADMIN_MENU3_04',	'공급사 정산관리');
define('ADMIN_MENU3_05',	'공급사 정산내역');

define('ADMIN_MENU4',		'카테고리 관리');
define('ADMIN_MENU4_01',	'카테고리 관리');
define('ADMIN_MENU4_02',	'카테고리 순위');

define('ADMIN_MENU5',		'상품관리');
define('ADMIN_MENU5_01',	'전체 상품관리');
define('ADMIN_MENU5_02',	'상품 진열관리');
define('ADMIN_MENU5_03',	'브랜드 관리');
define('ADMIN_MENU5_04',	'기획전 관리');
define('ADMIN_MENU5_05',	'가격비교사이트 연동');
define('ADMIN_MENU5_06',	'상품 재고관리');
define('ADMIN_MENU5_07',	'상품 옵션재고관리');
define('ADMIN_MENU5_08',	'상품 엑셀일괄등록');
define('ADMIN_MENU5_09',	'옵션 엑셀일괄등록');
define('ADMIN_MENU5_10',	'상품 엑셀일괄수정');
define('ADMIN_MENU5_11',	'빠른 판매가격 수정');
define('ADMIN_MENU5_12',	'빠른 적립쇼핑포인트 수정');
define('ADMIN_MENU5_13',	'빠른 판매여부 수정');
define('ADMIN_MENU5_14',	'빠른 상품이동 수정');
define('ADMIN_MENU5_15',	'빠른 브랜드 수정');
define('ADMIN_MENU5_16',	'빠른 배송비 수정');
define('ADMIN_MENU5_17',	'빠른 구매가능레벨 수정');
define('ADMIN_MENU5_18',	'공급사 대기상품');
define('ADMIN_MENU5_19',	'가맹점 상품관리');
define('ADMIN_MENU5_20',	'상품 문의관리');
define('ADMIN_MENU5_21',	'상품평 관리');
define('ADMIN_MENU5_22',	'쿠폰관리 (인쇄용)');
define('ADMIN_MENU5_23',	'쿠폰관리 (온라인)');
define('ADMIN_MENU5_24',	'네이버최저가URL업로드');
define('ADMIN_MENU5_25',	'네이버수동크롤링');
define('ADMIN_MENU5_27',	'카테고리별 상품 진열관리');

define('ADMIN_MENU6',		'주문관리');
define('ADMIN_MENU6_01',	'주문리스트(전체)');
define('ADMIN_MENU6_02',	'입금대기');
define('ADMIN_MENU6_03',	'입금완료');
define('ADMIN_MENU6_04',	'배송준비');
define('ADMIN_MENU6_05',	'배송중');
define('ADMIN_MENU6_06',	'배송완료');
define('ADMIN_MENU6_07',	'엑셀 배송일괄처리');
define('ADMIN_MENU6_08',	'입금전 취소');
define('ADMIN_MENU6_09',	'배송전 환불');
define('ADMIN_MENU6_10',	'배송후 반품');
define('ADMIN_MENU6_11',	'배송후 교환');
define('ADMIN_MENU6_12',	'관리자메모');
define('ADMIN_MENU6_13',	'엑셀주문관리');

// 통계분석
define('ADMIN_MENU7',		'통계분석');
define('ADMIN_MENU7_01',	'시간별 접속통계');
define('ADMIN_MENU7_02',	'일별 접속통계');
define('ADMIN_MENU7_03',	'요일별 접속통계');
define('ADMIN_MENU7_04',	'월별 접속통계');
define('ADMIN_MENU7_05',	'연도별 접속통계');
define('ADMIN_MENU7_06',	'브라우저별 접속통계');
define('ADMIN_MENU7_07',	'OS별 통계분석');
define('ADMIN_MENU7_08',	'도메인별 접속통계');
define('ADMIN_MENU7_09',	'접속자검색');
define('ADMIN_MENU7_10',	'일별 주문통계');
define('ADMIN_MENU7_11',	'월별 주문통계');
define('ADMIN_MENU7_12',	'일별 취소통계');
define('ADMIN_MENU7_13',	'일별 반품통계');
define('ADMIN_MENU7_14',	'일별 교환통계');
define('ADMIN_MENU7_15',	'일별 환불통계');

// 고객지원
define('ADMIN_MENU8',		'고객지원');
define('ADMIN_MENU8_01',	'1:1 상담문의');
define('ADMIN_MENU8_02',	'회원 탈퇴내역');
define('ADMIN_MENU8_03',	'FAQ 분류');
define('ADMIN_MENU8_04',	'FAQ 관리');
define('ADMIN_MENU8_05',	'리뷰관리');


// 디자인관리
define('ADMIN_MENU9',		'디자인관리');
define('ADMIN_MENU9_01',	'통합 배너관리');
define('ADMIN_MENU9_02',	'통합 배너관리(모바일)');
define('ADMIN_MENU9_03',	'로고 관리');
define('ADMIN_MENU9_04',	'개별 페이지관리');
define('ADMIN_MENU9_05',	'메인 진열관리');
define('ADMIN_MENU9_06',	'팝업 관리');
define('ADMIN_MENU9_07',	'이북 관리');
define('ADMIN_MENU9_08',	'책내용 관리');

// 환경설정
define('ADMIN_MENU10',		'환경설정');
define('ADMIN_MENU10_01',	'기본환경설정');
define('ADMIN_MENU10_02',	'검색엔진 최적화(SEO) 설정');
define('ADMIN_MENU10_03',	'소셜 네트워크 설정');
define('ADMIN_MENU10_04',	'회원가입 설정');
define('ADMIN_MENU10_05',	'메일전송 체크');
define('ADMIN_MENU10_06',	'SMS 기본설정');
define('ADMIN_MENU10_07',	'공급사 입점 설정');
define('ADMIN_MENU10_08',	'관리자 정보수정');
define('ADMIN_MENU10_09',	'전자결제(PG) 설정');
define('ADMIN_MENU10_10',	'카카오페이 설정');
define('ADMIN_MENU10_11',	'네이버페이 설정');
define('ADMIN_MENU10_12',	'배송/교환/반품 설정');
define('ADMIN_MENU10_13',	'지역별 추가배송비 관리');
define('ADMIN_MENU10_14',	'본인인증 / I-PIN 설정');
define('ADMIN_MENU10_15',	'IP 접속제한 설정');
define('ADMIN_MENU10_16',	'게시판 그룹관리');
define('ADMIN_MENU10_17',	'게시판 관리');
define('ADMIN_MENU10_18',	'검색키워드 관리');
define('ADMIN_MENU10_19',	'게시판 별도관리');
define('ADMIN_MENU10_20',	'룰렛관리');
define('ADMIN_MENU10_21',	'메인페이지새로읽기');

// 예약관리
define('ADMIN_MENU11',		'예약관리');
define('ADMIN_MENU11_01',	'예약관리');
define('ADMIN_MENU11_02',	'예약현황');
define('ADMIN_MENU11_03',	'월별전체예약현황');
define('ADMIN_MENU11_04',	'이용관리');
define('ADMIN_MENU11_05',	'이용상태관리');
define('ADMIN_MENU11_06',	'개별요금관리');
define('ADMIN_MENU11_07',	'옵션관리');
define('ADMIN_MENU11_08',	'공휴일관리');
define('ADMIN_MENU11_09',	'결제통계');
define('ADMIN_MENU11_10',	'팝업관리');
define('ADMIN_MENU11_11',	'환경설정');

// 포인트충전관리
define('ADMIN_MENU12',		'포인트충전관리');
define('ADMIN_MENU12_01',	'충전내역');
define('ADMIN_MENU12_02',	'결제통계');
define('ADMIN_MENU12_03',	'환경설정');

// PUSH관리
define('ADMIN_MENU13',		'PUSH관리');
define('ADMIN_MENU13_01',	'PUSH관리');
define('ADMIN_MENU13_02',	'PUSH추가');
define('ADMIN_MENU13_03',	'환경설정');

//그누보드 게시판 관리
define('ADMIN_MENU14',		'게시판관리');
define('ADMIN_MENU14_02',	'PUSH추가');
define('ADMIN_MENU14_03',	'환경설정'); 
?>
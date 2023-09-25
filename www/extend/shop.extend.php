<?php
if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가

// 관리자페이지에서 사용
if(isset($_REQUEST['page_rows']) && $_REQUEST['page_rows']) {
	set_session('ss_page_rows', $_REQUEST['page_rows']);
}

//==============================================================================
// 가맹점 권한체크
//------------------------------------------------------------------------------
$pf_auth_good = false;
$pf_auth_cgy  = false;
$pf_auth_pg   = false;

// 개별 상품판매
if($config['pf_auth_good'] == 2 || ($config['pf_auth_good'] == 3 && $member['use_good']))
	$pf_auth_good = true;

// 개별 결제연동
if($config['pf_auth_pg'] == 2 || ($config['pf_auth_pg'] == 3 && $member['use_pg']))
	$pf_auth_pg = true;

// 개별 카테고리
if($config['pf_auth_cgy'] == 2)
	$pf_auth_cgy = true;

//==============================================================================
// 자주사용되는 선언
//------------------------------------------------------------------------------
// 게시판에서 사용되는 변수들
$gw_search_value = array("subject","writer_s","memo");
$gw_search_text = array("제목","작성자","내용");

// 상품 정렬탭
$gw_psort = array(
	array("readcount",  "desc", "인기상품순"),
	array("goods_price", "asc", "낮은가격순"),
	array("goods_price", "desc", "높은가격순"),
	array("m_count", "desc", "후기많은순"),
	array("index_no", "desc", "최근등록순")
);

// 모바일 상품 정렬탭
$gw_msort = array(
	array("readcount",  "desc", "인기상품순"),
	array("goods_price", "asc", "낮은가격순"),
	array("goods_price", "desc", "높은가격순"),
	array("m_count", "desc", "후기많은순"),
	array("isnaver", "desc", "네이버최저가")
);

// 부관리자메뉴
$gw_auth = array(
	'회원관리',
	'가맹점관리',
	'공급사관리',
	'카테고리관리',
	'상품관리',
	'주문관리',
	'통계분석',
	'고객지원',
	'디자인관리',
	'환경설정'
);

// 상태
$gw_state = array(
	"0"=>"승인",
	"1"=>"대기",
	"2"=>"보류"
);

// 가맹점수수료 구분
$gw_ptype = array(
	"sale" => "판매수수료",
	"anew" => "추천수수료",
	"visit" => "접속수수료",
	"passive" => "기타수수료",// 본사적립
	"payment" => "마일리지정산",
    "anew_match" => "관리수수료", // 매칭수당 구분 추가
    "share_point" => "유지보너스", // 매칭수당 구분 추가
    "line-up" => "가맹점수보너스",
    'share_month' => '권리소득',
	"order"=>"상품주문"
);

$gw_ptype2 = array(
	"sale" => "구매",
	"anew" => "추천",
	"visit" => "접속",
	"passive" => "기타",// 본사적립
	"payment" => "마일리지정산",
    "anew_match" => "관리", // 매칭수당 구분 추가
    "share_point" => "유지보너스", // 매칭수당 구분 추가
    "line-up" => "가맹점수보너스",
    'share_month' => '권리소득',
	"order"=>"상품주문"
);

// 쇼핑페이 구분
$gw_sp_table = array(
    "member" => "가입쇼핑페이",
    "sale" => "판매쇼핑페이",
    "anew" => "추천쇼핑페이",
    "passive" => "추천쇼핑페이",// 본사적립
    "order" => "쇼핑사용",
    "order_reward" => "쇼핑적립",
    "exchange" => "쇼핑페이환전"
);

if( !(defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) ) {
    unset($gw_sp_table['exchange']);
}
// 가맹점 점수 구분
$gw_lp_table = array(
    "member" => "가입쇼핑포인트",
    "anew" => "추천쇼핑포인트",
    "passive" => "추천쇼핑포인트",// 본사적립
    "payment" => "쇼핑포인트정산",
    "anew_match" => "관리쇼핑포인트"
);

// 상품진열상태
$gw_isopen = array(
	"1"=>"진열",
	"2"=>"품절",
	"3"=>"단종",
	"4"=>"중지"
);

// 상품진열영역라벨
$gw_dp_label = array(
    'q_type1'=>'타임세일',//'쇼핑특가',   // 타임세일
    'q_type2'=>'회원쇼핑몰',  // 베스트셀러
    'q_type3'=>'반값상품',   // 신규상품
    'q_type4'=>'정회원',// 인기상품
    'q_type5'=>'골드회원'  // 후원상품
);


// 주문단계
$gw_status = array(
	"1"=>"입금대기",
	"2"=>"입금완료",
	"3"=>"배송준비",
	"4"=>"배송중",
	"5"=>"배송완료",
	"6"=>"취소",
	"7"=>"반품",
	//"10"=>"반품완료",
	"8"=>"교환",
	//"11"=>"교환완료",
	"9"=>"환불"
);

// 주문진행단계
/*
입금대기 => 입금완료, 취소
입금완료 => 배송준비, 환불
배송준비 => 배송중, 배송완료, 환불
배송중   => 배송완료
배송완료 => 반품, 교환
취소 => 단계변경 안됨(삭제만 가능)
환불 => 단계변경 안됨(삭제도 안됨)
반품 => 단계변경 안됨(삭제도 안됨)
교환 => 단계변경 안됨(삭제도 안됨)
*/
$gw_array_status = array(
	"1"=>array(2,6),
	"2"=>array(3,9),
	"3"=>array(4,5,9),
	"4"=>array(5),
	"5"=>array(7,10,8,11)
);

// 쿠폰
$gw_usepart = array(
	"0"=>"전체상품에 사용가능",
	"1"=>"일부 상품만 사용가능",
	"2"=>"일부 카테고리만 사용가능",
	"3"=>"일부 상품에서는 사용불가",
	"4"=>"일부 카테고리에서는 사용불가"
);

$gw_star = array(
	"1"=>"매우불만족",
	"2"=>"불만족",
	"3"=>"보통",
	"4"=>"만족",
	"5"=>"매우만족"
);


//==============================================================================
// 쇼핑몰 배너코드 정보
// array('코드', '가로사이즈',  '세로사이즈', '설명문구')
//------------------------------------------------------------------------------
// pc 배너코드
// 설정예시: $gw_pbanner['스킨명']
$gw_pbanner['basic'] = array(
	array('0',  '1200', '465', '[롤링] 메인 슬라이드'),
	array('1',  '1000',  '70', '[고정] 최상단 배너'),
	array('2',   '160',  '60', '[고정] 상단 > 로고우측'),
	array('3',   '280', '400', '[고정] 메인 > 메인배너 하단 > 좌측'),
	array('4',   '420', '195', '[고정] 메인 > 메인배너 하단 > 가운데 상'),
	array('5',   '420', '195', '[고정] 메인 > 메인배너 하단 > 가운데 하'),
	array('6',  '1000', '200', '[고정] 메인 > 카테고리별 베스트 하단'),
	array('7',  '1920',    '', '[고정] 메인 > 신상품 하단 > 글자입력 배너 (배너 이미지 배경)'),
	array('8',   '480', '290', '[고정] 메인 > 인기상품 하단 > 상단 좌측'),
	array('9',   '200', '290', '[고정] 메인 > 인기상품 하단 > 상단 가운데'),
	array('10',  '690', '200', '[고정] 메인 > 인기상품 하단 > 하단 좌측'),
	array('11',  '300', '500', '[고정] 메인 > 인기상품 하단 > 우측'),
	array('12',  '0', '0', '[고정] 메인 > 메뉴아이콘'),
	array('13',  '1200', '465', '[고정] 상품상세페이지 상단'),
	array('90',   '80',    '', '[연속] 퀵메뉴 좌측'),
	array('100', '410', '410', '[롤링] 인트로 우측')
);

$gw_pbanner['shopping'] = array(
    array('0',  '1000', '400', '[롤링] 메인 슬라이드'),
    array('1',  '1000',  '70', '[고정] 최상단 배너'),
    array('2',   '160',  '60', '[고정] 상단 > 로고좌측'),
    array('3',   '280', '400', '[고정] 메인 > 메인배너 하단 > 좌측'),
    array('4',   '420', '195', '[고정] 메인 > 메인배너 하단 > 가운데 상'),
    array('5',   '420', '195', '[고정] 메인 > 메인배너 하단 > 가운데 하'),
    array('6',  '1000', '200', '[고정] 메인 > 카테고리별 베스트 하단'),
    array('7',  '1920',    '', '[고정] 메인 > 신상품 하단 > 글자입력 배너 (배너 이미지 배경)'),
    array('8',   '480', '290', '[고정] 메인 > 인기상품 하단 > 상단 좌측'),
    array('9',   '200', '290', '[고정] 메인 > 인기상품 하단 > 상단 가운데'),
    array('10',  '690', '200', '[고정] 메인 > 인기상품 하단 > 하단 좌측'),
    array('11',  '300', '500', '[고정] 메인 > 인기상품 하단 > 우측'),
	array('12',  '0', '0', '[고정] 메인 > 메뉴아이콘'),
	array('13',  '1200', '465', '[고정] 상품상세페이지 상단'),
    array('90',   '80',    '', '[연속] 퀵메뉴 좌측'),
    array('100', '410', '410', '[롤링] 인트로 우측')
);

// 모바일 배너코드
// 설정예시: $gw_mbanner['스킨명']
$gw_mbanner['basic'] = array(
	array('0', '960', '720', '[롤링] 메인 슬라이드'),
	array('1', '960', '120', '[고정] 최상단 배너'),
	array('2', '475', '270', '[고정] 메인 > 메인배너 하단 > 상단 좌측'),
	array('3', '475', '270', '[고정] 메인 > 메인배너 하단 > 상단 우측'),
	array('4', '960', '233', '[고정] 메인 > 메인배너 하단 > 하단'),
	array('5', '960', '300', '[고정] 메인 > 카테고리별 베스트 하단'),
	array('6', '960', '300', '[고정] 메인 > 베스트셀러 하단'),
	array('7', '960', '300', '[고정] 메인 > 신상품 하단'),
	array('8', '960', '300', '[고정] 메인 > 인기상품 하단'),
	array('13',  '960', '120', '[고정] 상품상세페이지 상단'),
    array('9', '0', '0', '[고정] 메인 > 메뉴아이콘')
);
$gw_mbanner['shopping'] = array(
    array('0', '960', '720', '[롤링] 메인 슬라이드'),
    array('1', '960', '120', '[고정] 최상단 배너'),
    array('2', '475', '270', '[고정] 메인 > 메인배너 하단 > 상단 좌측'),
    array('3', '475', '270', '[고정] 메인 > 메인배너 하단 > 상단 우측'),
    array('4', '960', '233', '[고정] 메인 > 메인배너 하단 > 하단'),
    array('5', '960', '300', '[고정] 메인 > 카테고리별 베스트 하단'),
    array('6', '960', '300', '[고정] 메인 > 베스트셀러 하단'),
    array('7', '960', '300', '[고정] 메인 > 신상품 하단'),
    array('8', '960', '300', '[고정] 메인 > 인기상품 하단'),
	array('13',  '960', '120', '[고정] 상품상세페이지 상단'),
    array('9', '0', '0', '[고정] 메인 > 메뉴아이콘')
);

$gw_pbanner['paykhan'] = $gw_pbanner['shopping'];
$gw_mbanner['paykhan'] = $gw_mbanner['shopping'];
?>
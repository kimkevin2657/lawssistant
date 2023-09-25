<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/catalog.php?gs_id='.$index_no);
}

$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($index_no);

// 공급업체 정보
$sr = get_seller_cd($gs['mb_id']);
if($gs['use_aff']) {
	$sr = get_partner($gs['mb_id']);
}

// 포인트 적용에 따른 출력형태
if($gs['gpoint'] > 0 && $gs['goods_price'] > 0){
	$rate = number_format((($gs['gpoint'] / $gs['goods_price']) * 100), 0);
	$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>($rate%)</span>";
}

//상품평 건수 구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$index_no'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($index_no);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$index_no' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$index_no'");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$tb['title'] = $gs['gname'];


if( ! Request::isAjax() ) include_once(TB_PATH."/head.sub.php");
include_once(TB_LIB_PATH.'/goodsinfo.lib.php');
Theme::get_theme_part(TB_THEME_PATH,'/catalog.skin.php');

if( ! Request::isAjax() ) include_once(TB_PATH."/tail.sub.php");

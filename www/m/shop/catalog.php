<?php
include_once("./_common.php");

$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($gs_id);

// 공급업체 정보
$sr = get_seller_cd($gs['mb_id']);
if($gs['use_aff']) {
	$sr = get_partner($gs['mb_id']);
}

// 쇼핑포인트 적용에 따른 출력형태
if($gs['gpoint'] > 0 && $gs['goods_price'] > 0){
	$rate = number_format((($gs['gpoint'] / $gs['goods_price']) * 100), 0);
	$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>($rate%)</span>";
}

// 상품문의 건수구하기
$sql = "select count(*) as cnt from shop_goods_qa where gs_id = '$gs_id'";
$itemqa_count = (int)$row['cnt'];

// 구매후기 건수구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$gs_id'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($gs_id);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$gs_id'");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$pg['pagename'] = '상품 상세보기';
include_once(MS_LIB_PATH.'/goodsinfo.lib.php');


$slide_img = array();
for($i=2; $i<=6; $i++) { // 슬라이드 이미지
	$it_image = trim($gs['simg'.$i]);
	if(!$it_image) continue;

	if(preg_match("/^(http[s]?:\/\/)/", $it_image) == false) {
		$file = MS_DATA_PATH."/goods/".$it_image;	
		if(is_file($file)) {
			$slide_img[] = rpc($file, MS_PATH, MS_URL);		
		}
	} else {
		$slide_img[] = $it_image;
	}	
}

$slide_url = implode('|', $slide_img);
$slide_cnt = count($slide_img);

if( ! Request::isAjax() ) include_once(MS_PATH."/head.sub.php");

Theme::get_theme_part(MS_MTHEME_PATH,'/catalog.skin.php');

if( ! Request::isAjax() ) include_once(MS_PATH."/tail.sub.php");
?>
<?php
include_once("./_common.php");

$ss_tx = trim(strip_tags($ss_tx));
if(!$ss_tx) {
	alert('검색어가 넘어오지 않았습니다.');
}

$ms['title'] = '상품 검색 결과';
include_once("./_head.php");

$concat = array();
$concat[] = "a.gname";
$concat[] = "a.gcode";
$concat_fields = "concat(".implode(",' ',",$concat).")";

$sql_search = " and ( $concat_fields like '%$ss_tx%' or find_in_set('$ss_tx', a.keywords) >= 1 ) ";
$sql_common = get_sql_precompose($sql_search);
$sql_order = " group by a.index_no ";

// 상품 정렬
/*
if($sort && $sortodr) {
	$sql_order .= " order by a.{$sort} {$sortodr}, a.index_no desc ";
} else {
	$sql_order .= " order by a.index_no desc ";
}
*/
$sql_order .= " order by a.isnaver desc, a.index_no desc ";

$sql_add = "";

if($member['grade'] != 1){
	$sql_add .= " and a.display_level IN ( '{$member['grade']}', 10) ";
}

if($member['mb_category'] != ""){
	$sql_add .= " and a.category_name IN ( '{$member['mb_category']}', '') ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.* $sql_common {$sql_add} $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

Theme::get_theme_part(MS_MTHEME_PATH,'/search.skin.php');

include_once("./_tail.php");
?>
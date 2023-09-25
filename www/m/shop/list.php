<?php
include_once("./_common.php");

$ms['title'] = get_catename($ca_id);
include_once("./_head.php");

$bestgood = get_catebest($ca_id);

$len = strlen($ca_id);
$sql_search = " and left(b.gcate,$len)='$ca_id' ";
$sql_common = get_sql_precompose($sql_search);
$sql_order  = " group by a.index_no ";

// 상품 정렬
if($sort && $sortodr){
	$sql_order .= " order by a.{$sort} {$sortodr}, a.rank desc, a.index_no desc ";
}else{
	if($len == '3'){
		$sql_order .= " order by b.rank1 desc, a.index_no desc ";
	}elseif($len == '6'){
		$sql_order .= " order by b.rank2 desc, a.index_no desc ";
	}elseif($len == '9'){
		$sql_order .= " order by b.rank3 desc, a.index_no desc ";
	}elseif($len == '12'){
		$sql_order .= " order by b.rank4 desc, a.index_no desc ";
	}elseif($len == '15'){
		$sql_order .= " order by b.rank5 desc, a.index_no desc ";
	}else{
		$sql_order .= " order by a.rank desc, a.index_no desc ";
	}
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt, min(a.index_no) min_no, max(a.index_no) max_no $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if( $total_count == 1 && $row['min_no'] == $row['max_no'] ) {
	// 카테고리 상품이1개일시 상세보기페이지로 보내기
	//goto_url(MS_MSHOP_URL.'/view.php?index_no='.$row['min_no']);
}

$mod = 2; // 가로 출력 수

if($bestgood=="Y") {
  $page_rows = "50";
  $from_record = '0';
  $rows = '50';
  $total_count = '50';
} else {
  $rows = ($mod*9);
}

$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.* $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

Theme::get_theme_part(MS_MTHEME_PATH,'/list.skin.php');

include_once("./_tail.php");
?>

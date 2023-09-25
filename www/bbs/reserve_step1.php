<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/reserve_step1.php');
}

$ms['title'] = '나의 미용실 검색하기';
include_once("./_head.php");
$stx = $_GET['stx'];
$category = $_GET['category'];

$sql_search = " where (1) ";

if($stx){
	$sql_search .= " and a.name like '%{$stx}%' ";
}

if($category){
	$sql_search .= " and a.mb_category = '{$category}' ";

	if($member['grade'] != 1){ //관리자가 아닌경우

		$ch = sql_fetch("SELECT * FROM shop_member where id = '{$member['pt_id']}' ");

		if($ch['mb_category'] == $category){ //추천인의 카테고리랑 현재 카테고리가 같은 경우
			$sql_search .= " and a.pt_id = '{$member['pt_id']}' ";
		}

	}

	$sql_search2 = " where mb_category = '{$category}' ";
}

$sql_common = " from shop_member a INNER JOIN shop_partner b ON a.id = b.mb_id ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.* $sql_common $sql_search $sql_order limit $from_record, $rows ";

//echo $sql;

$result = sql_query($sql);

Theme::get_theme_part(MS_THEME_PATH,'/reserve_step1.skin.php');

include_once("./_tail.php");
?>
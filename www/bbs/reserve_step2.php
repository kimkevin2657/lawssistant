<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/reserve_step2.php');
}

$ms['title'] = '나의 미용실 검색하기';
include_once("./_head.php");
$stx = $_GET['stx'];

$id = $_GET['id'];

$sql_common = " from g5_wzb3_room ";

$sql_search = " where store_mb_id = '{$id}' ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

Theme::get_theme_part(MS_THEME_PATH,'/reserve_step2.skin.php');

include_once("./_tail.php");
?>
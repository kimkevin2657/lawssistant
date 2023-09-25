<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/point.php');
}

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '포인트 조회';
include_once("./_head.php");

$sql_common = " from shop_point ";
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_order  = " order by po_id desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

Theme::get_theme_part(MS_THEME_PATH,'/point.skin.php');

include_once("./_tail.php");
?>
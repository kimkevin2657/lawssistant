<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/minishops.php?type='.$type);
}

$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
$dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y'));
if( !isset($dpLabels[$type] ) ) {
    alert('상품유형이 아닙니다.', MS_URL);
} else {
    $ms['title'] = $dpLabels[$type]['type_label'];
}

include_once("./_head.php");

$sql_search = "";

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by a.{$sort} {$sortodr}, a.rank desc, a.index_no desc ";
else
	$sql_order = " order by a.rank desc, a.index_no desc ";

$res = query_itemtype($pt_id, $type, $sql_search, $sql_order);
$total_count = sql_num_rows($res);


$mod = 4; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$result = query_itemtype($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");

if( $total_count == 1 ) {
    $row = sql_fetch_array($result);
    goto_url(MS_SHOP_URL.'/view.php?index_no='.$row['index_no']);
}

Theme::get_theme_part(MS_THEME_PATH,'/listtype.skin.php');


include_once("./_tail.php");
?>

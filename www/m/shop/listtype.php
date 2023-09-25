<?php
include_once("./_common.php");

//$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
//$dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y'));
//if($type == 1)      $ms['title'] = $dpLabels['1']['type_label'];//$gw_dp_label['q_type1'];//'쇼핑특가';
//else if($type == 2) $ms['title'] = $dpLabels['2']['type_label'];//$gw_dp_label['q_type2'];//'베스트셀러';
//else if($type == 3) $ms['title'] = $dpLabels['3']['type_label'];//$gw_dp_label['q_type3'];//'신상품';
//else if($type == 4) $ms['title'] = $dpLabels['4']['type_label'];//$gw_dp_label['q_type4'];//'인기상품';
//else if($type == 5) $ms['title'] = $dpLabels['5']['type_label'];//$gw_dp_label['q_type5'];//'후원상품';
//else
//    alert('상품유형이 아닙니다.', MS_URL);
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
	$sql_order = " order by a.{$sort} {$sortodr}, a.index_no desc ";
else
	$sql_order = " order by a.index_no desc ";

$res = query_itemtype($pt_id, $type, $sql_search, $sql_order);
$total_count = sql_num_rows($res);

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$result = query_itemtype($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");
if( $total_count == 1 ) {
    $row = sql_fetch_array($result);
    goto_url(MS_MSHOP_URL.'/view.php?index_no='.$row['index_no']);
}
Theme::get_theme_part(MS_MTHEME_PATH,'/listtype.skin.php');

include_once("./_tail.php");
?>

<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$ms['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);

$stotal = get_order_spay($od_id); // 총계
$tot_price = get_session('tot_price'); // 결제금액

$order_action_url = MS_HTTPS_MSHOP_URL.'/orderformresult.php';
Theme::get_theme_part(MS_MTHEME_PATH,'/orderkcp.skin.php');

include_once("./_tail.php");
?>
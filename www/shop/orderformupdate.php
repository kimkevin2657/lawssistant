<?php
include_once("./_common.php");
include_once(MS_LIB_PATH.'/mailer.lib.php');

// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교.
if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
    // 맞으면 세션을 지워 다시 들어오도록 한다.
    set_session("ss_token", "");
} else {
	alert("정상적인 접근이 아닙니다.", MS_URL);
    exit;
}
$rslt = Order::doOrder($_POST, MS_SHOP_URL, false);

//print_r($rslt);

//exit;

$od_id = $rslt['od_id'];
$uid   = $rslt['uid'];


$od = sql_fetch("select * from shop_order where od_id = '$od_id'");
$item = sql_fetch("select * from shop_goods where index_no = '{$od['gs_id']}'"); 

if(in_array($_POST['paymethod'],array('무통장','복지포인트','쇼핑페이','마일리지'))) {

$subject_1 = '입금대기';
$message_1 = $od['name'].'님께서 주문하신 '.$item['gname'].'의 주문 결제가 정상적으로 신청되었습니다.

주문번호 : '.$od['od_id'].'

금액 : '.number_format($od['goods_price']).'

입금계좌 : '.$od['bank'].'

이용해 주셔서 감사합니다.';

	aligo_sms('TE_4913', $od['cellphone'], $od['name'], $subject_1, $message_1);

	goto_url(MS_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
	
} else if($_POST['paymethod'] == 'KAKAOPAY') {

$subject_1 = '입금완료';
$message_1 = $od['name'].'님께서 주문하신 '.$item['gname'].'의 주문 결제가 정상적으로 완료되었습니다.

주문번호 : '.$od['od_id'].'

금액 : '.number_format($od['goods_price']).'

이용해 주셔서 감사합니다.';

	aligo_sms('TE_4896', $od['cellphone'], $od['name'], $subject_1, $message_1);

	goto_url(MS_SHOP_URL.'/orderkakaopay.php?od_id='.$od_id);
} else {

$subject_1 = '입금완료';
$message_1 = $od['name'].'님께서 주문하신 '.$item['gname'].'의 주문 결제가 정상적으로 완료되었습니다.

주문번호 : '.$od['od_id'].'

금액 : '.number_format($od['goods_price']).'

이용해 주셔서 감사합니다.';

	aligo_sms('TE_4896', $od['cellphone'], $od['name'], $subject_1, $message_1);

	if($default['de_pg_service'] == 'kcp')
		goto_url(MS_SHOP_URL.'/orderkcp.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'inicis')
		goto_url(MS_SHOP_URL.'/orderinicis.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'lg')
		goto_url(MS_SHOP_URL.'/orderlg.php?od_id='.$od_id);
    else if($default['de_pg_service'] == 'easypay')
        goto_url(MS_SHOP_URL.'/ordereasypay.php?od_id='.$od_id);
}

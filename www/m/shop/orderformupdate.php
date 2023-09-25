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

ini_set('display_errors',1);
error_reporting(E_ALL^E_NOTICE^E_DEPRECATED^E_WARNING);

$rslt = Order::doOrder($_POST, MS_MSHOP_URL, true);
$od_id = $rslt['od_id'];
$uid   = $rslt['uid'];

if(in_array($_POST['paymethod'],array('무통장','포인트','쇼핑페이', '마일리지'))) {
	goto_url(MS_MSHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
} else if($_POST['paymethod'] == 'KAKAOPAY') {
	goto_url(MS_MSHOP_URL.'/orderkakaopay.php?od_id='.$od_id);
} else if($_POST['paymethod'] == '삼성페이') {
	goto_url(MS_MSHOP_URL.'/orderinicis.php?od_id='.$od_id);
} else {
	if($default['de_pg_service'] == 'kcp')
		goto_url(MS_MSHOP_URL.'/orderkcp.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'inicis')
		goto_url(MS_MSHOP_URL.'/orderinicis.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'lg')
		goto_url(MS_MSHOP_URL.'/orderlg.php?od_id='.$od_id);
    else if($default['de_pg_service'] == 'easypay')
        goto_url(MS_MSHOP_URL.'/ordereasypay.php?od_id='.$od_id);
}

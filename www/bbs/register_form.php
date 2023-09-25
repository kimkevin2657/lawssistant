<?php
include_once("./_common.php");

if($is_member) {
	goto_url(MS_URL);
}

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes'] && $pt_id == encrypted_admin()) {
	alert($config['admin_reg_msg'], MS_URL);
}

// 실명인증 사용중일때
if($default['de_certify_use']) {
	if(get_session("allow") != 'Y')
		alert("정상적인 접근이 아닙니다.", MS_BBS_URL."/register.php");

	$readonly = "readonly style='background-color:#ddd'";

	$sql = " select * from shop_joincheck where j_key='".get_session('j_key')."' ";
	$cert = sql_fetch($sql);

	$cert_name	= $cert['j_name'];
	$cert_year	= substr($cert['j_birthdate'],0,4);
	$cert_month	= substr($cert['j_birthdate'],4,2);
	$cert_day	= substr($cert['j_birthdate'],6,2);
}

$ms['title'] = '회원가입';
include_once("./_head.php"); 

// 불법접근을 막도록 토큰생성
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$gr_id = 'gnb_2';

// 주문폼과 공통 사용을 위해 추가
$od_id = get_uniqid();
set_session('ss_order_id', $od_id);
set_session('ss_order_inicis_id', $od_id);

$goods = '특가닷컴 정회원 가입';

$tot_price = '33000';

$register_action_url = MS_HTTPS_BBS_URL.'/register_form_update.php';
include_once(MS_THEME_PATH.'/register_form.skin.php');

include_once("./_tail.php");
?>
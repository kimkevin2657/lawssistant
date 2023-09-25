<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/register.php');
}

set_session("j_key", "");
set_session("allow", "");

if($is_member) {
	goto_url(MS_URL);
}

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes'] && $pt_id == encrypted_admin()) {
	alert($config['admin_reg_msg'], MS_URL);
}
$ms['title'] = '약관동의';

include_once("./_head.php"); 



// 실명인증 사용시
if($default['de_certify_use']) {
	$regReqSeq = get_session('REQ_SEQ');
	$sql = " delete from shop_joincheck where j_key = '$regReqSeq' ";
	sql_query($sql, FALSE);

	@include_once(MS_PLUGIN_PATH."/chekplus/checkplus_main.php");
	@include_once(MS_PLUGIN_PATH."/chekplus/ipin_main.php");
}

$register_action_url = MS_BBS_URL.'/register_form.php';
include_once(MS_THEME_PATH.'/register.skin.php');

include_once("./_tail.php");
?>
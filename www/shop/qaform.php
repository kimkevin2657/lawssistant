<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/qaform.php?gs_id='.$gs_id);
}

if(!$is_member) {
	alert_close("로그인 후 작성 가능합니다.");
}

$ms['title'] = '상품문의 쓰기';
include_once(MS_PATH."/head.sub.php");

$gs = get_goods($gs_id);

if($w == "") {
	$iq_name	 = $member['name'];
	$iq_email	 = $member['email'];
	$iq_hp		 = replace_tel($member['cellphone']);
}
else if($w == "u") {
	$iq = sql_fetch("select * from shop_goods_qa where iq_id='$iq_id'");
	$iq_ty		 = $iq['iq_ty'];
	$iq_name	 = $iq['iq_name'];
	$iq_email	 = $iq['iq_email'];
	$iq_hp		 = $iq['iq_hp'];
	$iq_subject  = $iq['iq_subject'];
	$iq_question = $iq['iq_question'];
	$iq_secret	 = nl2br($iq['iq_secret']);
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_SHOP_URL.'/qaform_update.php';
Theme::get_theme_part(MS_THEME_PATH,'/qaform.skin.php');

include_once(MS_PATH."/tail.sub.php");
?>
<?php
include_once("./_common.php");

if(!$is_member) { alert_close("로그인 후 작성 가능합니다."); }

if(!$_GET['od_id'] || !$gs_id) { alert_close("정보가 없습니다."); }

$ms['title'] = "구매후기 작성";
include_once(MS_MPATH."/head.sub.php");

$gs = sql_fetch("select * from shop_goods where index_no='$gs_id'");

$rd = get_review($_GET['od_id']);
if($rd['od_id']) { alert_close("해당 주문건에 등록된 리뷰가 있습니다."); }

if($w == "u") {
	$me = sql_fetch("select * from shop_goods_review where index_no='$me_id'");
	$wr_score = $me['score'];
	$wr_content = nl2br($me['memo']);
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_MSHOP_URL.'/orderreview_update.php';
Theme::get_theme_part(MS_MTHEME_PATH,"/orderreview.skin.php");

include_once(MS_MPATH."/tail.sub.php");
?>
<?php
include_once("./_common.php");

if(!$is_member) { alert_close("로그인 후 작성 가능합니다."); }

if(!$_GET['od_id'] || !$gs_id) { alert_close("정보가 없습니다."); }

$gs = get_goods($gs_id);

$rd = get_review($_GET['od_id']);

$ms['title'] = "구매후기 수정";
include_once(MS_MPATH."/head.sub.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = MS_HTTPS_MSHOP_URL.'/orderreview_mod_update.php';
Theme::get_theme_part(MS_MTHEME_PATH,"/orderreview_mod.skin.php");

include_once(MS_MPATH."/tail.sub.php");
?>
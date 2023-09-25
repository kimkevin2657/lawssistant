<?php
include_once('./_common.php');
include_once(MS_LIB_PATH.'/json.lib.php');

set_session('ss_admin_token', '');

$error = admin_referer_check(true);
if($error)
    die(json_encode(array('error'=>$error, 'url'=>MS_URL)));

\App\service\MemberCouponService::pub_coupon($_POST['cp_id'], $_POST['mb_id']);

die(json_encode(array('error'=>'', 'message'=>'발급되었습니다.', 'url'=>'')));
?>

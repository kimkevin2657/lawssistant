<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$mb_id = $_POST['mb_id'];
$pp_pay = $_POST['sp_price'];
$pp_content = $_POST['sp_content'];

$mb = get_member($mb_id, 'id, sp_point');
if(!$mb['id'])
    alert("존재하는 회원아이디가 아닙니다.");

if(($pp_pay < 0) && ($pp_pay * (-1) > $mb['sp_point']))
    alert("쇼핑페이를 차감하는 경우 현재 쇼핑페이보다 작으면 안됩니다.");

insert_shopping_pay($mb_id, $pp_pay, $pp_content, 'passive', $mb_id, $member['id'].'-'.uniqid(''));

goto_url(MS_ADMIN_URL."/minishop.php?$q1&page=$page");
?>
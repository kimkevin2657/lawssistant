<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$mb_id = $_POST['mb_id'];
$pp_pay = $_POST['lp_point'];
$pp_content = $_POST['lp_content'];

$mb = get_member($mb_id, 'id, line_point');
if(!$mb['id'])
    alert("존재하는 회원아이디가 아닙니다.");

if(($pp_pay < 0) && ($pp_pay * (-1) > $mb['line_point']))
    alert("가맹점수 차감하는 경우 현재 가맹점수보다 작으면 안됩니다.");

insert_line_point($mb_id, $pp_pay, $pp_content, 'passive', $mb_id, $member['id'].'-'.uniqid(''));

goto_url(MS_ADMIN_URL."/minishop.php?$q1&page=$page");
?>
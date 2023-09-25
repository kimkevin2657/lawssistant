<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$mb_id = $_POST['mb_id'];
$pp_pay = $_POST['pp_pay'];
$pp_content = $_POST['pp_content'];

$mb = get_member($mb_id, 'id, pay');
if(!$mb['id'])
    alert("존재하는 회원아이디가 아닙니다.");

if(($pp_pay < 0) && ($pp_pay * (-1) > $mb['pay']))
    alert("마일리지를 차감하는 경우 현재 마일리지보다 작으면 안됩니다.");

insert_pay($mb_id, $pp_pay, $pp_content, 'passive', $mb_id, $member['id'].'-'.uniqid(''));

if($_POST['pp_minishop']=="Y"){
	Order::insertSalePay2($mb_id, $pp_pay, $pp_content);
}

goto_url(MS_ADMIN_URL."/minishop.php?$q1&page=$page");
?>
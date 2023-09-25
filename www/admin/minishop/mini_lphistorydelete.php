<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    // 수수료 내역정보
    $sql = " select * from shop_minishop_line_point where lp_id = '{$_POST['lp_id'][$k]}' ";
    $row = sql_fetch($sql);

    if(!$row['lp_id'])
        continue;

    if($row['lp_point'] < 0) {
        $mb_id = $row['mb_id'];
        $lp_point = abs($row['lp_point']);

		delete_use_shopping_pay($mb_id, $lp_point);
    } else {
        if($row['lp_use_point'] > 0) {
            insert_use_shopping_pay($row['mb_id'], $row['lp_use_point'], $row['lp_id']);
        }
    }

    // 수수료 내역삭제
    $sql = " delete from shop_minishop_line_point where lp_id = '{$_POST['lp_id'][$k]}' ";
    sql_query($sql);

    // lp_balance에 반영
    $sql = " update shop_minishop_line_point
                set lp_balance = lp_balance - '{$row['lp_point']}'
              where mb_id = '{$_POST['mb_id'][$k]}'
                and lp_id > '{$_POST['lp_id'][$k]}' ";
    sql_query($sql);

    // 수수료 UPDATE
    $sum_pay = get_line_point_sum($_POST['mb_id'][$k]);
    $sql = " update shop_member set line_point = '$sum_pay' where id = '{$_POST['mb_id'][$k]}' ";
    sql_query($sql);
}

goto_url(MS_ADMIN_URL."/minishop.php?$q1&page=$page");
?>
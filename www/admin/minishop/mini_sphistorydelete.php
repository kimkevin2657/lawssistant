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
    $sql = " select * from shop_minishop_shopping_pay where sp_id = '{$_POST['sp_id'][$k]}' ";
    $row = sql_fetch($sql);

    if(!$row['sp_id'])
        continue;

    if($row['sp_price'] < 0) {
        $mb_id = $row['mb_id'];
        $sp_price = abs($row['sp_price']);

		delete_use_shopping_pay($mb_id, $sp_price);
    } else {
        if($row['sp_use_price'] > 0) {
            insert_use_shopping_pay($row['mb_id'], $row['sp_use_price'], $row['sp_id']);
        }
    }

    // 수수료 내역삭제
    $sql = " delete from shop_minishop_shopping_pay where sp_id = '{$_POST['sp_id'][$k]}' ";
    sql_query($sql);

    // sp_balance에 반영
    $sql = " update shop_minishop_shopping_pay
                set sp_balance = sp_balance - '{$row['sp_price']}'
              where mb_id = '{$_POST['mb_id'][$k]}'
                and sp_id > '{$_POST['sp_id'][$k]}' ";
    sql_query($sql);

    // 수수료 UPDATE
    $sum_pay = get_shopping_pay_sum($_POST['mb_id'][$k]);
    $sql = " update shop_member set sp_point = '$sum_pay' where id = '{$_POST['mb_id'][$k]}' ";
    sql_query($sql);
}

goto_url(MS_ADMIN_URL."/minishop.php?$q1&page=$page");
?>
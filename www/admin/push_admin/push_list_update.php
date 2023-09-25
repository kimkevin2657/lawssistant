<?php
$sub_menu = "600100";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

//auth_check($sub_menu);

if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

		sql_query("delete from push_data where pu_id = '{$_POST['pu_id'][$k]}'");
    }
}


goto_url('./push_list.php?'.$qstr);
?>

<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;

        $cm_idx = $_POST['cm_idx'][$k];
        
        sql_query("DELETE FROM category_manage where cm_idx = '{$cm_idx}' ");
    }
}

alert("선택된 분류가 삭제되었습니다.", MS_ADMIN_URL."/minishop.php?code=category_manage");
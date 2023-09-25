<?php
include_once("./_common.php");

check_demo();

check_admin_token();
$w = $_POST['w'];
$cm_idx = $_POST['cm_idx'];
$category_name = $_POST['category_name'];

if($w == ""){

    sql_query("INSERT INTO category_manage SET category_name = '{$category_name}', mk_datetime = NOW() ");
    $msg = "분류가 추가되었습니다.";

    //echo "INSERT INTO category_manage SET category_name = '{$category_name}', mk_datetime = NOW() "; 

}else{

    sql_query("UPDATE category_manage SET category_name = '{$category_name}' WHERE cm_idx = '{$cm_idx}' ");
    $msg = "분류가 수정되었습니다.";

}

alert($msg, MS_ADMIN_URL."/minishop.php?code=category_manage");
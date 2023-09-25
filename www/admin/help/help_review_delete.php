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

	$re_idx = trim($_POST['re_idx'][$k]);

	// 삭제
	sql_query("delete from review_list where re_idx = '{$re_idx}' ");
}

goto_url(MS_ADMIN_URL."/help.php?code=review");
?>
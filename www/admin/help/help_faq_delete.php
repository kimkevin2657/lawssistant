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

	$index_no = trim($_POST['index_no'][$k]);

	// 삭제
	$sql = "select memo from shop_faq where index_no='$index_no' ";
	$faq = sql_fetch($sql);
	delete_editor_image($faq['memo']);

	sql_query(" delete from shop_faq where index_no = '$index_no' ");
}

goto_url(MS_ADMIN_URL."/help.php?$q1&page=$page");
?>
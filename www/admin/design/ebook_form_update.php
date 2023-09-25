<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$_POST['title']) 
	alert("제목을 입력하세요.");

unset($value);
$value['title']		= $_POST['title'];
$value['state']		= $_POST['state'];
$value['bpage']		= $_POST['bpage'];
$bpage = $_POST['bpage'];

if($w == "") {
	$value['mb_id'] = encrypted_admin();
	$value['regdate'] = MS_TIME_YMDHIS; //등록일
	insert("shop_ebook", $value);
	$pp_id = sql_insert_id();

	goto_url(MS_ADMIN_URL."/design.php?code=ebook_form&w=u&pp_id=$pp_id");
} else if($w == "u") {
	update("shop_ebook", $value, "where no='$pp_id'");

	goto_url(MS_ADMIN_URL."/design.php?code=ebook_form&w=u&pp_id=$pp_id$qstr&page=$page");
}
?>
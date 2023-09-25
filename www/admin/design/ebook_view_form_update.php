<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$bpage		 = $_POST['bpage'];
$no			 = $_POST['no'];

$upl_dir = MS_DATA_PATH."/ebook";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['bn_file_del']) {
	$upl->del($_POST['bn_file_del']);
	$value['img'] = '';
}
if($_FILES['img']['name']) {
	$value['img'] = $upl->upload($_FILES['img']);
}

$value['con']		 = $_POST['con'];

if($w == "") {
	$value['no']		 = $_POST['no'];
	$value['bpage']		 = $_POST['bpage'];
	insert("shop_ebook_view", $value);
	$pp_id = sql_insert_id();

	goto_url(MS_ADMIN_URL."/design.php?code=ebook_view_form&w=u&bpage=$bpage&no=$no");
} else if($w == "u") {
	update("shop_ebook_view", $value, "where no='$no' and bpage='$bpage'");

	goto_url(MS_ADMIN_URL."/design.php?code=ebook_view_form&w=u&bpage=$bpage&no=$no");
}
?>
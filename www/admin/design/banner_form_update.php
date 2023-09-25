<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = MS_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['bn_file_del']) {
	$upl->del($_POST['bn_file_del']);
	$value['bn_file'] = '';
}
if($_FILES['bn_file']['name']) {
	$value['bn_file'] = $upl->upload($_FILES['bn_file']);
}

$value['mb_id']		= encrypted_admin();
$value['bn_device'] = 'pc';
$value['bn_theme']	= $super['theme'];
$value['bn_code']	= $_POST['bn_code'];
$value['bn_link']	= $_POST['bn_link'];
$value['bn_target'] = $_POST['bn_target'];
$value['bn_width']	= $_POST['bn_width'];
$value['bn_height'] = $_POST['bn_height'];
$value['bn_bg']		= preg_replace("/([^a-zA-Z0-9])/", "", $_POST['bn_bg']);
$value['bn_text']	= $_POST['bn_text'];
$value['bn_use']	= $_POST['bn_use'];
$value['bn_order']	= $_POST['bn_order'];
$value['mb_grade']	= $_POST['mb_grade'];
if($_POST['mb_grade'] == "5"){
	$value['category_name']	= $_POST['category_name'];
}else{
	$value['category_name']	= '';
}
if($w == "") {
	insert("shop_banner", $value);
	$bn_id = sql_insert_id();

	$sql = " select * from shop_minishop where state = '1' order by index_no asc";
	$result = sql_query($sql);
	for($z=0; $rowd=sql_fetch_array($result); $z++) {
		$value['mb_id']		= $rowd['mb_id'];
		insert("shop_banner", $value);
		$new_bn_id = sql_insert_id();

		$file = $upl_dir.'/'.$value['bn_file'];
		if(is_file($file) && $value['bn_file']) {
			$dstfile = $upl_dir.'/'.$new_bn_id.'_'.$value['bn_file'];
			$new_bn_file = basename($dstfile);

			@copy($file, $dstfile);
			@chmod($dstfile, MS_FILE_PERMISSION);

			$sql = " update shop_banner set bn_file = '$new_bn_file' where bn_id = '$new_bn_id' ";
			sql_query($sql);
		}

	}

	goto_url(MS_ADMIN_URL."/design.php?code=banner_form&w=u&bn_id=$bn_id");
} else if($w == "u") {
	update("shop_banner", $value," where bn_id='$bn_id'");

	goto_url(MS_ADMIN_URL."/design.php?code=banner_form&w=u&bn_id=$bn_id$qstr&page=$page");
}
?>
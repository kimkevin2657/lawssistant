<?php
if(!defined('_MALLSET_')) exit;

$nw = sql_fetch("select * from shop_ebook where no='$pp_id'");
   if(!$nw['no'])
      alert("ebook이 존재하지 않습니다.");


$value['title']		= $nw['title'];
$value['state']		= $nw['state'];
$value['bpage']		= $nw['bpage'];
$bpage = $nw['bpage'];
// 복제
$value['mb_id'] = encrypted_admin();
$value['regdate'] = MS_TIME_YMDHIS; //등록일
insert("shop_ebook", $value);
$new_pp_id = sql_insert_id();
unset($value);

$upl_dir = MS_DATA_PATH."/ebook";

$sql = "select * from shop_ebook_view where no='$pp_id' ";
$result = sql_query($sql);
for($z=0; $row=sql_fetch_array($result); $z++) {
$img = '';
$newname = '';
$copyimg = '';
$newfilename = '';
$img_copy = '';
	$img = $upl_dir."/".$row['img'];
	$copyimg = end(explode(".",$row['img']));//확장자

	$newname = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
	$newname = str_shuffle($newname);
	$newname = substr($newname, 0, 30);
	$newfilename = $newname.".".$copyimg;

	$img_copy = $upl_dir."/".$newfilename;
	@copy($img,$img_copy);
	unset($value);
	$value['no']			= $new_pp_id;
	$value['bpage']			= $row['bpage'];
	$value['con']			= $row['con'];
	$value['img']			= $newfilename;
	insert("shop_ebook_view", $value);
}

goto_url(MS_ADMIN_URL."/design.php?code=ebook_list");

?>

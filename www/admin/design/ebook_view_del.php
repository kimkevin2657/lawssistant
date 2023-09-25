<?php
if(!defined('_MALLSET_')) exit;

$nw = sql_fetch("select * from shop_ebook_view where no='$no' and bpage='$bpage'");
if(!$nw['b_no']){ alert("삭제할 값이 존재하지 않습니다."); }

// 이미지 삭제
$upl_dir = MS_DATA_PATH."/ebook";
$upl = new upload_files($upl_dir);
delete_editor_image($nw['con']);
$upl->del($nw['img']);

sql_query("delete from shop_ebook_view where no='$no' and bpage='$bpage'");

$lg = sql_fetch("select * from shop_ebook where no = '{$no}' ");
$num = $lg['bpage'];

for($i=$bpage; $i<$lg['bpage']; $i++)	{
	$crpage = $i + 1;
	sql_query("update shop_ebook_view set bpage='$i' where no='$no' and bpage='$crpage'");
}

goto_url(MS_ADMIN_URL."/design.php?code=ebook_view_list&no=$no");
?>
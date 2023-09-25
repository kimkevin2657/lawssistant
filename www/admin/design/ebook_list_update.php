<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_ebook
					set title = '{$_POST['title'][$k]}',
						bpage = '{$_POST['bpage'][$k]}'
				  where no = '{$_POST['pp_id'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$pp_id = trim($_POST['pp_id'][$k]);

		// 삭제
		$upl_dir = MS_DATA_PATH."/ebook";
		$upl = new upload_files($upl_dir);
		$sql = "select * from shop_ebook_view where no='$pp_id' ";
		$result = sql_query($sql);
		for($z=0; $row=sql_fetch_array($result); $z++) {
			delete_editor_image($row['con']);
			$upl->del($row['img']);
		}

		sql_query("delete from shop_ebook where no='$pp_id'");	
		sql_query("delete from shop_ebook_view where no='$pp_id'");	
	}
}

goto_url(MS_ADMIN_URL."/design.php?$q1&page=$page");
?>
<?php
include_once("./_common.php");

$ss_tx = trim(strip_tags($ss_tx));

if($_POST['hash_token'] && MS_HASH_TOKEN == $_POST['hash_token']) {		
	get_sql_search($ss_tx, $pt_id);

	goto_url(MS_SHOP_URL."/search.php?ss_tx=".urlencode($ss_tx));
} else {
	alert("잘못된 접근 입니다!", MS_URL);
}
?>
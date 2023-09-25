<?php
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');

$sql = " select * from shop_goods WHERE mb_id = 'AP-000172' limit 20000";
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
	$gs_id = '';
	$gs_id = $row['index_no'];
	$sqldel = " delete from shop_goods_option WHERE gs_id='{$gs_id}'";
	$resultdel = sql_query($sqldel);
	$sqlde2 = " delete from shop_goods_cate WHERE gs_id='{$gs_id}'";
	$resultde2 = sql_query($sqlde2);
	$sqldel3 = " delete from shop_goods WHERE index_no='{$gs_id}' and mb_id = 'AP-000172' ";
	$resultdel3 = sql_query($sqldel3);
}

?>
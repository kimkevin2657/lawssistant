<?php
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');
include_once('/home/owner/www/lib/famiwel.lib.php');

$sql = " select * from shop_order where famiwel_op_no != '' and (famiwel_res_cd != '0000' OR famiwel_res_cd IS NULL) and dan = '2'";
//$sql = " select * from famiwel_shop_list where gid = '15403695671100'";

//echo $sql;
//echo "<br>";
//exit;
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
$od_id = '';
$od_id = $row['od_id'];
//ECHO $od_id;
echo "<br>";
	// 주문완료 데이터전송
	echo famiwel_order_send($od_id);

}
?>
<?php
define('TB_IS_ADMIN', true);
include_once('./_common.php');
// 옵션 가격 재조정 프로그램 2022-05-09
$sql = " select * from shop_goods where use_rc = '0' and famiwel_seller_id IS NOT NULL order by gcode asc limit 5000";
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {
	$rowd['supply_price'] = str_replace(',','',$rowd['supply_price']);

	// 테이블의 전체 레코드수만 얻음
	$sql = " SELECT COUNT(*) AS cnt FROM shop_goods_option WHERE gs_id = '{$rowd['index_no']}' ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	if($total_count > '0'){
		if($total_count == '1'){
			$op = sql_fetch(" SELECT * FROM shop_goods_option WHERE gs_id = '{$rowd['index_no']}'"); //db에 등록되었었는지 여부 검사
				$io_supply_price = '0';
				$io_price = '0';
				$sqlcc = "update shop_goods_option set io_price = '0', io_supply_price ='0' where gs_id = '{$op['gs_id']}'";
				$resultcc = sql_query($sqlcc);
				$sqlbb = "update shop_goods set use_rc = '1' where index_no='{$rowd['index_no']}'";
				$resultbb = sql_query($sqlbb);
		}else{
			$sql_w = " SELECT * FROM shop_goods_option WHERE gs_id = '{$rowd['index_no']}'";
			$result_w = sql_query($sql_w);
			for($c=0; $rowa=sql_fetch_array($result_w); $c++) {


			}
				$sqlbb = "update shop_goods set use_rc = '2' where index_no='{$rowd['index_no']}'";
				$resultbb = sql_query($sqlbb);
		}
	}else{
				$sqlbb = "update shop_goods set use_rc = '3' where index_no='{$rowd['index_no']}'";
				$resultbb = sql_query($sqlbb);
	}
}
//echo "<meta http-equiv='refresh' content='1'>";
?>
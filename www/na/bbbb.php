<?
define('TB_IS_ADMIN', true);
include_once('./_common.php');
$sql = " select * from na_aaa where user_d = '0' order by gcode asc limit 5000";
//$sql = " select * from na_aaa where gcode = '15163392931500'";
//echo $sql;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {
	$index_no = '';
	$index_no['index_no'] = '';
	$rowd['price'] = str_replace(',','',$rowd['price']);
//	echo $rowd['price'];
//	exit;
	//echo " select index_no from shop_goods where gcode='{$rowd['gcode']}' limit 1";


	// 테이블의 전체 레코드수만 얻음
	$sql = " SELECT COUNT(*) AS cnt FROM shop_goods WHERE gcode = '{$rowd['gcode']}' ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	if($total_count > '0'){
		$index_no = sql_fetch(" SELECT * FROM shop_goods WHERE gcode = '{$rowd['gcode']}'"); //db에 등록되었었는지 여부 검사
		$sqlaa = "update shop_goods set goods_price = '{$rowd['price']}' where gcode = '{$rowd['gcode']}'";
		$resultaa = sql_query($sqlaa);
		$sqlcc = "update shop_goods_option set io_price = '0' where gs_id = '{$index_no['index_no']}'";
		$resultcc = sql_query($sqlcc);
		$sqlbb = "update na_aaa set user_d = '2' where no='{$rowd['no']}'";
		$resultbb = sql_query($sqlbb);

	}else{
			$sqlbb = "update na_aaa set user_d = '3' where no='{$rowd['no']}'";
			$resultbb = sql_query($sqlbb);
	}
}

echo "222";
?>
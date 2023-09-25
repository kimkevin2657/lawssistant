<?
include_once('./_common.php');
$sql = " select * from shop_goods_cate_20220105_back where user_d = '0' order by index_no asc limit 10000";
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {
	$index_no = '';
	$index_no['index_no'] = '';
	//echo " select index_no from shop_goods where gcode='{$rowd['gcode']}' limit 1";
	// 테이블의 전체 레코드수만 얻음
	$sql = " SELECT COUNT(*) AS cnt FROM shop_goods_cate_20220105_back WHERE gs_id = '{$rowd['gs_id']}' ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	if($total_count > '1'){

		$index_no = sql_fetch(" SELECT index_no FROM shop_goods_cate_20220105_back WHERE gs_id = '{$rowd['gs_id']}' order by index_no desc limit 1"); //db에 등록되었었는지 여부 검사
		if($index_no['index_no']){
			$sqlaa = "delete from shop_goods_cate_20220105_back where index_no = '{$index_no['index_no']}'";
			$resultaa = sql_query($sqlaa);
			$sqlbb = "update shop_goods_cate_20220105_back set user_d = '2' where index_no='{$rowd['index_no']}'";
			$resultbb = sql_query($sqlbb);
		}

	}else{
			$sqlbb = "update shop_goods_cate_20220105_back set user_d = '3' where index_no='{$rowd['index_no']}'";
			$resultbb = sql_query($sqlbb);
	}
}

echo "222";
?>
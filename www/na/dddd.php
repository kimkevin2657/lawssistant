<?
include_once('./_common.php');
$sql = " select * from na_ccc where gs_id is NULL order by gcode asc limit 5000";
//echo $sql;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {
	$index_no = '';
	//echo " select index_no from shop_goods where gcode='{$rowd['gcode']}' limit 1";
	$index_no = sql_fetch(" select index_no from shop_goods where gcode='{$rowd['gcode']}' limit 1"); //db에 등록되었었는지 여부 검사
	$index_no = $index_no['index_no'];
	if($index_no != ''){
		$sqlaa = "update na_ccc set gs_id = '{$index_no}' where no='{$rowd['no']}'";
		$resultaa = sql_query($sqlaa);
			// 테이블의 전체 레코드수만 얻음
			$sql = " SELECT COUNT(*) AS cnt FROM shop_goods_cate WHERE gs_id = '{$index_no}' ";
			$row = sql_fetch($sql);
			$total_count = $row['cnt'];
			if($total_count > '1'){
				$index_noc = sql_fetch(" SELECT index_no FROM shop_goods_cate WHERE gs_id = '{$index_no}' order by index_no asc limit 1"); //db에 등록되었었는지 여부 검사
					if($index_noc['index_no']){
						$sqlbb = "delete from shop_goods_cate where index_no != '{$index_noc['index_no']}' and gs_id = '{$index_no}'";
						$resultbb = sql_query($sqlbb);
					}
			}
	}
}

?>
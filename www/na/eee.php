<?
include_once('./_common.php');
$sql = " select * from na_ccc where user_d = '0' order by gcode asc limit 5000";
//$sql = " select * from na_ddd where gs_id = '13011'";
//echo $sql;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {
	$index_no = '';
	$index_no['index_no'] = '';
	//echo " select index_no from shop_goods where gcode='{$rowd['gcode']}' limit 1";
	$index_no = sql_fetch(" select index_no from shop_goods_cate where gcate='{$rowd['gcate']}' and gs_id = '{$rowd['gs_id']}' limit 1"); //db에 등록되었었는지 여부 검사
	echo $rowd['gcate'];
	echo "/";
	echo $index_no['index_no'];
	echo "<br>";
	if(!$index_no['index_no']){
		$sqlaa = "update na_ccc set user_d = '1' where no='{$rowd['no']}'";
		$resultaa = sql_query($sqlaa);
		$sqlbb = "insert into shop_goods_cate(gcate,gs_id) values('{$rowd['gcate']}','{$rowd['gs_id']}')";
		$resultaa = sql_query($sqlbb);
	}else{
		$sqlaa = "update na_ccc set user_d = '2' where no='{$rowd['no']}'";
		$resultaa = sql_query($sqlaa);
	}

}

echo "222";
?>
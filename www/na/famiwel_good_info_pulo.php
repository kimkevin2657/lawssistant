<?php
exit;
// 파미웰 디비 이용해서 마진율 계산후 판매가 재등록하기
header("Content-Type: text/html; charset=UTF-8");
include_once('./_common.php');
//$row3 = sql_fetch("select count(*) as cnt from shop_goods where use_r = '1'"); //db에 등록되었었는지 여부 검사
//echo $row3['cnt'];
$sql = " select * from shop_goods where use_r = '1' limit 10000";
//$sql = " select * from shop_goods where gcode = '16122289269400'";

//echo $sql;
//echo "<br>";
//exit;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {

	$d_gid = $rowd['gcode'];

//	echo "select * from tb_goods where gid='$d_gid'";
//	exit;

		$row2 = sql_fetch("select * from tb_goods where gid='$d_gid'"); //db에 등록되었었는지 여부 검사
		if(!$row2['gid']){
			unset($value);
			$value['use_r']					= 3;
			update("shop_goods", $value," where gcode = '{$d_gid}'");
			echo "<meta http-equiv='refresh' content='1'>";
			exit;
		}
//		echo $row2['supply_price'];
$goods_price = '';
$supply_price = '';
$supply_price = $row2['supply_price'];
			$dl_margin='5'; //업체마진율

		if($dl_margin > 0){
			$ea = "0.";
			$dl_margin = $ea.(100 - $dl_margin);

			$supply_price = $row2[supply_price]/$dl_margin;
			$supply_price = $supply_price/10;
			$supply_price = ceil($supply_price);
			$supply_price = $supply_price*10;
		}else{
			$supply_price = $row2[supply_price];
		}

### 공급가 완성
$goods_price  = conv_number(ceil($supply_price/((100 - 10)/100)/10)*10);
$notax = '';
	$tax	 = trim(strtolower($row2['tax'])); //gcode값
//	echo $tax;

	if($tax == '2'){
		$notax = '0';
	}else{
		$notax = '1';
	}

### 판매가 완성
unset($value);
			$value['notax'] = $notax;
			$value['supply_price'] = $supply_price;
			$value['goods_price'] = $goods_price;
			$value['use_r']					= 2;
			update("shop_goods", $value," where gcode = '{$d_gid}'");
}


$row3 = sql_fetch("select count(*) as cnt from shop_goods where use_r = '1'"); //db에 등록되었었는지 여부 검사
echo $row3['cnt'];
?>
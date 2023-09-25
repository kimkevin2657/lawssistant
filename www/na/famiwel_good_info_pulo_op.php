<?php
exit;
// 파미웰 디비 이용해서 마진율 계산후 판매가 재등록하기
header("Content-Type: text/html; charset=UTF-8");
include_once('./_common.php');

$sql = " select * from shop_goods_option where use_r = '1' and io_famiwel_no != '' limit 5000";
//$sql = " select * from famiwel_shop_list where gid = '15403695671100'";

//echo $sql;
//echo "<br>";
//exit;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {

	$io_famiwel_no = $rowd['io_famiwel_no'];

//	echo "select * from tb_goods where gid='$d_gid'";
//	exit;

		$row2 = sql_fetch("select * from na_option where no='$io_famiwel_no'"); //db에 등록되었었는지 여부 검사
		if(!$row2['no']){
			unset($value);
			$value['use_r']					= 3;
			update("shop_goods_option", $value," where io_famiwel_no = '{$io_famiwel_no}'");
			echo "<meta http-equiv='refresh' content='1'>";
			exit;
		}
//		echo $row2['supply_price'];
$op_price = '';
$op_supply_price = '';
$op_supply_price = $row2['op_supply_price'];
			$dl_margin='5'; //업체마진율

		if($dl_margin > 0){
			$ea = "0.";
			$dl_margin = $ea.(100 - $dl_margin);

			$op_supply_price = $row2[op_supply_price]/$dl_margin;
			$op_supply_price = $op_supply_price/10;
			$op_supply_price = ceil($op_supply_price);
			$op_supply_price = $op_supply_price*10;
		}else{
			$op_supply_price = $row2[op_supply_price];
		}

### 공급가 완성
$op_price  = conv_number(ceil($op_supply_price/((100 - 10)/100)/10)*10);
### 판매가 완성
unset($value);
			$value['io_supply_price'] = $op_supply_price;
			$value['io_price'] = $op_price;
			$value['use_r']					= 2;
			update("shop_goods_option", $value," where io_famiwel_no = '{$io_famiwel_no}'");
}

$row3 = sql_fetch("select count(*) as cnt from shop_goods_option where use_r = '1' and io_famiwel_no != ''"); //db에 등록되었었는지 여부 검사
echo $row3['cnt'];
?>
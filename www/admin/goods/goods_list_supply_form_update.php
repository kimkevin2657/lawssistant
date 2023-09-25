<?php
include_once("./_common.php");
	function getMicrotime()
	{
		$time=microtime();
		$time=explode(' ',$time);
		$time=$time[0]+$time[1];
		$time=str_replace('.','',$time);
		while(strlen($time)<14) $time.="0";
		return $time;
	}
ECHO $w;
EXIT;

if($w == "") {
$gid = '';
$gid=getMicrotime();
for($i=0; $i<sizeof($to); $i++){
unset($value);
$value['sunbun_c'] = $i;
$value['title']		 = $_POST['title'];
$value['tablecode_c'] = $to[$i];
$tablename_c = '';
		switch ($to[$i]) {
		  case "gcode":
			$tablename_c = "상품코드[필수]";
			break;
		  case "gname":
			$tablename_c = "상품명[필수]";
			break;
		  case "explan":
			$tablename_c = "짧은설명";
			break;
		  case "keywords":
			$tablename_c = "키워드";
			break;
		  case "shop_state":
			$tablename_c = "승인상태";
			break;
		  case "isopen":
			$tablename_c = "판매여부";
			break;
		  case "normal_price":
			$tablename_c = "시중가격";
			break;
		  case "supply_price":
			$tablename_c = "공급가격";
			break;
		  case "goods_price":
			$tablename_c = "판매가격";
			break;
		  case "simg1":
			$tablename_c = "1번 이미지url";
			break;
		  case "simg2":
			$tablename_c = "2번 이미지url";
			break;
		  case "maker":
			$tablename_c = "제조사";
			break;
		  case "origin":
			$tablename_c = "원산지";
			break;
		  case "model":
			$tablename_c = "모델명";
			break;
		  case "notax":
			$tablename_c = "과세유무";
			break;
		  case "reg_time":
			$tablename_c = "상품등록일시";
			break;
		  case "memo":
			$tablename_c = "상세설명";
			break;
		  case "admin_memo":
			$tablename_c = "관리자메모";
			break;
		  case "update_time":
			$tablename_c = "상품수정일시";
			break;
		  case "brand_nm":
			$tablename_c = "브랜드명";
			break;
		  case "sc_type":
			$tablename_c = "배송비유형";
			break;
		  case "sc_minimum":
			$tablename_c = "조건배송비";
			break;
		  case "sc_amt":
			$tablename_c = "기본배송비";
			break;
		}
$value['tablename_c'] =$tablename_c;
$value['muc_code'] =$gid;
insert("shop_down_excel", $value);
}


	goto_url(MS_ADMIN_URL."/goods.php?code=list_supply");

} else if($w == "u") {
	$no		 = $_POST['no'];
	unset($value);
	$value['title']		 = $_POST['title'];
	$value['gcode'] = $_POST['gcode'];
	$value['gname']	 = $_POST['gname'];
	$value['explan']	 = $_POST['explan'];
	$value['keywords']	 = $_POST['keywords'];
	$value['shop_state']	 = $_POST['shop_state'];
	$value['isopen']	 = $_POST['isopen'];
	$value['normal_price']	 = $_POST['normal_price'];
	$value['supply_price']	 = $_POST['supply_price'];
	$value['goods_price']	 = $_POST['goods_price'];
	$value['simg1']	 = $_POST['simg1'];
	$value['simg2']	 = $_POST['simg2'];
	$value['maker']	 = $_POST['maker'];
	$value['origin']	 = $_POST['origin'];
	$value['model']	 = $_POST['model'];
	$value['notax']	 = $_POST['notax'];
	$value['reg_time']	 = $_POST['reg_time'];
	$value['memo']	 = $_POST['memo'];
	$value['admin_memo']	 = $_POST['admin_memo'];
	$value['update_time']	 = $_POST['update_time'];
	$value['brand_nm']	 = $_POST['brand_nm'];
	$value['sc_type']	 = $_POST['sc_type'];
	$value['sc_minimum']	 = $_POST['sc_minimum'];
	$value['sc_amt']	 = $_POST['sc_amt'];
	update("shop_down_excel", $value, "where no='$no'");

	goto_url(MS_ADMIN_URL."/goods.php?code=list_supply_form&w=u&no=$no");
} else if($w == "d") {
$$muc_code = $_GET['muc_code'];
echo "delete from shop_down_excel where muc_code='$muc_code'";
exit;
	sql_query("delete from shop_down_excel where muc_code='$muc_code'");

	goto_url(MS_ADMIN_URL."/goods.php?code=list_supply");
}
?>
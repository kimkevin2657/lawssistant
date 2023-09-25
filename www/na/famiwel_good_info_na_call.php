<?php
#####################################################
# 2022년 1월24일 - 공급사 대기상품으로 넘겨주는 작업
##########
#####################################################
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');
$url = 'https://famiwel.co.kr/_prozn/_system/connect_data/good_info_json.php'; //접속할 url 입력
function han ($s) { return @reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return @preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
$post_data["company"] = "blingbeauty"; //업체아이디
$post_data["pass"] = "blingbeauty@user@"; // 업체비밀번호

//$regdate = sql_fetch("select regdate from famiwel_shop_list order by regdate desc limit 0,1"); //db에서 상품가져오기
//$regdate = $regdate['regdate'];

$sql = " select * from famiwel_shop_list where use_r = '1' order by server_update_date asc limit 200";
//$sql = "select * from shop_goods where opt_subject = '' and mb_id = 'AP-000172' order by reg_time asc limit 100";
//$sql = " select * from famiwel_shop_list where gid = '15403695671100'";

//echo $sql;
//echo "<br>";
//exit;
$result = sql_query($sql);
for($z=0; $rowd=sql_fetch_array($result); $z++) {

	$d_gid = $rowd['gid'];
//	$d_gid = $rowd['gcode'];

		$sqlt = " select count(*) as cnt from shop_goods_del where gid = '$d_gid'";
		$rowt = sql_fetch($sqlt);
		if($rowt['cnt'] > '0') {

			$sqlaa = "delete from famiwel_shop_list where gid = '$d_gid'";
			$resultaa = sql_query($sqlaa);
			echo "<meta http-equiv='refresh' content='1'>";
			exit;

		}

$post_data["gid"] = '';
	$post_data["gid"] = $rowd['gid']; // 상품고유값 전달
//	$post_data["gid"] = $rowd['gcode']; // 상품고유값 전달
	$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");

	$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
	 
	curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
	curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
	curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
	$res = curl_exec ($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($res, 0, $header_size);
	$json = substr($res, $header_size); 
	$row = '';
	$row = json_decode($json, true);
	//$row['category_name'];

if(!$row){
	$sql = "delete from famiwel_shop_list where gid = '$d_gid'";
	$result = sql_query($sql);
			echo "<meta http-equiv='refresh' content='1'>";
	exit;
}
if($row['result_code'] == '5'){ // 파미웰에서 삭제한 상품 지우기
	$sql = "delete from famiwel_shop_list where gid = '$d_gid'";
	$result = sql_query($sql);
			echo "<meta http-equiv='refresh' content='1'>";
	exit;
}




	$catecode = '';
	$op_count = '';
	$gid = '';
	$tax = '';
	$notax = '';
	$sc_type = '';
	$sc_each_use = '';
	$catecode = sql_fetch(" select catecode from shop_cate where famiwel_ca_id='{$row['category']}' limit 1"); //db에 등록되었었는지 여부 검사
	$catecode = $catecode['catecode'];
	if(!$catecode){
		$catecode = sql_fetch(" select catecode from shop_cate where catename='{$row['category_name']}' limit 1"); //db에 등록되었었는지 여부 검사
		$catecode = $catecode['catecode'];
		if(!$catecode){
			$catecode = sql_fetch(" select catecode from shop_cate where catename like '%{$row['category_name']}%' limit 1"); //db에 등록되었었는지 여부 검사
			$catecode = $catecode['catecode'];
		}
	}
	//echo $catecode['catecode'];
	//exit;
	$row['brand']					= addslashes(trim($row['brand']));
	$br_id = sql_fetch(" select br_id from shop_brand where br_name='{$row['brand']}' limit 1"); //db에 등록되었었는지 여부 검사
	$regdater = date("Y-m-d H:i:s");
	if(!$br_id['br_id']){
		unset($value);
//		$value['mb_id']					= "admin";
//		$value['br_name']				= $row['brand'];
//		$value['br_user_yes']			= 0;
//		$value['br_time']				= $regdater;
//		$value['br_updatetime']			= $regdater;
//		insert("shop_brand", $value);
//		$br_id = sql_insert_id();		// 2022-02-17 브랜드 등록 안되게 처리요청
	}else{
//		$br_id =$br_id['br_id'];
	}

	$op_count	 = (int)trim(strtolower($row['op_count'])); //총 상품수
//	echo $op_count;
	$gid	 = (int)trim(strtolower($row['gid'])); //gcode값
	$tax	 = trim(strtolower($row['tax'])); //gcode값
	if($tax == "Y"){
		$notax = '0';
	}else{
		$notax = '1';
	}
	$isFreeShipping	 = trim(strtolower($row['isFreeShipping'])); //gcode값
	if($isFreeShipping == "Y"){
		$sc_type = '1';
	}else{
		$sc_type = '3';
	}

	$bindShipping	 = trim(strtolower($row['bindShipping'])); //묶음배송가능여부
	if($bindShipping == "Y"){
		$sc_each_use = '0';
	}else{
		$sc_each_use = '1';
	}

	if($catecode){	
//$c_supply_price = ceil($row['supply_price']/((100 - 15)/100)/10)*10;
$c_supply_price = ceil($row['supply_price']/((100 - 27)/100)/10)*10;
		$row2 = sql_fetch(" select * from shop_goods where gcode='$gid' "); //db에 등록되었었는지 여부 검사
		if(!$row2['index_no']){
			unset($value);
		if($row['productState'] == 'soldout'){ // 파미웰에서 삭제한 상품 지우기
			$value['isopen']				= '4';
		}else{
			$value['isopen']				= '1';
		}
		
			$value['shop_state']				= '1'; // 1월 24 추가 승인상태 0: 승인 1: 승인대기
			$value['gcode']						= $row['gid'];
			$value['mb_id']						= "AP-000172";
			$value['famiwel_seller_id']			= $row['seller_id'];
			$value['gname']						= addslashes(trim($row['gname']));
			$value['keywords']					= $row['search_key'];
			$value['normal_price']				= $row['market_price'];
			$value['supply_price']				= $row['op_products'][0]['op_supply_price'];
			$value['goods_price']				= $c_supply_price;
			$value['opt_subject']				= "";
			$value['simg_type']					= 1;
			$value['simg1']						= $row['img'];
			$value['simg2']						= $row['img'];
			$value['maker']						= addslashes(trim($row['makec']));
			$value['origin']					= addslashes(trim($row['contury']));
			$value['model']						= addslashes(trim($row['model']));
			$value['notax']						= $notax;
			$value['memo']						= addslashes(trim($row['cont']));
			$value['repair']					= "상품상세설명참조";
//			$value['brand_uid']					= $br_id; //2022-02-17 브랜드값 안넘어오게 조치
//			$value['brand_nm']					= addslashes(trim($row['brand']));
			$value['sc_type']					= $sc_type;
			$value['sc_method']					= $row['deliv_sunbul'];
			$value['sc_minimum']				= $row['jogunprice'];
			$value['sc_amt']					= $row['shippingCharge'];
			$value['sc_each_use']				= $sc_each_use;
			$value['zone']						= "전국";
			$value['zone_msg']					= "도서산간지역 추가배송료청구";
			$value['buy_level']					= 7;
			$value['buy_only']					= 1;
			$value['isnaver']					= 0; //2022-02-17 네이버 미노출로 처리
			$value['point_pay_allow']					= 1;
			$value['zone_msg']					= "도서산간지역 추가배송료청구";
			$value['reg_time']					= $regdater;
			$value['update_time']				= $regdater;
			insert("shop_goods", $value);
			$index_no = sql_insert_id();

			unset($value);
			$value['gcate']					= $catecode;
			$value['gs_id']					= $index_no;
			insert("shop_goods_cate", $value);
			unset($value);
			$value['use_r']					= 2;
			update("famiwel_shop_list", $value," where gid = '{$gid}'");


unset($temp_option);
unset($temp_supply_price);
unset($temp_goods_price);
unset($pricepice);
unset($supply_pricepice);
$del_sql = " delete from shop_goods_option where gs_id = '$index_no'";
$del_result = sql_query($del_sql);

		for($i = 0; $i < $op_count; $i++){ // 판매가 가장 낮은 가격 책정하기
			if(!$temp_goods_price){
				$temp_goods_price  = conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 27)/100)/10)*10);
			}else{
				$pricepice = '';
				$pricepice = ceil($row['op_products'][$i]['op_supply_price']/((100 - 27)/100)/10)*10;
				if($temp_goods_price > $pricepice){
					$temp_goods_price  = conv_number($pricepice);
				}			
			}
			if(!$temp_supply_price){
				$temp_supply_price  = conv_number($row['op_products'][$i]['op_supply_price']);
			}else{
				$supply_pricepice = '';
				$supply_pricepice = $row['op_products'][$i]['op_supply_price'];
				if($temp_supply_price > $supply_pricepice){
					$temp_supply_price  = conv_number($supply_pricepice);
				}			
			}
		}

		for($i = 0; $i < $op_count; $i++){

					$opt1_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_01']))));
					$opt2_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_02']))));
					$opt3_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_03']))));
					$opt4_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_04']))));
					$opt5_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_05']))));

					$arr_subj = array();
					if($opt1_subject) $arr_subj[] = $opt1_subject;
					if($opt2_subject) $arr_subj[] = $opt2_subject;
					if($opt3_subject) $arr_subj[] = $opt3_subject;
					if($opt4_subject) $arr_subj[] = $opt4_subject;
					if($opt5_subject) $arr_subj[] = $opt5_subject;
					$it_option_subject = implode(',', $arr_subj);

					if(!$it_option_subject)
						continue;

					$opt1_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_01']))));
					$opt2_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_02']))));
					$opt3_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_03']))));
					$opt4_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_04']))));
					$opt5_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_05']))));

					$arr_val = array();
					if($opt1_val) $arr_val[] = $opt1_val;
					if($opt2_val) $arr_val[] = $opt2_val;
					if($opt3_val) $arr_val[] = $opt3_val;
					if($opt4_val) $arr_val[] = $opt4_val;
					if($opt5_val) $arr_val[] = $opt5_val;
					$opt_id = implode(chr(30), $arr_val);

					if(!$opt_id)
						continue;
					if(!$temp_option) {
						$temp_option = $it_option_subject;
					}



			unset($value);
			$value['io_famiwel_no']			= $row['op_products'][$i]['no'];
			$value['io_id']					= $opt_id;
			$value['io_type']				= 0;
			$value['gs_id']					= $index_no;
			$value['io_supply_price']		= conv_number($row['op_products'][$i]['op_supply_price']);
			$value['io_price']				= conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 27)/100)/10)*10);
			$value['io_stock_qty']			= conv_number($row['op_products'][$i]['op_jego']);
			$value['io_noti_qty']			= 0;
			$value['io_use']				= 1;
			$value['io_famiwel_use']		= $regdater;
			insert("shop_goods_option", $value);
		}

				if($temp_option) {
					unset($value);
					$value['opt_subject']	= $temp_option; //상품 선택옵션
					$value['goods_price']	= $temp_goods_price; //판매가격
					$value['supply_price']	= $temp_supply_price; //공급가격
					update("shop_goods", $value," where index_no = '$index_no'");
					sql_query(" update shop_goods_option set io_price=io_price-$temp_goods_price where gs_id = '$index_no' ");
					sql_query(" update shop_goods_option set io_supply_price=io_supply_price-$temp_supply_price where gs_id = '$index_no' ");
				}




		}else{
			unset($value);
		if($row['productState'] == 'soldout'){ // 파미웰에서 삭제한 상품 지우기
			$value['isopen']				= '4';
		}else{
			$value['isopen']				= '1';
		}
			$value['shop_state']				= '1'; // 1월 24 추가 승인상태 0: 승인 1: 승인대기
			$value['gname']						= addslashes(trim($row['gname']));
			$value['keywords']					= $row['search_key'];
			$value['normal_price']				= $row['market_price'];
			$value['supply_price']				= $row['op_products'][0]['op_supply_price'];
			$value['opt_subject'] = '';
			$value['simg_type']					= 1;
			$value['simg1']						= $row['img'];
			$value['simg2']						= $row['img'];
			$value['maker']						= addslashes(trim($row['makec']));
			$value['origin']					= addslashes(trim($row['contury']));
			$value['model']						= addslashes(trim($row['model']));
			$value['notax']						= $notax;
			$value['memo']						= addslashes(trim($row['cont']));
			$value['repair']					= "상품상세설명참조";
			$value['brand_uid']					= $br_id;
			$value['brand_nm']					= addslashes(trim($row['brand']));
			$value['sc_type']					= $sc_type;
			$value['sc_method']					= $row['deliv_sunbul'];
			$value['sc_minimum']				= $row['jogunprice'];
			$value['sc_amt']					= $row['shippingCharge'];
			$value['sc_each_use']				= $sc_each_use;
			$value['zone']						= "전국";
			$value['zone_msg']					= "도서산간지역 추가배송료청구";
			$value['buy_level']					= 7;
			$value['buy_only']					= 1;
			$value['isnaver']					= 0;
			$value['zone_msg']					= "도서산간지역 추가배송료청구";
			$value['update_time']					= $regdater;
			update("shop_goods", $value," where index_no = '{$row2['index_no']}'");
//			unset($value);
//			$value['gcate']					= $catecode;
//			update("shop_goods_cate", $value," where gs_id = '{$row2['index_no']}'");
			unset($value);
			$value['use_r']					= 2;
			update("famiwel_shop_list", $value," where gid = '{$gid}'");


		for($i = 0; $i < $op_count; $i++){
		$opopno = '';
		$opopno = $row['op_products'][$i]['no'];
		$row3 = sql_fetch(" select * from shop_goods_option where io_famiwel_no='$opopno' "); //db에 등록되었었는지 여부 검사

					$opt1_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_01']))));
					$opt2_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_02']))));
					$opt3_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_03']))));
					$opt4_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_04']))));
					$opt5_subject = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_05']))));

					$arr_subj = array();
					if($opt1_subject) $arr_subj[] = $opt1_subject;
					if($opt2_subject) $arr_subj[] = $opt2_subject;
					if($opt3_subject) $arr_subj[] = $opt3_subject;
					if($opt4_subject) $arr_subj[] = $opt4_subject;
					if($opt5_subject) $arr_subj[] = $opt5_subject;
					$it_option_subject = implode(',', $arr_subj);

					if(!$it_option_subject)
						continue;

					$opt1_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_01']))));
					$opt2_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_02']))));
					$opt3_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_03']))));
					$opt4_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_04']))));
					$opt5_val = preg_replace(TB_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_05']))));

					$arr_val = array();
					if($opt1_val) $arr_val[] = $opt1_val;
					if($opt2_val) $arr_val[] = $opt2_val;
					if($opt3_val) $arr_val[] = $opt3_val;
					if($opt4_val) $arr_val[] = $opt4_val;
					if($opt5_val) $arr_val[] = $opt5_val;
					$opt_id = implode(chr(30), $arr_val);

					if(!$opt_id)
						continue;
					if(!$temp_option) {
						$temp_option = $it_option_subject;
					}


			if($row3['io_no']){
				unset($value);
				$value['io_id']					= $opt_id;
				$value['io_type']				= 0;
				$value['gs_id']					= $row2['index_no'];
				$value['io_supply_price']		= conv_number($row['op_products'][$i]['op_supply_price']);
				$value['io_stock_qty']			= conv_number($row['op_products'][$i]['op_jego']);
				$value['io_noti_qty']			= 0;
				$value['io_use']				= 1;
				$value['io_famiwel_use']		= $regdater;
				update("shop_goods_option", $value," where io_famiwel_no = '$opopno'");
			}else{
				unset($value);
				$opp_price = $row2['goods_price'];
				$opp_supply_price = $row2['supply_price'];
				$value['io_famiwel_no']			= $row['op_products'][$i]['no'];
				$value['io_id']					= $opt_id;
				$value['io_type']				= 0;
				$value['gs_id']					= $row2['index_no'];
				$value['io_supply_price']		= conv_number($row['op_products'][$i]['op_supply_price']) - $opp_supply_price;
				$value['io_price']				= conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 27)/100)/10)*10) - $opp_price;
				$value['io_stock_qty']			= conv_number($row['op_products'][$i]['op_jego']);
				$value['io_noti_qty']			= 0;
				$value['io_use']				= 1;
				$value['io_famiwel_use']		= $regdater;
				insert("shop_goods_option", $value);
			}
		}

		$index_no = $row2['index_no'];

				if($temp_option) {
					unset($value);
					$value['opt_subject']	= $temp_option; //상품 선택옵션
					update("shop_goods", $value," where index_no = '$index_no'");
				}

$del_sql = " delete from shop_goods_option where gs_id = '$index_no' and io_famiwel_use != '$regdater'";
$del_result = sql_query($del_sql);

		}


   curl_close($ch);
	}else{
		   	unset($value);
			$value['use_r']					= 3;
			update("famiwel_shop_list", $value," where gid = '{$gid}'");
	}
}
$row4 = sql_fetch(" select count(*) as haha from famiwel_shop_list where use_r='2' "); //db에 등록되었었는지 여부 검사
echo "등록상품수 : ";
echo $row4['haha'];
echo "<br>";
$row5 = sql_fetch(" select count(*) as haha from famiwel_shop_list where use_r='1' "); //db에 등록되었었는지 여부 검사
echo "예정상품수 : ";
echo $row5['haha'];
echo "<br>";
$row6 = sql_fetch(" select count(*) as haha from famiwel_shop_list where use_r='3' "); //db에 등록되었었는지 여부 검사
echo "문제상품수 : ";
echo $row6['haha'];
echo "<br>";
//echo "<meta http-equiv='refresh' content='1'>";
?>
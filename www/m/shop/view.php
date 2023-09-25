<?php
include_once("./_common.php");

$is_seometa = 'it'; // SEO 메타태그
if(!$gs_id){
$gs_id = $index_no;
}
if($member['id']){
	if($gs_id){
		// 기존 장바구니 자료를 먼저 삭제
		$sql = "select * from shop_cart where gs_id='$gs_id' and ct_select='0' and ct_direct='{$member['id']}'";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			 $gradeInfo = sql_fetch("select * from shop_order
					  where od_id = '{$row['od_id']}'
						and od_no = '{$row['od_no']}'
						and gs_id = '{$row['gs_id']}'
						and dan = '0' ");
			if($gradeInfo['od_id']){
				add_io_stock($row['od_no'], $row['od_id']);
			}
			$sql = " delete from shop_order
					  where od_id = '{$row['od_id']}'
						and od_no = '{$row['od_no']}'
						and gs_id = '{$row['gs_id']}'
						and dan = '0' ";
			sql_query($sql, FALSE);
		}
	}
}
$gs = get_goods($gs_id);
if(!$gs['index_no'])
	alert('등록된 상품이 없습니다');
else if(!is_admin() && $gs['shop_state'])
	alert('현재 판매가능한 상품이 아닙니다.');

/*
################# 임시 삽입 ########################
$ser_regdate = date("Y-m-d H",strtotime ("-1 days")); // 기본적으로는 현시간으로부터 2시간전 데이터중 판매중 상품을 가져옵니다. 크론탭으로 항시 데이터를 가져가는 업체라면 이 기능 위주로 쓰게 되겠죠.
//if($gs['update_time'] < $ser_regdate){
	//echo "11";
$regdater = date("Y-m-d H:i:s");
$url = 'http://famiwel.co.kr/_prozn/_system/connect_data/good_info_json.php'; //접속할 url 입력
function han ($s) { return @reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return @preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
$post_data["company"] = "payshop"; //업체아이디
$post_data["pass"] = "payshop!@"; // 업체비밀번호
$post_data["gid"] = '';
	$post_data["gid"] = $gs['gcode']; // 상품고유값 전달
//	echo $gs['gcode'];
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

	$op_count	 = (int)trim(strtolower($row['op_count'])); //총 상품수
	$gid	 = (int)trim(strtolower($row['gid'])); //gcode값
	if($gid){
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
//	echo $op_count;
//echo $row['supply_price'];
unset($temp_option);
unset($temp_supply_price);
unset($temp_goods_price);
unset($pricepice);
unset($supply_pricepice);
			$c_supply_price = '';
			$d_supply_price = '';
			$c_supply_price = ceil($row['supply_price']/((100 - 10)/100)/10)*10;
			//$d_supply_price = ceil($c_supply_price/((100 - 25)/100)/10)*10;

			unset($value);
			$value['gname']						= addslashes(trim($row['gname']));
			$value['keywords']					= $row['search_key'];
			$value['normal_price']				= $row['market_price'];
			$value['supply_price']				= $row['supply_price'];
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
			$value['buy_only']					= 0;
			$value['zone_msg']					= "도서산간지역 추가배송료청구";
			$value['update_time']					= $regdater;
			update("shop_goods", $value," where index_no = '{$index_no}'");
			unset($value);
			$value['use_r']					= 2;
			update("famiwel_shop_list", $value," where gid = '{$gid}'");

$del_sql = " delete from shop_goods_option where gs_id = '{$index_no}'";
$del_result = sql_query($del_sql);

		for($i = 0; $i < $op_count; $i++){ // 판매가 가장 낮은 가격 책정하기
			if(!$temp_goods_price){
				$temp_goods_price  = conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 10)/100)/10)*10);
				//echo $temp_goods_price;
			}else{
				$pricepice = '';
				$pricepice = ceil($row['op_products'][$i]['op_supply_price']/((100 - 10)/100)/10)*10;
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

					$opt1_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_01']))));
					$opt2_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_02']))));
					$opt3_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_03']))));
					$opt4_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_04']))));
					$opt5_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['title_05']))));

					$arr_subj = array();
					if($opt1_subject) $arr_subj[] = $opt1_subject;
					if($opt2_subject) $arr_subj[] = $opt2_subject;
					if($opt3_subject) $arr_subj[] = $opt3_subject;
					if($opt4_subject) $arr_subj[] = $opt4_subject;
					if($opt5_subject) $arr_subj[] = $opt5_subject;
					$it_option_subject = implode(',', $arr_subj);

					if(!$it_option_subject)
						continue;

					$opt1_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_01']))));
					$opt2_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_02']))));
					$opt3_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_03']))));
					$opt4_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_04']))));
					$opt5_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes(rpc($row['op_products'][$i]['op_name_05']))));

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
			unset($io_famiwel_no);
			unset($row3);
			$io_famiwel_no = $row['op_products'][$i]['no'];
			$row3 = sql_fetch(" select io_famiwel_no from shop_goods_option where io_famiwel_no = '$io_famiwel_no'"); //db에 등록되었었는지 여부 검사
			if(!$row3['io_famiwel_no']){
				$value['io_famiwel_no']			= $row['op_products'][$i]['no'];
				$value['io_id']					= $opt_id;
				$value['io_type']				= 0;
				$value['gs_id']					= $index_no;
				$value['io_supply_price']		= conv_number($row['op_products'][$i]['op_supply_price']);
				$value['io_price']				= conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 10)/100)/10)*10);
				$value['io_stock_qty']			= conv_number($row['op_products'][$i]['op_jego']);
				$value['io_noti_qty']			= 0;
				$value['io_use']				= 1;
				$value['io_famiwel_use']		= $regdater;
				insert("shop_goods_option", $value);
			}else{
				$value['io_id']					= $opt_id;
				$value['io_type']				= 0;
				$value['gs_id']					= $index_no;
				$value['io_supply_price']		= conv_number($row['op_products'][$i]['op_supply_price']);
				$value['io_price']				= conv_number(ceil($row['op_products'][$i]['op_supply_price']/((100 - 10)/100)/10)*10);
				$value['io_stock_qty']			= conv_number($row['op_products'][$i]['op_jego']);
				$value['io_noti_qty']			= 0;
				$value['io_use']				= 1;
				$value['io_famiwel_use']		= $regdater;
				update("shop_goods_option", $value," where io_famiwel_no = '$io_famiwel_no'");
			}
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

   curl_close($ch);
	}
//}
################# 임시 삽입 ########################
*/


// 오늘 본 상품 저장 시작
if(get_session('ss_pr_idx')) {
	$arr_ss_pr_idx = get_session('ss_pr_idx');
	$arr_tmps = explode(",",$arr_ss_pr_idx);
	if(!in_array($gs_id,$arr_tmps)) {
		$ss_pr_idx = get_session('ss_pr_idx').",".$gs_id;
		set_session('ss_pr_idx', $ss_pr_idx);
	}
} else {
	$ss_pr_idx = get_session('ss_pr_idx').$gs_id;
	set_session('ss_pr_idx', $ss_pr_idx);
}

// 공급업체 정보
$sr = get_seller_cd($gs['mb_id']);
if($gs['use_aff']) {
	$sr = get_partner($gs['mb_id']);
}




$goods_kv_basic = $gs['goods_kv_basic'];
$gpoint_basic = $gs['gpoint_basic'];

include $_SERVER["DOCUMENT_ROOT"]."/extend/_point_kv.php";


// 상품문의 건수구하기
$sql = "select count(*) as cnt from shop_goods_qa where gs_id = '$gs_id'";
$itemqa_count = (int)$row['cnt'];

// 구매후기 건수구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$gs_id'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($gs_id);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$gs_id'");

// 수량체크
if(!$gs['stock_mod']) {
	$gs['stock_qty'] = 999999999;
}

if($gs['odr_min']) // 최소구매수량
	$odr_min = (int)$gs['odr_min'];
else
	$odr_min = 1;

if($gs['odr_max']) // 최대구매수량
	$odr_max = (int)$gs['odr_max'];
else
	$odr_max = 0;

$is_only = false;
$is_buy_only = false;
$is_pr_msg = false;
$is_social_end = false;
$is_social_ing = false;

// 품절체크
$is_soldout = is_soldout($gs_id);

if($is_soldout) {
	$script_msg = "현재상품은 품절 상품입니다.";
} else {
	if($gs['price_msg']) {
		$is_pr_msg = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
		$is_only = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
		if(!$is_member) {
			$is_buy_only = true;
			$script_msg = "현재상품은 회원만 구매 하실 수 있습니다.";
		} else {
			$script_msg = "현재상품을 구매하실 권한이 없습니다.";
		}
	} else {
		$script_msg = "";
	}

	if(substr($gs['sb_date'],0,1) != '0' && substr($gs['eb_date'],0,1) != '0') {
		if($gs['eb_date'] < MS_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "(판매종료) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매기간이 종료 되었습니다.";

		} else if($gs['sb_date'] > MS_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "(판매대기) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매대기 상품 입니다.";

		} else if($gs['sb_date'] <= MS_TIME_YMD && $gs['eb_date'] >= MS_TIME_YMD) {
			$is_social_ing	= true;

			// 소셜 스크립트
			define('M_TIMESALE', MS_MTHEME_PATH.'/time.skin.php');
		}
	}
}

// 필수 옵션
$option_item = mobile_item_options($gs_id, $gs['opt_subject'], " style='width:100%'");

// 추가 옵션
$supply_item = mobile_item_supply($gs_id, $gs['spl_subject'], " style='width:100%'");

// 가맹점상품은 쿠폰발급안함
if(!$gs['use_aff'] && $config['coupon_yes']) {
	// 쿠폰발급 (회원직접 다운로드)
	$cp_used = is_used_coupon('0', $gs_id);

	if($is_member)
		$cp_btn = "<a href=\"javascript:window.open('".MS_MSHOP_URL."/pop_coupon.php?gs_id=$gs_id','_blank');\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
	else
		$cp_btn = "<a href=\"javascript:alert('로그인 후 이용 가능합니다.')\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
}

// SNS
/*$sns_title = get_text($gs['gname']).' | '.get_head_title('head_title', $pt_id);
$sns_url = MS_SHOP_URL.'/view.php?index_no='.$gs_id;
$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, MS_IMG_URL.'/sns/facebook.gif');
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, MS_IMG_URL.'/sns/twitter.gif');
$sns_share_links .= get_sns_share_link('kakaostory', $sns_url, $sns_title, MS_IMG_URL.'/sns/kakaostory.gif');
$sns_share_links .= get_sns_share_link('naverband', $sns_url, $sns_title, MS_IMG_URL.'/sns/naverband.gif');
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, MS_IMG_URL.'/sns/googleplus.gif');
$sns_share_links .= get_sns_share_link('naver', $sns_url, $sns_title, MS_IMG_URL.'/sns/naver.gif');
$sns_share_links .= get_sns_share_link('pinterest', $sns_url, $sns_title, MS_IMG_URL.'/sns/pinterest.gif'); */

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$pg['pagename'] = '상품 상세보기';
include_once("./_head.php");
include_once(MS_LIB_PATH.'/goodsinfo.lib.php');
//include_once(MS_SHOP_PATH.'/settle_naverpay.inc.php');

$slide_img = array();
for($i=2; $i<=6; $i++) { // 슬라이드 이미지
	$it_image = trim($gs['simg'.$i]);
	if(!$it_image) continue;

	if(preg_match("/^(http[s]?:\/\/)/", $it_image) == false) {
		$file = MS_DATA_PATH."/goods/".$it_image;
		if(is_file($file)) {
			$slide_img[] = rpc($file, MS_PATH, MS_URL);
		}
	} else {
		$slide_img[] = $it_image;
	}
}

$slide_url = implode('|', $slide_img);
$slide_cnt = count($slide_img);

/* 이용재 추가 : 가격비교 */
if($gs['compare']=="Y") {
  if($gs['compare_0']) {
    $compare_0 = explode(">", $gs['compare_0']);
  }
  if($gs['compare_1']) {
    $compare_1 = explode(">", $gs['compare_1']);
  }
  if($gs['compare_2']) {
    $compare_2 = explode(">", $gs['compare_2']);
  }
  if($gs['compare_3']) {
    $compare_3 = explode(">", $gs['compare_3']);
  }
  if($gs['compare_4']) {
    $compare_4 = explode(">", $gs['compare_4']);
  }
  if($gs['compare_5']) {
    $compare_5 = explode(">", $gs['compare_5']);
  }
  if($gs['compare_6']) {
    $compare_6 = explode(">", $gs['compare_6']);
  }
  if($gs['compare_7']) {
    $compare_7 = explode(">", $gs['compare_7']);
  }
  if($gs['compare_8']) {
    $compare_8 = explode(">", $gs['compare_8']);
  }
  if($gs['compare_9']) {
    $compare_9 = explode(">", $gs['compare_9']);
  }
}

Theme::get_theme_part(MS_MTHEME_PATH,'/view.skin.php');

include_once("./_tail.php");
?>
<?php
if(!defined('_MALLSET_')) exit;

/*************************************************************************
**
**  파미웰 api 함수 모음
**
*************************************************************************/

// xml 데이터 파싱
function famiwel_curl($url, $params=array())
{
    $url = $url.'?'.http_build_query($params, '', '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);			 //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //요청 결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);	 //connection timeout 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //원격 서버의 인증서가 유효한지 검사 안함
    $response = curl_exec($ch);
    curl_close($ch);

    return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
}

// 파미웰 주문API 연동
function famiwel_json_curl($url, $params)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

// 파미웰 카테고리번호로 쇼핑몰 카테고리번호 호출
function famiwel_ca_id($ca_id)
{
	$ca_id = trim($ca_id);
	if(!$ca_id) return '';

	$row = sql_fetch(" select * from shop_cate where famiwel_ca_id = '$ca_id' and famiwel_ca_id <> '' ");

	return $row['catecode'];
}

// 파미웰 공급사 만들기
function famiwel_seller($mb_id)
{
	if(!trim($mb_id)) return '';

	$mb_id = 'fmw_'.trim($mb_id);

	$row = get_seller($mb_id);
	if($row['seller_code']) {
		return $row['seller_code'];
	} else {
		unset($mfrm);
		$mfrm['id']				= $mb_id; //회원아이디
		$mfrm['name']			= '파미웰공급사('.$mb_id.')'; //회원명
		$mfrm['passwd']			= $mb_id; //비밀번호
		$mfrm['gender']			= "M"; //성별
		$mfrm['mailser']		= 'N'; //E-Mail을 수신
		$mfrm['smsser']			= 'N'; //SMS를 수신
		$mfrm['pt_id']			= 'admin'; //추천인
		$mfrm['grade']			= '9'; //레벨
		$mfrm['supply']			= 'Y'; //공급사로설정
		$mfrm['reg_time']		= MS_TIME_YMDHIS; //가입일
		insert("shop_member", $mfrm);

		unset($sfrm);
		$sfrm['seller_code']	= code_uniqid();
		$sfrm['mb_id']			= $mb_id;
		$sfrm['company_name']	= '파미웰공급사('.$mb_id.')';
		$sfrm['state']			= '1';
		$sfrm['seller_open']	= '1';
		$sfrm['reg_time']		= MS_TIME_YMDHIS;
		$sfrm['update_time']	= MS_TIME_YMDHIS;
		insert("shop_seller", $sfrm);

		return $sfrm['seller_code'];
	}
}

// 파미웰 주문정보 API 전달
function famiwel_order_send($od_id)
{
	if(!$od_id) return;

	// 입금완료된 파미웰 주문정보 호출
	$sql = "select *
			  from shop_order
			 where od_id = '$od_id'
			   and famiwel_mb_id <> ''
			   and (famiwel_res_cd != '0000' OR famiwel_res_cd IS NULL)
			   limit 1 ";



	$od = sql_fetch($sql);
	if(!$od['od_id'])
		return '1';

	// 파미웰 공급가총합+배송비 구하기
	$sql = " select SUM(supply_price + baesong_price) as ordsum_price
			   from shop_order
			  where od_id = '$od_id'
			    and famiwel_mb_id <> '' ";
	$sum = sql_fetch($sql);

	if($od['addr3'])
		$od['addr2'] .= ' '.$od['addr3'];
	if($od['b_addr3'])
		$od['b_addr2'] .= ' '.$od['b_addr3'];

	if(!$od['telephone'])
		$od['telephone'] = $od['cellphone'];
	if(!$od['b_telephone'])
		$od['b_telephone'] = $od['b_cellphone'];

	$od_tel   = explode('-', replace_tel($od['telephone']));
	$od_hp    = explode('-', replace_tel($od['cellphone']));
	$od_b_tel = explode('-', replace_tel($od['b_telephone']));
	$od_b_hp  = explode('-', replace_tel($od['b_cellphone']));

	$data = array();
	$data['dl_comcode']		= 'omarket'; // 업체코드
	$data['order_key']		= $od['od_id']; // 주문번호
	$data['ordsum_price']	= $sum['ordsum_price']; // 주문 총 합계 금액(배송비포함)
	$data['mname']			= $od['name']; // 주문자명
	$data['email']			= $od['email']; // 주문자이메일
	$data['zip1']			= substr($od['zip'], 0, 3);
	$data['zip2']			= substr($od['zip'], 3);
	$data['addr']			= $od['addr1'];
	$data['addr2']			= $od['addr2'];
	$data['phone'][]		= $od_tel[0];
	$data['phone'][]		= $od_tel[1];
	$data['phone'][]		= $od_tel[2];
	$data['mobile'][]		= $od_hp[0];
	$data['mobile'][]		= $od_hp[1];
	$data['mobile'][]		= $od_hp[2];
	$data['mname2']			= $od['b_name']; // 받는분 이름
	$data['zip11']			= substr($od['b_zip'], 0, 3);
	$data['zip22']			= substr($od['b_zip'], 3);
	$data['addr11']			= $od['b_addr1'];
	$data['addr22']			= $od['b_addr2'];
	$data['phone2'][]		= $od_b_tel[0];
	$data['phone2'][]		= $od_b_tel[1];
	$data['phone2'][]		= $od_b_tel[2];
	$data['mobile2'][]		= $od_b_hp[0];
	$data['mobile2'][]		= $od_b_hp[1];
	$data['mobile2'][]		= $od_b_hp[2];
	$data['cont']			= $od['memo']; // 배송시 요청사항

	// 장바구니를 배열로 돌림
	$sql = " select *
			   from shop_cart
			  where od_id = '$od_id'
				and famiwel_mb_id <> ''
				and io_famiwel_no <> ''
			  order by gs_id, io_type, index_no ";



	$result = sql_query($sql);
	$cnt = @sql_num_rows($result);
	if(!$cnt)
		return '2';

	for($i=0; $ct=sql_fetch_array($result); $i++)
	{
		$gs = get_goods($ct['gs_id'], 'gcode');

		$io_id = str_replace(chr(30), '+', trim($ct['io_id']));

		$data['gid'][]		= $gs['gcode']; // 상품아이디
		$data['qunt'][]		= $ct['ct_qty']; // 수량
		$data['price'][]	= $ct['io_supply_price']; // 파미웰제공공급가
		$data['no'][]		= $ct['io_famiwel_no']; // 옵션키값
		$data['option'][]	= $io_id; // 옵션
	}

	$res = famiwel_json_curl("https://www.famiwel.co.kr/_prozn/_system/connect_data/order/order_op_na.php", $data);
	$res_cd = trim($res->result_code); // 결과코드
	$res_order_id = trim($res->order_info->order_id); // 파미웰 주문번호

	// 결과값을 주문서에 UPDATE
	$sql = " update shop_order
				set famiwel_res_cd = '$res_cd'
				  , famiwel_od_id = '$res_order_id'
			  where od_id = '$od_id' ";
	sql_query($sql);

	// 결과코드가 정상이면 장바구니에 '주문상품번호'를 업데이트한다.
	// 주문상품번호는 파미웰에서 주문고유값으로 사용하는듯
	if($res_cd == '0000') {
		foreach($res->order_info->goods as $k=>$v)
		{
			// 장바구니에 UPDATE
			$sql = " update shop_cart
						set famiwel_od_no = '".$v->order_num."'
					  where od_id = '$od_id'
					    and gs_cd = '".$v->order_gid."'
						and io_famiwel_no = '".$v->op_no."'
						and io_famiwel_no <> ''
						and famiwel_mb_id <> '' ";
			sql_query($sql);

			// 장바구니에 UPDATE
			$sql = " update shop_order
						set famiwel_od_no = '".$v->order_num."'
					  where od_id = '$od_id'
						and famiwel_op_no = '".$v->op_no."' ";
			sql_query($sql);
		} // foreach end
	}
}

// 파미웰 주문상태
function famiwel_order_status($od)
{
	global $famiwel_status;

	if(!($od['famiwel_mb_id'] && $od['famiwel_od_id']))
		return;

	if($od['famiwel_res_cd'] != '0000')
		return;

	$sql = " select famiwel_od_no
			   from shop_cart
			  where gs_id = '{$od['gs_id']}'
				and od_id = '{$od['od_id']}'
			  order by io_type, index_no limit 1 ";
	$ct = sql_fetch($sql);

	if(!$ct['famiwel_od_no'])
		return;

	$data = array();
	$data['order_id']  = $od['famiwel_od_id'];
	$data['order_num'] = $ct['famiwel_od_no'];

	$res = famiwel_json_curl("http://www.famiwel.co.kr/_prozn/_system/connect_data/order/order_info.php", $data);

	$str = "<div class=\"famiwel-msg\">";
	$str.= "<p>주문상태:<span>{$famiwel_status[$res->orderstep]}</span></p>";

	if($res->orderord)
		$str .= "<p>배송업체:<span>{$res->orderord}</span></p>";
	if($res->delivnum)
		$str .= "<p>송장번호:<span>{$res->delivnum}</span></p>";

	$str .= "</div>";

	return $str;
}
?>
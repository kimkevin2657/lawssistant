<?php
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');
	function han ($s) { return @reset(json_decode('{"s":"'.$s.'"}')); }
	function to_han ($str) { return @preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }

$sql = " SELECT * FROM shop_order WHERE famiwel_op_no != '' AND famiwel_res_cd = '0000' ";
//$sql = " select * from famiwel_shop_list where gid = '15403695671100'";

//echo $sql;
//echo "<br>";
//exit;
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
$od_id = '';
$dan = '';
$od_no = '';
$od_id = $row['famiwel_od_id'];
$dan = $row['dan'];
$od_no = $row['od_no'];

	$row2 = sql_fetch(" select famiwel_od_no from shop_cart where od_no='$od_no' "); //db에 등록되었었는지 여부 검사 
	if($row2['famiwel_od_no']){ // db에 등록 안된상품이면 굳이 판매중지된 상품을 가져올 이유가 없음. 등록된 상품만 수정
		$famiwel_od_no = '';
		$famiwel_od_no = $row2['famiwel_od_no'];

		echo famiwel_status_send($famiwel_od_no,$od_no,$od_id,$dan);
	}

}



function famiwel_status_send($order_num,$od_no,$od_id,$dan){
	$url = 'http://www.famiwel.co.kr/_prozn/_system/connect_data/order/order_info_na.php'; //접속할 url 입력
	$post_data["order_num"] = $order_num; //상품번호
	$post_data["order_id"] = $od_id; //파미웰주문번호
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
	$row = json_decode($json, true);
	$regdate = date("Y-m-d H:i:s");

	unset($value);
	unset($gid);
	$orderstep = $row['orderstep']; // 파미웰 배송단계 3: 취소완료, 5: 배송중, 7: 교환완료, 9: 반품완료, 12: 배송준비중, 13: 배송완료
									// 페이샵 배송단계 6: 취소완료, 4: 배송중, 8: 교환, 7: 반품, 3: 배송준비중, 5: 배송완료
	$delivery_no = $row['delivnum']; //송장번호
	$delivery = urldecode($row['orderord']); // 배송업체
	//echo $delivery;
	if($delivery == "롯데택배"){
		$delivery = "롯데택배(구현대택배)|https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo=";
	}elseif($delivery == "일양로지스"){
		$delivery = "일양로지스|http://www.ilyanglogis.com/functionality/card_form_waybill.asp?hawb_no=";
	}elseif($delivery == "CJ대한통운"){
		$delivery = "CJ대한통운|https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=";
	}elseif($delivery == "CJ-GLS"){
		$delivery = "CJ대한통운|https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=";
	}elseif($delivery == "로젠택배"){
		$delivery = "로젠택배|http://d2d.ilogen.com/d2d/delivery/invoice_tracesearch_quick.jsp?slipno=";
	}elseif($delivery == "한진택배"){
		$delivery = "한진택배|https://www.hanjin.co.kr/kor/CMS/DeliveryMgr/WaybillResult.do?mCode=MN038&schLang=KR&wblnumText2=";
	}elseif($delivery == "경동택배"){
		$delivery = "경동택배|http://kdexp.com/basicNewDelivery.kd?barcode=";
	}elseif($delivery == "CVSnet편의점택배"){
		$delivery = "CVSnet편의점택배|http://was.cvsnet.co.kr/_ver2/board/ctod_status.jsp?invoice_no=";
	}elseif($delivery == "농협택배"){
		$delivery = "";
	}elseif($delivery == "KGB택배"){
		$delivery = "KGB택배|http://www.kgbls.co.kr//sub5/trace.asp?f_slipno=";
	}elseif($delivery == "우체국택배"){
		$delivery = "우체국|http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=";
	}elseif($delivery == "건영택배"){
		$delivery = "";
	}elseif($delivery == "천일택배"){
		$delivery = "천일택배|http://www.cyber1001.co.kr/kor/taekbae/HTrace.jsp?transNo=";
	}elseif($delivery == "GTX로지스"){
		$delivery = "GTX로지스|http://www.gtxlogis.co.kr/tracking/default.asp?awblno=";
	}elseif($delivery == "합동택배"){
		$delivery = "";
	}elseif($delivery == "대신택배"){
		$delivery = "대신택배|http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp?billno1=";
	}elseif($delivery == ""){
		$delivery = "";
	}else{

	}
/* 페미메이커 배송업체리스트
<option value="KG로지스|http://www.kglogis.co.kr/delivery/delivery_result.jsp?item_no=">KG로지스</option>
<option value="KGB택배|http://www.kgbls.co.kr//sub5/trace.asp?f_slipno=">KGB택배</option>
<option value="KG옐로우캡택배|http://www.yellowcap.co.kr/custom/inquiry_result.asp?invoice_no=">KG옐로우캡택배</option>
<option value="CVSnet편의점택배|http://was.cvsnet.co.kr/_ver2/board/ctod_status.jsp?invoice_no=">CVSnet편의점택배</option>
<option value="CJ대한통운|https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=">CJ대한통운</option>
<option value="롯데택배(구현대택배)|https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo=">롯데택배(구현대택배)</option>
<option value="한진택배|http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=">한진택배</option>
<option value="이노지스택배|http://www.innogis.net/trace02.asp?invoice=">이노지스택배</option>
<option value="우체국|http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=">우체국</option>
<option value="로젠택배|http://d2d.ilogen.com/d2d/delivery/invoice_tracesearch_quick.jsp?slipno=">로젠택배</option>
<option value="동부택배|http://www.dongbups.com/delivery/delivery_search_view.jsp?item_no=">동부택배</option>
<option value="대신택배|http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp?billno1=">대신택배</option>
<option value="경동택배|http://kdexp.com/basicNewDelivery.kd?barcode=">경동택배</option>
<option value="일양로지스|http://www.ilyanglogis.com/functionality/card_form_waybill.asp?hawb_no=">일양로지스</option>
<option value="GTX로지스|http://www.gtxlogis.co.kr/tracking/default.asp?awblno=">GTX로지스</option>
<option value="천일택배|http://www.cyber1001.co.kr/kor/taekbae/HTrace.jsp?transNo=">천일택배</option>
*/
// od_no 값으로 결과값 보는부분
if($od_id == "23011122204918"){
	echo "2222222";
	echo "<br>";
	echo $orderstep;
	echo "<br>";
	echo $order_num;
}
	if($orderstep == '3' or $orderstep == '5' or $orderstep == '7' or $orderstep == '9' or $orderstep == '12' or $orderstep == '13'){
		switch ($orderstep) {
		  case '3':
			$change_status = '6';
			break;
		  case '5':
			$change_status = '4';
			break;
		  case '7':
			$change_status = '8';
			break;
		  case '9':
			$change_status = '7';
			break;
		  case '12':
			$change_status = '3';
			break;
		  case '13':
			$change_status = '5';
			break;
		}

		$row2 = sql_fetch(" select * from shop_order where od_no='$od_no' "); //db에 등록되었었는지 여부 검사 
		if($row2['famiwel_od_id']){ // db에 등록 안된상품이면 굳이 판매중지된 상품을 가져올 이유가 없음. 등록된 상품만 수정
			switch($change_status) {
				case '3': // 배송준비
					change_order_status_3($od_no, $delivery, $delivery_no);
					break;
				case '4': // 배송중
					change_order_status_4($od_no, $delivery, $delivery_no);
					break;
				case '5': // 배송완료
					if($dan != '5'){
					//	change_order_status_5($od_no);
					}
					break;
				case '6': // 취소
					if($dan != '6'){
						if($dan != '7'){
							change_order_status_6($od_no);
						}
					}
					break;
				case '7': // 반품
					if($dan != '7'){
						if($dan != '6'){
							change_order_status_7($od_no);
						}
					}
					break;
				case '8': // 교환
					if($dan != '8'){
						change_order_status_8($od_no);
					}
					break;
			}

		}
	}

	//var_dump($res);//결과값 확인하기
	//echo '<br>';
	//print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
	//echo curl_errno($ch);//마지막 에러 번호 출력
	//echo curl_error($ch);//현재 세션의 마지막 에러 출력
	curl_close($ch);
}
?>

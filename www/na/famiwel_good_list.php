<?php
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');
$url = 'http://api.famiwel.co.kr/_prozn/_system/connect_data/good_list_json.php'; //접속할 url 입력
function han ($s) { return @reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return @preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
$ser_regdate = date("Y-m-d H:i",strtotime ("-2 minutes")); // 기본적으로는 현시간으로부터 2시간전 데이터중 판매중 상품을 가져옵니다. 크론탭으로 항시 데이터를 가져가는 업체라면 이 기능 위주로 쓰게 되겠죠.
//$ser_regdate = "2023-01-05 00";
$post_data["company"] = "omarket"; //업체아이디
$post_data["pass"] = "omarket!@"; // 업체비밀번호
$post_data["sale_ing"] = "1"; // 판매상품설정
$post_data["modifyDate"] = $ser_regdate.":00"; // 날짜설정 기본적으로 지정날짜보다 큰 값을 불러온다. 
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
//var_dump($res);

$count	 = (int)trim(strtolower($row['count'])); //총 상품수
$sale_ing	 = (int)trim(strtolower($row['sale_ing'])); // 판매여부 1: 판매중 , 2: 판매종료
$server_update_date	 = trim(strtolower($row['modifyDate'])); // 내가 설정한 날짜 리턴
$regdate = date("Y-m-d H:i:s");
if($count){
	for($i=0; $i < $count; $i++){
		unset($value);
		unset($gid);
		unset($modifyDate);
		$gid = $row['products'][$i]['gid'];
		$sqlt = " select count(*) as cnt from shop_goods_del where gid = '$gid'";
		$rowt = sql_fetch($sqlt);
		if($rowt['cnt'] < '1') {
			$row2 = sql_fetch(" select gid from famiwel_shop_list where gid='$gid' "); //db에 등록되었었는지 여부 검사
			if(!$row2['gid']){
				$value['gid']						= $row['products'][$i]['gid'];
				$value['modifyDate']				= $row['products'][$i]['modifyDate'];
				$value['sale_ing']					= $sale_ing;
				$value['server_update_date']		= $server_update_date;
				$value['regdate']					= $regdate;
				$value['use_r']						=1;
				insert("famiwel_shop_list", $value);
			}else{
				$modifyDate				= $row['products'][$i]['modifyDate'];
				$row3 = sql_fetch(" select gid from famiwel_shop_list where gid='$gid' and modifyDate = '$modifyDate' "); //db에 등록되었었는지 여부 검사
				if(!$row3['gid']){
					$value['modifyDate']				= $row['products'][$i]['modifyDate'];
					$value['sale_ing']					= $sale_ing;
					$value['server_update_date']		= $server_update_date;
					$value['regdate']					= $regdate;
					$value['use_r']						=1;
					update("famiwel_shop_list", $value," where gid = '$gid'");
				}
			}
		}
	}
}else{
		$value['result_code']		= $row['result_code'];
		$value['result_msg']		= $row['result_msg'];
		$value['regdate']					= $regdate;
		insert("famiwel_shop_err_log", $value); //에러로그 저장
}


//var_dump($res);//결과값 확인하기
//echo '<br>';
//print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
//echo curl_errno($ch);//마지막 에러 번호 출력
//echo curl_error($ch);//현재 세션의 마지막 에러 출력
curl_close($ch);
echo "끗";
?>

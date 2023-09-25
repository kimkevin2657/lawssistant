<?php
if(!defined('_MALLSET_')) exit;

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods a";
$sql_search = " where a.use_aff = 0 and a.shop_state = 0";

include_once(MS_ADMIN_PATH.'/goods/goods_query.inc.php');
function getUserAgent()
{
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5";
	$userAgentArray[] = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36 OPR/49.0.2725.47";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64; rv:52.0) Gecko/20100101 Firefox/52.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:52.0) Gecko/20100101 Firefox/52.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36 OPR/49.0.2725.64";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/62.0.3202.94 Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0";
	$userAgentArray[] = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;  Trident/5.0)";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; rv:52.0) Gecko/20100101 Firefox/52.0";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/63.0.3239.84 Chrome/63.0.3239.84 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0;  Trident/5.0)";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:57.0) Gecko/20100101 Firefox/57.0";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0";
	$userAgentArray[] = "Mozilla/5.0 (iPad; CPU OS 11_1_2 like Mac OS X) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0 Mobile/15B202 Safari/604.1";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0";
	$userAgentArray[] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Safari/604.1.38";
	$userAgentArray[] = "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
	$userAgentArray[] = "Mozilla/5.0 (X11; CrOS x86_64 9901.77.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.97 Safari/537.36";
	
	$getArrayKey = array_rand($userAgentArray);
	return $userAgentArray[$getArrayKey];
 
}

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod ";
if($crol == '1'){
	if($page < '2'){
			// 테이블의 전체 레코드수만 얻음
			$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
			$row = sql_fetch($sql);
			$total_count = $row['cnt'];


			//$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
			$sql = " select a.* $sql_common $sql_search $sql_order";
			$result = sql_query($sql);
			$gcode = '';
			$url = '';
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$gcode = $row['gcode'];
				$url = $row['naver_price_url'];
				if(!$url or $url == ''){ 

					$data = explode("(",$row['gname']);
					$dcount = sizeof($data)-1;
					$row['gname'] = str_replace("(".$data[$dcount],'',$row['gname']);
					$url = "https://search.shopping.naver.com/search/all?query=".urlencode($row['gname']);
					$sql3 = " update shop_goods set naver_price_url = '$url' where gcode = '$gcode'"; //echo $sql;
					sql_query($sql3);
				}

				if(strpos($url,'search.shopping.naver.com/catalog/') !== false){
					

					$header_data = getUserAgent();
					$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
					curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
					curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
					curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
					curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
					$res = curl_exec ($ch);
				//	echo $res;
					$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$header = substr($res, 0, $header_size);
					$naver_data = substr($res, $header_size); 
					$naver_data1 = explode("\"application/json\">",$naver_data);
					$naver_data2 = explode("</script>",$naver_data1[1]);
					$json = $naver_data2[0];
					$row = json_decode($json, true);
				//	print_r($row);
					//echo $row->pageProps->initialState->products->list[0]->item->productName;
					//echo sizeof($row["props"]);

					//	print_r($row["props"]["pageProps"]["initialState"]["products"]["list"][0]["item"]);
						//print_r($row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"]);

					$count = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["totalCount"];
					$img_com = '';
					if($count == '0'){
						$img_com = "nonc";
							$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '0', '0')"; //echo $sql;
							sql_query($sql);
						continue;
					}
					$price = '';
					$sql = " delete from naver_list where gcode = '$gcode'"; //echo $sql;
					sql_query($sql);
					for($z = 0; $z < $count; $z++){

						$img_com = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$z]["mallName"];
						//echo $img_com;
						$pcPrice = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$z]["pcPrice"];
						$deliveryFee = '';
						$deliveryFee = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$z]["deliveryFee"];
						if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "SSG닷컴" or $img_com == "G9" or $img_com == "롯데백화점" or $img_com == "롯데ON" or $img_com == "롯데홈쇼핑" or $img_com == "현대Hmall" or $img_com == "GSSHOP" or $img_com == "CJ온스타일" or $img_com == "홈앤쇼핑" or $img_com == "NS홈쇼핑" or $img_com == "AK몰"){
							$price = $pcPrice+$deliveryFee;

							$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '$price', '$deliveryFee')"; //echo $sql;
							sql_query($sql);
						}

					}
				}else{
	//				$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
					$header_data = getUserAgent();
					$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
					curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
					curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
					curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
					curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
					$res = curl_exec ($ch);
				//	echo $res;
					$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$header = substr($res, 0, $header_size);
					$naver_data = substr($res, $header_size); 
					$naver_data1 = explode("\"application/json\">",$naver_data);
					$naver_data2 = explode("</script>",$naver_data1[1]);
					$json = $naver_data2[0];
					$row = json_decode($json, true);
					$count = '';
					$count = sizeof($row["props"]["pageProps"]["initialState"]["products"]["list"]);
					$img_com = '';
					if($count == '0'){
						$img_com = "nonc";
							$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '0', '0')"; //echo $sql;
							sql_query($sql);
						continue;
					}


					$sql = " delete from naver_list where gcode = '$gcode'"; //echo $sql;
					sql_query($sql);
				//	print_r($row["props"]["pageProps"]["initialState"]["products"]["list"][0]["item"]);
					for($c = 0; $c < $count; $c++){
						$img_com = $row["props"]["pageProps"]["initialState"]["products"]["list"][$c]["item"]["mallName"];
						$pcPrice = $row["props"]["pageProps"]["initialState"]["products"]["list"][$c]["item"]["price"];
						$deliveryFee = '';
						$deliveryFee = $row["props"]["pageProps"]["initialState"]["products"]["list"][$c]["item"]["deliveryFeeContent"];
						if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "SSG닷컴" or $img_com == "G9" or $img_com == "롯데백화점" or $img_com == "롯데ON" or $img_com == "롯데홈쇼핑" or $img_com == "현대Hmall" or $img_com == "GSSHOP" or $img_com == "CJ온스타일" or $img_com == "홈앤쇼핑" or $img_com == "NS홈쇼핑" or $img_com == "AK몰"){
							$price = $pcPrice+$deliveryFee;

							$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '$price', '$deliveryFee')"; //echo $sql;
							sql_query($sql);
						}
					}
					
				}
				curl_close($ch);

			}
	}
}

$target_table = 'shop_cate';
include_once(MS_LIB_PATH."/categoryinfo.lib.php");
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

/*
$sql2 = " select a.* $sql_common $sql_search $sql_order ";
$result2 = sql_query($sql2);
for($i=0; $row2=sql_fetch_array($result2); $i++) {
	if($row2['gpoint']>0&&!$row2['marper']){
		$marper = round($row2['gpoint']/$row2['goods_price']*100);

		sql_query("update shop_goods set marper='$marper' where index_no=".$row2['index_no']);
	}
}
*/
$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택판매가수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./goods/goods_naver_list_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;

?>

<h2>동작설정</h2>

<div class="price_engine">
	<p class="lh6">
		카테고리를 선택해야만 크롤링이 시작됩니다.<br>
		상품이 5000개이상일 경우 매우 많은 시간이 소요 될 수 있으므로 검색설정을 꼼꼼히 하여주시기 바랍니다.<br>
		최저가 보다 판매가가 비싼 상품만 검색됩니다.<br>
		동작 버튼을 누르면 크롤링이 함께 진행 됩니다. 단순 검색을 위해서라면 네이버자동크롤링 메뉴를 이용하시면 됩니다. <br>
		네이버 최저가 부분의 상품전체가 nonc로 노출 될경우는 네이버에서 우리서버를 차단할 경우 입니다.  <br>
		이때에는 30분 정도 후에 다시 하시면 됩니다.  <br>
	</p>
</div>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<input type="hidden" name="excelType_tt">
<input type="hidden" name="crol" value='1'>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w100">
		<col>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">카테고리</th>
		<td colspan="3">
			<script>multiple_select('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td colspan="3">
			<select name="q_date_field" id="q_date_field">
				<?php echo option_selected('update_time', $q_date_field, "최근수정일"); ?>
				<?php echo option_selected('reg_time', $q_date_field, "최초등록일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">판매여부</th>
		<td>
			<?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '1', '진열'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '2', '품절'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '3', '단종'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '4', '중지'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<?php if($count){ ?>
<div>
총 <?php echo $total_count; ?>개의 상품을 크롤링 했습니다.
</div>
<?php } ?>
<div class="btn_confirm">
	<input type="submit" value="동작" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>
<?php
// 테이블의 전체 레코드수만 얻음
$sql_search .= " AND a.goods_price+a.sc_amt > (SELECT price FROM naver_list WHERE gcode = a.gcode ORDER BY price ASC LIMIT 1)";
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
//echo $sql;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
?>

<form name="fgoodslist" id="fgoodslist" method="post" action="./goods/goods_list_update.php" onsubmit="return fgoodslist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head02">
	<table id="sodr_list" class="tablef">
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w60">
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
        <col class="w80">
        <?php endif; ?>
		<col class="w120">
		<col>
		<col>
		<col class="w80">
		<col class="w80">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w100">
		<col class="w80">
		<col class="w80">
		<col class="w100">
		<col class="w60">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2">번호</th>
		<th scope="col" rowspan="2">이미지</th>
		<th scope="col"><?php echo subject_sort_link('a.gcode',$q2); ?>상품코드</a></th>
		<th scope="col" colspan="<?php echo defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ? "3" :"2"?>"><?php echo subject_sort_link('a.gname',$q2); ?>상품명</a></th>
        <th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>최초등록일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.isopen',$q2); ?>진열</a></th>
		<th scope="col" colspan="4" class="th_bg">가격정보</th>
		<th scope="col" colspan="3" class="th_bg">적립정보</th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('a.rank',$q2); ?>순위</a></th>
		<th scope="col" rowspan="2">관리</th>
	</tr>
	<tr class="rows">
		<th scope="col"><?php echo subject_sort_link('a.mb_id',$q2); ?>업체코드</a></th>
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
		<th scope="col">가맹상품</th>
        <?php endif; ?>
        <th scope="col">공급사명</th>
		<th scope="col">카테고리</th>
		<th scope="col"><?php echo subject_sort_link('a.update_time',$q2); ?>최근수정일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.stock_qty',$q2); ?>재고</a></th>
		<th scope="col" class="th_bg">신판매가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.supply_price',$q2); ?>공급가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.goods_price',$q2); ?>판매가</a></th>
		<th scope="col" class="th_bg">네이버최저가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.goods_kv',$q2); ?>마일리지</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.gpoint',$q2); ?>쇼핑포인트</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.point_pay_point',$q2); ?>쇼핑포인트결제</a></th>
	</tr>
	</thead>
	<tbody>
	<?php

	if($_SESSION['ss_page_rows'])
		$page_rows = $_SESSION['ss_page_rows'];
	else
		$page_rows = 30;


	$rows = $page_rows;
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함
	$num = $total_count - (($page-1)*$rows);
	$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
	//echo $sql;
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs_id = $row['index_no'];

		if($row['stock_mod'])
			$stockQty = number_format($row['stock_qty']);
		else
			$stockQty = '<span class="txt_false">무제한</span>';

		$bg = 'list'.($i%2);
    $QUERY_STRING = htmlspecialchars(urlencode($row[gname]));
	$data = explode("(",$row['gname']);
	$dcount = sizeof($data)-1;
	$row['gname'] = str_replace("(".$data[$dcount],'',$row['gname']);
	$between_link = "https://search.shopping.naver.com/search/all?query=".urlencode($row['gname']);

	$row2 = sql_fetch(" select * from naver_list where gcode='{$row['gcode']}' order by price asc limit 1"); //db에 등록되었었는지 여부 검사

	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2"><?php echo $num--; ?></td>
		<td rowspan="2"><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo $row['gcode']; ?></td>
		<td colspan="3" class="tal"><?php echo get_text($row['gname']); ?></td>
		<td><?php echo substr($row['reg_time'],2,8); ?></td>
		<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
		<td rowspan="2" class="tar"><input type="text" name="goods_price[<?php echo $i; ?>]" value="" class="frm_input"></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['supply_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['goods_price']+$row['sc_amt']); ?></td>
		<td rowspan="2" class="tar"><?php echo $row2['img_com']; ?>|<?php echo number_format($row2['price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['goods_kv']); ?>원<br><?php echo number_format($row['goods_kv_per']); ?>%</td>
		<td rowspan="2" class="tar"><?php echo number_format($row['gpoint']); ?>P<br><?php echo number_format($row['gpoint_per']); ?>%</td>
		<td rowspan="2" class="tar"><?if($row['point_pay_allow']==1){?><?php echo number_format($row['point_pay_point']); ?>P<br><?php echo number_format($row['point_pay_per']); ?>%<?}?></td>
		<td rowspan="2"><input type="text" name="rank[<?php echo $i; ?>]" value="<?php echo $row['rank']; ?>" class="frm_input"></td>
		<td rowspan="2">
      <a href="./goods.php?code=form&w=u&gs_id=<?php echo $gs_id.$qstr; ?>&page=<?php echo $page; ?>&bak=<?php echo $code; ?>" class="btn_small">수정</a><br/>
      <a href="<?=$between_link?>" target="_blank" class="btn_small red" style="margin-top:3px;">가격비교</a>
  	  <a href="./naver_price_url.php?gcode=<?php echo $row['gcode']; ?>" onclick="win_open(this,'pop_naver_price_url','550','500','no');return false" class="btn_small" style="margin-top:3px;">최저가가져오기</a>
    </td>
	</tr>
	<tr class="<?php echo $bg; ?>">
		<td class="fc_00f"><?php echo $row['mb_id']; ?></td>
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
        <td scope="tal"><?php echo minishop::minishopLevelSelect('buy_minishop_grade', $row['buy_minishop_grade'], "가맹상품아님"); ?></td>
        <?php endif; ?>
		<td class="tal txt_succeed"><?php echo get_seller_name($row['mb_id']); ?></td>
		<td class="tal txt_succeed"><?php echo get_cgy_info($row); ?></td>
		<td class="fc_00f"><?php echo substr($row['update_time'],2,8); ?></td>
		<td><?php echo $stockQty; ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tr><td colspan="17" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function downExcel_guid_select() {
	var f = document.fgoodslist;
	var c = document.fsearch;
	
	c.excelType_tt.value = f.excelType.value;
	msg = "검색된 상품을 EXCEL로 저장하겠습니까?";

	if (confirm(msg)) {
		c.method = "post";
		c.action = "./goods/goods_list_excel_down.php";
		c.submit();
	}
}
function fgoodslist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?php echo $sel_ca1; ?>');
	categorychange('<?php echo $sel_ca1; ?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?php echo $sel_ca2; ?>');
	categorychange('<?php echo $sel_ca2; ?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?php echo $sel_ca3; ?>');
	categorychange('<?php echo $sel_ca3; ?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?php echo $sel_ca4; ?>');
	categorychange('<?php echo $sel_ca4; ?>', 'sel_ca5');
	<?php } ?>
	<?php if($sel_ca5) { ?>
	$("select#sel_ca5").val('<?php echo $sel_ca5; ?>');
	<?php } ?>

	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date,#to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>


<?php
include_once("./_common.php");
$ms['title'] = '네이버최저가 가져오기';
include_once(MS_ADMIN_PATH."/admin_head.php");
$gcode = $_GET['gcode'];
if(!$gcode){
exit;
}
$row = sql_fetch(" select * from shop_goods where gcode='{$gcode}' limit 1"); 
$url = $row['naver_price_url'];
if(!$url){ 

	$data = explode("(",$row['gname']);
	$dcount = sizeof($data)-1;
	$row['gname'] = str_replace("(".$data[$dcount],'',$row['gname']);
	$url = "https://search.shopping.naver.com/search/all?query=".urlencode($row['gname']);
	unset($value);
	$value['naver_price_url']=$url;
	update("shop_goods", $value," where gcode='{$gcode}'");
}
if(strpos($url,'search.shopping.naver.com/catalog/') !== false){
	$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
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
	$price = '';
		?>
		<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
		<div class="new_win_body">
			<div class="guidebox tac">
	<?php
			$sql = " delete from naver_list where gcode = '$gcode'"; //echo $sql;
			sql_query($sql);
	for($i = 0; $i < $count; $i++){

		$img_com = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$i]["mallName"];
		//echo $img_com;
		$pcPrice = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$i]["pcPrice"];
		$deliveryFee = $row["props"]["pageProps"]["initialState"]["catalog"]["products"][2]["productsPage"]["products"][$i]["deliveryFee"];
		if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "G9" or $img_com == "롯데백화점" or $img_com == "롯데ON" or $img_com == "롯데홈쇼핑" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
			$price = $pcPrice+$deliveryFee;

			$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '$price', '$deliveryFee')"; //echo $sql;
			sql_query($sql);

	?>
			<b><?php echo $img_com?> : <?php echo number_format($price);?>원</b>&nbsp;&nbsp; <!--button type="button" onClick="yes('<?php echo $price; ?>')" class="btn_small grey">선택</button-->
	<?php
		}

	}
}else{
	$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
	$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
	curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
	curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // 302 found 에러 발생으로 추가
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  // 302 found 에러 발생으로 추가
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	$res = curl_exec ($ch);
//	echo $_SERVER['HTTP_USER_AGENT'];
//	echo $res;
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($res, 0, $header_size);
	$naver_data = substr($res, $header_size); 
	$naver_data1 = explode("\"application/json\">",$naver_data);
	$naver_data2 = explode("</script>",$naver_data1[1]);
	$json = $naver_data2[0];
	$row = json_decode($json, true);
//	print_r($row);
		?>
		<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
		<div class="new_win_body">
			<div class="guidebox tac">
	<?php

	$count = sizeof($row["props"]["pageProps"]["initialState"]["products"]["list"]);
	if($count == '0'){
		 echo "검색된 상품이 없습니다";?>
	</div>
</div>
	<center>	<a href="<?php echo $url; ?>" target="_blank" class="btn_small">URL확인하기</a>&nbsp;<a href="#" onclick="clo_go()" class="btn_small">닫기</a></center>
<script>
function yes(goods_price){
	opener.document.fregform.goods_price.value = goods_price;
	self.close();
}
function clo_go(){
	self.close();
}
</script>

<?php
include_once(MS_ADMIN_PATH.'/admin_tail.sub.php');
?>

<?php
	exit;
	}

	$sql = " delete from naver_list where gcode = '$gcode'"; //echo $sql;
	sql_query($sql);
//	print_r($row["props"]["pageProps"]["initialState"]["products"]["list"][0]["item"]);
	for($i = 0; $i < $count; $i++){
		$img_com = $row["props"]["pageProps"]["initialState"]["products"]["list"][$i]["item"]["mallName"];
		$pcPrice = $row["props"]["pageProps"]["initialState"]["products"]["list"][$i]["item"]["price"];
		$deliveryFee = $row["props"]["pageProps"]["initialState"]["products"]["list"][$i]["item"]["deliveryFeeContent"];
		if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "롯데온" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
			$price = $pcPrice+$deliveryFee;

			$sql = " insert into naver_list( gcode, img_com, price, delivery ) VALUES ('$gcode', '$img_com', '$price', '$deliveryFee')"; //echo $sql;
			sql_query($sql);

	?>
			<b><?php echo $img_com?> : <?php echo number_format($price);?>원</b> &nbsp;&nbsp;<!--button type="button" onClick="yes('<?php echo $price; ?>')" class="btn_small grey">선택</button-->
	<?php
		}
	}
	
}
?>
	</div>
</div>
	<center>	<a href="<?php echo $url; ?>" target="_blank" class="btn_small">URL확인하기</a>&nbsp;<a href="#" onclick="clo_go()" class="btn_small">닫기</a></center>
<script>
function yes(goods_price){
	opener.document.fregform.goods_price.value = goods_price;
	self.close();
}
function clo_go(){
	self.close();
}
</script>

<?php
include_once(MS_ADMIN_PATH.'/admin_tail.sub.php');
?>

<?php

/*
	[price] => 28800 
	[mallName] => Realven Food 
	[deliveryFeeContent] => 3000 
Array ( [pageProps] => Array ( 
			[initialState] => Array ( 
				[products] => Array ( 
					[list] => Array ( 
						[0] => Array ( 
							[item] => Array ( 
*/
exit;

//naver_price_url=https://search.shopping.naver.com/search/all?frm=NVSHATC&origQuery=레노마%20옴므%20리프레싱%20남성%20스킨케어세트&pagingIndex=1&pagingSize=40&productSet=total&query=레노마%20옴므%20리프레싱%20남성%20스킨케어세트&sort=rel&timestamp=&viewType=list
$url = $row['naver_price_url'];
$origQuery = $_GET['origQuery'];
$pagingIndex = $_GET['pagingIndex'];
$pagingSize = $_GET['pagingSize'];
$productSet = $_GET['productSet'];
$query = $_GET['query'];














if($origQuery){
$url_new_1 = $url."&origQuery=".urlencode($origQuery)."&pagingIndex=".$pagingIndex."&pagingSize=".$pagingSize."&productSet=".$productSet."&query=".urlencode($query);

			$ch2 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
			curl_setopt($ch2, CURLOPT_URL, $url_new_1); //URL 지정하기
			curl_setopt($ch2, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
			curl_setopt($ch2, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
			curl_setopt($ch2, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
			curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
			$res_d = curl_exec ($ch2);
			$header_size = curl_getinfo($ch2, CURLINFO_HEADER_SIZE);
			$header = substr($res_s, 0, $header_size);
			$naver_data = substr($res_d, $header_size); 
			$naver_data1 = explode("price_num__",$naver_data);
			$count = sizeof($naver_data1);
	?>
	<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
	<div class="new_win_body">
		<div class="guidebox tac">
	<?php
			for($i = 1; $i < $count; $i++){
				$naver_data2 = explode("\">",$naver_data1[$i]);
				$naver_data2 = explode("</span>",$naver_data2[1]);
				echo $naver_data2[0];
	} for($i = 1; $i < $count; $i++){
				exit;
				$naver_data_t = explode("</a><button type=\"button\" class=\"common_btn_detail_",$naver_data[$i]);
				echo $naver_data_t[1];
				$naver_data_b = explode("\">",$naver_data_t[0]);
				$scount_b = sizeof($naver_data_b)-1;
				$naver_data_c = $naver_data_b[$scount_b]; //업체명1
				$naver_data_d = explode("alt=\"",$naver_data_c);
				$naver_data_e = explode("\" height",$naver_data_d[1]);
				$img_com = $naver_data_e[0]; //이미지로 된 대형몰(업체명2)
				$price_a = explode("<em>",$naver_data[$i]);
				$price_a = explode("</em>",$price_a[1]);
				if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "롯데온" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
					//$price_a[0] = str_replace(',','',$price_a[0]);
	?>
				<b><?php echo $img_com?> : <?php echo $price_a[0]?>원</b> <button type="button" onClick="yes('<?php echo $price_a[0]; ?>')" class="btn_small grey">선택</button>
	<?php
				}
			}


}elseif(strpos($url,'search.shopping.naver.com/catalog/') !== false){
	$url = explode("query=",$url);
	$url = $url[0]."query=".urlencode($url[1]);
		$ch2 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
		curl_setopt($ch2, CURLOPT_URL, $url); //URL 지정하기
		curl_setopt($ch2, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
		curl_setopt($ch2, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
		curl_setopt($ch2, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
		curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
		$res_d = curl_exec ($ch2);
		$header_size = curl_getinfo($ch2, CURLINFO_HEADER_SIZE);
		$header = substr($res_s, 0, $header_size);
		$naver_data = substr($res_d, $header_size); 
		$naver_data = explode("<tbody>",$naver_data);
		$naver_data = explode("</tbody>",$naver_data[1]);
		$naver_data = explode("<tr>",$naver_data[0]);
		$count = sizeof($naver_data);
?>
<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
<div class="new_win_body">
	<div class="guidebox tac">
<?php
		for($i = 0; $i < $count; $i++){
			$naver_data_t = explode("</a><span class=\"productByMall_mall_cell__",$naver_data[$i]);
			//echo $naver_data_t[1];
			$naver_data_b = explode("\">",$naver_data_t[0]);
			$scount_b = sizeof($naver_data_b)-1;
			$naver_data_c = $naver_data_b[$scount_b]; //업체명1
			$naver_data_d = explode("alt=\"",$naver_data_c);
			$naver_data_e = explode("\" height",$naver_data_d[1]);
			$img_com = $naver_data_e[0]; //이미지로 된 대형몰(업체명2)
			$price_a = explode("<em>",$naver_data[$i]);
			$price_a = explode("</em>",$price_a[1]);
			if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "롯데온" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
				//$price_a[0] = str_replace(',','',$price_a[0]);
?>
			<b><?php echo $img_com?> : <?php echo $price_a[0]?>원</b> <button type="button" onClick="yes('<?php echo $price_a[0]; ?>')" class="btn_small grey">선택</button>
<?php
			}
		}
}else{
	$url = explode("query=",$url);
	$url = $url[0]."query=".urlencode($url[1]);
	echo $url;
	exit;

	$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
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
	$naver_data1 = explode("브랜드 카탈로그",$naver_data);
	$naver_data = explode("\">쇼핑몰별 최저가</a>",$naver_data);

	if($naver_data[1]){
		$naver_data = explode("href=\"",$naver_data[0]);
		$scount = sizeof($naver_data)-1;
		$url_new = $naver_data[$scount]; 
		if($url_new){
			curl_close($ch);
			$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
			$ch1 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
			curl_setopt($ch1, CURLOPT_URL, $url_new); //URL 지정하기
			curl_setopt($ch1, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
			curl_setopt($ch1, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
			curl_setopt($ch1, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
			curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
			$res_s = curl_exec ($ch1);
			curl_close($ch1);
			$naver_data_a = explode("location: ",$res_s);
			$naver_data_a = explode("content-language: ",$naver_data_a[1]);
			$url_new_1 = trim($naver_data_a[0]);
	//		echo $url_new_1;
			$ch2 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
			curl_setopt($ch2, CURLOPT_URL, $url_new_1); //URL 지정하기
			curl_setopt($ch2, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
			curl_setopt($ch2, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
			curl_setopt($ch2, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
			curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
			$res_d = curl_exec ($ch2);
			$header_size = curl_getinfo($ch2, CURLINFO_HEADER_SIZE);
			$header = substr($res_s, 0, $header_size);
			$naver_data = substr($res_d, $header_size); 
			$naver_data = explode("<tbody>",$naver_data);
			$naver_data = explode("</tbody>",$naver_data[1]);
			$naver_data = explode("<tr>",$naver_data[0]);
			$count = sizeof($naver_data);
	?>
	<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
	<div class="new_win_body">
		<div class="guidebox tac">
	<?php
			for($i = 0; $i < $count; $i++){
				$naver_data_t = explode("</a><span class=\"productByMall_mall_cell__",$naver_data[$i]);
				//echo $naver_data_t[1];
				$naver_data_b = explode("\">",$naver_data_t[0]);
				$scount_b = sizeof($naver_data_b)-1;
				$naver_data_c = $naver_data_b[$scount_b]; //업체명1
				$naver_data_d = explode("alt=\"",$naver_data_c);
				$naver_data_e = explode("\" height",$naver_data_d[1]);
				$img_com = $naver_data_e[0]; //이미지로 된 대형몰(업체명2)
				$price_a = explode("<em>",$naver_data[$i]);
				$price_a = explode("</em>",$price_a[1]);
				if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "롯데온" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
					//$price_a[0] = str_replace(',','',$price_a[0]);
	?>
				<b><?php echo $img_com?> : <?php echo $price_a[0]?>원</b> <button type="button" onClick="yes('<?php echo $price_a[0]; ?>')" class="btn_small grey">선택</button>
	<?php
				}
			}
		}
	}elseif($naver_data1[1]){
		$naver_data1 = explode("<em class=\"basicList_brand__",$naver_data1[0]);
		$naver_data = explode("href=\"",$naver_data1[0]);
		$scount = sizeof($naver_data)-1;
		$url_new = $naver_data[$scount]; 
		$url_new = explode("\">",$url_new);
		$url_new = $url_new[0];
		if($url_new){
			curl_close($ch);
			$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
			$ch1 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
			curl_setopt($ch1, CURLOPT_URL, $url_new); //URL 지정하기
			curl_setopt($ch1, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
			curl_setopt($ch1, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
			curl_setopt($ch1, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
			curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
			$res_s = curl_exec ($ch1);
			//echo $res_s;
			curl_close($ch1);
			$naver_data_a = explode("location: ",$res_s);
			$naver_data_a = explode("content-language: ",$naver_data_a[1]);
			$url_new_1 = trim($naver_data_a[0]);
	//		echo $url_new_1;
			$ch2 = curl_init(); //curl 사용 전 초기화 필수(curl handle)
			curl_setopt($ch2, CURLOPT_URL, $url_new_1); //URL 지정하기
			curl_setopt($ch2, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
			curl_setopt($ch2, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
			curl_setopt($ch2, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
			curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
			$res_d = curl_exec ($ch2);
			$header_size = curl_getinfo($ch2, CURLINFO_HEADER_SIZE);
			$header = substr($res_s, 0, $header_size);
			$naver_data = substr($res_d, $header_size); 
			$naver_data = explode("<tbody>",$naver_data);
			$naver_data = explode("</tbody>",$naver_data[1]);
			$naver_data = explode("<tr>",$naver_data[0]);
			$count = sizeof($naver_data);
	?>
	<h1 class="newp_tit"><?php echo $ms['title']; ?></h1>
	<div class="new_win_body">
		<div class="guidebox tac">
	<?php
			for($i = 0; $i < $count; $i++){
				$naver_data_t = explode("</a><span class=\"productByMall_mall_cell__",$naver_data[$i]);
				//echo $naver_data_t[1];
				$naver_data_b = explode("\">",$naver_data_t[0]);
				$scount_b = sizeof($naver_data_b)-1;
				$naver_data_c = $naver_data_b[$scount_b]; //업체명1
				$naver_data_d = explode("alt=\"",$naver_data_c);
				$naver_data_e = explode("\" height",$naver_data_d[1]);
				$img_com = $naver_data_e[0]; //이미지로 된 대형몰(업체명2)
				$price_a = explode("<em>",$naver_data[$i]);
				$price_a = explode("</em>",$price_a[1]);
				if($img_com == "쿠팡" or $img_com == "티몬" or $img_com == "11번가" or $img_com == "위메프" or $img_com == "인터파크" or $img_com == "옥션" or $img_com == "G마켓" or $img_com == "ssg" or $img_com == "롯데온" or $img_com == "현대몰" or $img_com == "지에스샵" or $img_com == "씨제이온사타일" or $img_com == "홈앤쇼핑" or $img_com == "엔에스몰" or $img_com == "에이케이몰"){
					//$price_a[0] = str_replace(',','',$price_a[0]);
	?>
				<b><?php echo $img_com?> : <?php echo $price_a[0]?>원</b> <button type="button" onClick="yes('<?php echo $price_a[0]; ?>')" class="btn_small grey">선택</button>
	<?php
				}
			}
		}

	}
}

?>
	</div>
</div>

<script>
function yes(goods_price){
	opener.document.fregform.goods_price.value = goods_price;
	self.close();
}
</script>

<?php
include_once(MS_ADMIN_PATH.'/admin_tail.sub.php');
?>
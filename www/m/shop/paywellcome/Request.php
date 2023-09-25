<?php
ini_set('display_errors','0');

require_once('./libs/StdPayUtil.php');
$SignatureUtil = new StdPayUtil();
/*
  //*** 위변조 방지체크를 signature 생성 ***

  oid, price, timestamp 3개의 키와 값을

  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값

  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004


 * key기준 알파벳 정렬

 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
 */

//############################################
// 1.전문 필드 값 설정(***가맹점 개발수정***)
//############################################
// 여기에 설정된 값은 Form 필드에 동일한 값으로 설정

$call_url="https://tmobile.paywelcome.co.kr";

$mid = "welcometst";  // 가맹점 ID(가맹점 수정후 고정)					
//인증
$signKey = "QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09"; // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
$timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

$oid = $mid . "_" . $SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)
$price = "1000";        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)

$cardNoInterestQuota = ""; // 카드 분담 무이자 여부 설정(별도 카드사와 계약한 가맹점에서 직접 설정 예시: 11-2:3,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4)
$cardQuotaBase = "2:3:4";  // 가맹점에서 사용할 할부 개월수 설정

//###################################
// 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
//###################################
$mKey = $SignatureUtil->makeHash($signKey, "sha256");

$params = array(
	"mkey" => $mKey,
    "P_AMT" => $price,
    "P_OID" => $oid,
    "P_TIMESTAMP" => $timestamp
);

$sign = $SignatureUtil->makeSignature($params, "sha256");

/* 기타 */
$siteDomain = "http://218.237.49.210:8080/WPMobileSample"; //가맹점 도메인 입력
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html> 
<head>
<link rel="icon" href="data:;base64,=">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
<title>웰컴PG Mobile Sample Page</title>
<style>
	input[type=button] {
		width:100%;
		margin:5px 0;
		padding:7px;
	}
	
	input[type=text] {
		width:80%;
	}
	
	#quickMenuBtn {
		position:fixed;
		_position:absolute;
		top:0px;
		right:0px;
		background:red;
		cursor:pointer;
		color:#FFF;
		z-index:100
	}
	
	#quickMenuMiddle {
		position:fixed;
		_position:absolute;
		top:0px;
		right:48.5px;
		background:blue;
		cursor:pointer;
		color:#FFF;
		z-index:100
	}
	
	#quickMenuTop {
		position:fixed;
		_position:absolute;
		top:0px;
		right:99px;
		background:green;
		cursor:pointer;
		color:#FFF;
		z-index:100
	}
	
	.submenu .option-table {
		width : 100%;
	}
	
	.submenu .option-table input {
		width : 70%;
	}
</style>
<style>
	.option-table tr td { word-break:break-all;}
	.option-table tr > td { font-size:12px; }
</style>
</head>

<body onload="reserved_change();">
<h3>웰컴PG Mobile Sample Page</h3>
<div id="quickMenuTop" onClick="location.href='#top'">&nbsp;Top&nbsp;</div>
<div id="quickMenuMiddle" onClick="location.href='#middle'">Option</div>
<div id="quickMenuBtn" onClick="location.href='#btnSection'">Button</div>

<form name="payForm" method="post" accept-charset="euc-kr">
	<table border="1" style="width:100%;" class="option-table">
	<h4 id="top">필수정보</h4>
		<tr>
			<td class="key">P_MID</td>
			<td>
				<select style="width:48%; height:25px; margin-bottom:5px;" name="MID" onchange="mid_change(this.form)">
					<option value="<?php echo $mid ?>"><?php echo $mid ?></option>
					<option value="0">직접입력</option>
				</select>
				<br>
				<input type="text" name="P_MID" id="P_MID" value="<?php echo $mid ?>" readonly="readonly" />
			</td>
		</tr>
		
		<tr>
			<td>P_OID(가맹점 주문번호)</td>
			<td><input type="text" name="P_OID" id="P_OID" value="<?php echo $oid ?>" ></td>
		</tr>
		
		<tr>
			<td>P_AMT(금액)</td>
			<td><input type="text" class="P_AMT" name="P_AMT" id="P_AMT" value="<?php echo $price ?>"></td> 
		</tr>
		
		<tr>
			<td>P_UNAME(고객명)</td>
			<td><input type="text" name="P_UNAME" value="Hong gildong" ></td>
		</tr>
		
		<tr>
			<td>P_MNAME(가맹점)</td>
			<td><input type="text" name="P_MNAME" value="Test Merchant" ></td>
		</tr>
		
		<tr>
			<td>P_NOTI</td>
			<td><input type="text" name="P_NOTI" value="Test Oder" maxlength = "1024" ></td>
		</tr>
		
		<tr>
			<td>P_GOODS(상품명)</td>
			<td><input type="text" name="P_GOODS" value="Americano" >
		</tr>
		
		<tr>
			<td>P_MOBILE(고객 휴대폰번호)</td>
			<td><input type="text" name="P_MOBILE" value="010-1234-5678" ></td>
		</tr>
		
		<tr>
			<td>P_EMAIL(고객 이메일)</td>
			<td><input type="text" name="P_EMAIL" value="aaa@bbb.ccc"  /> </td>
		</tr>
		
		<tr>
			<td>P_CHARSET(인증/승인 결과 수신 CHARSET)</td>
			<td><select name="P_CHARSET" id="P_CHARSET"> 
				<option value="" selected>없음</option>
				<option value="utf8">utf8</option>
			</select>
			</td>
		</tr>
		
		<tr>
			<td>P_NEXT_URL(인증결과 수신 URL)</td>
			<td><input type="text" name="P_NEXT_URL" value="<?php echo $siteDomain ?>/nextUrl.php"/> </td>
		</tr>
		<tr>
			<td>P_RETURN_URL</td>
			<td><input type="text" name="P_RETURN_URL" id="return" value="<?php echo $siteDomain ?>/return" ></td>
		</tr>
		<tr>
			<td>P_NOTI_URL(노티수신 URL)</td>
			<td><input type="text" name="P_NOTI_URL" id="P_NOTI_URL" value="<?php echo $siteDomain ?>/noti" /></td>
		</tr>
		
		<tr>
			<td>P_TAX</td>
			<td><input type="text" name="P_TAX" value="" ></td>
		</tr>
		
		<tr>
			<td>P_TAXFREE</td>
			<td><input type="text" name="P_TAXFREE" value="" ></td>
		</tr>
		
		<tr>
			<td>P_TIMESTAMP</td>
			<td><input type="text" name="P_TIMESTAMP" value="<?php echo $timestamp ?>" ></td>
		</tr>
		<tr>
			<td>P_SIGNATURE</td>
			<td><input type="text" id="signature" name="P_SIGNATURE" value="<?php echo $sign ?>" ></td>
		</tr>
		<tr>
			<td rowspan="2">P_OFFER_PERIOD</td>
			<td><input type="text" name="P_OFFER_PERIOD" value="" size="50"></td>
		</tr>
	</table>
	
	<br><br>

	<div id="middle">
		<table border="1" style="width:100%;" class="option-table">
			<h4>옵션정보</h4>
			<tr>
				<td rowspan="1">P_CARD_OPTION</td>
				<td>
					<input type="text" name="P_CARD_OPTION" value="" />
					<br />
					selcode="카드코드"(selcode=14) 결제 카드 선택시 우선으로 보임(selected, 간편결제 불가능) visa3d만 : onlycard=visa3d isp만 : onlycard=isp 간편결제만 : onlycard=easypay (selcode=14:onlycard=visa3d)
				</td>
			</tr>
			<tr>
				<td rowspan="1">P_ONLY_CARDCODE</td>
				<td>
					<input type="text" name="P_ONLY_CARDCODE" value="" >
					<br/>
					가맹점 선택 카드코드 (예)03:롯데,01:외환,11:bC를 설정한 경우, 03:01:11 로 설정
				</td>
			</tr>
			<tr>
				<td rowspan="1">P_ONLY_EASYPAYCODE</td>
				<td>
					<input type="text" name="P_ONLY_EASYPAYCODE" value="" >
					<br/>
					가맹점 선택 간편결제코드 (예)KAKAOPAY:카카오페이,LPAY:엘페이,PAYCO:페이코를 설정한 경우, KAKAOPAY:LPAY:PAYCO 로 설정
				</td>
			</tr>			
			<tr>
				<td rowspan="1">P_QUOTABASE</td>
				<td>
					<input type="text" name="P_QUOTABASE" value="" >
					<br />
					가맹점 선택 할부기간 지정(36개월 MAX)
					<br/>(예) 01:02:03:04... 01은 일시불, 02는 2개월, 99은 일시불 없애는 옵션. 등등
				</td>
			</tr>
			<!-- P_HPP_METHOD -->
			<tr>
				<td>P_HPP_METHOD</td>
				<td><input type="text" name="P_HPP_METHOD" value="2" > 
				<br>(1:컨텐츠 , 2: 실물)
				</td>
			</tr>
			<tr>
				<td>P_VBANK_DT</td>
				<td><input type="text" name="P_VBANK_DT" value="" ></td>
			</tr>
			<tr>
				<td>P_VBANK_TM</td>
				<td><input type="text" name="P_VBANK_TM" value="" ></td>
			</tr> 
			<tr>
				<td class="key">P_RESERVED</td>
				<td>
				<textarea name="P_RESERVED" id="reserved" style="width:100%; height:25px" onKeyDown="reserved_keyDown(this);"></textarea><br />
				복합 파라미터<br>
			</tr>
			<tr>
				<td><b>P_RESERVED 설명</b></td>
				<td>
					<b>1. ISP 관련 옵션</b><br/>
					1) ISP앱 새창 방지 옵션 & ISP 2트렌젝션<br/>
					block_isp=Y&twotrs_isp=Y
					<input type="checkbox" name="p_rsd" value="twotrs_isp=Y&block_isp=Y&" onclick="reserved_change()" checked><br />
					2) isp 2trs 노티 미발생 옵션<br/>
					twotrs_isp_noti=N
					<input type="checkbox" name="p_rsd" value="twotrs_isp_noti=N&" onclick="reserved_change()" checked>
					3)  휴대폰 사용 통신사 옵션<br/>
					hpp_corp=SKT:KTF:LGT
					<input type="checkbox" name="p_rsd" value="hpp_corp=KTF:LGT:MVNO&" onclick="reserved_change()" checked>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>2. 카드포인트 사용하기 옵션</b><br/>
					1) cp_yn=Y
					<input type="checkbox" name="p_rsd" value="cp_yn=Y&" onclick="reserved_change()"><br/>
					<font color="red"><b>단, 직접 호출 설정시에는 </b></font> dcp_yn=Y<b> 를 사용합니다.</b><br />
					2) cp_yn=Y&cp_option=03
					<input type="checkbox" name="p_rsd" value="cp_yn=Y&cp_option=03&" onclick="reserved_change()"><br/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>3. 안심클릭/ISP 결제창 직접 호출 옵션</b><br/>
					카드사&할부> d_card=00(코드)&d_quota=00(개월)<input type="checkbox" name="p_rsd" value="d_card=&d_quota=&" onclick="reserved_change()"><br/>
					ex> d_card=04&d_quota=03<br/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>4. 상점무이자 옵션</b><br/>
					사용여부 > merc_noint=Y<br/>
					설정방법 > noint_quota=00-00:00(카드-개월:개월)<br/>
					** <b>[카드-월:월]^</b> 카드는 00 두자리, 할부개월 01->1 형태, <font color="red"><b>잘못된 예 ></b></font>  <b>[카드-월:월<font color="red">:</font>]^</b> **<br/>
					ex> merc_noint=Y&noint_quota=11-2:3<font color="red"><b>^</b></font>06-3:6:9<font color="red"><b>^</b></font>03-8:9 <font color="red"><b>카드,개월 직접 입력</b></font>
					<input type="checkbox" name="p_rsd" value="merc_noint=Y&noint_quota=04-7:9^06-6:9:12^12-9:10:12&" onclick="reserved_change()">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>5. 앱 설치 유/무 체크</b><br/>
					apprun_check=Y
					<input type="checkbox" name="p_rsd" value="apprun_check=Y&" onclick="reserved_change()" checked>
				</td>
			</tr>
			<!-- 
			<tr>
				<td></td>
				<td>
					<b>6. Escrow 사용여부</b><br/>
					useescrow=Y
					<input type="checkbox" name="p_rsd" value="useescrow=Y&" onclick="reserved_change()">
				</td>
			</tr>
			 -->
			<tr>
				<td></td>
				<td>
					<b>7. below1000(1000원 미만 결제허용 / 미사용 : 지정안해주면 자동 미사용)</b><br/>
					사용 : below1000=Y
					<input type="checkbox" name="p_rsd" value="below1000=Y&" onclick="reserved_change()">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>8. 가상계좌 현금영수증사용유무,가상계좌는 기본미사용</b><br/>
					vbank_receipt=Y
					<input type="checkbox" name="p_rsd" value="vbank_receipt=Y&" onclick="reserved_change()">
				</td>
			</tr>
		</table>
	</div>
</form>

<div id="btnSection"></div>

<br>
<input type="button" value="신용카드(visa3d,isp)" onclick="pay_submit('visa3d','<?php echo $call_url ?>');">
<input type="button" value="휴대폰" onclick="pay_submit('mobile','<?php echo $call_url ?>');">
<input type="button" value="가상계좌" onclick="pay_submit('vbank','<?php echo $call_url ?>');"">
<input type="button" value="계좌이체" onclick="pay_submit('bank','<?php echo $call_url ?>');"">

<br><br><br>
</body>

<script type="text/javascript">
	
	//지불 수단 별 호출 URL
	//paymethod : 신용카드 (visa3d), 휴대폰(mobile), 가상계좌(vbank), 계좌이체(bank)  
	//call_url : 호출할 도메인
	//type : form target 설정 (현재창:self 새창:self외)
	function pay_submit(paymethod,call_url,type){
		
		var payForm = document.payForm;
		
		if (type == 'self') {
			payForm.target = "_self";
		} else {
			payForm.target = "_blank";
		}
		
		if(call_url.substr(-1,1).indexOf("/")<0){
			call_url+="/";
		}
		
		if (paymethod == 'visa3d') {
			payForm.action = call_url + "smart/wcard/";
		} else if (paymethod == 'mobile') {
			payForm.action = call_url + "smart/mobile/";
		} else if (paymethod == 'vbank') {
			payForm.action = call_url + "smart/vbank/";
		} else if (paymethod == 'bank') {
			payForm.action = call_url + "smart/bank/";
		} else {
			alert('등록되지 않은 지불 수단 입니다(paymethod:' + paymethod + ')');
			return;
		}
		document.charset = 'euc-kr';
		payForm.submit();
	}
	
	
	
	/* 
		아래부터 현재 페이지 편의성을 위한 script.
		실제 개발시 제거해도 무방 
	*/
	// mid select box 직접입력 함수
	function mid_change(userinput) {
		var pmid = userinput.MID.value;
	
		if(pmid == "0") {
			userinput.P_MID.value="";
			userinput.P_MID.readOnly=false;
		} else {
			userinput.P_MID.value=pmid;
			userinput.P_MID.readOnly=true;
		}
	}
	
	// checked된 항목 P_RESERVED필드 추가하기 위한 함수 
	function reserved_change(){
		var checkboxs = document.getElementsByName("p_rsd");
		var rVal = "";
		for(i=0; i<checkboxs.length; i++){
			if(checkboxs[i].checked)
				rVal += checkboxs[i].value;
		}
		
		document.getElementById("reserved").value = rVal;
	}
	
	// P_RESERVED필드 height 조절 위한 함수
	function reserved_keyDown(obj){
		var reserved_value=document.getElementById("reserved").value;
		document.getElementById("reserved").setAttribute("height",(reserved_value.length/50*15)+15);
	}

</script>


</html>
<?php
if(!defined('_MALLSET_')) exit;
?>

<form name="fboardform" id="fboardform" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fboardform_submit(this);" autocomplete="off" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<h2 class="anc_tit">등록자 정보</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">등록자 이름</th>
		<td>
			<?php
			if($is_member) {
				echo $write['writer_s'];
				echo "<input type=\"hidden\" name=\"writer_s\" value=\"$write[writer_s]\">";
			} else {
				echo "<input type=\"text\" name=\"writer_s\" value=\"$write[writer_s]\" class=\"frm_input\">";
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">등록자정보</th>
		<td>
			<select name="w_info">
				<option value="">선택하세요</option>
				<option value="임대인"<?php if($write[w_info] == "임대인"){ echo " selected"; }?>>임대인</option>
				<option value="기존세입자"<?php if($write[w_info] == "기존세입자"){ echo " selected"; }?>>기존세입자</option>
				<option value="공인중개사"<?php if($write[w_info] == "공인중개사"){ echo " selected"; }?>>공인중개사</option>
			</select>
		</td>
	</tr>
</table>
</div>


<h2 class="anc_tit">위치 정보</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">제목</th>
		<td><input type="text" name="subject" value="<?php echo $write['subject']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">매물특징</th>
		<td>
			<select name="features">
				<option value="">선택하세요</option>
				<option value="빌라/주택"<?php if($write[features] == "빌라/주택"){ echo " selected"; }?>>빌라/주택</option>
				<option value="오피스텔"<?php if($write[features] == "오피스텔"){ echo " selected"; }?>>오피스텔</option>
				<option value="아파트"<?php if($write[features] == "아파트"){ echo " selected"; }?>>아파트</option>
				<option value="상가/사무실"<?php if($write[features] == "상가/사무실"){ echo " selected"; }?>>상가/사무실</option>
				<option value="토지"<?php if($write[features] == "토지"){ echo " selected"; }?>>토지</option>
				<option value="빌딩"<?php if($write[features] == "빌딩"){ echo " selected"; }?>>빌딩</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="b_zip" value="<?php echo $write['b_zip']; ?>" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('fboardform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" value="<?php echo $write['b_addr1']; ?>" required itemname="주소" class="frm_input required" size="60" readonly> 기본주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" value="<?php echo $write['b_addr2']; ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" value="<?php echo $write['b_addr3']; ?>" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr> 
	</table>
</div>

<h2 class="anc_tit">거레 정보</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">거래형태</th>
			<td>
			<select name="transaction_type">
			<option value="">선택하세요</option>
			<option value="전세"<?php if($write[transaction_type] == "전세"){ echo " selected"; }?>>전세</option>
			<option value="월세"<?php if($write[transaction_type] == "월세"){ echo " selected"; }?>>월세</option>
			<option value="단기"<?php if($write[transaction_type] == "단기"){ echo " selected"; }?>>단기</option>
			<option value="매매"<?php if($write[transaction_type] == "매매"){ echo " selected"; }?>>매매</option>
			<option value="교환"<?php if($write[transaction_type] == "교환"){ echo " selected"; }?>>교환</option>
					</select>
			</td>
		</tr>
		<tr>
			<th scope="row">전세보증금</th>
			 <td colspan="3">
			 <input type="text" name="deposit_lease" value="<?php echo $write['deposit_lease']; ?>" class="frm_input w100"> 원
		</tr>
		<tr>
			<th scope="row">월세보증금/월세</th>
			<td colspan="3">
			보증금 <input type="text" name="mon_rent_de" value="<?php echo $write['mon_rent_de']; ?>" class="frm_input w100"> 
			월세 <input type="text" name="mon_rent" value="<?php echo $write['mon_rent']; ?>" class="frm_input w100"> 
		</tr>
		<tr>
			<th scope="row">단기/보증금/월세</th>
			<td colspan="3">
			보증금 <input type="text" name="short_rent_de" value="<?php echo $write['short_rent_de']; ?>" class="frm_input w100"> 
			월세 <input type="text" name="short_rent" value="<?php echo $write['short_rent']; ?>" class="frm_input w100"> 
		</tr>
		<tr>
			<th scope="row">매매</th>
			<td><input type="text" name="dealing" value="<?php echo $write['dealing']; ?>" class="frm_input wfull"></td>
		</tr>
		<tr>
			<th scope="row">관리비</th>
				<td colspan="3">
				<input type="text" name="expenses" value="<?php echo $write['expenses']; ?>" class="frm_input w100"> 원  
				<label><input type="checkbox" name="expenses_c" value="없음"<?php if($write[expenses_c] == "없음"){ echo " checked"; }?>> 없음</label>
				<b class="marl10">공용관리비포함내역</b>
				<label><input type="checkbox" name="expenses_a1" value="전기"<?php if($write[expenses_a1] == "전기"){ echo " checked"; }?>> 전기</label>
				<label><input type="checkbox" name="expenses_a2" value="가스"<?php if($write[expenses_a2] == "가스"){ echo " checked"; }?>> 가스</label>
				<label><input type="checkbox" name="expenses_a3" value="수도"<?php if($write[expenses_a3] == "수도"){ echo " checked"; }?>> 수도</label>
				<label><input type="checkbox" name="expenses_a4" value="인터넷"<?php if($write[expenses_a4] == "인터넷"){ echo " checked"; }?>> 인터넷</label>
				<label><input type="checkbox" name="expenses_a5" value="TV"<?php if($write[expenses_a5] == "TV"){ echo " checked"; }?>> TV</label>
				<label><input type="checkbox" name="expenses_a6" value="청소"<?php if($write[expenses_a6] == "청소"){ echo " checked"; }?>> 청소</label>
			</td>
		</tr>
		<tr>
			<th scope="row">개별사용료</th>
			<td>
				<select name="expenses_b">
					<option value="">선택하세요</option>
					<option value="없음"<?php if($write[expenses_b] == "없음"){ echo " selected"; }?>>없음</option>
					<option value="전기"<?php if($write[expenses_b] == "전기"){ echo " selected"; }?>>전기</option>
					<option value="가스"<?php if($write[expenses_b] == "가스"){ echo " selected"; }?>>가스</option>
					<option value="수도"<?php if($write[expenses_b] == "수도"){ echo " selected"; }?>>수도</option>
					<option value="난방"<?php if($write[expenses_b] == "난방"){ echo " selected"; }?>>난방</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">융자여부</th>
			<td>
				<select name="loan_a">
					<option value="">선택하세요</option>
					<option value="없음"<?php if($write[loan_a] == "없음"){ echo " selected"; }?>>없음</option>
					<option value="시세대비 30% 미만"<?php if($write[loan_a] == "시세대비 30% 미만"){ echo " selected"; }?>>시세대비 30% 미만</option>
					<option value="시세대비 30% 이상"<?php if($write[loan_a] == "시세대비 30% 이상"){ echo " selected"; }?>>시세대비 30% 이상</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">융자금</th>
			<td><input type="text" name="loan" value="<?php echo $write['loan']; ?>" class="frm_input wfull"></td>
		</tr>
	</table>
</div>

<h2 class="anc_tit">물건 정보</h2>
<div class="tbl_frm01 tbl_wrap">
<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">계약/전용면적</th>
		<td><input type="text" name="contract" value="<?php echo $write['contract']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">해당층/총층</th>
		<td><input type="text" name="floor" value="<?php echo $write['floor']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">방수/욕실수</th>
		<td><input type="text" name="room" value="<?php echo $write['room']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">용도</th>
		<td>
			<select name="purpose">
				<option value="">선택하세요</option>
				<option value="주거용"<?php if($write[purpose] == "주거용"){ echo " selected"; }?>>주거용</option>
				<option value="사무용"<?php if($write[purpose] == "사무용"){ echo " selected"; }?>>사무용</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">방구조</th>
		<td>
			<select name="structure">
				<option value="">선택하세요</option>
				<option value="오픈형"<?php if($write[structure] == "오픈형"){ echo " selected"; }?>>오픈형</option>
				<option value="분리형"<?php if($write[structure] == "분리형"){ echo " selected"; }?>>분리형</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">복층여부</th>
		<td>
			<select name="double_c">
				<option value="">선택하세요</option>
				<option value="단층"<?php if($write[double_c] == "단층"){ echo " selected"; }?>>단층</option>
				<option value="복층"<?php if($write[double_c] == "복층"){ echo " selected"; }?>>복층</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">현관구조</th>
		<td>
			<select name="entrance">
				<option value="">선택하세요</option>
				<option value="계단식"<?php if($write[entrance] == "계단식"){ echo " selected"; }?>>계단식</option>
				<option value="복도식"<?php if($write[entrance] == "복도식"){ echo " selected"; }?>>복도식</option>
				<option value="복합식"<?php if($write[entrance] == "복합식"){ echo " selected"; }?>>복합식</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">주실방향기준</th>
		<td>
			<select name="direction_a">
				<option value="">선택하세요</option>
				<option value="안방"<?php if($write[direction_a] == "안방"){ echo " selected"; }?>>안방</option>
				<option value="거실"<?php if($write[direction_a] == "거실"){ echo " selected"; }?>>거실</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">추가옵션</th>
		<td class="td_label">
			<label><input type="checkbox" name="add_option1" value="신축"<?php if($write[add_option1] == "신축"){ echo " checked"; }?>> 신축</label>
			<label><input type="checkbox" name="add_option2" value="풀옵션"<?php if($write[add_option2] == "풀옵션"){ echo " checked"; }?>> 풀옵션</label>
			<label><input type="checkbox" name="add_option3" value="큰길가"<?php if($write[add_option3] == "큰길가"){ echo " checked"; }?>> 큰길가</label>
			<label><input type="checkbox" name="add_option4" value="주차가능"<?php if($write[add_option4] == "주차가능"){ echo " checked"; }?>> 주차가능</label>
			<label><input type="checkbox" name="add_option5" value="엘리베이터"<?php if($write[add_option5] == "엘리베이터"){ echo " checked"; }?>> 엘리베이터</label>
			<label><input type="checkbox" name="add_option6" value="반려동물"<?php if($write[add_option6] == "반려동물"){ echo " checked"; }?>> 반려동물</label>
			<label><input type="checkbox" name="add_option7" value="전세자금대출"<?php if($write[add_option7] == "전세자금대출"){ echo " checked"; }?>> 전세자금대출</label>
		</td>
	</tr>
	<tr>
		<th scope="row">방향</th>
		<td class="td_label">
			<label><input type="checkbox" name="direction1" value="동"<?php if($write[direction1] == "동"){ echo " checked"; }?>> 동</label>
			<label><input type="checkbox" name="direction2" value="서"<?php if($write[direction2] == "서"){ echo " checked"; }?>> 서</label>
			<label><input type="checkbox" name="direction3" value="남"<?php if($write[direction3] == "남"){ echo " checked"; }?>> 남</label>
			<label><input type="checkbox" name="direction4" value="북"<?php if($write[direction4] == "북"){ echo " checked"; }?>> 북</label>
			<label><input type="checkbox" name="direction5" value="남동"<?php if($write[direction5] == "남동"){ echo " checked"; }?>> 남동</label>
			<label><input type="checkbox" name="direction6" value="남서"<?php if($write[direction6] == "남서"){ echo " checked"; }?>> 남서</label>
			<label><input type="checkbox" name="direction7" value="북서"<?php if($write[direction7] == "북서"){ echo " checked"; }?>> 북서</label>
			<label><input type="checkbox" name="direction8" value="북동"<?php if($write[direction8] == "북동"){ echo " checked"; }?>> 북동</label>
		</td>
	</tr>
	<tr>
		<th scope="row">입주가능일</th>
		<td><input type="text" name="come_date" value="<?php echo $write['come_date']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">총주차대수</th>
		<td><input type="text" name="parking" value="<?php echo $write['parking']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">해당면적 세대수</th>
		<td><input type="text" name="households" value="<?php echo $write['households']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">준공연도</th>
		<td><input type="text" name="whenbuild" value="<?php echo $write['whenbuild']; ?>" class="frm_input wfull"></td>
	</tr>
</table>
</div>

<h2 class="anc_tit">시설 정보</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">난방(방식/연료)</th>
		<td>
			<select name="heating">
				<option value="">선택하세요</option>
				<option value="개별난방"<?php if($write[heating] == "개별난방"){ echo " selected"; }?>>개별난방</option>
				<option value="중앙난방"<?php if($write[heating] == "중앙난방"){ echo " selected"; }?>>중앙난방</option>
				<option value="지역난방"<?php if($write[heating] == "지역난방"){ echo " selected"; }?>>지역난방</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">에어컨</th>
		<td>
			<select name="air_conditioner">
			<option value="">선택하세요</option>
			<option value="없음"<?php if($write[air_conditioner] == "없음"){ echo " selected"; }?>>없음</option>
			<option value="벽걸이에어컨"<?php if($write[air_conditioner] == "벽걸이에어컨"){ echo " selected"; }?>>벽걸이에어컨</option>
			<option value="스탠드에어컨"<?php if($write[air_conditioner] == "스탠드에어컨"){ echo " selected"; }?>>스탠드에어컨</option>
			<option value="천정에어컨"<?php if($write[air_conditioner] == "천정에어컨"){ echo " selected"; }?>>천정에어컨</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">내부시설</th>
		<td class="td_label">
			<label><input type="checkbox" name="facilities1" value="냉장고"<?php if($write[facilities1] == "냉장고"){ echo " checked"; }?>> 냉장고</label>
			<label><input type="checkbox" name="facilities2" value="세탁기"<?php if($write[facilities2] == "세탁기"){ echo " checked"; }?>> 세탁기</label>
			<label><input type="checkbox" name="facilities3" value="싱크대"<?php if($write[facilities3] == "싱크대"){ echo " checked"; }?>> 싱크대</label>
			<label><input type="checkbox" name="facilities4" value="책상"<?php if($write[facilities4] == "책상"){ echo " checked"; }?>> 책상</label>
			<label><input type="checkbox" name="facilities5" value="옷장"<?php if($write[facilities5] == "옷장"){ echo " checked"; }?>> 옷장</label>
			<label><input type="checkbox" name="facilities6" value="붙박이장"<?php if($write[facilities6] == "붙박이장"){ echo " checked"; }?>> 붙박이장</label>
			<label><input type="checkbox" name="facilities7" value="침대"<?php if($write[facilities7] == "침대"){ echo " checked"; }?>> 침대</label>
			<label><input type="checkbox" name="facilities8" value="신발장"<?php if($write[facilities8] == "신발장"){ echo " checked"; }?>> 신발장</label>
			<label><input type="checkbox" name="facilities9" value="전자레인지"<?php if($write[facilities9] == "전자레인지"){ echo " checked"; }?>> 전자레인지</label>
			<label><input type="checkbox" name="facilities10" value="가스레인지"<?php if($write[facilities10] == "가스레인지"){ echo " checked"; }?>> 가스레인지</label>
			<label><input type="checkbox" name="facilities11" value="인덕션"<?php if($write[facilities11] == "인덕션"){ echo " checked"; }?>> 인덕션</label>
			<label><input type="checkbox" name="facilities12" value="가스오븐"<?php if($write[facilities12] == "가스오븐"){ echo " checked"; }?>> 가스오븐</label>
			<label><input type="checkbox" name="facilities13" value="샤워부스"<?php if($write[facilities13] == "샤워부스"){ echo " checked"; }?>> 샤워부스</label>
			<label><input type="checkbox" name="facilities14" value="욕조"<?php if($write[facilities14] == "욕조"){ echo " checked"; }?>> 욕조</label>
			<label><input type="checkbox" name="facilities15" value="비데"<?php if($write[facilities15] == "비데"){ echo " checked"; }?>> 비데</label>
			<label><input type="checkbox" name="facilities16" value="건조기"<?php if($write[facilities16] == "건조기"){ echo " checked"; }?>> 건조기</label>
			<label><input type="checkbox" name="facilities17" value="식기세적기"<?php if($write[facilities17] == "식기세적기"){ echo " checked"; }?>> 식기세적기</label>
			<label><input type="checkbox" name="facilities18" value="식탁"<?php if($write[facilities18] == "식탁"){ echo " checked"; }?>> 식탁</label>
			<label><input type="checkbox" name="facilities19" value="쇼파"<?php if($write[facilities19] == "쇼파"){ echo " checked"; }?>> 쇼파</label>
			<label><input type="checkbox" name="facilities20" value="TV"<?php if($write[facilities20] == "TV"){ echo " checked"; }?>> TV</label>
		</td>
	</tr>
	<tr>
		<th scope="row">보안 및 기타시설</th>
		<td class="td_label">
			<label><input type="checkbox" name="security1" value="현관보안"<?php if($write[security1] == "현관보안"){ echo " checked"; }?>> 현관보안</label>
			<label><input type="checkbox" name="security2" value="CCTV"<?php if($write[security2] == "CCTV"){ echo " checked"; }?>> CCTV</label>
			<label><input type="checkbox" name="security3" value="인터폰"<?php if($write[security3] == "인터폰"){ echo " checked"; }?>> 인터폰</label>
			<label><input type="checkbox" name="security4" value="비디오폰"<?php if($write[security4] == "비디오폰"){ echo " checked"; }?>> 비디오폰</label>
			<label><input type="checkbox" name="security5" value="카드키"<?php if($write[security5] == "카드키"){ echo " checked"; }?>> 카드키</label>
			<label><input type="checkbox" name="security6" value="방범창"<?php if($write[security6] == "방범창"){ echo " checked"; }?>> 방범창</label>
			<label><input type="checkbox" name="security7" value="자체경비원"<?php if($write[security7] == "자체경비원"){ echo " checked"; }?>> 자체경비원</label>
			<label><input type="checkbox" name="security8" value="사설경비"<?php if($write[security8] == "사설경비"){ echo " checked"; }?>> 사설경비</label>
			<label><input type="checkbox" name="security9" value="화재경보기"<?php if($write[security9] == "화재경보기"){ echo " checked"; }?>> 화재경보기</label>
			<label><input type="checkbox" name="security10" value="무인택배함"<?php if($write[security10] == "무인택배함"){ echo " checked"; }?>> 무인택배함</label>
			<label><input type="checkbox" name="security11" value="베란다"<?php if($write[security11] == "베란다"){ echo " checked"; }?>> 베란다</label>
			<label><input type="checkbox" name="security12" value="테라스"<?php if($write[security12] == "테라스"){ echo " checked"; }?>> 테라스</label>
			<label><input type="checkbox" name="security13" value="마당"<?php if($write[security13] == "마당"){ echo " checked"; }?>> 마당</label>
			<label><input type="checkbox" name="security14" value="소화기"<?php if($write[security14] == "소화기"){ echo " checked"; }?>> 소화기</label>
		</td>
	</tr>
</table>
</div>

<h2 class="anc_tit">게시판 정보</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<?php if(!$is_member) { ?>
	<tr>
		<th scope="row">비밀번호</th>
		<td><input name="passwd" type="password" class='frm_input'></td>
	</tr>
	<?php } ?>
	<?php if($board['use_category'] == '1') { ?>
	<tr>
		<th scope="row">지역</th>
		<td>
			<select name="ca_name">
			<option value="">선택하세요</option>
			<?php echo get_category_option($board['usecate']); ?>
			</select>
			<script>document.fboardform.ca_name.value='<?php echo $write[ca_name]; ?>';</script>
		</td>
	</tr>
	<tr>
		<th scope="row">제목 분류</th>
		<td>
			<select name="classification">
			<option value="">선택하세요</option>
			<option value="apartment"<?php if($write[classification] == "apartment"){ echo " selected"; }?>>아파트/오피스텔/분양</option>
			<option value="villa"<?php if($write[classification] == "villa"){ echo " selected"; }?>>빌라/주택</option>
			<option value="oneroom"<?php if($write[classification] == "oneroom"){ echo " selected"; }?>>원룸/투룸</option>
			<option value="shopping"<?php if($write[classification] == "shopping"){ echo " selected"; }?>>상가/업무/공장/토지</option>
			</select>
		</td>
	</tr>
	<?php } ?>
	<?php
	$option = "";
	$option_hidden = "";
	if(is_admin()) {
		unset($checked);
		if($write['btype']=='1') { $checked = 'checked'; }
		$option .= "<input type=\"checkbox\" name=\"btype\" value=\"1\" $checked> 공지사항&nbsp;&nbsp;";

		unset($checked);
		if($write['issecret']=='Y') { $checked = 'checked'; }
		$option .= "<input type=\"checkbox\" name=\"issecret\" value=\"Y\" $checked> 비밀글";
	} else {

		switch($board['use_secret']){
			case '0':
				$option_hidden .= "<input type=\"hidden\" value=\"N\" name=\"issecret\">";
				break;
			case '1':
				unset($checked);
				if($write['issecret']=='Y') { $checked = 'checked'; }
				$option .= "<input type=\"checkbox\" value=\"Y\" name=\"issecret\" $checked> 비밀글";
				break;
			case '2':
				$option_hidden .= "<input type=\"hidden\" value=\"Y\" name=\"issecret\">";
			break;
		}
	}

	echo $option_hidden;
	if($option) {
	?>
	<tr>
		<th scope="row">옵션</th>
		<td><?php echo $option; ?></td>
	</tr>
	<?php } ?>

	<tr>
		<th scope="row">내용</th>
		<td>
			<?php echo editor_html('memo', get_text($write['memo'], 0)); ?>
		</td>
	</tr>
	<?php if($board['usefile']=='Y' ) { ?>
	<tr>
		<th scope="row">이미지1</th>
		<td>
			<input type="file" name="file1">
			<?php if($write['fileurl1']) { ?>
			<a href="<?php echo MS_DATA_URL.'/board/'.$boardid.'/'.$write['fileurl1']; ?>" target="_blank"><span class="bold fc_255"><?php echo $write['fileurl1']; ?></span></a>
			<input type="checkbox" name="del_file1" value="<?php echo $write['fileurl1']; ?>"> 삭제
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">이미지2</th>
		<td>
			<input type="file" name="file2">
			<?php if($write['fileurl2']) { ?>
			<a href="<?php echo MS_DATA_URL.'/board/'.$boardid.'/'.$write['fileurl2']; ?>" target="_blank"><span class="bold fc_255"><?php echo $write['fileurl2']; ?></span></a>
			<input type="checkbox" name="del_file2" value="<?php echo $write['fileurl2']; ?>"> 삭제
			<?php } ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="글쓰기" class="btn_lsmall">
	<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">취소</a>
</div>
</form>

<script>
function fboardform_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.writer_s.value)
	{
		alert('작성자명을 입력하세요.');
		f.writer_s.focus();
		return false;
	}

	if(!f.passwd.value)
	{
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	<?php if($board['use_category'] == '1') { ?>
	if(!f.ca_name.value) {
		alert('분류를 선택하세요.');
		f.ca_name.focus();
		return false;
	}
	<?php } ?>

	if(!f.subject.value)
	{
		alert('제목을 입력하세요.');
		f.subject.focus();
		return false;
	}

	<?php echo get_editor_js('memo'); ?>
	<?php echo chk_editor_js('memo'); ?>

    return true;
}
</script>

<?php

use App\service\MemberCouponService;

define('_NEWWIN_', true);
include_once('./_common.php');
include_once(MS_ADMIN_PATH."/admin_access.php");

$ms['title'] = "회원정보수정";
include_once(MS_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$pt = get_minishop($mb_id);
?>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<form name="fmemberform" id="fmemberform" action="./pop_memberformupdate.php" onsubmit="return check_form(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="lat" id="lat" value="<? echo $mb['lat']; ?>">
<input type="hidden" name="lng" id="lng" value="<? echo $mb['lng']; ?>">
<div id="memberform_pop" class="new_win">
	<h1><?php echo $ms['title']; ?></h1>

	<section class="new_win_desc marb50">

	<ul class="anchor">
        <?php include('pop_membermenu.php'); ?>
	</ul>

	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w130">
			<col>
			<col class="w130">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">회원명</th>
			<td><input type="text" name="name" value="<?php echo $mb['name']; ?>" required itemname="회원명" class="frm_input required"><?php if($mb['intercept_date']) { ?> <strong class="fc_red">[차단된회원]</strong><?php } ?></td>
			<th scope="row">아이디</th>
			<td>
                <?php echo $mb['id']; ?>
            </td>
		</tr>
		<tr>
			<th scope="row">패스워드</th>
			<td ><input type="text" name="passwd" value="" class="frm_input"></td>
			<th scope="row">생년월일</th>
			<td>
				<input type="text" name="birth_year" value="<?php echo $mb['birth_year']; ?>" class="frm_input" size="8"> -
				<input type="text" name="birth_month" value="<?php echo $mb['birth_month']; ?>" class="frm_input" size="5"> -
				<input type="text" name="birth_day" value="<?php echo $mb['birth_day']; ?>" class="frm_input" size="5">
			</td>
		</tr>
		<tr>
			<th scope="row">성별</th>
			<td>
				<select name="gender">
					<?php echo option_selected('',  $mb['gender'], '선택'); ?>
					<?php echo option_selected('M', $mb['gender'], '남자'); ?>
					<?php echo option_selected('F', $mb['gender'], '여자'); ?>
				</select>
			</td>
			<th scope="row">&nbsp;</th>
			<td>&nbsp;</td>
		</tr>
        <tr>
            <th scope="row">입금자명</th>
            <td ><input type="text" name="pt_deposit_name" value="<?php echo $mb['pt_deposit_name']; ?>" class="frm_input"></td>
			<th scope="row">E-Mail</th>
			<td><input type="text" name="email" value="<?php echo $mb['email']; ?>" email itemname="E-Mail" class="frm_input" size="30"></td>
        </tr>
            <tr>
                <th scope="row">추천아이디</th>
                <td ><input type="hidden" name="pt_id_org" value="<?php echo $mb['pt_id']; ?>">
                    <input type="text" name="pt_id" value="<?php echo $mb['pt_id']; ?>" _required _memberid itemname="추천인아이디" class="frm_input _required">
                </td>
                <th scope="row">닉네임</th>
                <td><input type="hidden" name="pt_id_name" value="<?php echo $mb['pt_id_name']; ?>">
                    <input type="text" name="pt_id_name" value="<?php echo $mb['pt_id_name']; ?>" class="frm_input _required">
                </td>
            </tr>
        <!--tr>
            <th scope="row">주민등록번호</th>
            <td colspan="3"><input type="text" name="jumin6" id="jumin6" maxlength="6" value="<?php echo Mcrypt::jumin_decrypt($mb['jumin6']); ?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="12">
                -
                <input type="password" name="jumin7" id="jumin7" maxlength="7" value="<?php echo Mcrypt::jumin_decrypt($mb['jumin7']); ?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="14">
                <?php echo in_array(substr(Mcrypt::jumin_decrypt($mb['jumin7']),0,1), ['5','6','7','8']) ? '외국인' : '내국인'; ?>
            </td>
        </tr--> 
        <?php if( false ) :?>
		<tr>
			<th scope="row">생년월일</th>
			<td>
				<input type="text" name="birth_year" value="<?php echo $mb['birth_year']; ?>" class="frm_input" size="8"> -
				<input type="text" name="birth_month" value="<?php echo $mb['birth_month']; ?>" class="frm_input" size="5"> -
				<input type="text" name="birth_day" value="<?php echo $mb['birth_day']; ?>" class="frm_input" size="5">
			</td>
			<th scope="row">E-Mail</th>
			<td><input type="text" name="email" value="<?php echo $mb['email']; ?>" email itemname="E-Mail" class="frm_input" size="30"></td>
		</tr>
        <?php endif; ?>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $mb['telephone']; ?>"class="frm_input"></td>
			<th scope="row">휴대전화</th>
			<td colspan="3"><input type="text" name="cellphone" value="<?php echo $mb['cellphone']; ?>" class="frm_input"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td colspan="3">
				<input type="text" name="zip" id="zip" value="<?php echo $mb['zip']; ?>" class="frm_input" size="8" maxlength="5"> <a href="javascript:sample6_execDaumPostcode();" class="btn_small grey">주소검색</a>
				<p class="mart5"><input type="text" name="addr1" id="addr1" value="<?php echo $mb['addr1']; ?>" class="frm_input" size="60"> 기본주소</p>
				<p class="mart5"><input type="text" name="addr2" id="addr2" value="<?php echo $mb['addr2']; ?>" class="frm_input" size="60"> 상세주소</p>
				<p class="mart5"><input type="text" name="addr3" id="addr3" value="<?php echo $mb['addr3']; ?>" class="frm_input" size="60"> 참고항목
				<input type="hidden" name="addr_jibeon" id="addr_jibeon" value="<?php echo $mb['addr_jibeon']; ?>"></p>
			</td>
		</tr>
		<tr>
			<th scope="row">샵회원(5레벨)이미지</th>
			<td colspan="3">
				<input type="file" name="store_thumb" id="store_thumb">
				<?php
					$file = MS_DATA_PATH.'/store_img/'.$mb['store_thumb'];
					if(is_file($file) && $mb['store_thumb']) {
						$store_thumb = MS_DATA_URL.'/store_img/'.$mb['store_thumb'];
				?>
				<input type="checkbox" name="store_thumb_del" value="1" id="store_thumb_del">
				<label for="store_thumb_del">삭제</label>
				<span style="color:red;">이미지 최적 사이즈: 가로650px / 세로430px</span>
				<div class="banner_or_img"><img src="<?php echo $store_thumb; ?>" width="200"></div>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">회원레벨</th>
			<td>
				<?php echo get_member_select("mb_grade", $mb['grade']); ?>
				<? if($mb['grade'] <= 5){ ?>
				<select name="mb_category">
					<option value="">선택</option>
					<?
						$sql = sql_query("SELECT * FROM category_manage");
						while($row = sql_fetch_array($sql)){
					?>
						<option value="<? echo $row['category_name']; ?>" <? echo $row['category_name'] == $mb['mb_category'] ? "selected":""; ?> ><? echo $row['category_name']; ?></option>
					<? } ?>
				</select>
				<? } ?>
			</td>
			<th scope="row">쇼핑포인트</th>
			<td>
				<b><?php echo number_format($mb['point']); ?></b> Point
				<a href="<?php echo MS_ADMIN_URL; ?>/member/member_point_req.php?mb_id=<?php echo $mb_id; ?>" onclick="win_open(this,'pop_point_req','600','500','yes');return false;" class="btn_small grey marl10">강제적립</a>
			</td>
		</tr>
        <tr>
        <th scope="row">가맹점 점수</th>
            <td>
                <b><?php echo number_format($mb['line_point']); ?></b> Point
                <a href="<?php echo MS_ADMIN_URL; ?>/member/member_line_point_req.php?mb_id=<?php echo $mb_id; ?>" onclick="win_open(this,'line_point_req','600','500','yes');return false;" class="btn_small grey marl10">강제적립</a>
            </td>
            <th scope="row">쿠폰현황</th>
            <td>
                <?php echo MemberCouponService::get_balance($mb['id']); ?>개
                <?php MemberCouponService::pub_able_coupons($mb['id']); ?>
                <?php MemberCouponService::pub_script(); ?>
            </td>
            <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) :?>
            <th scope="row">쇼핑페이</th>
            <td>
                <b><?php echo number_format($mb['sp_point']); ?></b> P
                <a href="<?php echo MS_ADMIN_URL; ?>/member/member_shopping_pay_req.php?mb_id=<?php echo $mb_id; ?>" onclick="win_open(this,'shopping_pay_req','600','500','yes');return false;" class="btn_small grey marl10">강제적립</a>
            </td>
            <?php endif; ?>
        </tr>
		<tr class="mb_adm_fld">
			<th scope="row">부운영자 접근허용</th>
			<td colspan="3">
				<div class="sub_frm02">
					<table>
					<tr>
						<?php for($i=0; $i<5; $i++) { $k = ($i+1); ?>
						<td><input id="auth_<?php echo $k; ?>" type="checkbox" name="auth_<?php echo $k; ?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k; ?>"><?php echo $gw_auth[$i]; ?></label></td>
						<?php } ?>
					</tr>
					<tr>
						<?php for($i=5; $i<10; $i++) { $k = ($i+1); ?>
						<td><input id="auth_<?php echo $k; ?>" type="checkbox" name="auth_<?php echo $k; ?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k; ?>"><?php echo $gw_auth[$i]; ?></label></td>
						<?php } ?>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      <tr class="pt_pay_fld">
           <th scope="row" class="">엑셀ID</th>
            <td colspan="3"><input type="text" name="from_biz_id" value="<?php echo $pt['from_biz_id']; ?>" class="frm_input" size="30"></td>
        </tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">추가 판매수수료</th>
			<td colspan="3">
				<input type="text" name="payment" value="<?php echo $mb['payment']; ?>" class="frm_input" size="10">
				<select name="payflag">
					<?php echo option_selected('0', $mb['payflag'], '%'); ?>
					<?php echo option_selected('1', $mb['payflag'], '원'); ?>
				</select>
				(판매수수료를 개별적으로 추가적립 하실 수 있습니다)
			</td>
		</tr>
        <tr class="pt_pay_fld">
            <th scope="row" class="fc_red">수수료은행명</th>
            <td><input type="text" name="bank_name" value="<?php echo $pt['bank_name']; ?>" class="frm_input"></td>
            <th scope="row" class="fc_red">수수료계좌번호</th>
            <td><input type="text" name="bank_account" value="<?php echo $pt['bank_account']; ?>" class="frm_input" size="30"></td>
        </tr>
        <tr class="pt_pay_fld">
            <th scope="row" class="fc_red">수수료예금주명</th>
            <td colspan="3"><input type="text" name="bank_holder" value="<?php echo $pt['bank_holder']; ?>" class="frm_input"></td>
        </tr>
        <?php if( defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) : ?>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">페이명</th>
			<td><input type="text" name="pay_bank_name" value="<?php echo $pt['pay_bank_name']; ?>" class="frm_input"></td>
			<th scope="row" class="fc_red">페이계좌번호</th>
			<td><input type="text" name="pay_bank_account" value="<?php echo $pt['pay_bank_account']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">페이계좌주명</th>
			<td colspan="3"><input type="text" name="pay_bank_holder" value="<?php echo $pt['pay_bank_holder']; ?>" class="frm_input"></td>
		</tr> 
        <?php endif; ?> 
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">PC 쇼핑몰스킨</th>
			<td>
				<?php echo get_theme_select('theme', $mb['theme']); ?>
			</td>
			<th scope="row" class="fc_197">모바일 쇼핑몰스킨</th>
			<td>
				<?php echo get_mobile_theme_select('mobile_theme', $mb['mobile_theme']); ?>
			</td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">개별 PG결제 허용</th>
			<td class="bo_label"><label><input type="checkbox" name="use_pg" value="1"<?php echo get_checked($mb['use_pg'], '1'); ?>> 승인<span>(본사지정)</span></label></td>
			<th scope="row" class="fc_197">개별 상품판매 허용</th>
			<td class="bo_label"><label><input type="checkbox" name="use_good" value="1"<?php echo get_checked($mb['use_good'], '1'); ?>> 승인</b><span>(본사지정)</span></label></td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">개별 도메인</th>
			<td colspan="3">
				<span class="sitecode">www.</span><label><input type="text" name="homepage" value="<?php echo $mb['homepage']; ?>" class="frm_input"></label>
				단독서버인경우만 입력하세요. 예시) sample.com
			</td>
		</tr>
		<tr>
			<th scope="row">메일수신</th>
			<td>
				<input type="radio" name="mailser" value="Y" id="mb_mailling_yes"<?php echo get_checked($mb['mailser'], 'Y'); ?>>
				<label for="mb_mailling_yes">예</label>
				<input type="radio" name="mailser" value="N" id="mb_mailling_no"<?php echo get_checked($mb['mailser'], 'N'); ?>>
				<label for="mb_mailling_no">아니오</label>
			</td>
			<th scope="row">SMS수신</th>
			<td>
				<input type="radio" name="smsser" value="Y" id="mb_sms_yes"<?php echo get_checked($mb['smsser'], 'Y'); ?>>
				<label for="mb_sms_yes">예</label>
				<input type="radio" name="smsser" value="N" id="mb_sms_no"<?php echo get_checked($mb['smsser'], 'N'); ?>>
				<label for="mb_sms_no">아니오</label>
            </td>
		</tr>
		<tr>
			<th scope="row">가입일시</th>
			<td><?php echo $mb['reg_time']; ?></td>
			<th scope="row">최후아이피</th>
			<td><?php echo $mb['login_ip']; ?></td>
		</tr>
		<tr>
			<th scope="row">로그인횟수</th>
			<td><?php echo number_format($mb['login_sum']); ?> 회</td>
			<th scope="row">마지막로그인</th>
			<td><?php echo (!is_null_time($mb['today_login'])) ? $mb['today_login'] : ''; ?></td>
		</tr>
		<tr>
			<th scope="row">구매횟수</th>
			<td><?php echo number_format(shop_count($mb['id'])); ?> 회</td>
			<th scope="row">총구매금액</th>
			<td><?php echo number_format(shop_price($mb['id'])); ?> 원</td>
		</tr>
		<tr>
			<th scope="row">접근차단일자</th>
			<td>
				<input type="text" name="intercept_date" value="<?php echo $mb['intercept_date']; ?>" id="intercept_date" class="frm_input" size="10" maxlength="8">
				<input type="checkbox" value="<?php echo date("Y-m-d"); ?>" id="mb_intercept_date_set_today" onclick="if(this.form.intercept_date.value==this.form.intercept_date.defaultValue) { this.form.intercept_date.value=this.value; } else {
this.form.intercept_date.value=this.form.intercept_date.defaultValue; }">
				<label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
			</td>
            <th scope="row">가맹만료일</th>
            <td >
                <input type="text" name="term_date" value="<?php echo $mb['term_date']; ?>" id="term_date" class="frm_input" size="10" maxlength="8">
                <input type="checkbox" value="<?php echo date("Y-m-d"); ?>" id="mb_term_date_set_today" onclick="if(this.form.term_date.value==this.form.term_date.defaultValue) { this.form.term_date.value=this.value; } else {
this.form.term_date.value=this.form.term_date.defaultValue; }">
                <label for="mb_term_date_set_today">가맹만료일을 오늘로 지정</label>
            </td>
		</tr>
		<tr>
			<th scope="row">관리자메모</th>
			<td colspan="3"><textarea name="memo" class="frm_textbox" rows="3"><?php echo $mb['memo']; ?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm">
		<input type="submit" value="저장" class="btn_medium" accesskey="s">
		<button type="button" class="btn_medium bx-red" onclick="member_leave();">탈퇴</button>
		<button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
	</div>
	</section>
</div>
</form>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=ac4ff377303ec171373bc69bc9a30030&libraries=services"></script>
<script>
    var ssnCheck =function (ssn) {
        var lastid, li_mod, li_minus, li_last;
        var checkValue = 0;

        if (ssn.length != 13) return false;
        lastid = parseFloat(ssn.substring(12,13));

        checkValue += (parseInt(ssn.substring(0,1)) * 2) + (parseInt(ssn.substring(1,2)) * 3)
            + (parseInt(ssn.substring(2,3)) * 4) + (parseInt(ssn.substring(3,4)) * 5)
            + (parseInt(ssn.substring(4,5)) * 6) + (parseInt(ssn.substring(5,6)) * 7)
            + (parseInt(ssn.substring(6,7)) * 8) + (parseInt(ssn.substring(7,8)) * 9)
            + (+parseInt(ssn.substring(8,9)) * 2)  + (parseInt(ssn.substring(9,10)) * 3)
            + (parseInt(ssn.substring(10,11)) * 4) + (parseInt(ssn.substring(11,12)) * 5);

        li_mod = checkValue % 11;
        li_minus = 11 - li_mod;
        li_last = li_minus % 10;

        if (li_last != lastid) return false;

        return true;
    };

    function check_form(f){

        return true;
        if(!ssnCheck(f.jumin6.value + '' + f.jumin7.value)){
            alert('주민번호가 올바르지 않습니다.');
            f.jumin6.focus();
            return false;
        }
    }
function member_leave() {
    if(confirm("영구 탈퇴처리 하시겠습니까?\n한번 삭제된 데이터는 복구 불가능합니다.")) {
        var token = get_ajax_token();
        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }
        location.href = "./member_delete.php?mb_id=<?php echo $mb_id; ?>&token="+token;
        return true;
    } else {
        return false;
    }
}

$(function() {
    $(".pt_pay_fld").hide();
	$(".mb_adm_fld").hide();
	<?php if(is_minishop($mb[id])) { ?>
    $(".pt_pay_fld").show();
    <?php } ?>
	<?php if($mb[grade] == 1) { ?>
    $(".mb_adm_fld").show();
    <?php } ?>
	$("#mb_grade").on("change", function() {
		$(".pt_pay_fld:visible").hide();
		$(".mb_adm_fld:visible").hide();
        var level = $(this).val();
		if(level >= 2 && level <= 6) {
			$(".pt_pay_fld").show();
		} else if(level == 1) {
			$(".mb_adm_fld").show();
		}
    }).trigger('change');

});
</script>
<script>
    function sample6_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    document.getElementById("addr3").value = extraAddr;
                
                } else {
                    document.getElementById("addr3").value = '';
                }

                // 주소-좌표 변환 객체를 생성합니다
                var geocoder = new kakao.maps.services.Geocoder();

                // 주소로 좌표를 검색합니다
                geocoder.addressSearch(addr, function(result, status) {

                    // 정상적으로 검색이 완료됐으면 
                    if (status === kakao.maps.services.Status.OK) {

                        //var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
                        $("#lat").val(result[0].y);
                        $("#lng").val(result[0].x);
                    }

                });

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('zip').value = data.zonecode;
                document.getElementById("addr1").value = addr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("addr2").focus();
            }
        }).open();
    }
</script>
<?php
include_once(MS_ADMIN_PATH."/admin_tail.sub.php");
?>

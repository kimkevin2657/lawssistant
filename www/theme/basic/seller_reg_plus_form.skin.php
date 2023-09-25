<?php
if(!defined('_MALLSET_')) exit;
?>

<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/jquery-ui.css?ver=418854">
<script type="text/javascript">
jQuery(function($){
    $.datepicker.regional["ko"] = {
        closeText: "CLOSE",
        prevText: "이전달",
        nextText: "다음달",
        currentText: "TODAY",
        monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
        monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
        dayNames: ["SUN","MON","TUE","WED","THU","FRI","SAT"],
        dayNamesShort: ["SUN","MON","TUE","WED","THU","FRI","SAT"],
        dayNamesMin: ["SUN","MON","TUE","WED","THU","FRI","SAT"],
        weekHeader: "Wk",
        dateFormat: "yy-mm-dd",
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: ""
    };
$.datepicker.setDefaults($.datepicker.regional["ko"]);

// Today 버튼 코드 추가
$.datepicker._gotoToday = function(id) {
  $(id).datepicker('setDate', new Date());
  $(".ui-datepicker").hide().blur();
  };
});
function checkNumber(event) {
  if(event.key >= 0 && event.key <= 9) {
    return true;
  }
  
  return false;
}
</script>
<style>
	#redN{color:red;}
</style>
<div><img src="<?php echo MS_IMG_URL; ?>/seller_reg_from.gif" style="width: 1200px;"></div>

<form name="fsellerform" id="fsellerform" action="<?php echo $from_action_url; ?>" onsubmit="return fsellerform_submit(this);" method="post" autocomplete="off" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fsellerform_term">
	<h2>이용약관</h2>
	<textarea readonly><?php echo $config['seller_reg_agree']; ?></textarea>
	<fieldset class="fsellerform_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">위 내용을 읽었으며 약관에 동의합니다.</label>
	</fieldset>
</div>
        <h3 class="anc_tit">사이트 이용정보 입력</h3>
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="w120"><col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="reg_mb_name">회원명</label></th>
                    <td>
                        <input type="text" name="name" value="<?php echo $member['name']; ?>" id="reg_mb_name" required itemname="회원명" class="frm_input required"<?php if($w=='u' || $default['de_certify_use']) echo $readonly; ?>><span id="redN">&nbsp;&nbsp;&nbsp;필수</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="reg_mb_id">아이디</label></th>
                    <td>
						<input type="text" name="id" id="reg_mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="5" maxlength="20"><span id="redN">&nbsp;&nbsp;&nbsp;필수</span>
						<span id="msg_mb_id" class="marl5"></span>
						<span class="frm_info">※ 영문자, 숫자, _ 만 입력 가능. 최소 8자이상 입력하세요.</span>
                    </td>
                </tr>
				<tr>
					<th scope="row">추천인선택</th>
					<td colspan="3">
						<input type="text" name="pt_id" id="pt_id"<?php if($w=='') { ?> value="<?php echo $prt_id; ?>" <?php }else{ ?> value="<?php echo $member['pt_id']; ?>" <?php } ?> class="frm_input" size="30">
						<a href="pt_id_reglist.php?target=pt_id&seller=1" onclick="win_open(this,'pt_id_reglist','550','500','1'); return false" class="btn_small grey">추천인확인</a>
					</td>
				</tr>

                <?php if($w=='') { ?>
                    <tr>
                        <th scope="row"><label for="reg_mb_password">비밀번호</label></th>
                        <td>
                            <input type="password" name="passwd" id="reg_mb_password" required itemname="비밀번호" class="frm_input required" data-minlength="4" maxlength="20"><span id="redN">&nbsp;&nbsp;&nbsp;필수</span>
                            <span class="frm_info">4자 이상의 영문이나 숫자(혼용가능)</span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="reg_mb_password_re">비밀번호확인</label></th>
                        <td><input type="password" name="repasswd" id="reg_mb_password_re" required itemname="비밀번호확인" class="frm_input required" data-minlength="4" maxlength="20"><span id="redN">&nbsp;&nbsp;&nbsp;필수</span></td>
                    </tr>
                <?php } else if($w=='u') { ?>
                    <tr>
                        <th scope="row"><label for="reg_mb_password_db">현재비밀번호</label></th>
                        <td><input type="password" name="dbpasswd" id="reg_mb_password_db" required itemname="현재비밀번호" class="frm_input required" data-minlength="4" maxlength="20"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="reg_mb_password">새비밀번호</label></th>
                        <td>
                            <input type="password" name="passwd" id="reg_mb_password" class="frm_input" data-minlength="4" maxlength="20">
                            <span class="frm_info">4자 이상의 영문 및 숫자</span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="reg_mb_password_re">새비밀번호확인</label></th>
                        <td><input type="password" name="repasswd" id="reg_mb_password_re" class="frm_input" data-minlength="4" maxlength="20"></td>
                    </tr>
                <?php } ?>
                    <tr>
                      <th scope="row"><label for="reg_birth">생년월일</label></th>
                      <td>
                        <input type="text" name="birthday" id="reg_birth" itemname="생년월일" readonly required  class="frm_input required" maxlength="20">
                        <span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span>
                        <span class="frm_info">※ (ex. 1975-01-01)</span>
                      </td>
                    </tr>

                    <tr>
                      <th scope="row"><label for="reg_gender">성별</label></th>
                      <td style="padding-left:24px;">
                        남 <input type="radio" name="gender" value="M" id="reg_gender">
                        여 <input type="radio" name="gender" value="W" id="reg_gender1" checked>
                      </td>
                    </tr>				
                <?php if($config['register_use_tel']) { ?>
                    <tr>
                        <th scope="row"><label for="reg_telephone">전화번호</label></th>
                        <td>
                            <input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" id="reg_telephone"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>">
                        </td>
                    </tr>
                <?php } ?>
                <?php if($config['register_use_hp']) { ?>
                    <tr>
                        <th scope="row"><label for="reg_cellphone">핸드폰</label></th>
                        <td>
                            <input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" id="reg_cellphone"<?php echo $config['register_req_hp']?' required':''; ?> itemname="핸드폰" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" onkeypress='return checkNumber(event)'><span id="redN">&nbsp;&nbsp;&nbsp;필수</span>
                            <div class="padt5">
                                <input type="checkbox" name="smsser" value="Y" id="smsser_yes"<?php echo get_checked('Y', $member['smsser']); ?> class="css-checkbox lrg"><label for="smsser_yes" class="css-label">SMS를 수신합니다.</label>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if($config['register_use_email']) { ?>
                    <tr>
                        <th scope="row"><label for="reg_mb_email">이메일</label></th>
                        <td>
                            <input type="email" name="email" value="<?php echo $member['email']; ?>" id="reg_mb_email"<?php echo $config['register_req_email']?' required':''; ?> itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>"><span id="redN">&nbsp;&nbsp;&nbsp;필수</span>
                            <div class="padt5">
                                <input type="checkbox" name="mailser" value="Y" id="mailser_yes"<?php echo get_checked('Y', $member['mailser']); ?> class="css-checkbox lrg"><label for="mailser_yes" class="css-label">E-Mail을 수신합니다. / 비밀번호 분실 시 메일로 확인 가능</label>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if($config['register_use_addr']) { ?>
                    <tr>
                        <th scope="row">주소</th>
                        <td>
                            <label for="reg_mb_zip" class="sound_only">우편번호</label>
                            <input type="text" name="zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="5" maxlength="5" readonly>
                            <button type="button" class="btn_small grey" onclick="win_zip('fsellerform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">주소검색</button><span id="redN">&nbsp;&nbsp;&nbsp;필수</span><br>

                            <label for="reg_mb_addr1" class="sound_only">주소</label>
                            <input type="text" name="addr1" size="60" value="<?php echo $member['addr1']; ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" readonly><br>

                            <label for="reg_mb_addr2" class="sound_only">상세주소</label>
                            <input type="text" name="addr2" size="60" value="<?php echo $member['addr2']; ?>" id="reg_mb_addr2" class="frm_input frm_address"><br>

                            <label for="reg_mb_addr3" class="sound_only">참고항목</label>
                            <input type="text" name="addr3" size="60" value="<?php echo $member['addr3']; ?>" id="reg_mb_addr3" class="frm_input frm_address" readonly>
                            <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
<h3 class="anc_tit mart30">사업자정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
<input type="hidden" name="up_id" id="up_id" itemname="추천인아이디" class="frm_input" size="30" readonly value="<?php echo $pt_id;?>">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_seller_item">제공상품</label></th>
		<td><input type="text" name="seller_item" id="reg_seller_item" itemname="제공상품" class="frm_input required" required size="30" placeholder="예) 가전제품"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_name">업체(법인)명</label></th>
		<td><input type="text" name="company_name" id="reg_company_name" itemname="업체(법인)명" class="frm_input required" required size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_owner">대표자명</label></th>
		<td><input type="text" name="company_owner" id="reg_company_owner" itemname="대표자명" class="frm_input required" required size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_saupja_no">사업자등록번호</label></th>
		<td><input type="text" name="company_saupja_no" id="reg_company_saupja_no" itemname="사업자등록번호" class="frm_input required" required size="30" placeholder="예) 206-23-12552"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row">사업자등록증 사본</th>
		<td>
			<input type="file" name="bn_file1" id="bn_file1">
            <span class="frm_info">※ 이미지용량은 (1MB이하) .JPG / .PNG 파일만 업로드해주세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row">통신판매업신고증 사본</th>
		<td>
			<input type="file" name="bn_file2" id="bn_file2"><span id="redN">※통신판매신고증이 없을경우 1522-0992 올마켓 고객센터로 문의해주세요</span><span class="frm_info">※ 이미지용량은 (1MB이하) .JPG / .PNG 파일만 업로드해주세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row">입금계좌 통장사본</th>
		<td>
			<input type="file" name="bn_file3" id="bn_file3">
            <span class="frm_info">※ 이미지용량은 (1MB이하) .JPG / .PNG 파일만 업로드해주세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row">건강기능식품 영업신고증사본<br>(건강식품판매사일 경우 필수)</th>
		<td>
			<input type="file" name="bn_file4" id="bn_file4">
            <span class="frm_info">※ 이미지용량은 (1MB이하) .JPG / .PNG 파일만 업로드해주세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_item">업태</label></th>
		<td><input type="text" name="company_item" id="reg_company_item" itemname="업태" class="frm_input required" required size="30" placeholder="예) 서비스업"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_service">종목</label></th>
		<td><input type="text" name="company_service" id="reg_company_service" itemname="종목" class="frm_input required" required size="30" placeholder="예) 전자상거래업"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_tel">전화번호</label></th>
		<td><input type="text" name="company_tel" id="reg_company_tel" class="frm_input required" required size="30" placeholder="예) 02-1234-5678"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_fax">팩스번호</label></th>
		<td><input type="text" name="company_fax" id="reg_company_fax" class="frm_input required" required size="30" placeholder="예) 02-1234-5678"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row">사업장주소</th>
		<td>
			<label for="reg_company_zip" class="sound_only">우편번호</label>
			<input type="text" name="company_zip" id="reg_company_zip" required itemname="우편번호" class="required frm_input" size="8" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fsellerform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');">주소검색</button><br>

			<label for="reg_company_addr1" class="sound_only">주소</label>
			<input type="text" name="company_addr1" id="reg_company_addr1" required itemname="기본주소" class="required frm_input frm_address" size="60" readonly> 기본주소<br>

			<label for="reg_company_addr2" class="sound_only">상세주소</label>
			<input type="text" name="company_addr2" id="reg_company_addr2" class="frm_input frm_address" size="60"> 상세주소<br>

			<label for="reg_company_addr3" class="sound_only">참고항목</label>
			<input type="text" name="company_addr3" id="reg_company_addr3" class="frm_input frm_address" size="60" readonly> 참고항목
			<input type="hidden" name="company_addr_jibeon" value="">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_hompage">홈페이지</label></th>
		<td><input type="text" name="company_hompage" id="reg_company_hompage" class="frm_input" size="30" placeholder="예) http://homepage.com"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_memo">전달사항</label></th>
		<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox wfull h60"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">입금계좌정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_bank_name">은행명</label></th>
		<td><input type="text" name="bank_name" id="reg_bank_name" class="frm_input required" required size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_account">계좌번호</label></th>
		<td><input type="text" name="bank_account" id="reg_bank_account" class="frm_input required" required size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_holder">예금주명</label></th>
		<td><input type="text" name="bank_holder" id="reg_bank_holder" class="frm_input required" required size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">담당자정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_info_name">담당자명</label></th>
		<td><input type="text" name="info_name" id="reg_info_name" required itemname="담당자명" class="required frm_input" size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_tel">담당자 핸드폰</label></th>
		<td><input type="text" name="info_tel" id="reg_info_tel" required itemname="담당자 핸드폰" class="required frm_input" size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_email">담당자 이메일</label></th>
		<td><input type="text" name="info_email" id="reg_info_email" required email itemname="담당자 이메일" class="required frm_input" size="30"><span id="msg_mb_id" class="marl5"></span><span id="redN">&nbsp;필수</span></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="신청하기" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo MS_URL; ?>" class="btn_large bx-white">취소</a>
</div>
</form>

<script>
function fsellerform_submit(f) {
	if(!f.agree.checked) {
		alert("약관에 동의하셔야 신청 가능합니다.");
		f.agree.focus();
		return false;
	}

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n신청하시겠습니까?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}



function reg_mb_id_ajax() {
	var mb_id = $.trim($("#reg_mb_id").val());

	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
// 추가 끝




function fregisterform_submit(f)
{
	var str;
	<?php if($w=='') { ?>
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}
	<?php } ?>

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert('아이디를 3글자 이상 입력하십시오.');
		f.id.focus();
		return false;
	}

	<?php if($w=='') { ?>

//    if(!number_check(f.jumin6.value + '' + f.jumin7.value)) return false;

    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert('패스워드를 4글자 이상 입력하십시오.');
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert('패스워드가 같지 않습니다.');
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert('패스워드를 4글자 이상 입력하십시오.');
            f.repasswd.focus();
            return false;
        }
    }
	if(f.cellphone.value.length < 10 || f.cellphone.value.length > 11) {
		alert('핸드폰 번호는 10~11자리를 입력하여주세요.');
		f.cellphone.focus();
		return false;
	}

//    if( $('#from_biz_id').length > 0 && $('#from_biz_id').val() == '' ) {
//        alert('엑셀ID를 입력하세요');
//        return false;
//    }
//
//    if( parseInt($('#receipt_price').val()) > 0 && $('.holder--good_no-label:visible').prev('input').not(':checked').length > 0 ) {
//        alert('구매 상품을 체크 하세요.');
//        return false;
//    }
//
    // if( $('canvase.signature').size() > 0 && $('#signatureJSON').val() == '' || $('#signatureJSON').val() == $('#signatureJSON').data('default')) {
    //     alert('서명 하세요');
    //     return false;
    // }
    // 입금자명 확인
 //   if( $('#bank_acc').is(':visible') && $('#bank_acc').val() == '' ) {
 //       alert('입금 계좌를 선택하세요.');
 //       return false;
 //   }
 //
 //   if( $('#deposit_name').is(':visible') && $('#deposit_name').val() == '' ) {
 //       alert('입금자명을 입력하세요.');
 //       return false;
 //   }
 //
        str = "회원가입";
	<?php } else if($w=='u') { ?>


//    if( $('#jumin6').size() > 0 && $('#jumin7').size() > 0 ) {
//        if(!ssnCheck(f.jumin6.value + '' + f.jumin7.value)){
//            alert('주민번호가 올바르지 않습니다.');
//            f.jumin6.focus();
//            return false;
//        }
//    }
//
//	
//

	if( f.passwd.value) {
		// 패스워드 검사
		if(f.passwd.value.length < 4) {
			alert('패스워드를 4글자 이상 입력하십시오.');
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert('패스워드가 같지 않습니다.');
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert('패스워드를 4글자 이상 입력하십시오.');
				f.repasswd.focus();
				return false;
			}
		}
	}

	str = "정보수정";
	<?php } ?>

//    if( $('[name=marketing_yn]').filter(':checked').size() == 0 ) {
//        alert('신상품 출시, 홍보 안내를 위하여 개인정보를 이용 동의 여부를 체크 하세요.');
//        return false;
//    }
//
	<?php if($config['register_use_email']) { ?>
	
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}

	<?php } ?>

	if(confirm(str+" 하시겠습니까?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['prohibit_id'])); ?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
}

// 금지 메일 도메인 검사
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 메일 도메인만 얻는다

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

$(function(){
  $("#reg_birth").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d", duration: "normal", showAnim: "slide" });
});
</script>
<style>
.ui-datepicker-calendar th { background-color:#ff0099; }
</style>

`

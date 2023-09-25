<?php
if(!defined('_MALLSET_')) exit;
?>

<form name="fregisterform" method="post" action="./member/member_register_form_update.php" onsubmit="return fregisterform_submit(this);">
<input type="hidden" name="token" value="">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w130">
		<col>
        <col class="w130">
        <col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">아이디</th>
		<td colspan="3">
			<input type="text" name="id" id="mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="3" maxlength="20">
			<span id="msg_mb_id" class="marl5"></span>
			<?php echo help('영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호</th>
		<td>
			<input type="password" name="passwd" required itemname="비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20">
			<?php echo help('4자 이상의 영문 및 숫자'); ?>
		</td>
		<th scope="row">비밀번호확인</th>
		<td><input type="password" name="repasswd" required itemname="비밀번호확인" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
	</tr>
    <?php if(false) : ?>
	<tr>
		<th scope="row">생년월일</th>
		<td>
			<input name="birth_year" required itemname="생년월일" class="frm_input required" size="8" maxlength="4"> 년
			<input name="birth_month" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 월
			<input name="birth_day" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 일
			<select name="birth_type">
				<option value="S">양력</option>
				<option value="L">음력</option>
			</select>
			<select name="gender">
				<option value="">성별</option>
				<option value="M">남자</option>
				<option value="F">여자</option>
			</select>
		</td>
	</tr>
    <?php endif; ?>
    <tr>
        <th scope="row">회원명</th>
        <td><input type="text" name="name" required itemname="회원명" class="frm_input required" size="20"></td>
       <th scope="row">주민등록번호</th>
        <td><input type="text" name="jumin6" id="jumin6" maxlength="6" value="" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="6">
            -
            <input type="password" name="jumin7" id="jumin7" maxlength="7" value="" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="7"></td>
    </tr>
	<?php if($config['register_use_tel'] || $config['register_use_hp']) { ?>
	<tr>
        <?php if($config['register_use_tel']) { ?>
		<th scope="row">전화번호</th>
		<td colspan="<?php echo ( $config['register_use_hp'] ) ? '' : '3'; ?>"><input type="text" name="telephone" size="20"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>"></td>

	    <?php } ?>
	    <?php if($config['register_use_hp']) { ?>

		<th scope="row">휴대전화</th>
		<td colspan="<?php echo ( $config['register_use_tel'] ) ? '' : '3'; ?>">
			<input type="text" name="cellphone" size="20"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<label><input type="checkbox" value="Y" name="smsser" checked> SMS를 수신합니다.</label>
		</td>
        <?php } ?>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row">이메일</th>
		<td colspan="3">
			<input type="text" name="email"<?php echo $config['register_req_email']?' required':''; ?>
			email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
			<label><input type="checkbox" value="Y" name="mailser" checked> E-Mail을 수신합니다.</label>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소</th>
		<td colspan="3">
			<div>
				<input type="text" name="zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
			</div>
			<div class="mart5">
				<input type="text" name="addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 기본주소
			</div>
			<div class="mart5">
				<input type="text" name="addr2" class="frm_input" size="60"> 상세주소
			</div>
			<div class="mart5">
				<input type="text" name="addr3" class="frm_input" size="60" readonly> 참고항목
				<input type="hidden" name="addr_jibeon" value="">
			</div>
		</td>
	</tr>
	<?php } ?>
    <tr>
		<th scope="row">대표 이미지</th>
		<td>
			<input type="file" name="store_thumb" id="store_thumb">
			<?php
                $file = MS_DATA_PATH.'/store_img/'.$mb['store_thumb'];
                if(is_file($file) && $mb['store_thumb']) {
                    $store_thumb = MS_DATA_URL.'/store_img/'.$mb['store_thumb'];
			?>
			<input type="checkbox" name="store_thumb_del" value="1" id="store_thumb_del">
			<label for="store_thumb_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $store_thumb; ?>"></div>
			<?php } ?>
		</td>
	</tr>
    <tr>
        <th scope="row">지점</th>
        <td colspan="3"><?php echo minishop::selectBoxCenter('pc_no', '', '', '해당없음'); ?></td>
    </tr>
	<tr>
		<th scope="row">추천인</th>
		<td><input type="text" name="pt_id" value="admin" required itemname="추천인" class="frm_input required"></td>
	    <th scope="row">추천인</th>
        <td><input type="text" name="up_id" value="admin"  itemname="추천인" class="frm_input "></td>
    </tr>

    <tr>
        <th scope="row">회원레벨</th>
        <td colspan="3">
            <?php echo get_member_select("mb_grade", $mb['grade']); ?>
            <span style="margin-left:10px; display:inline-block" class="pt_pay_fld">
                <input type="checkbox" name="minishop_state" value="1" id="minishop_state"> <label style="color:red;" for="minishop_state">승인 (가맹점)</label>
            </span>
        </td>
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
        <th scope="row" class="fc_red">가맹점 가입비</th>
        <td colspan="3">

            <select name="receipt_price">
                <?php
                $price_sql = "select distinct receipt_price from 
                              (select gb_anew_price  receipt_price from shop_member_grade union all 
                               select biz_anew_price receipt_price from shop_minishop_type) a 
                              order by receipt_price desc";
                $price_rslt= sql_query($price_sql);
                while($price_row = sql_fetch_array($price_rslt)){
                    ?><option value="<?php echo $price_row['receipt_price']?>"><?php echo number_format($price_row['receipt_price']); ?></option><?php
                }
                ?>
            </select>원
        </td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_red">추가 판매수수료</th>
        <td colspan="3">
            <input type="text" name="payment" value="<?php echo maybe($mb['payment'], 0); ?>" class="frm_input" size="10">
            <select name="payflag">
                <?php echo option_selected('0', $mb['payflag'], '%'); ?>
                <?php echo option_selected('1', $mb['payflag'], '원'); ?>
            </select>
            (판매수수료를 개별적으로 추가적립 하실 수 있습니다)
        </td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="">타법인명</th>
        <td><input type="text" name="from_biz_name" value="<?php echo $pt['from_biz_name']; ?>" class="frm_input"></td>
        <th scope="row" class="">엑셀ID</th>
        <td><input type="text" name="from_biz_id" value="<?php echo $pt['from_biz_id']; ?>" class="frm_input" size="30"></td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="">타법인명직급</th>
        <td><select name="from_biz_job_title" id="from_biz_job_title">
                <option value="">해당없음</option>
                <option value="과장" <?php echo $pt['from_biz_job_title'] == '과장' ? ' selected' : ''; ?>>과장</option>
                <option value="부장" <?php echo $pt['from_biz_job_title'] == '부장' ? ' selected' : ''; ?>>부장</option>
                <option value="이사" <?php echo $pt['from_biz_job_title'] == '이사' ? ' selected' : ''; ?>>이사</option>
                <option value="지점장" <?php echo $pt['from_biz_job_title'] == '지점장' ? ' selected' : ''; ?>>지점장</option>
            </select></td>
        <th scope="row" class="">타법인명등급</th>
        <td><select name="from_biz_grade" id="from_biz_grade">
                <option value="">해당없음</option>
                <option value="정회원" <?php echo $pt['from_biz_grade'] == '정회원' ? ' selected' : ''; ?>>정회원</option>
                <option value="VIP회원" <?php echo $pt['from_biz_grade'] == 'VIP회원' ? ' selected' : ''; ?>>VIP회원</option>
            </select></td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_red">수수료은행명</th>
        <td><input type="text" name="bank_name" value="<?php echo maybe($pt['bank_name'], ''); ?>" class="frm_input"></td>
        <th scope="row" class="fc_red">수수료계좌번호</th>
        <td><input type="text" name="bank_account" value="<?php echo maybe($pt['bank_account'], ''); ?>" class="frm_input" size="30"></td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_red">수수료예금주명</th>
        <td colspan="3"><input type="text" name="bank_holder" value="<?php echo maybe($pt['bank_holder'], ''); ?>" class="frm_input"></td>
    </tr>
    <?php if( defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) : ?>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_red">페이은행명</th>
        <td><input type="text" name="pay_bank_name" value="<?php echo maybe($pt['pay_bank_name'], ''); ?>" class="frm_input"></td>
        <th scope="row" class="fc_red">페이계좌번호</th>
        <td><input type="text" name="pay_bank_account" value="<?php echo maybe($pt['pay_bank_account'], ''); ?>" class="frm_input" size="30"></td>
    </tr>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_red">페이예금주명</th>
        <td colspan="3"><input type="text" name="pay_bank_holder" value="<?php echo maybe($pt['pay_bank_holder'], ''); ?>" class="frm_input"></td>
    </tr>
    <?php endif; ?>
    <tr class="pt_pay_fld">
        <th scope="row" class="fc_197">PC 쇼핑몰스킨</th>
        <td>
            <?php echo get_theme_select('theme', maybe($mb['theme'],'basic')); ?>
        </td>
        <th scope="row" class="fc_197">모바일 쇼핑몰스킨</th>
        <td>
            <?php echo get_mobile_theme_select('mobile_theme', maybe($mb['mobile_theme'], 'basic')); ?>
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
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>

<script>

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
        var receiptPrice = $(this).find('option:selected').data('anewPrice');
        $('[name=receipt_price]').val(receiptPrice);
        var level = $(this).val();
        if(level >= PARTNER_LEVEL_MIN && level <= PARTNER_LEVEL_MAX) {
            $(".pt_pay_fld").show();
        } else if(level == 1) {
            $(".mb_adm_fld").show();
        }
    }).trigger('change');
});

// 내국인
var ssnCheck =function (rrn) {
    var sum = 0;

    if (rrn.length != 13) {
        return false;
    } else if (rrn.substr(6, 1) != 1 && rrn.substr(6, 1) != 2 && rrn.substr(6, 1) != 3 && rrn.substr(6, 1) != 4) {
        return false;
    }

    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
    }

    if (((11 - (sum % 11)) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }

    return false;


    //출처: https://yangyag.tistory.com/356 [Hello Brother!]
}

var number_check = function(ssn){

    if(ssn.charAt(6) == '1' || ssn.charAt(6) == '3' ||
        ssn.charAt(6) == '2' || ssn.charAt(6) == '4') {
        // 국내 주민번호 체크
        if (ssnCheck(ssn) == false ) {
            alert('주민번호를 정확히 입력하십시오.');
            return false;
        }
    } else {
        // 외국인 등록번호 체크
        if(isFrgNo(ssn) == false) {
            alert( "외국인 등록번호를 정확히 입력하십시오." );
            return false;
        }
    }

    return true;
};


// 외국인
var isFrgNo = function(rrn) {
    var sum = 0;

    if (rrn.length != 13) {
        return false;
    } else if (rrn.substr(6, 1) != 5 && rrn.substr(6, 1) != 6 && rrn.substr(6, 1) != 7 && rrn.substr(6, 1) != 8) {
        return false;
    }

    if (Number(rrn.substr(7, 2)) % 2 != 0) {
        return false;
    }

    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
    }

    if ((((11 - (sum % 11)) % 10 + 2) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }

    return false;

};

function fregisterform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert("아이디를 3글자 이상 입력하십시오.");
		f.id.focus();
		return false;
	}

    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert("패스워드를 4글자 이상 입력하십시오.");
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert("패스워드가 같지 않습니다.");
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert("패스워드를 4글자 이상 입력하십시오.");
            f.repasswd.focus();
            return false;
        }
    }

    if(!number_check(f.jumin6.value + '' + f.jumin7.value)) return false;

	<?php if($config['register_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n저장 하시려면 '확인'버튼을 클릭하세요") == false)
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

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>

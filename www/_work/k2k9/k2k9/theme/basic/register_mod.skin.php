<?php
if(!defined('_TUBEWEB_')) exit;

Theme::get_theme_part(TB_THEME_PATH,'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<h3>사이트 이용정보 입력</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">회원명</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" <?php echo $readonly; ?> class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">아이디</th>
			<td><input type="text" name="id" value="<?php echo $member['id']; ?>" <?php echo $readonly; ?> class="frm_input" size="20" minlength="3" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">현재비밀번호</th>
			<td><input type="password" name="dbpasswd" required itemname="현재비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">새비밀번호</th>
			<td><input type="password" name="passwd" class="frm_input" size="20" data-minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">새비밀번호확인</th>
			<td><input type="password" name="repasswd" class="frm_input" size="20" data-minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="mart30">개인정보 입력</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
        <?php if( Mcrypt::jumin_decrypt($member['jumin6']) && Mcrypt::jumin_decrypt($member['jumin7'])) : ?>
            <tr>
                <th scope="row">주민등록번호</th>
                <td>
                    <?php echo Mcrypt::jumin_decrypt($member['jumin6']); ?>
                    -
                    <?php echo substr(Mcrypt::jumin_decrypt($member['jumin7']),0,1).'******'; ?></td>
            </tr>
        <?php else : ?>
            <tr>
                <th scope="row">주민등록번호</th>
                <td><input type="text" name="jumin6" id="jumin6" maxlength="6" value="<?php echo Mcrypt::jumin_decrypt($member['jumin6'])?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="6">
                    -
                    <input type="password" name="jumin7" id="jumin7" maxlength="7" value="<?php echo Mcrypt::jumin_decrypt($member['jumin7'])?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="주민등록번호" class="frm_input required" size="7"></td>
            </tr>
        <?php endif; ?>
        <?php if( false ) : ?>
		<tr>
			<th scope="row">생년월일</th>
			<td>
				<div class="ini_wrap">
				<table>
				<tr>
					<td><input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" required itemname="생년월일" class="frm_input required" size="8" maxlength="4"> 년</td>
					<td class="padl5"><input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 월</td>
					<td class="padl5"><input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 일</td>
					<td class="padl5">
						<select name="gender">
						<option value="">성별</option>
						<option value="M"<?php echo get_selected($member['gender'],"M"); ?>>남자</option>
						<option value="F"<?php echo get_selected($member['gender'],"F"); ?>>여자</option>
						</select>
					</td>
					<td class="padl5">
						<select name="birth_type">
						<option value="S"<?php echo get_selected($member['birth_type'],"S"); ?>>양력</option>
						<option value="L"<?php echo get_selected($member['birth_type'],"L"); ?>>음력</option>
						</select>
					</td>
				</tr>
				</table>
				</div>
			</td>
		</tr>
        <?php endif; ?>
		<?php if($config['register_use_tel']) { ?>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20"></td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_hp']) { ?>
		<tr>
			<th scope="row">휴대전화</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20">
				<input type="checkbox" value="Y" name="smsser" class="marl7"<?php echo $member['smsser'] == 'Y'?' checked':''; ?>> SMS를 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_email']) { ?>
		<tr>
			<th scope="row">이메일</th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email']; ?>"<?php echo $config['register_req_email']?' required':''; ?> email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
				<input type="checkbox" value="Y" name="mailser" class="marl7"<?php echo $member['mailser'] == 'Y'?' checked':''; ?>> E-Mail을 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_addr']) { ?>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
					<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">주소검색</a>
				</div>
				<div class="mart5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 기본주소
				</div>
				<div class="mart5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="mart5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60"> 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
    <h3>
        신상품 출시, 홍보 안내를 위하여 개인정보를 이용하는데 (전화,문자,우편)
        <ul class="btn_group mart5">
            <li>
                <input class="css-checkbox" type="radio" name="marketing_yn" <?php if( $member['marketing_yn'] ) echo ' checked="checked" '; ?>value="1" id="marketing_yn1">
                <label class="css-label" for="marketing_yn1">동의</label>

                <input class="css-checkbox marl10" type="radio" name="marketing_yn"  <?php if( !$member['marketing_yn'] ) echo ' checked="checked" '; ?> value="0" id="marketing_yn2">
                <label class="css-label" for="marketing_yn2">동의하지 않음</label>
            </li>
        </ul>
    </h3>
	<div class="btn_confirm">
		<input type="submit" value="정보수정" id="btn_submit" class="btn_large wset" accesskey="s">
		<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
	</div>
	</form>
</div>

<script>

    // 내국인
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
    }


    // 외국인
    var isFrgNo = function(fgnno) {
        var sum = 0;
        var odd = 0;
        buf = new Array(13);

        for(i=0; i<13; i++) buf[i] = parseInt(fgnno.charAt(i));

        odd = buf[7]*10 + buf[8];

        if(odd%2 != 0) return false;

        if((buf[11]!=6) && (buf[11]!=7) && (buf[11]!=8) && (buf[11]!=9)) return false;

        multipliers = [2,3,4,5,6,7,8,9,2,3,4,5];

        for(i=0, sum=0; i<12; i++) sum += (buf[i] *= multipliers[i]);

        sum = 11 - (sum%11);

        if(sum >= 10) sum -= 10;

        sum += 2;
        if(sum >= 10) sum -= 10;

        if(sum != buf[12]) return false;

        return true;
    }


    function fregisterform_submit(f)
{
    if( $('#jumin6').size() > 0 && $('#jumin7').size() > 0 ) {
        if( !ssnCheck(f.jumin6.value + '' + f.jumin7.value) ) {
            alert('주민등록번호가 올바르지 않습니다.');
            f.jumin6.focus();
            return false;
        }
    }

	if(f.passwd.value) {
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
	}

	<?php if($config['register_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
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
</script>

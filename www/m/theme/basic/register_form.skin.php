<?php
if(!defined('_MALLSET_')) exit;
function get_sub_domain(){
	preg_match("/(([a-z0-9\-]+\.)*)([a-z0-9\-]+)\.([a-z]{3,4}|[a-z]{2,3}\.[a-z]{2})(\:[0-9]+)?$/", $_SERVER['HTTP_HOST'], $matches);
	$sub_domain = null;
	if($matches[1]) {
		$sub_domain = substr($matches[1], 0, -1);
	}
	return $sub_domain;
}
$prt_id = '';
$prt_id = get_sub_domain();
if(!$member['sns_id']){
$readonly1=$readonly;
}
?>

<style>
.ui-datepicker-calendar th { background-color:#454545; }
</style>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/jquery-ui.css?ver=438854">
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


<h2 class="pg_titb">
    <p class="titxt">MEMBER JOIN</p>
	<p class="pg_nav">HOME<i class="ionicons ion-ios-arrow-right"></i>회원가입</p>
    <span>회원가입</span>
   </h2>

 <div class="register">

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="grade" id="grade" value="<?php echo $join_type == 'fb_none' ? 6 : 5; ?>">
<?php if($prt_id){ ?>
	<!--input type="hidden" name="prt_id" id="prt_id"  value="<?php echo $prt_id; ?>"-->
<?php } ?>
  
             
        <div class="form_01">           
               <h3 class="anc_tit">사이트 이용정보 입력</h3>                       
                <ul>
                                  
                    <!--th scope="row"><label for="reg_mb_id">아이디</label></th-->
                    <li>
						<input type="text" name="id" id="reg_mb_id" required memberid itemname="아이디" value="<?php echo $member['id']; ?>" class="frm_input required" onkeyup="reg_mb_id_ajax();" <?php if($w=='u' || $default['de_certify_use']) echo $readonly; ?> size="11" minlength="5" maxlength="20" placeholder="아이디  (필수)">
						<span id="msg_mb_id" class="frm_info"></span>
						<span class="frm_info">※ 영문자, 숫자, _ 만 입력 가능. 최소 5자이상 입력하세요.</span>
                    </li>
                

                <?php if($w=='') { ?>
                <?php if($prt_id=='') { ?>
				
					<!--th scope="row">기업코드 또는 단체명</th-->
					<!--li>
						<input type="text" name="prt_id" id="prt_id" required class="frm_input required" onkeyup="reg_prt_id_ajax();" size="20"  maxlength="20" placeholder="기업코드  (필수)">
						<span id="msg_prt_id" class="frm_info"></span>
						<span class="frm_info">※ 기업코드 또는 부여받은 이름(한글 또는 영문 코드)을 입력하세요.</span>
					</li-->
				 
				      <?php } ?>
                       <?php if(!$member['sns_id']){?>
                    
                        <!--th scope="row"><label for="reg_mb_password">비밀번호</label></th-->
                        <li>
                            <input type="password" name="passwd" id="reg_mb_password" required itemname="비밀번호" class="frm_input required" data-minlength="4" maxlength="20" placeholder="비밀번호  (필수)">
                            <span class="frm_info">※ 4자 이상의 영문이나 숫자(혼용가능)</span>
                        </li>
                   
                    
                        <!--th scope="row"><label for="reg_mb_password_re">비밀번호확인</label></th-->
                        <li><input type="password" name="repasswd" id="reg_mb_password_re" required itemname="비밀번호확인" class="frm_input required" data-minlength="4" maxlength="20" placeholder="비밀번호 확인  (필수)"></li>
                    
                 	<?php } ?>

                   <?php } else if($w=='u') { ?>
                    <?php if(!$member['sns_id']){?>
                    
                        <!--th scope="row"><label for="reg_mb_password_db">현재비밀번호</label></th-->
                        <li><input type="password" name="dbpasswd" id="reg_mb_password_db" required itemname="현재비밀번호" class="frm_input required" data-minlength="4" maxlength="20" placeholder="현재 비밀번호  (필수)"></li>
                    
                        <!--th scope="row"><label for="reg_mb_password">새비밀번호</label></th-->
                        <li>
                            <input type="password" name="passwd" id="reg_mb_password" class="frm_input" data-minlength="4" maxlength="20" placeholder="비밀번호  (필수)">
                            <span class="frm_info">※ 4자 이상의 영문이나 숫자(혼용가능)</span>
                        </li>
                    
                        <!--th scope="row"><label for="reg_mb_password_re">새비밀번호확인</label></th-->
                        <li><input type="password" name="repasswd" id="reg_mb_password_re" class="frm_input" data-minlength="4" maxlength="20" placeholder="비밀번호 확인  (필수)"></li>
                                     
                               
         
                  <?php }else{ ?>
                  <?php if(!$member['pt_id'] or $member['pt_id'] = ''){ ?>
			
					<!--th scope="row">기업코드 또는 단체명</th-->
					<li>
						<input type="text" name="prt_id" id="prt_id" required class="frm_input required" onkeyup="reg_prt_id_ajax();" size="20"  maxlength="20" placeholder="기업코드  (필수)">
						<span id="msg_prt_id" class="frm_info"></span>
						<span class="frm_info">※ 기업코드 또는 단체명에서 부여받은 이름(한글 또는 영문 코드)을 입력하세요.</span>
					</li>		
              <?php } ?>      
            <?php } ?>
		<?php } ?>

                  </ul>
                </div>	

              <div class="form_01">
             <h3 class="anc_tit">개인정보 입력</h3>
                
                <colgroup>
                    <col class="w120"><col>
                </colgroup>
               <ul>

                          <!--th scope="row"><label for="reg_mb_name">회원명</label></th-->
                    <li>
                        <input type="text" name="name" value="<?php echo $member['name']; ?>" id="reg_mb_name" required itemname="이름" class="frm_input required"<?php if($w=='u' || $default['de_certify_use']) echo $readonly1; ?> placeholder="이름  (필수)">
                    </li>
                
                <?php if($config['register_use_tel']) { ?>
                    
                        <!--th scope="row"><label for="reg_telephone">전화번호</label></th-->
                        <li>
                            <input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" id="reg_telephone"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" onkeypress='return checkNumber(event)' placeholder="전화번호  (선택)">
                        </li>
                   
                <?php } ?>
                <?php if($config['register_use_hp']) { ?>
                    
                        <!--th scope="row"><label for="reg_cellphone">핸드폰</label></th-->
                        <li>
                            <input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" id="reg_cellphone"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대폰번호" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" onkeypress='return checkNumber(event)' placeholder="휴대폰번호  (필수)">
                            <div class="padt5">
                                <input type="checkbox" name="smsser" value="Y" id="smsser_yes"<?php echo get_checked('Y', $member['smsser']); ?> class="css-checkbox lrg"><label for="smsser_yes" class="css-label">SMS를 수신합니다.</label>
                            </div>
                        </li>
                    
                <?php } ?>
                <?php if($config['register_use_email']) { ?>
                    
                        <!--th scope="row"><label for="reg_mb_email">이메일</label></th-->
                        <li>
                            <input type="email" name="email" value="<?php echo $member['email']; ?>" id="reg_mb_email"<?php echo $config['register_req_email']?' required':''; ?> email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" placeholder="이메일  (선택)">
                            <div class="padt5" style="height:40px;">
                                <input type="checkbox" name="mailser" value="Y" id="mailser_yes"<?php echo get_checked('Y', $member['mailser']); ?> class="css-checkbox lrg"><label for="mailser_yes" class="css-label">E-Mail을 수신합니다. / 비밀번호 분실 시 메일로 발송 됩니다.</label>
                            </div>
                        </li>
                    
                <?php } ?>
          <?php if($_GET['w']=="") { ?>
					
						<!--th scope="row"><label for="reg_birth">생년월일</label></th-->
						<li>
							<input type="text" name="birthday" id="reg_birth" itemname="생년월일" required class="frm_input required" readonly placeholder="생년월일  (필수)">
							<span id="msg_mb_id" class="marl5"></span>
						</li>
					
					
						<!--th scope="row"><label for="reg_gender">성별</label></th-->
						<li>
                            <div class="gender">
                            <label for="reg_gender">성별 (선택)</label>&nbsp;&nbsp;&nbsp;
							<span>남 <input type="radio" name="gender" value="M" id="reg_gender"></span>&nbsp;&nbsp;
							<span>여 <input type="radio" name="gender" value="W" id="reg_gender1" checked></span>
                            </div>
						</li>
					
          <?php } ?>
                <?php if($config['register_use_addr']) { ?>
                   
                        <!--th scope="row">주소</th-->
                        <li>
                            <div class="adress">
                            <label for="reg_mb_zip" class="sound_only">우편번호</label>
                            <input type="text" name="zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="5" maxlength="5" readonly placeholder="우편번호  (필수)">
                            <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">주소검색</button><br></div>

                            <label for="reg_mb_addr1" class="sound_only">주소</label>
                            <input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" readonly placeholder="주소  (필수)"><br>

                            <label for="reg_mb_addr2" class="sound_only">상세주소</label>
                            <input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" id="reg_mb_addr2"<?php echo $config['register_req_addr']?' required':''; ?> itemname="상세주소" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" placeholder="상세주소  (필수)"><br>

                            <label for="reg_mb_addr3" class="sound_only">참고항목</label>
                            <input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" id="reg_mb_addr3" class="frm_input frm_address" readonly placeholder="참고항목">
                            <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
                        </li>
                   
                <?php } ?>
          
                            <!--li class="is_captcha_use">
	            <span class="frm_label">자동등록방지</span>
	            <script>var g5_captcha_url  = "https://ownermarket.co.kr/plugin/kcaptcha";</script>
                <script src="https://sownermarket.co.kr/plugin/kcaptcha/kcaptcha.js"></script>
                <fieldset id="captcha" class="captcha m_captcha">
                <legend><label for="captcha_key">자동등록방지</label></legend><audio id="captcha_audio" controls="" src="https://ownermarket.co.kr/data/cache/kcaptcha-209a2512f3455016957354e7a4d37273_1680679666.mp3?t=1680679667047"></audio>
                <img src="https://ownermarket.co.kr/plugin/kcaptcha/kcaptcha_image.php?t=1680679667024" alt="" id="captcha_img"><input type="text" name="captcha_key" id="captcha_key" required="" class="captcha_box required" size="6" maxlength="6">
                <button type="button" id="captcha_reload"><span></span>새로고침</button>
                <span id="captcha_info">자동등록방지 숫자를 순서대로 입력하세요.</span>
                </fieldset>	        </li-->
       
   
      <?php $pt = get_minishop($member['id']); ?>
    <?php if(  $pt ) : ?>
    
        <h3 class="anc_tit mart15">입금받으실 계좌</h3>
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="w120">
                    <col>
                </colgroup>
                <tbody>
               
                    <!--th>은행명</th--><li><input type="text" name="bank_name" id="bank_name" value="<?php echo $pt['bank_name']?>" class="frm_input" size="20" placeholder="은행명"></li>
               
              
                    <!--th>계좌번호</th--><li><input type="text" name="bank_account" id="bank_account" value="<?php echo $pt['bank_account']?>" class="frm_input" size="30" placeholder="계좌번호"></li>
               
           
                    <!--th>예금주명</th--><li><input type="text" name="bank_holder" id="bank_holder" value="<?php echo $pt['bank_holder']?>" class="frm_input" size="20" placeholder="예금주명"></li>
                         
        </div>
        
      


            </ul>
       </div>
</div>
        <p class="padt5 fc_red">※ 입금받으실 계좌정보를 정확히 입력해주세요. (이후 마이페이지에서도 입력 가능합니다)</p>
    
    <?php endif; ?>

 <section id="pt-up-id-selector">
 
<!--h3 class="anc_tit mart15">연회비입금안내</h3>
<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w160">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">결제금액</th>
				<td>
					<input type="hidden" name="pt_level" value="3">
					<input type="hidden" name="pt_price" value="22000">
					<span id="reg_tot_price">22,000원(무통장입금)</span>
				</td>
			</tr>
			<tr id="dicc1">
				<th scope="row">입금계좌</th>
				<td>
					<div class="padno">
						<input type="hidden" name="pt_bank_account" value="01088589354" id="pt_bank_account1" checked="checked">
						<label for="pt_bank_account1">기업은행 01088589354 신종식(제이에스패밀리)</label>
					</div>
				</td>
			</tr>
			<tr id="dicc2">
				<th scope="row"><label for="pt_deposit_name">입금자명</label></th>
				<td>
					<input type="text" name="pt_deposit_name" id="pt_deposit_name" class="frm_input w140">
					<p class="padt5 fc_red">참고) 무통장 신청 후 7일이내 입금확인이 되지 않으면 신청내역은 자동취소 됩니다.</p>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
<p class="padt10 fc_red2">결제완료와 동시에 폐쇄몰 상품 가격 열람 및 구매가 가능하며 결제 후에는 환불이 불가능합니다.</p> -->

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo MS_MURL; ?>" class="btn_medium bx-white">취소</a>
</div>
</form>

<script>

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

 //   if(!number_check(f.jumin6.value + '' + f.jumin7.value)) return false;
<?php if(!$member['sns_id']){?>
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
	<?php } ?>
//    if( $('#from_biz_id').length > 0 && $('#from_biz_id').val() == '' ) {
//        alert('엑셀ID를 입력하세요');
//        return false;
//    }
//
 //   if( parseInt($('#receipt_price').val()) > 0 && $('.holder--good_no-label:visible').prev('input').not(':checked').length > 0 ) {
 //       alert('구매 상품을 체크 하세요.');
 //       return false;
 //   }
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
//    if( $('#deposit_name').is(':visible') && $('#deposit_name').val() == '' ) {
//        alert('입금자명을 입력하세요.');
//        return false;
//    }
//
        str = "회원가입";
	<?php } else if($w=='u') { ?>


 //   if( $('#jumin6').size() > 0 && $('#jumin7').size() > 0 ) {
 //       if(!ssnCheck(f.jumin6.value + '' + f.jumin7.value)){
 //           alert('주민번호가 올바르지 않습니다.');
 //           f.jumin6.focus();
 //           return false;
 //       }
 //   }
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
/*
    if( $('[name=marketing_yn]').filter(':checked').size() == 0 ) {
        alert('신상품 출시, 홍보 안내를 위하여 개인정보를 이용 동의 여부를 체크 하세요.');
        return false;
    }
*/
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
// 추천인 찾기
function reg_prt_id_ajax() {
	var prt_id = $.trim($("#prt_id").val());
	$.post(
		tb_bbs_url+"/ajax.pt_id_check.php",
		{ prt_id: prt_id },
		function(data) {
			$("#msg_prt_id").empty().html(data);
		}
	);
}
$(function(){
  $("#reg_birth").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d", duration: "normal", showAnim: "slide" });
});

</script>

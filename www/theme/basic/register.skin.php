<?php
if(!defined('_MALLSET_')) exit;
?>

<h2 class="pg_titb">
    <p class="titxt">MEMBER AGREE</p>
	<p class="pg_nav">HOME<i class="ionicons ion-ios-arrow-right"></i>약관동의</p>
    <span>약관동의</span>
   </h2>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<!-- <div><img src="<?php echo MS_IMG_URL; ?>/register_1.gif"></div> -->

<?php if($default['de_certify_use']) { // 실명인증 사용시 ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<?php if($default['de_sns_login_use']) { ?>

<div class="sns_box mart20">	
 <h3 class="line_tit"><span>SNS 계정으로 간편가입</span></h3>

	<p>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</p>
</div>
<?php } ?>

<h3 class="line_tit mart20"><span>쇼핑몰 회원가입</span></h3>
<section id="fregister_provision">
	<h2>회원가입 약관 (필수)</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">회원가입 약관 내용에 동의합니다.</label>
	</fieldset>
</section>

<section id="fregister_private">
    <h2>개인정보 수집 및 이용 (필수)</h2>
    <textarea readonly><?php echo $config['shop_private']; ?></textarea>    
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree1" value="2" id="agree21">
		<label for="agree21">개인정보 수집 및 이용 내용에 동의합니다.</label>
	</fieldset>

</section>

<section id="fregister_policy">
	<h2>3자정보 제공동의 (필수)</h2>
	<textarea readonly><?php echo $config['shop_policy']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree2" value="3" id="agree31">
		<label for="agree31">3자정보 제공동의 내용에 동의합니다.</label>
	</fieldset>
	<fieldset class="fregister_agree total">
		<input type="checkbox" name="chk_all" id="chk_all">
		<label for="chk_all" class="bold fs14">모든 약관을 확인하고 전체 동의합니다. <span>(전체선택)</span></label>
	</fieldset>
</section>
	

<?php if($default['de_certify_use']) { // 실명인증 사용시 ?>
<section>
	<div class="agree_txt">
		<i class="fa fa-exclamation-circle"></i> 개정 정보통신법 제23조에 따라 회원가입시에는 주민등록번호를 수집하지 않습니다.
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">휴대폰 인증</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">아이핀 인증</label>
		</span>
	</div>
</section>
<?php } ?>



<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_large wset">
	<a href="<?php /*echo MS_URL; */?>" class="btn_large bx-white">취소</a>
</div>
</form>

<script>
window.name ="Parent_window";
function fnPopup(val){
	switch(val){
		case 1: //휴대폰인증
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // 아이핀인증
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
	if(!f.agree.checked) {
		alert("회원가입 약관 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree.focus();
		return false;
	}

	if(!f.agree1.checked) {
		alert("개인정보 수집 및 이용 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree1.focus();
		return false;
	}
    
    	if(!f.agree2.checked) {
		alert("3자정보 제공동의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree2.focus();
		return false;
	}
	<?php if($default['de_certify_use']) { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("휴대폰인증 및 아이핀인증 후 회원가입 하실 수 있습니다.");
        return false;
    }
    if(chkplus[0].checked) {
        fnPopup(1);
		return false;
    }
	if(chkplus[1].checked) {
        fnPopup(0);
		return false;
    }
	<?php } else { ?>
	return true;
	<?php } ?>
}


jQuery(function($){
	// 모두선택
	$("input[name=chk_all]").click(function() {
		if ($(this).prop('checked')) {
			$("input[name^=agree]").prop('checked', true);
		} else {
			$("input[name^=agree]").prop("checked", false);
		}
	});

	$("input[name^=agree]").click(function() {
		$("input[name=chk_all]").prop("checked", false);
	});
});
</script>
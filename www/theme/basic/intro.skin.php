<?php
if(!defined('_MALLSET_')) exit;

include_once(MS_PATH.'/head.sub.php');
?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">

<div id="intro-wrap">
<form name="flogin" action="<?php echo MS_HTTPS_BBS_URL; ?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
<div id="intro">
	<div id="int_wrap">
		<div class="lcont">
			<h1><?php echo display_logo(); ?></h1>
			<!--
			<h2 class="tit">MEMBER <b>LOGIN</b></h2>
			<p class="fs13">아이디와 패스워드를 입력하신 후 로그인 버튼을 눌러주세요.</p>
			-->
			<dl class="int_login">
				<dd>
					<label for="login_id" class="sound_only">회원아이디</label>
					<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="아이디">					
				</dd>
				<dd>
					<label for="login_pw" class="sound_only">비밀번호</label>
					<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="비밀번호">		
				</dd>
				<dt><input type="submit" value="로그인" class="btn_large wset"></dt>
				<dd class="a_login">
					<p><input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login" class="fs11">자동로그인</label></p>
				</dd>
			</dl>

		<?php if($default['de_sns_login_use']) { ?>
			<div>
				<div class="sns_login lg_naver">
					<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
					<?php echo get_login_oauth('naver', 1); ?>
					<?php } ?>
				</div>
				<div class="sns_login lg_facebook">
					<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
					<?php echo get_login_oauth('facebook', 1); ?>
					<?php } ?>
				</div>
				<div class="sns_login lg_kakao">
					<?php if($default['de_kakao_rest_apikey']) { ?>
					<?php echo get_login_oauth('kakao', 1); ?>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

			<div class="int_btn">
				<a href="<?php echo MS_BBS_URL; ?>/register.php" class="btn_lsmall">회원가입</a>
				<a href="<?php echo MS_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;" class="btn_lsmall">아이디/비밀번호 찾기</a>
			</div>
			<ul class="int-txt">
				<li>간편로그인으로 가입하시는 경우 복지몰창업아이디로는 이용하실 수 없습니다.</li>
				<li>아이디/비밀번호를 분실하신 경우, 아이디/비밀번호 찾기 또는 상담센터로 문의 바랍니다.</li>
				<li>상담센터 <?php echo $config['company_tel']; ?>(<?php echo $config['company_hours']; ?>)</li>
			</ul>
			<ul class="int-txtM">
				<li class="c_center">상담센터</li>
				<li class="c_tel"><?php echo $config['company_tel']; ?></li>
				<li class="c_hours">(<?php echo $config['company_hours']; ?>)</li>
			<!--	<li><p>Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p></li>-->
			</ul>
		</div>
		<div class="rbanner">
			<?php echo display_banner_rows(100, $pt_id); ?>
			<script>
			$(document).ready(function(){
				$('.rbanner ul').slick({
					autoplay: true,
					dots: true,
					arrows: false
				});
			});
			</script>
		</div>
	</div>
	<div class="int_copy">
		<!--<?php echo $config['company_name']; ?> <span class="g_hl"></span> 대표자 : <?php echo $config['company_owner']; ?> <span class="g_hl"></span> <?php echo $config['company_addr']; ?><br>
		Email : <?php echo $super['email']; ?> <span class="g_hl"></span> 사업자번호 : <?php echo $config['company_saupja_no']; ?> <a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall bx-white marl5">사업자정보확인</a> <span class="g_hl"></span> 통신판매번호 : <?php echo $config['tongsin_no']; ?><br>
		<p class="mart5 fc_137 fs11">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
		-->
		<ul>
			<li><?php echo $config['company_name']; ?></li>
			<li><span class="g_hl"></span></li>
			<li> 대표자 : <?php echo $config['company_owner']; ?> </li>
			<li><span class="g_hl"></span></li>
			<li><?php echo $config['company_addr']; ?></li>
			<li><span class="g_hl"></span></li>
			<li> Email : <?php echo $super['email']; ?></li>
			<li><span class="g_hl"></span></li>
			<li> 사업자번호 : <?php echo $config['company_saupja_no']; ?> <a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall bx-white marl5">사업자정보확인</a></li>
			<li><span class="g_hl"></span></li>
			<li> 통신판매번호 : <?php echo $config['tongsin_no']; ?></li>
		</ul>
		<p class="mart5 fc_137 fs11">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
	</div>
</div>
</form>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert('아이디를 입력하세요.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert('비밀번호를 입력하세요.');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once(MS_PATH."/tail.sub.php");
?>
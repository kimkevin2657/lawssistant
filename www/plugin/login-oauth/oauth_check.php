<?php
if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가

if(!trim($mb_id) || !trim($token_value)) {
	alert_close("정보가 제대로 넘어오지 않아 오류가 발생했습니다.");
}
//Check Mobile
$mAgent = array("iPhone","iPod","Android","Blackberry", 
    "Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $chkMobile = true;
        break;
    }
}

//소셜아이디
$sns_id = $mb_id;

// 소셜아이디(sns_id) 체크
$mb = sql_fetch(" select * from shop_member where sns_id = '{$sns_id}' ", false);
if(!isset($mb['sns_id'])) {
	// sn_id 필드생성
	sql_query(" ALTER TABLE `shop_member` ADD `sns_id` varchar(255) NOT NULL COMMENT '소셜아이디(sns_id) 체크' AFTER `id` ", false);
}

// 소셜아이디가 없으면 기존방식으로 회원가입여부 체크
if(!$mb['sns_id']) {
	//기존에 풀인증번호로 변경하신 분은 여기를 수정해 주셔야 합니다.
	$mb_id = substr($mb_id,0,18); //최대 20자 = 구분자(2자) + 아이디값(18자)
	$mb_id = $mb_gubun.''.$mb_id;
	$mb = get_member($mb_id);
}

$register_script = '';
if($mb['id']) { // 가입된 회원이면

	// 소셜아이디 업데이트
	if(!$mb['sns_id']) {
		$mb['sns_id'] = $sns_id;
		sql_query(" update shop_member set sns_id = '{$sns_id}' where id = '{$mb['id']}' ", false);
	}

	// 세션 생성
	set_session('ss_mb_id', $mb['id']);

	// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
	set_session('ss_mb_key', md5($mb['reg_time'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

	// 쇼핑포인트 체크
	$sum_point = get_point_sum($mb['id']);

	$sql= " update shop_member set point = '$sum_point' where id = '{$mb['id']}' ";
	sql_query($sql);

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);

} else {

	//회원아이디 처리 - 16자리 임의 아이디 발급
	//$mb_id = $mb_gubun . (get_microtime() * 100);
	$arr_id = str_split('abcdefghijklmnopqrstuvwxyz012345678901234567890123456789');
	for($i=0; $i<999; $i++) {
		shuffle($arr_id);
		$tmp_id = $mb_gubun . implode('',array_slice($arr_id,0,16));
		$sql = " select count(*) as cnt from shop_member where id = '$tmp_id' ";
		$row = sql_fetch($sql);
		if(!$row['cnt'])
			break;
	}

	$mb_id = $tmp_id;

	//이름
	$mb_name = clean_xss_tags($mb_name);
	if(!$mb_name) {
		$mb_name = $mb_nick;
	}

	// 스크립트 알림
	$msg_alert = '회원가입을 축하드립니다.';
	if($mb_email) {
		if(!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_email)) {
			$mb_email = '';
		}
	}

	//임시비밀번호 생성 - 10자리 임의 발급
	$arr_pw = str_split('abcdefghijklmnopqrstuvwxyz012345678901234567890123456789');
	shuffle($arr_pw);
	$tmp_pw = implode('',array_slice($arr_pw,0,10));

	unset($value);
	$value['id']		= $mb_id; //회원아이디
	$value['sns_id']	= $sns_id; //sns_id
	$value['name']		= $mb_name; //회원명
	$value['passwd']	= $tmp_pw; //비밀번호
	$value['email']		= $mb_email; //이메일
	$value['gender']	= 'M'; //성별
	$value['mailser']	= 'N'; //E-Mail을 수신
	$value['smsser']	= 'N'; //SMS를 수신
	$value['reg_time']	= MS_TIME_YMDHIS; //가입일
	//$value['pt_id']		= $pt_id; //추천인
	$value['up_id']     = $pt_id; //후원인
	$value['grade']		= 7; //레벨
	insert("shop_member", $value);
	$mb_no = sql_insert_id();

	$mb = get_member_no($mb_no);

	// 회원가입 쇼핑포인트 부여
	insert_point($mb['id'], $config['register_point'], '회원가입 축하', '@member', $mb['id'], '회원가입');

	// 추천인에게 쇼핑포인트 부여
	if($mb['pt_id'] != encrypted_admin())
		insert_point($mb['pt_id'], $config['partner_point'], $mb['id'].'의 추천인', '@member', $mb['id'], $mb['id'].' 추천');

	// 신규회원가입 쿠폰발급
	if($config['coupon_yes']) {
		$cp_used = false;
		$cp = sql_fetch("select * from shop_coupon where cp_type = '5'");
		if($cp['cp_id'] && $cp['cp_use']) {
			if(($cp['cp_pub_sdate'] <= MS_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
			   ($cp['cp_pub_edate'] >= MS_TIME_YMD || $cp['cp_pub_edate'] == '9999999999'))
				$cp_used = true;

			if($cp_used) {
				insert_used_coupon($mb['id'], $mb['name'], $cp);
			}
		}
	}

	// 회원가입 문자발송
	icode_sms_send($mb['id'], '1');

	// 회원가입 메일발송
	if($mb['email']) {
		include_once(MS_LIB_PATH."/mailer.lib.php");

		// 회원님께 메일 발송
		$subject = '['.$config['company_name'].'] 회원가입을 축하드립니다.';

		ob_start();
		include_once(MS_BBS_PATH.'/register_form_update_mail1.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($config['company_name'], $super['email'], $mb['email'], $subject, $content, 1);

		// 최고관리자님께 메일 발송
		$subject = '['.$config['company_name'].'] '.$mb['name'] .'님께서 회원으로 가입하셨습니다.';

		ob_start();
		include_once(MS_BBS_PATH.'/register_form_update_mail2.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($mb['name'], $mb['email'], $super['email'], $subject, $content, 1);
	}

	// 가입완료 알림
	$register_script = 'alert("'.$msg_alert.'");';

	// 세션 생성
	set_session('ss_mb_id', $mb['id']);

	set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

/*
if(!$mb['pt_id']){
	if($chkMobile) {
	?>
	<script>
	location.href = "https://<?php echo $_SERVER['HTTP_HOST']; ?>/m/bbs/register_form.php?w=u";
	</script>
	<?
		}else{
	?>
	<script>
	opener.location.href = "https://<?php echo $_SERVER['HTTP_HOST']; ?>/bbs/register_mod.php";
	window.close();
	</script>
	<?
	}
	exit;
}


$pb = get_member($mb['pt_id']);


if($pb['homepage']){
		if($chkMobile) {
	?>
	<script>
	location.href = "https://<?php echo $pb['homepage']; ?>/";
	</script>
	<?	}else{ ?>
	<script>
	opener.location.href = "https://<?php echo $pb['homepage']; ?>/";
	window.close();
	</script>
<? }
		exit;
}else{
		if($chkMobile) {
	?>
	<script>
	location.href = "https://<?php echo $mb['pt_id']; ?>.blinglife.co.kr/";
	</script>
	<?	}else{ ?>
	<script>
	opener.location.href = "https://<?php echo $mb['pt_id']; ?>.blinglife.co.kr/";
	window.close();
	</script>
<? }
		exit;
}
*/

// 메타태그 사용안함
$is_no_meta = true;

$tb['title'] = 'SNS LOGIN';
include_once(MS_PATH.'/head.sub.php');
?>

<script>
var slr = opener.document.getElementsByName("slr_url").length;
var url = "";

<?php echo $register_script;?>

if(slr) {
	url = opener.document.getElementsByName("slr_url")[0].value;
}

if(url) {
	opener.location.href = decodeURIComponent(url);
} else {
	opener.location.reload();
}

window.close();
</script>

<?php
include_once(MS_PATH.'/tail.sub.php');
?>
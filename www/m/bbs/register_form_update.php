<?php
include_once("./_common.php");
include_once(MS_LIB_PATH."/mailer.lib.php");

check_demo();

if($_POST['w']=="") {
  if(!$_POST['id']) {
    alert('회원아이디가 없습니다. 올바른 방법으로 이용해 주십시오.');
  }

if($_POST['passwd'] != $_POST['repasswd']){
	alert("비밀번호를 다시 확인하여 주시기 바랍니다.");
}

if(strlen($_POST['cellphone']) < 10 || strlen($_POST['cellphone']) > 11)
{
	alert("핸드폰 번호는 10~11자리를 입력하여주세요.");
	exit;
}

  $sql = " select count(*) as cnt from shop_member where id = '{$_POST['id']}' ";
  $row = sql_fetch($sql);
  if($row['cnt']) { alert("이미 사용중인 회원아이디 입니다."); }
}

$pt_bank_account	= trim($_POST['pt_bank_account']);
$pt_settle_case		= trim($_POST['pt_settle_case']);
$pt_deposit_name	= trim($_POST['pt_deposit_name']);
$pt_signature_json	= trim($_POST['signatureJSON']);
$pt_level			= preg_replace('/[^0-9]/', '', $_POST['pt_level']);
$pt_price			= preg_replace('/[^0-9]/', '', $_POST['pt_price']);
$pt_call_use		= preg_replace('/[^0-9]/', '', $_POST['pt_call_use']);

unset($value);
if($_POST['w']=="") {
  $value['id']			= $_POST['id']; //회원아이디
  $value['name']			= $_POST['name']; //회원명
  $value['passwd']		= $_POST['passwd']; //비밀번호
  $value['gender']		= strtoupper($_POST['gender']); //성별
} else {
	if($member['sns_id']){
		$value['name']			= $_POST['name']; //이메일
	}else{
	  if(!check_password($_POST['dbpasswd'], $member['passwd'])) {
		alert('비밀번호가 맞지 않습니다.');
	  }
	  if($_POST['passwd']) $value['passwd'] = $_POST['passwd'];
	}
}
if($_POST['birthday']) {
  $brith = explode("-",$_POST['birthday']);
  $value['birth_year']	= $brith[0]; //년
  $value['birth_month']	= $brith[1]; //월
  $value['birth_day']		= $brith[2]; //일
  $value['age']			= substr(date("Y")-$value['birth_year'],0,1).'0'; //연령대
}

$value['birth_type']	= strtoupper($_POST['birth_type']); //음력,양력
$value['email']			= $_POST['email']; //이메일
$value['cellphone']		= replace_tel($_POST['cellphone']);	 //핸드폰
$value['telephone']		= replace_tel($_POST['telephone']);	 //전화번호
$value['zip']			= $_POST['zip']; //우편번호
$value['addr1']			= $_POST['addr1']; //주소
$value['addr2']			= $_POST['addr2']; //상세주소
$value['addr3']			= $_POST['addr3']; //참고항목
$value['addr_jibeon']	= $_POST['addr_jibeon']; //지번주소
$value['mailser']		= $_POST['mailser'] ? $_POST['mailser'] : 'N'; //E-Mail을 수신
$value['smsser']		= $_POST['smsser'] ? $_POST['smsser'] : 'N'; //SMS를 수신
if($_POST['w']=="") {
	$value['pt_id']			= "admin"; //추천인
	$value['grade']			= 9; //레벨
	$value['reg_time']		= MS_TIME_YMDHIS; //가입일
	$value['pt_deposit_name'] = $pt_deposit_name;
}

if($_POST['w']=="u") {
	if($_POST['prt_id']){
		$pt = get_member_pt($_POST['prt_id']);
		$ptr = get_member_pt_name($_POST['prt_id']);
		if($pt['id']){
			$value['pt_id']			= $pt['id']; //추천인
			if($pt['grade'] == '5'){
				$value['grade']			= 7; //레벨
			}elseif($pt['grade'] == '4'){
				$value['grade']			= 8; //레벨
			}
		}else{
			if($ptr['id']){
				$value['pt_id']			= $ptr['id']; //추천인
				if($ptr['grade'] == '5'){
					$value['grade']			= 7; //레벨
				}elseif($ptr['grade'] == '4'){
					$value['grade']			= 8; //레벨
				}
			}else{
				alert("추천인이 검색되지 않습니다. ");
				exit;
			}
		}
	}




  update("shop_member", $value, "where id='$member[id]'");
} else {
  insert("shop_member", $value);
  $mb_no = sql_insert_id();
  $mb = get_member_no($mb_no);
  if($mb['id'] && $config['register_point'] > 0) {
    insert_point($mb['id'], $config['register_point'], '회원가입 축하', '@member', $mb['id'], '회원가입');
  }
  if($mb['pt_id'] && $config['partner_point'] > 0) {
    insert_point($mb['pt_id'], $config['partner_point'], '추천', '@member', $mb['id'], '추천');
  }
}

// 신규회원가입 쿠폰발급
if($_POST['w']=="") {
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
}
if($_POST['w']=="") {
  $msg = "회원가입이 완료 되었습니다.";
} else {
  $msg = "회원수정이 완료 되었습니다";
}
if($_POST['w']=="") {
$subject_1 = '회원가입';
$message_1 = '안녕하세요. '.$mb['name'].'님!

블링뷰티에 가입하신것을 환영합니다!

'.$mb['name'].'을 행복하게 만들어 드리는 뷰티라이프 플랫폼이 되겠습니다!

진심으로 감사드립니다!';

aligo_sms('TE_4915', $mb['cellphone'], $mb['name'], $subject_1, $message_1);
$cddd = icode_sms_send($_POST['id'], 1);
}

// 회원가입 메일발송
if($_POST['w']=="") {
  if($mb['email']) {
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
}

if( empty($msg) ) {
    $msg = "회원가입이 완료 되었습니다.";
}

// 관리자인증을 사용하지 않는 경우에만 로그인
if($config['cert_admin_yes'] == '1'){
	$msg = "회원가입이 완료 되었으며 승인 처리 이후 로그인 가능합니다";
}else{
	set_session('ss_mb_id', $mb['id']);
}

if($_POST['w']=="") {
   alert($msg, MS_MURL);
}else{
   alert($msg, "/m/bbs/register_form.php?w=u");
}
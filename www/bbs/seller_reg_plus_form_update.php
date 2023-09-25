<?php
include_once("./_common.php");
include_once(MS_LIB_PATH."/mailer.lib.php");

check_demo();

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', MS_URL);
}

if(is_admin()) {
	alert('관리자는 신청을 하실 수 없습니다.');
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}
if(!$member['id']){

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
	if($row['cnt'])
		alert("이미 사용중인 회원아이디 입니다.");



	unset($value);

	$value['id']			= $_POST['id']; //회원아이디
	$value['name']			= $_POST['name']; //회원명
	$value['passwd']		= $_POST['passwd']; //비밀번호
	if($_POST['birthday']) {
	  $brith = explode("-",$_POST['birthday']);
	  $value['birth_year']	= $brith[0]; //년
	  $value['birth_month']	= $brith[1]; //월
	  $value['birth_day']		= $brith[2]; //일
	  $value['age']			= substr(date("Y")-$value['birth_year'],0,1).'0'; //연령대
	}
	$value['birth_type']	= strtoupper($_POST['birth_type']); //음력,양력
	$value['gender']		= strtoupper($_POST['gender']); //성별
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
		$pt = get_member($_POST['pt_id']);
		if($pt['id']){
			$value['pt_id']			= $pt['id']; //추천인
			$value['grade']			= 9; //레벨
		}else{
			$value['pt_id']			= 'admin'; //추천인
			$value['grade']			= 9; //레벨
		}
	//$value['up_id'] = $_POST['up_id'];
	$value['reg_time']		= MS_TIME_YMDHIS; //가입일
	$value['pt_deposit_name'] = $pt_deposit_name;

	insert("shop_member", $value);
	$mb_no = sql_insert_id();

	$mb = get_member_no($mb_no);
}else{
	$mb = get_member($member['id']);
}

$upl_dir = MS_DATA_PATH."/seller";
$upl = new upload_files($upl_dir);

unset($value);

if($_FILES['bn_file1']['name']) {
	$value['bn_file1'] = $upl->upload($_FILES['bn_file1']);
}
if($_FILES['bn_file2']['name']) {
	$value['bn_file2'] = $upl->upload($_FILES['bn_file2']);
}
if($_FILES['bn_file3']['name']) {
	$value['bn_file3'] = $upl->upload($_FILES['bn_file3']);
}
if($_FILES['bn_file4']['name']) {
	$value['bn_file4'] = $upl->upload($_FILES['bn_file4']);
}
$value['mb_id'] = $mb['id'];
$value['seller_code'] = code_uniqid();
if($_POST['up_id']){
	$value['up_id'] = $_POST['up_id'];
}
$value['seller_item'] = $_POST['seller_item'];
$value['company_name'] = $_POST['company_name'];
$value['company_saupja_no'] = $_POST['company_saupja_no'];
$value['company_item'] = $_POST['company_item'];
$value['company_service'] = $_POST['company_service'];
$value['company_owner'] = $_POST['company_owner'];
$value['company_tel'] = $_POST['company_tel'];
$value['company_fax'] = $_POST['company_fax'];
$value['company_zip'] = $_POST['company_zip'];
$value['company_addr1'] = $_POST['company_addr1'];
$value['company_addr2'] = $_POST['company_addr2'];
$value['company_addr3'] = $_POST['company_addr3'];
$value['company_addr_jibeon'] = $_POST['company_addr_jibeon'];
$value['company_hompage'] = $_POST['company_hompage'];
$value['info_name'] = $_POST['info_name'];
$value['info_email'] = $_POST['info_email'];
$value['info_tel'] = $_POST['info_tel'];
$value['bank_name'] = $_POST['bank_name'];
$value['bank_account'] = $_POST['bank_account'];
$value['bank_holder'] = $_POST['bank_holder'];
$value['memo'] = $_POST['memo'];
$value['reg_time'] = MS_TIME_YMDHIS;
$value['update_time'] = MS_TIME_YMDHIS;
insert("shop_seller", $value);

$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
$wr_name = get_text($mb['name']);
$subject = '['.$company_name.'] '.$wr_name.'님께서 입점신청을 하셨습니다.';

if($mb['email']) {
	ob_start();
	include_once(MS_BBS_PATH.'/seller_reg_from_update_mail.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($mb['name'], $mb['email'], $super['email'], $subject, $content, 1);
}

icode_member_send($super_hp, $subject);

alert('정상적으로 신청 되었습니다.', MS_BBS_URL.'/seller_reg_from.php');
?>
<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}



unset($value);
if($member['sns_id']){
$value['name']			= $_POST['name']; //이메일
}else{
	if(!check_password($_POST['dbpasswd'], $member['passwd'])) {
		alert('비밀번호가 맞지 않습니다.');
	}
if($_POST['passwd']) $value['passwd'] = $_POST['passwd'];
}

//if(!$_POST['prt_id'] or $_POST['prt_id'] ==''){
//	$value['pt_id']			= "admin"; //추천인
//	$value['grade']			= 9; //레벨
//}else{
//	$pt = get_member_pt($_POST['prt_id']);
//	$ptr = get_member_pt_name($_POST['prt_id']);
//	if($pt['id']){
//		$value['pt_id']			= $pt['id']; //추천인
//			if($pt['grade'] == '5'){
//				$value['grade']			= 7; //레벨
//			}elseif($pt['grade'] == '4'){
//				$value['grade']			= 8; //레벨
//			}
//	}else{
//		if($ptr['id']){
//			$value['pt_id']			= $ptr['id']; //추천인
//				if($ptr['grade'] == '5'){
//					$value['grade']			= 7; //레벨
//				}elseif($ptr['grade'] == '4'){
//					$value['grade']			= 8; //레벨
//				}
//		}else{
//			alert("추천인이 검색되지 않습니다. ");
//			exit;
//		}
//	}
//}

//$value['birth_year']	= $_POST['birth_year']; //년
//$value['birth_month']	= sprintf('%02d',$_POST['birth_month']); //월
//$value['birth_day']		= sprintf('%02d',$_POST['birth_day']); //일
//$value['age']			= substr(date("Y")-$_POST['birth_year'],0,1).'0'; //연령대
//$value['birth_type']	= strtoupper($_POST['birth_type']); //음력,양력
//$value['gender']		= strtoupper($_POST['gender']); //성별
$value['email']							= $_POST['email']; //이메일
$value['cellphone']						= replace_tel($_POST['cellphone']); //핸드폰
$value['telephone']						= replace_tel($_POST['telephone']); //전화번호
$value['zip']							= $_POST['zip']; //우편번호
$value['addr1']							= $_POST['addr1']; //주소
$value['addr2']							= $_POST['addr2']; //상세주소
$value['addr3']							= $_POST['addr3']; //참고항목
$value['addr_jibeon']					= $_POST['addr_jibeon']; //지번주소
if( isset($_POST['jumin6'])) $value['jumin6'] = Mcrypt::jumin_encrypt($_POST['jumin6']);
if( isset($_POST['jumin7'])) $value['jumin7'] = Mcrypt::jumin_encrypt($_POST['jumin7']);
//$value['mailser']		= $_POST['mailser'] ? $_POST['mailser'] : 'N'; //E-Mail을 수신
//$value['smsser']		= $_POST['smsser'] ? $_POST['smsser'] : 'N'; //SMS를 수신
$value['marketing_yn']					= $_POST['marketing_yn'];
$value['refund_account']				= $_POST['refund_account']; //환불계좌번호
$value['refund_account_bank_name']		= $_POST['refund_account_bank_name']; //은행명
$value['refund_account_name']			= $_POST['refund_account_name']; //예금주


update("shop_member", $value, "where id='$member[id]'");
 alert('정보가 수정되었습니다.', MS_URL);

?>

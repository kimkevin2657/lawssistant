<?php
include_once("./_common.php");

//check_demo();
//
//check_admin_token();

if(!$_POST['id']) {
	alert('회원아이디가 없습니다. 올바른 방법으로 이용해 주십시오.');
}

$upl_dir = MS_DATA_PATH."/store_img";
$upl = new upload_files($upl_dir);

$sql = " select count(*) as cnt from shop_member where id = '".$_POST['id']."' ";
$row = sql_fetch($sql);
if($row['cnt'])
	alert("이미 사용중인 회원아이디 입니다.");

// 미성년자 체크
//if($_POST['birth_year'] && $_POST['birth_month'] && $_POST['birth_day']) {
//	$mb_birth = trim($_POST['birth_year']);
//	$mb_birth .= sprintf('%02d',trim($_POST['birth_month']));
//	$mb_birth .= sprintf('%02d',trim($_POST['birth_day']));
//
//	$todays = date("Ymd", MS_SERVER_TIME);
//
//	// 오늘날짜에서 생일을 빼고 거기서 140000 을 뺀다.
//	// 결과가 0 이상의 양수이면 만 14세가 지난것임
//	$check = $todays - (int)$mb_birth - 140000;
//	if($check < 0) {
//		alert("만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률\\r\\n제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로\\r\\n법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.");
//	}
//}
$mb_grade = isset($_POST['mb_grade']) ? $_POST['mb_grade'] : (minishop::LEVEL_MAX+minishop::USER_LEVEL_CNT);
$mb_id    = $_POST['id'];
$state    = isset( $_POST['minishop_state'] ) ? $_POST['minishop_state'] : 0;

unset($value);
$value['id']			= $mb_id; //회원아이디
$value['name']			= $_POST['name']; //회원명
$value['pc_no']			= $_POST['pc_no']; //회원명
$value['passwd']		= $_POST['passwd']; //비밀번호
$value['jumin6']        = Mcrypt::jumin_encrypt($_POST['jumin6']);
$value['jumin7']        = Mcrypt::jumin_encrypt($_POST['jumin7']);
//$value['birth_year']	= $_POST['birth_year']; //년
//$value['birth_month']	= sprintf('%02d',$_POST['birth_month']); //월
//$value['birth_day']		= sprintf('%02d',$_POST['birth_day']); //일
//$value['age']			= substr(date("Y")-$_POST['birth_year'],0,1).'0'; //연령대
//$value['birth_type']	= strtoupper($_POST['birth_type']); //음력,양력
//$value['gender']		= strtoupper($_POST['gender']); //성별
$value['email']			= $_POST['email']; //이메일
$value['cellphone']		= replace_tel($_POST['cellphone']); //핸드폰
$value['telephone']		= replace_tel($_POST['telephone']); //전화번호
$value['zip']			= $_POST['zip']; //우편번호
$value['addr1']			= $_POST['addr1']; //주소
$value['addr2']			= $_POST['addr2']; //상세주소
$value['addr3']			= $_POST['addr3']; //참고항목
$value['addr_jibeon']	= $_POST['addr_jibeon']; //지번주소
$value['mailser']		= $_POST['mailser'] ? $_POST['mailser'] : 'N'; //E-Mail을 수신
$value['smsser']		= $_POST['smsser'] ? $_POST['smsser'] : 'N'; //SMS를 수신
$value['pt_id']			= $_POST['pt_id']; //추천인
$value['up_id']			= $_POST['up_id']; //추천인
$value['reg_time']		= MS_TIME_YMDHIS; //가입일

$value['grade']			= $mb_grade;

$value['use_good']		= $_POST['use_good']; // 개별상품판매
$value['use_pg']		= $_POST['use_pg']; // 개인결제
$value['payment']		= $_POST['payment']; // 추가 판매수수료
$value['payflag']		= $_POST['payflag']; // 추가 판매수수료 유형
$value['homepage']		= $_POST['homepage']; // 도메인
$value['theme']			= $_POST['theme']; //테마스킨
$value['mobile_theme']	= $_POST['mobile_theme']; //모바일테마스킨
$value['memo']			= $_POST['memo']; // 메모
$value['intercept_date']= $_POST['intercept_date']; // 접근차단일자

$value['auth_1']		= $_POST['auth_1'];
$value['auth_2']		= $_POST['auth_2'];
$value['auth_3']		= $_POST['auth_3'];
$value['auth_4']		= $_POST['auth_4'];
$value['auth_5']		= $_POST['auth_5'];
$value['auth_6']		= $_POST['auth_6'];
$value['auth_7']		= $_POST['auth_7'];
$value['auth_8']		= $_POST['auth_8'];
$value['auth_9']		= $_POST['auth_9'];
$value['auth_10']		= $_POST['auth_10'];

$lg = sql_fetch("select * from shop_member where id = '{$mb_id}' ");

if($store_thumb_del) {
	$upl->del($lg['store_thumb']);
	$value['store_thumb'] = '';

	update("shop_member", $value, "where id = '$mb_id'");
}

if($_FILES['store_thumb']['name']) {
	$value['store_thumb'] = $upl->upload($_FILES['store_thumb']);

	update("shop_member", $value, "where id = '$mb_id'");

	//echo "UPDATE shop_member SET store_thumb = '{$_FILES['store_thumb']['name']}' where id = '{$mb_id}' ";
}

if( minishop::isminishopGrade($mb_grade)){
    $value['use_app']   = $config['cert_admin_yes'] && $state ? 1 : 0;
} else {
    $value['use_app']   = $config['cert_admin_yes'] ;
}

insert("shop_member", $value);

if( minishop::isminishopGrade($mb_grade)){

    // 카테고리 생성
    sql_member_category($mb_id);

    $pfrm = array();
    $pb = get_minishop_basic($mb_grade);

    $pfrm['mb_id']			 = $mb_id; //회원명
    $pfrm['bank_name']		 = $_POST['bank_name']; //은행명
    $pfrm['bank_account']	 = $_POST['bank_account']; //계좌번호
    $pfrm['bank_holder']	 = $_POST['bank_holder']; //예금주
    if( defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) {
        $pfrm['pay_bank_name'] = $_POST['pay_bank_name']; //은행명
        $pfrm['pay_bank_account'] = $_POST['pay_bank_account']; //계좌번호
        $pfrm['pay_bank_holder'] = $_POST['pay_bank_holder']; //예금주
    }
    $pfrm['anew_grade']		 = $mb_grade; //레벨 인덱스번호
    $pfrm['receipt_price']	 = $_POST['receipt_price']; //분양개설비
    $pfrm['deposit_name']	 = $mb['name']; //입금자명
    $pfrm['pay_settle_case'] = 1; //결제방식 1은 무통장, 2는 신용카드결제
    $pfrm['memo']			 = ($state) ? '관리자에 의해 승인처리 되었습니다.' : ''; //메모
    $pfrm['state']			 = $state; //처리결과 1은 완료, 0은 대기
    $pfrm['reg_ip']			 = $_SERVER['REMOTE_ADDR'];
    $pfrm['reg_time']		 = MS_TIME_YMDHIS;
    $pfrm['update_time']	 = MS_TIME_YMDHIS;

    insert("shop_minishop", $pfrm);

    if( $state ) {

        $term_date = get_term_date($config['pf_expire_term']);//만료일
        $anew_date = MS_TIME_YMD;

        update('shop_member', compact('term_date','anew_date'), " where id = '{$mb_id}' ");

        // 후원 소개수수료
        insert_anew_pay($mb_id); // 후원수수료

        minishop::insert_hierarchy($mb_id);
    }



}

alert("회원가입이 완료 되었습니다.", MS_ADMIN_URL."/member.php?code=register_form");
?>
<?php

if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가

if(defined('_PURENESS_')) return;

if(defined('MS_IS_ADMIN')) return;

//------------------------------------------------------------------------------
// 가맹점관련 모음 시작
//------------------------------------------------------------------------------

$mk = array();
$pt = array();
unset($pt_id);

// 개별도메인을 검사 후 있으면 세션을 바꾼다
$sql = "select id from shop_member where homepage = '{$_SERVER['HTTP_HOST']}'";

$row = sql_fetch($sql);
if($row['id']) {
	$temp_shopid = $row['id'].'.';
} else {
	$temp_domain = get_basedomain($_SERVER['HTTP_HOST']);
	$temp_shopid = preg_replace("/{$temp_domain}/", "", $_SERVER['HTTP_HOST']);
}

// 접속도메인이 (아이디.domain) 형태인 경우
if(substr_count($temp_shopid, ".") == 1) {
	$fr_shopid = explode('.', $temp_shopid);
	$pt_id = trim($fr_shopid[0]);

	$pt_id = $pt_id;

	// 가맹점인가?
	if(is_minishop($pt_id)) {
		// 관리비를 사용중일때 기간이 만료되었다면 pt_id 를 비움
		if($config['pf_expire_use'] && $config['pf_session_no']) {
			$row = get_member($pt_id, 'term_date');
			if(!is_null_time($row['term_date'])) {
				if($row['term_date'] < MS_TIME_YMD) {
					unset($pt_id);
				}
			}
		}
	}
}

// 가맹점이 아니면 최고관리자로 변경
if(!is_minishop($pt_id) && !is_seller($pt_id)) {
	$pt_id = encrypted_admin();
}

// 가맹점아이디를 세션에담는다.
set_session('pt_id', $pt_id);

$mk = get_member($pt_id);
$pt = get_minishop($pt_id);

if(!$mk['theme']) $mk['theme'] = 'basic';
if(!$mk['mobile_theme']) $mk['mobile_theme'] = 'basic';

define('MS_THEME_PATH', get_theme_path($mk['theme']));
define('MS_THEME_URL',  get_theme_url($mk['theme']));
define('MS_MTHEME_PATH', get_mobile_theme_path($mk['mobile_theme']));
define('MS_MTHEME_URL',  get_mobile_theme_url($mk['mobile_theme']));

// 방문자수의 접속을 남김
include_once(MS_LIB_PATH.'/visit_insert.inc.php');

$auth_good = false;
$auth_pg = false;

// 기본값 본사 카테고리 테이블명
$ms['category_table'] = 'shop_cate';

if($pt_id != encrypted_admin()) {
	$ms['category_table'] = 'shop_cate_'.$pt_id;

	// 카테고리가 생성되지 않았을때 새로 생성.
	if(!table_exists($ms['category_table'])) {
		sql_member_category($pt_id);
	}

	// 개별 상품판매
	if($config['pf_auth_good'] == 2 || ($config['pf_auth_good'] == 3 && $mk['use_good']))
		$auth_good = true;

	// 개별 PG결제
	if($config['pf_auth_pg'] == 2 || ($config['pf_auth_pg'] == 3 && $mk['use_pg']))
		$auth_pg = true;
}

// 인트로사용시 로그인페이지로 이동을 제외함.
$intro_run = 0;
if( !$is_member && $config['shop_intro_yes']) {
    if(preg_match("/^\/$/", $_SERVER['PHP_SELF'])) $intro_run++;
    if(preg_match("/^\/m\/$/", $_SERVER['PHP_SELF'])) $intro_run++;
    if(preg_match("/^\/m$/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/catalog.php/", $_SERVER['PHP_SELF'])) $intro_run++;
    if(preg_match("/index.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/register.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/register_form.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/register_form_update.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/password_lost.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/password_lost2.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/password_lost_certify.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_check.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/provision.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/policy.php/", $_SERVER['PHP_SELF'])) $intro_run++;
    if(preg_match("/ajax.mb_id_check.php/", $_SERVER['PHP_SELF'])) $intro_run++;
    if(preg_match("/ajax.find_user.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/checkplus_fail.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/checkplus_success.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/ipin_process.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/ipin_result.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_with_facebook.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_with_google.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_with_kakao.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_with_naver.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/login_with_twitter.php/", $_SERVER['PHP_SELF'])) $intro_run++;
	if(preg_match("/oauth_check.php/", $_SERVER['PHP_SELF'])) $intro_run++;

	if(!$intro_run) {
		if(MS_IS_MOBILE) // 모바일 접속인가?
			goto_url(MS_MURL);
		else
			goto_url(MS_URL);
	}
}

// 가맹점 사업자정보
if($pt_id != encrypted_admin() && $pt['saupja_yes']) {
	$config['company_type'] = $pt['company_type'];
	$config['shop_name'] = $pt['shop_name'];
	$config['company_name'] = $pt['company_name'];
	$config['company_saupja_no'] = $pt['company_saupja_no'];
	$config['tongsin_no'] = $pt['tongsin_no'];
	$config['company_tel'] = $pt['company_tel'];
	$config['company_fax'] = $pt['company_fax'];
	$config['company_owner'] = $pt['company_owner'];
	$config['info_name'] = $pt['info_name'];
	$config['info_email'] = $pt['info_email'];
	$config['company_zip'] = $pt['company_zip'];
	$config['company_addr'] = $pt['company_addr'];
	$config['company_hours'] = $pt['company_hours'];
	$config['company_lunch'] = $pt['company_lunch'];
	$config['company_close'] = $pt['company_close'];
	$super['email'] = $mk['email'];
}

// 개별 전자결제(PG)
if($auth_pg) {
	$pt_settle_pid = $pt_id;
	$default = set_minishop_value($pt_id);
} else {
	$pt_settle_pid = encrypted_admin();
}

// 본사접속일 아닐때는 가맹점 설정정보를 불러옴
if($pt_id != encrypted_admin()) {
	// 소셜네트워크서비스(SNS : Social Network Service)
	$default['de_sns_login_use'] = $pt['de_sns_login_use'];
	$default['de_naver_appid'] = $pt['de_naver_appid'];
	$default['de_naver_secret'] = $pt['de_naver_secret'];
	$default['de_facebook_appid'] = $pt['de_facebook_appid'];
	$default['de_facebook_secret'] = $pt['de_facebook_secret'];
	$default['de_kakao_rest_apikey'] = $pt['de_kakao_rest_apikey'];
	$default['de_googl_shorturl_apikey'] = $pt['de_googl_shorturl_apikey'];

	// INSTAGRAM / SNS 연결
	$default['de_insta_url'] = $pt['de_insta_url'];
	$default['de_insta_client_id'] = $pt['de_insta_client_id'];
	$default['de_insta_redirect_uri'] = $pt['de_insta_redirect_uri'];
	$default['de_insta_access_token'] = $pt['de_insta_access_token'];
	$default['de_sns_facebook'] = $pt['de_sns_facebook'];
	$default['de_sns_twitter'] = $pt['de_sns_twitter'];
	$default['de_sns_instagram'] = $pt['de_sns_instagram'];
	$default['de_sns_pinterest'] = $pt['de_sns_pinterest'];
	$default['de_sns_naverblog'] = $pt['de_sns_naverblog'];
	$default['de_sns_naverband'] = $pt['de_sns_naverband'];
	$default['de_sns_kakaotalk'] = $pt['de_sns_kakaotalk'];
	$default['de_sns_kakaostory'] = $pt['de_sns_kakaostory'];

	// 메인 카테고리별 베스트
	if($pt['de_maintype_title']) $default['de_maintype_title'] = $pt['de_maintype_title'];
	if($pt['de_maintype_best']) $default['de_maintype_best'] = $pt['de_maintype_best'];

	// 회원가입약관
	if($pt['shop_provision']) $config['shop_provision'] = $pt['shop_provision'];
	if($pt['shop_private']) $config['shop_private'] = $pt['shop_private'];
	if($pt['shop_policy']) $config['shop_policy'] = $pt['shop_policy'];

	// head, body 스크립트
	$config['head_script'] = $pt['head_script'];
	$config['tail_script'] = $pt['tail_script'];
}

// 역슬래시가 생기는 현상을 방지
$config['shop_provision'] = preg_replace("/\\\/", "", $config['shop_provision']);
$config['shop_private'] = preg_replace("/\\\/", "", $config['shop_private']);
$config['shop_policy'] = preg_replace("/\\\/", "", $config['shop_policy']);

// 매출전표 url 설정
if($default['de_card_test']) {
    define('MS_BILL_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('MS_CASH_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
} else {
    define('MS_BILL_RECEIPT_URL', 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('MS_CASH_RECEIPT_URL', 'https://admin.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
}
?>
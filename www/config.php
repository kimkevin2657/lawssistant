<?php
/********************
    상수 선언
********************/

define('G5_VERSION', '그누보드5');
define('G5_GNUBOARD_VER', '5.4.5.1');
define('G5_YOUNGCART_VER', '5.4.5.1');

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('_GNUBOARD_', true);

// 기본 시간대 설정
date_default_timezone_set("Asia/Seoul");

/********************
    경로 상수
********************/

/*
보안서버 도메인
회원가입, 글쓰기에 사용되는 https 로 시작되는 주소를 말합니다.
포트가 있다면 도메인 뒤에 :443 과 같이 입력하세요.
보안서버주소가 없다면 공란으로 두시면 되며 보안서버주소 뒤에 / 는 붙이지 않습니다.
입력예) https://www.domain.com:443/gnuboard5
*/

//G5 변수
define('G5_DOMAIN', '');
define('G5_HTTPS_DOMAIN', '');

if( $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    define('G5_DOMAIN_NAME', 'mylinkshop.shop');
} else {
    define('G5_DOMAIN_NAME', 'mylinkshop.shop');
}

// 디버깅 상수, 실제 서버운영시 false 로 설정해 주제요.
define('G5_DEBUG', false);

// Set Databse table default engine is Databse default_storage_engine, If you want to use MyISAM or InnoDB, change to MyISAM or InnoDB.
define('G5_DB_ENGINE', '');

// Set Databse table default Charset
// utf8, utf8mb4 등 지정 가능 기본값은 utf8, 설치전에 utf8mb4 으로 수정시 모든 테이블에 이모지 입력이 가능합니다. utf8mb4 는 mysql 또는 mariadb 5.5 버전 이상을 요구합니다.
define('G5_DB_CHARSET', 'utf8');

/*
www.sir.kr 과 sir.kr 도메인은 서로 다른 도메인으로 인식합니다. 쿠키를 공유하려면 .sir.kr 과 같이 입력하세요.
이곳에 입력이 없다면 www 붙은 도메인과 그렇지 않은 도메인은 쿠키를 공유하지 않으므로 로그인이 풀릴 수 있습니다.
*/
define('G5_COOKIE_DOMAIN',  '');

define('G5_DBCONFIG_FILE',  'dbconfig.php');

define('G5_ADMIN_DIR',      'admin');
define('G5_BBS_DIR',        'bbs');
define('G5_CSS_DIR',        'css');
define('G5_DATA_DIR',       'data');
define('G5_EXTEND_DIR',     'extend');
define('G5_IMG_DIR',        'img');
define('G5_JS_DIR',         'js');
define('G5_LIB_DIR',        'lib');
define('G5_MOBILE_DIR',     'm');
define('G5_MYPAGE_DIR',     'mypage');
define('G5_PLUGIN_DIR',     'plugin');
define('G5_SHOP_DIR',       'shop');
define('G5_THEME_DIR',		'theme');
define('G5_EDITOR_DIR',     'editor');
define('G5_LGXPAY_DIR',     'lgxpay');
define('G5_PHPMAILER_DIR',  'PHPMailer');
define('G5_SESSION_DIR',    'session');


define('G5_SKIN_DIR',       'skin');
define('G5_EDITOR_DIR',     'editor');
define('G5_MOBILE_DIR',     'mobile');
define('G5_OKNAME_DIR',     'okname');

define('G5_KCPCERT_DIR',    'kcpcert');
define('G5_LGXPAY_DIR',     'lgxpay');

define('G5_SNS_DIR',        'sns');
define('G5_SYNDI_DIR',      'syndi');
define('G5_PHPMAILER_DIR',  'PHPMailer');
define('G5_SESSION_DIR',    'session');
define('G5_THEME_DIR',      'theme');

define('G5_GROUP_DIR',      'group');
define('G5_CONTENT_DIR',    'content');

// URL 은 브라우저상에서의 경로 (도메인으로 부터의)
if (G5_DOMAIN) {
    define('G5_URL', G5_DOMAIN);
} else {
    if (isset($ms_path['url']))
        define('G5_URL', $ms_path['url']);
    else
        define('G5_URL', '');
}

if (isset($ms_path['path'])) {
    define('G5_PATH', $ms_path['path']);
} else {
    define('G5_PATH', '');
}

define('G5_ADMIN_URL',      G5_URL.'/'.G5_ADMIN_DIR);
define('G5_BBS_URL',        G5_URL.'/'.G5_BBS_DIR);
define('G5_CSS_URL',        G5_URL.'/'.G5_CSS_DIR);
define('G5_DATA_URL',       G5_URL.'/'.G5_DATA_DIR);
define('G5_IMG_URL',        G5_URL.'/'.G5_IMG_DIR);
define('G5_JS_URL',         G5_URL.'/'.G5_JS_DIR);
define('G5_SKIN_URL',       G5_URL.'/'.G5_SKIN_DIR);
define('G5_PLUGIN_URL',     G5_URL.'/'.G5_PLUGIN_DIR);
define('G5_EDITOR_URL',     G5_PLUGIN_URL.'/'.G5_EDITOR_DIR);
define('G5_OKNAME_URL',     G5_PLUGIN_URL.'/'.G5_OKNAME_DIR);
define('G5_KCPCERT_URL',    G5_PLUGIN_URL.'/'.G5_KCPCERT_DIR);
define('G5_LGXPAY_URL',     G5_PLUGIN_URL.'/'.G5_LGXPAY_DIR);
define('G5_SNS_URL',        G5_PLUGIN_URL.'/'.G5_SNS_DIR);
define('G5_SYNDI_URL',      G5_PLUGIN_URL.'/'.G5_SYNDI_DIR);
define('G5_MOBILE_URL',     G5_URL.'/'.G5_MOBILE_DIR);

// PATH 는 서버상에서의 절대경로
define('G5_ADMIN_PATH',     G5_PATH.'/'.G5_ADMIN_DIR);
define('G5_BBS_PATH',       G5_PATH.'/'.G5_BBS_DIR);
define('G5_DATA_PATH',      G5_PATH.'/'.G5_DATA_DIR);
define('G5_EXTEND_PATH',    G5_PATH.'/'.G5_EXTEND_DIR);
define('G5_LIB_PATH',       G5_PATH.'/'.G5_LIB_DIR);
define('G5_PLUGIN_PATH',    G5_PATH.'/'.G5_PLUGIN_DIR);
define('G5_SKIN_PATH',      G5_PATH.'/'.G5_SKIN_DIR);
define('G5_MOBILE_PATH',    G5_PATH.'/'.G5_MOBILE_DIR);
define('G5_SESSION_PATH',   G5_DATA_PATH.'/'.G5_SESSION_DIR);
define('G5_EDITOR_PATH',    G5_PLUGIN_PATH.'/'.G5_EDITOR_DIR);
define('G5_OKNAME_PATH',    G5_PLUGIN_PATH.'/'.G5_OKNAME_DIR);

define('G5_KCPCERT_PATH',   G5_PLUGIN_PATH.'/'.G5_KCPCERT_DIR);
define('G5_LGXPAY_PATH',    G5_PLUGIN_PATH.'/'.G5_LGXPAY_DIR);

define('G5_SNS_PATH',       G5_PLUGIN_PATH.'/'.G5_SNS_DIR);
define('G5_SYNDI_PATH',     G5_PLUGIN_PATH.'/'.G5_SYNDI_DIR);
define('G5_PHPMAILER_PATH', G5_PLUGIN_PATH.'/'.G5_PHPMAILER_DIR);
//==============================================================================


//==============================================================================
// 사용기기 설정
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
define('G5_SET_DEVICE', 'both');

define('G5_USE_MOBILE', true); // 모바일 홈페이지를 사용하지 않을 경우 false 로 설정
define('G5_USE_CACHE',  true); // 최신글등에 cache 기능 사용 여부


/********************
    시간 상수
********************/
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
define('G5_SERVER_TIME',    time());
define('G5_TIME_YMDHIS',    date('Y-m-d H:i:s', G5_SERVER_TIME));
define('G5_TIME_YMD',       substr(G5_TIME_YMDHIS, 0, 10));
define('G5_TIME_HIS',       substr(G5_TIME_YMDHIS, 11, 8));

// 입력값 검사 상수 (숫자를 변경하시면 안됩니다.)
define('G5_ALPHAUPPER',      1); // 영대문자
define('G5_ALPHALOWER',      2); // 영소문자
define('G5_ALPHABETIC',      4); // 영대,소문자
define('G5_NUMERIC',         8); // 숫자
define('G5_HANGUL',         16); // 한글
define('G5_SPACE',          32); // 공백
define('G5_SPECIAL',        64); // 특수문자

// SEO TITLE 문단 길이
define('G5_SEO_TITEL_WORD_CUT', 8);        // SEO TITLE 문단 길이

// 퍼미션
define('G5_DIR_PERMISSION',  0755); // 디렉토리 생성시 퍼미션
define('G5_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('G5_MOBILE_AGENT',   'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|BB10|android|sony');

// SMTP
// lib/mailer.lib.php 에서 사용
define('G5_SMTP',      '127.0.0.1');
define('G5_SMTP_PORT', '25');


/********************
    기타 상수
********************/

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
// 5.4 버전 이전에는 sql_password 이 사용됨, 5.4 버전부터 기본이 create_hash 로 변경
//define('G5_STRING_ENCRYPT_FUNCTION', 'sql_password');
define('G5_STRING_ENCRYPT_FUNCTION', 'create_hash');
define('G5_MYSQL_PASSWORD_LENGTH', 41);         // mysql password length 41, old_password 의 경우에는 16

// SQL 에러를 표시할 것인지 지정
// 에러를 표시하려면 TRUE 로 변경
define('G5_DISPLAY_SQL_ERROR', FALSE);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('G5_ESCAPE_FUNCTION', 'sql_escape_string');

// sql_escape_string 함수에서 사용될 패턴
//define('G5_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
//define('G5_ESCAPE_REPLACE',  '');

// 게시판에서 링크의 기본개수를 말합니다.
// 필드를 추가하면 이 숫자를 필드수에 맞게 늘려주십시오.
define('G5_LINK_COUNT', 2);

// 썸네일 jpg Quality 설정
define('G5_THUMB_JPG_QUALITY', 90);

// 썸네일 png Compress 설정
define('G5_THUMB_PNG_COMPRESS', 5);

// 모바일 기기에서 DHTML 에디터 사용여부를 설정합니다.
define('G5_IS_MOBILE_DHTML_USE', true);

// MySQLi 사용여부를 설정합니다.
define('G5_MYSQLI_USE', true);

// Browscap 사용여부를 설정합니다.
define('G5_BROWSCAP_USE', true);

// 접속자 기록 때 Browscap 사용여부를 설정합니다.
define('G5_VISIT_BROWSCAP_USE', false);

// ip 숨김방법 설정
/* 123.456.789.012 ip의 숨김 방법을 변경하는 방법은
\\1 은 123, \\2는 456, \\3은 789, \\4는 012에 각각 대응되므로
표시되는 부분은 \\1 과 같이 사용하시면 되고 숨길 부분은 ♡등의
다른 문자를 적어주시면 됩니다.
*/
define('G5_IP_DISPLAY', '\\1.♡.\\3.\\4');

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {   //https 통신일때 daum 주소 js
    define('G5_POSTCODE_JS', '<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>');
} else {  //http 통신일때 daum 주소 js
    define('G5_POSTCODE_JS', '<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>');
}

/********************
    상수 선언
********************/
function ipBlock($ip, $iplist) {
  
  foreach ($iplist as $value) { 
   if (strpos($ip, $value) === 0) return true;
   else continue; 

  }
  return false;
}

$dl_comcode = "omarket";

$ip = $_SERVER['REMOTE_ADDR']; 

$iplist = array("444.444.444.444","333.333.333"); // 블럭시킬 아이피


if(ipBlock($ip, $iplist)){ echo "ip: ".$ip." 는 접근금지 아이피입니다."; exit;}
define('MS_VERSION', '분양몰 v2.0.9');

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('_MALLSET_', true);

if(PHP_VERSION >= '5.1.0') {
    //if(function_exists("date_default_timezone_set")) date_default_timezone_set("Asia/Seoul");
    date_default_timezone_set("Asia/Seoul");
}

/********************
    경로 상수
********************/

/*
보안서버 도메인
회원가입, 글쓰기에 사용되는 https 로 시작되는 주소를 말합니다.
포트가 있다면 도메인 뒤에 :443 과 같이 입력하세요.
보안서버주소가 없다면 공란으로 두시면 되며 보안서버주소 뒤에 / 는 붙이지 않습니다.
입력예) https://www.domain.com:443
*/
$domain_na = $_SERVER['HTTP_HOST'];
define('MS_DOMAIN', '');
define('MS_HTTPS_DOMAIN', '');

if( $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    define('MS_DOMAIN_NAME', $domain_na);
} else {
    define('MS_DOMAIN_NAME', $domain_na);
}

/*
www.sample.co.kr 과 sample.co.kr 도메인은 서로 다른 도메인으로 인식합니다. 쿠키를 공유하려면 .sample.co.kr 과 같이 입력하세요.
이곳에 입력이 없다면 www 붙은 도메인과 그렇지 않은 도메인은 쿠키를 공유하지 않으므로 로그인이 풀릴 수 있습니다.
*/
define('MS_COOKIE_DOMAIN',  '.'.$domain_na);
define('G5_COOKIE_DOMAIN',  '.'.$domain_na);


define('MS_DBCONFIG_FILE',  'dbconfig.php');

define('MS_ADMIN_DIR',      'admin');
define('MS_BBS_DIR',        'bbs');
define('MS_BBSORG_DIR',     'bbs_origin');
define('MS_CSS_DIR',        'css');
define('MS_DATA_DIR',       'data');
define('MS_EXTEND_DIR',     'extend');
define('MS_IMG_DIR',        'img');
define('MS_JS_DIR',         'js');
define('MS_LIB_DIR',        'lib');
define('MS_MOBILE_DIR',     'm');
define('MS_MYPAGE_DIR',     'mypage');
define('MS_PLUGIN_DIR',     'plugin');
define('MS_SHOP_DIR',       'shop');
define('MS_THEME_DIR',		'theme');
define('MS_EDITOR_DIR',     'editor');
define('MS_LGXPAY_DIR',     'lgxpay');
define('MS_PHPMAILER_DIR',  'PHPMailer');
define('MS_SESSION_DIR',    'session');

// URL 은 브라우저상에서의 경로 (도메인으로 부터의)
if(MS_DOMAIN) {
    define('MS_URL', MS_DOMAIN);
} else {
    if(isset($ms_path['url']))
        define('MS_URL', $ms_path['url']);
    else
        define('MS_URL', '');
}

if(isset($ms_path['path'])) {
    define('MS_PATH', $ms_path['path']);
} else {
    define('MS_PATH', '');
}

define('MS_ADMIN_URL',      MS_URL.'/'.MS_ADMIN_DIR);
define('MS_ADMIN_BOOK_URL',    MS_URL.'/'.MS_ADMIN_DIR.'/wz_bookingC_prm_admin');
define('MS_ADMIN_POINT_URL',   MS_URL.'/'.MS_ADMIN_DIR.'/wz_chargepoint_admin');
define('MS_PUSH_URL',       MS_URL.'/'.MS_ADMIN_DIR.'/push_admin');
define('MS_ORG_URL',        MS_URL.'/'.MS_ADMIN_DIR.'/bbs_origin');
define('MS_BBS_URL',        MS_URL.'/'.MS_BBS_DIR);
define('MS_BBSORG_URL',     MS_URL.'/'.MS_BBSORG_DIR);
define('MS_CSS_URL',        MS_URL.'/'.MS_CSS_DIR);
define('MS_DATA_URL',       MS_URL.'/'.MS_DATA_DIR);
define('MS_IMG_URL',        MS_URL.'/'.MS_IMG_DIR);
define('MS_JS_URL',         MS_URL.'/'.MS_JS_DIR);
define('MS_SHOP_URL',       MS_URL.'/'.MS_SHOP_DIR);
define('MS_LIB_URL',        MS_URL.'/'.MS_LIB_DIR);
define('MS_PLUGIN_URL',     MS_URL.'/'.MS_PLUGIN_DIR);
define('MS_MYPAGE_URL',     MS_URL.'/'.MS_MYPAGE_DIR);
define('MS_EDITOR_URL',     MS_PLUGIN_URL.'/'.MS_EDITOR_DIR);
define('MS_LGXPAY_URL',     MS_PLUGIN_URL.'/'.MS_LGXPAY_DIR);

// PATH 는 서버상에서의 절대경로
define('MS_ADMIN_PATH',     MS_PATH.'/'.MS_ADMIN_DIR);
define('MS_BBS_PATH',       MS_PATH.'/'.MS_BBS_DIR);
define('MS_DATA_PATH',      MS_PATH.'/'.MS_DATA_DIR);
define('MS_EXTEND_PATH',    MS_PATH.'/'.MS_EXTEND_DIR);
define('MS_LIB_PATH',       MS_PATH.'/'.MS_LIB_DIR);
define('MS_PLUGIN_PATH',    MS_PATH.'/'.MS_PLUGIN_DIR);
define('MS_SHOP_PATH',      MS_PATH.'/'.MS_SHOP_DIR);
define('MS_MYPAGE_PATH',    MS_PATH.'/'.MS_MYPAGE_DIR);
define('MS_SESSION_PATH',   MS_DATA_PATH.'/'.MS_SESSION_DIR);
define('MS_EDITOR_PATH',    MS_PLUGIN_PATH.'/'.MS_EDITOR_DIR);
define('MS_LGXPAY_PATH',    MS_PLUGIN_PATH.'/'.MS_LGXPAY_DIR);
define('MS_PHPMAILER_PATH', MS_PLUGIN_PATH.'/'.MS_PHPMAILER_DIR);

// 모바일경로 상수
define('MS_MPATH',			MS_PATH.'/'.MS_MOBILE_DIR);
define('MS_MURL',			MS_URL.'/'.MS_MOBILE_DIR);
define('MS_MBBS_PATH',		MS_MPATH.'/'.MS_BBS_DIR);
define('MS_MBBS_URL',		MS_MURL.'/'.MS_BBS_DIR);
define('MS_MCSS_PATH',		MS_MPATH.'/'.MS_CSS_DIR);
define('MS_MCSS_URL',		MS_MURL.'/'.MS_CSS_DIR);
define('MS_MIMG_PATH',		MS_MPATH.'/'.MS_IMG_DIR);
define('MS_MIMG_URL',		MS_MURL.'/'.MS_IMG_DIR);
define('MS_MJS_PATH',		MS_MPATH.'/'.MS_JS_DIR);
define('MS_MJS_URL',		MS_MURL.'/'.MS_JS_DIR);
define('MS_MSHOP_PATH',		MS_MPATH.'/'.MS_SHOP_DIR);
define('MS_MSHOP_URL',		MS_MURL.'/'.MS_SHOP_DIR);
//==============================================================================


//==============================================================================
// 사용기기 설정
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
define('MS_SET_DEVICE', 'both');
define('MS_USE_MOBILE', true); // 모바일 홈페이지를 사용하지 않을 경우 false 로 설정
define('MS_USE_UP_ID', false);


/********************
    시간 상수
********************/
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
define('MS_SERVER_TIME',    time());
define('MS_TIME_YEAR',		date("Y", MS_SERVER_TIME));
define('MS_TIME_MONTH',		date("m", MS_SERVER_TIME));
define('MS_TIME_DAY',		date("d", MS_SERVER_TIME));
define('MS_TIME_YM',		date("Y-m", MS_SERVER_TIME));
define('MS_TIME_YMDHIS',	date("Y-m-d H:i:s", MS_SERVER_TIME));
define('MS_TIME_YHS',		date("YmdHis", MS_SERVER_TIME));
define('MS_TIME_YMD',		substr(MS_TIME_YMDHIS, 0, 10));
define('MS_TIME_HIS',		substr(MS_TIME_YMDHIS, 11, 8));

define('G5_SERVER_TIME',         time());
define('G5_TIME_YMDHIS',         date('Y-m-d H:i:s', G5_SERVER_TIME));
define('G5_TIME_YMD',             substr(G5_TIME_YMDHIS, 0, 10));
define('G5_TIME_HIS',             substr(G5_TIME_YMDHIS, 11, 8));

// 입력값 검사 상수 (숫자를 변경하시면 안됩니다.)
define('MS_ALPHAUPPER',		1); // 영대문자
define('MS_ALPHALOWER',		2); // 영소문자
define('MS_ALPHABETIC',		4); // 영대,소문자
define('MS_NUMERIC',		8); // 숫자
define('MS_HANGUL',		   16); // 한글
define('MS_SPACE',         32); // 공백
define('MS_SPECIAL',       64); // 특수문자

// 퍼미션
define('MS_DIR_PERMISSION',  0707); // 디렉토리 생성시 퍼미션
define('MS_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('MS_MOBILE_AGENT', 'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony');

// SMTP
// lib/mailer.lib.php 에서 사용
define('MS_SMTP',      '127.0.0.1');
if( $_SERVER['REMOTE_ADDR'] == '127.0.0.1') define('MS_SMTP_PORT', '1025');
else define('MS_SMTP_PORT', '25');

// 아이코드 코인 최소금액 설정
// 코인 잔액이 설정 금액보다 작을 때는 주문시 SMS 발송 안함
define('MS_ICODE_COIN', 100);
/********************
    기타 상수
********************/

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('MS_STRING_ENCRYPT_FUNCTION', 'sql_password');

// SQL 에러를 표시할 것인지 지정
// 에러를 표시하려면 TRUE 로 변경
define('MS_DISPLAY_SQL_ERROR', TRUE);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('MS_ESCAPE_FUNCTION', 'sql_escape_string');

// sql_escape_string 함수에서 사용될 패턴
//define('MS_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
//define('MS_ESCAPE_REPLACE',  '');

// 썸네일 jpg Quality 설정
define('MS_THUMB_JPG_QUALITY', 90);

// 썸네일 png Compress 설정
define('MS_THUMB_PNG_COMPRESS', 5);

// MySQLi 사용여부를 설정합니다.
define('MS_MYSQLI_USE', true);

// 옵션 ID 특수문자 필터링 패턴
define('MS_OPTION_ID_FILTER', '/[\'\"\\\'\\\"]/');

// 스팸방지를 위한 암호화 키값
define('MS_HASH_TOKEN', md5(MS_URL.MS_TIME_YMD.$_SERVER['REMOTE_ADDR']));

// ip 숨김방법 설정
/* 123.456.789.012 ip의 숨김 방법을 변경하는 방법은
\\1 은 123, \\2는 456, \\3은 789, \\4는 012에 각각 대응되므로
표시되는 부분은 \\1 과 같이 사용하시면 되고 숨길 부분은 ♡등의
다른 문자를 적어주시면 됩니다.
*/
define('MS_IP_DISPLAY', '\\1.♡.\\3.\\4');

/*
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {   //https 통신일때 daum 주소 js
    define('MS_POSTCODE_JS', '<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>');
} else {  //http 통신일때 daum 주소 js
    define('MS_POSTCODE_JS', '<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>');
}
*/

define('MS_POSTCODE_JS', '<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>');

?>

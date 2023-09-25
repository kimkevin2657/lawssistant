<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
//error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );
//error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING );
//include_once(__DIR__.'/under.html'); die();
error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED^E_STRICT);
ini_set('display_errors', '1');
// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!defined('MS_SET_TIME_LIMIT')) define('MS_SET_TIME_LIMIT', 0);
@set_time_limit(MS_SET_TIME_LIMIT);


//===========================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//-----------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if(isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if(isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//===========================================================================================================

function ms_path()
{
    $result['path'] = str_replace('\\', '/', dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$ms_path = ms_path();

include_once($ms_path['path'].'/config.php');   // 설정 파일

unset($ms_path);

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('MS_ESCAPE_PATTERN') && defined('MS_ESCAPE_REPLACE')) {
        $pattern = MS_ESCAPE_PATTERN;
        $replace = MS_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if(get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(MS_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(MS_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(MS_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(MS_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config  = array();
$default = array();
$super	 = array();
$member  = array();
$minishop = array();
$seller	 = array();
$ms		 = array();
$g5		 = array();
//==============================================================================
// 항상 "www" 를 타고 들어오는 도메인은 "www" 를 제거
if(preg_match("/www\./i", $_SERVER['HTTP_HOST'])) {
	header("Location:http://".preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
}

//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = MS_DATA_PATH.'/'.MS_DBCONFIG_FILE;
if(file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
	include_once(MS_LIB_PATH."/minishop.lib.php"); // 가맹점 라이브러리
	include_once(MS_LIB_PATH."/global.lib.php"); // PC+모바일 공통 라이브러리
	include_once(MS_LIB_PATH."/common.lib.php"); // PC전용 라이브러리
	include_once(MS_LIB_PATH."/mobile.lib.php"); // 모바일전용 라이브러리
	include_once(MS_LIB_PATH."/thumbnail.lib.php"); // 썸네일 라이브러리
	include_once(MS_LIB_PATH."/editor.lib.php"); // 에디터 라이브러리
	include_once(MS_LIB_PATH."/login-oauth.php"); // SNS 로그인
	include_once(MS_LIB_PATH."/uri.lib.php"); // URL 설정
	include_once(MS_LIB_PATH."/get_data.lib.php"); //get_datalib
	include_once(MS_LIB_PATH."/hook.lib.php"); // URL 설정
	include_once(MS_LIB_PATH."/cache.lib.php"); // URL 설정
    include_once(MS_PLUGIN_PATH."/zentool/zentool.lib.php"); // ZenTool Library

    $connect_db = sql_connect(MS_MYSQL_HOST, MS_MYSQL_USER, MS_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(MS_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $ms 배열에 저장 - 명랑폐인님 제안
    $ms['connect_db'] = $connect_db;
    $g5['connect_db'] = $connect_db;

    sql_set_charset('utf8', $connect_db);
    if(defined('MS_MYSQL_SET_MODE') && MS_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if(defined('MS_TIMEZONE')) sql_query(" set time_zone = '".MS_TIMEZONE."'");

} else {
	header('Content-Type: text/html; charset=utf-8');

	die($dbconfig_file.' 파일을 찾을 수 없습니다.');


}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0); // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

session_save_path(MS_SESSION_PATH);

if(isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 43200); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 2592000); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 0); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", MS_COOKIE_DOMAIN);
ini_set("session.cookie_domain", G5_COOKIE_DOMAIN);
################################################################################################
################################################################################################
################################################################################################
//==============================================================================
// ### Chrome80 버전부터 아래 이슈 대응### 크롬
//------------------------------------------------------------------------------

define('G5_EDITOR_LIB', MS_LIB_PATH."/editor.lib.php");


if( ! class_exists('XenoPostToForm') ){
	class XenoPostToForm
	{
		public static function check() {
			return !isset($_COOKIE['PHPSESSID']) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']) || ! isset($_SERVER['HTTP_REFERER']) ));
		}

		public static function submit($posts) {
			echo '<html><head><meta charset="UTF-8"></head><body>';
			echo '<form id="f" name="f" method="post">';
			echo self::makeInputArray($posts);
			echo '</form>';
			echo '<script>';
					echo 'document.f.submit();';
					echo '</script></body></html>';
			exit;
		}

		public static function makeInputArray($posts) {
			$res = array();
			foreach($posts as $k => $v) {
				$res[] = self::makeInputArray_($k, $v);
			}
			return implode('', $res);
		}

		private static function makeInputArray_($k, $v) {
			if(is_array($v)) {
				$res = array();
				foreach($v as $i => $j) {
					$res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
				}
				return implode('', $res);
			}
			return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
		}
	}
}


if( !function_exists('shop_check_is_pay_page') ){
	function shop_check_is_pay_page(){
		$shop_dir = 'shop';
		$mobile_dir = MS_MOBILE_DIR;

		// PG 결제사의 리턴페이지 목록들
		$pg_checks_pages = array(
			$shop_dir.'/inicis/INIStdPayReturn.php',	// 영카트 5.2.9.5 이하에서 사용됨, 그 이상버전에서는 파일 삭제됨
			$shop_dir.'/inicis/inistdpay_return.php',	// 영카트 5.2.9.6 이상에서 사용됨
			$mobile_dir.'/'.$shop_dir.'/inicis/pay_return.php',
			$mobile_dir.'/'.$shop_dir.'/inicis/pay_approval.php',
			$shop_dir.'/lg/returnurl.php',
			$mobile_dir.'/'.$shop_dir.'/lg/returnurl.php',
			$mobile_dir.'/'.$shop_dir.'/lg/xpay_approval.php',
            'plugin/wz.bookingC.prm/gender/inicis/INIStdPayReturn.php', //wetoz
            'plugin/wz.chargepoint/gender/inicis/INIStdPayReturn.php', //wetoz
		);

		$server_script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

		// PG 결제사의 리턴페이지이면
		foreach( $pg_checks_pages as $pg_page ){
			if( preg_match('~'.preg_quote($pg_page).'$~i', $server_script_name) ){
				return true;
			}
		}

		return false;
	}
}

// PG 결제시에 세션이 없으면 내 호출페이지를 다시 호출하여 쿠키 PHPSESSID를 살려내어 세션값을 정상적으로 불러오게 합니다.
// 위와 같이 코드를 전부 한페이지에 넣은 이유는 이전 버전 사용자들이 패치시 어려울수 있으므로 한페이지에 코드를 다 넣었습니다.
if(XenoPostToForm::check()) {
	if ( shop_check_is_pay_page() ){	// PG 결제 리턴페이지에서만 사용
		XenoPostToForm::submit($_POST); // session_start(); 하기 전에
	}
}
//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch("select * from shop_config");

// 본인인증 또는 쇼핑몰 사용시에만 secure; SameSite=None 로 설정합니다.
	// https://developers-kr.googleblog.com/2020/01/developers-get-ready-for-new.html?fbclid=IwAR0wnJFGd6Fg9_WIbQPK3_FxSSpFLqDCr9bjicXdzy--CCLJhJgC9pJe5ss
	if(!function_exists('session_start_samesite')) {
		function session_start_samesite($options = array())
		{
			$res = @session_start($options);

			// IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None 을 설정하지 않습니다.
			if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) ){
				return $res;
			}

			$headers = headers_list();
			krsort($headers);
			foreach ($headers as $header) {
				if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
				$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
				header($header, false);
				break;
			}
			return $res;
		}
	}
	session_start_samesite();

//==============================================================================
// ### 여기까지 Chrome80 버전 이슈 대응 크롬################################################
//==============================================================================
################################################################################################
################################################################################################
################################################################################################

$default = sql_fetch("select * from shop_default");
$super = get_member(encrypted_admin());
$super_hp = $super['cellphone'];

// 보안서버주소 설정
if(MS_HTTPS_DOMAIN) {
	define('MS_HTTPS_BBS_URL', MS_HTTPS_DOMAIN.'/'.MS_BBS_DIR);
    define('MS_HTTPS_MBBS_URL', MS_HTTPS_DOMAIN.'/'.MS_MOBILE_DIR.'/'.MS_BBS_DIR);
    define('MS_HTTPS_SHOP_URL', MS_HTTPS_DOMAIN.'/'.MS_SHOP_DIR);
    define('MS_HTTPS_MSHOP_URL', MS_HTTPS_DOMAIN.'/'.MS_MOBILE_DIR.'/'.MS_SHOP_DIR);
} else {
    define('MS_HTTPS_BBS_URL', MS_BBS_URL);
    define('MS_HTTPS_MBBS_URL', MS_MBBS_URL);
    define('MS_HTTPS_SHOP_URL', MS_SHOP_URL);
    define('MS_HTTPS_MSHOP_URL', MS_MSHOP_URL);
}

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if(isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(MS_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if(isset($_REQUEST['set'])) {
	$set = trim($_REQUEST['set']);
	$qstr .= '&set=' . urlencode($set);
}

if(isset($_REQUEST['sca'])) {
    $sca = trim($_REQUEST['sca']);
    $qstr .= '&sca=' . urlencode($sca);
}

if(isset($_REQUEST['sfl'])) {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    $qstr .= '&sfl=' . urlencode($sfl); // search field (검색 필드)
}

if(isset($_REQUEST['stx'])) {
    $stx = trim($_REQUEST['stx']);
    $qstr .= '&stx=' . urlencode($stx);
}

if(isset($_REQUEST['sst'])) {
    $sst = trim($_REQUEST['sst']);
    $qstr .= '&sst=' . urlencode($sst);
}

if(isset($_REQUEST['sod'])) {
    $sod = trim($_REQUEST['sod']);
    $qstr .= '&sod=' . urlencode($sod);
}

if(isset($_REQUEST['sop'])) {
    $sop = trim($_REQUEST['sop']);
    $qstr .= '&sop=' . urlencode($sop);
}

if(isset($_REQUEST['spt'])) {
    $spt = trim($_REQUEST['spt']);
    $qstr .= '&spt=' . urlencode($spt);
}

if(isset($_REQUEST['ca_id'])) {
    $ca_id = trim($_REQUEST['ca_id']);
    $qstr .= '&ca_id=' . urlencode($ca_id);
}

if(isset($_REQUEST['fr_date'])) {
    $fr_date = trim($_REQUEST['fr_date']);
    $qstr .= '&fr_date=' . urlencode($fr_date);
}

if(isset($_REQUEST['to_date'])) {
    $to_date = trim($_REQUEST['to_date']);
    $qstr .= '&to_date=' . urlencode($to_date);
}

if(isset($_REQUEST['filed'])) {
    $filed = trim($_REQUEST['filed']);
    $qstr .= '&filed=' . urlencode($filed);
}

if(isset($_REQUEST['orderby'])) {
    $orderby = trim($_REQUEST['orderby']);
    $qstr .= '&orderby=' . urlencode($orderby);
}

// URL ENCODING
if(isset($_REQUEST['url'])) {
	$url = strip_tags(trim($_REQUEST['url']));
	$urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if(MS_DOMAIN) {
        $p = @parse_url(MS_DOMAIN);
        $urlencode = MS_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}
//===================================

// 자동로그인 부분에서 첫로그인에 쇼핑포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if($_SESSION['ss_mb_id']) { // 로그인중이라면
	$member = get_member($_SESSION['ss_mb_id']);

    // 차단된 회원이면 ss_mb_id 초기화
    if($member['intercept_date'] && $member['intercept_date'] <= date("Ymd", MS_SERVER_TIME)) {
		if(!get_session('admin_ss_mb_id')) { // 관리자 강제접속이 아닐때만.
			set_session('ss_mb_id', '');
			$member = array();
		}
    } else {
        // 오늘 처음 로그인 이라면
        if(substr($member['today_login'], 0, 10) != MS_TIME_YMD) {
            // 첫 로그인 쇼핑포인트 지급
            insert_point($member['id'], $config['login_point'], MS_TIME_YMD.' 첫로그인', '@login', $member['id'], MS_TIME_YMD);

            // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
            // 해당 회원의 접근일시와 IP 를 저장
            $sql = " update shop_member set login_sum = login_sum + 1, today_login = '".MS_TIME_YMDHIS."', login_ip = '{$_SERVER['REMOTE_ADDR']}' where id = '{$member['id']}' ";
            sql_query($sql);
        }
    }
} else {
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if($tmp_mb_id = get_cookie('ck_mb_id')) {

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if(strtolower($tmp_mb_id) != encrypted_admin()) {

            $sql = " select passwd, intercept_date from shop_member where id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['passwd']);
            // 쿠키에 저장된 키와 같다면

            $tmp_key = get_cookie('ck_auto');
			if($tmp_key === $key && $tmp_key) {
                // 차단, 인트로 사용이 아니라면
           //     if($row['intercept_date'] == '' && !$config['shop_intro_yes'] ) {
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session('ss_mb_id', $tmp_mb_id);

                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
              //  }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}

if($boardid) {
    //echo "111111111";
	$board = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
  if($board['index_no']) {
    $write_table = 'shop_board_'.$boardid; // 게시판 테이블 전체이름
    if(isset($index_no) && $index_no) {
      $write = sql_fetch(" select * from $write_table where index_no = '$index_no' ");
    }
  }

}

if ($bo_table) {
    //echo "222222222";
    $write = array();
    $write_table = "";
    $board = sql_fetch(" select * from g5_board where bo_table = '$bo_table' ");
    //echo " select * from g5_board where bo_table = '$bo_table' "; 
    if ($board['bo_table']) {
        set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
        $gr_id = $board['gr_id'];
        $write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        //$comment_table = $g5['write_prefix'] . $bo_table . $g5['comment_suffix']; // 코멘트 테이블 전체이름
        if (isset($wr_id) && $wr_id)
            $write = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");
    }
}


if ($gr_id) {
    $group = sql_fetch(" select * from g5_group where gr_id = '$gr_id' ");
}

// 비회원구매를 위해 쿠키를 1년간 저장
if(!get_cookie("ck_guest_cart_id"))
	set_cookie("ck_guest_cart_id", MS_SERVER_TIME, 86400 * 365);

$set_cart_id = get_cookie('ck_guest_cart_id');

// 회원, 비회원 구분
$is_admin = $mb_no = '';
if($member['id']) {
	$is_member = 1;
  $is_admin = get_admin($member['id']);
  if($member['pt_id']) { $pt_name = get_member($member['pt_id']); }
	$minishop = get_minishop($member['id']);
	$seller = get_seller($member['id']);
	$mb_no = $member['index_no'];
} else {
	$is_member = 0;
    $member['id'] = '';
    $member['grade'] = 10; // 비회원의 경우 회원레벨을 가장 낮게 설정
}

if(!is_admin()) {
    // 접근가능 IP
    $possible_ip = trim($config['possible_ip']);
    if($possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $possible_ip);
        for($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if(empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if($is_possible_ip)
                break;
        }
        if(!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['intercept_ip']));
    for($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if(empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}

//==============================================================================
// 사용기기 설정
// config.php MS_SET_DEVICE 설정에 따라 사용자 화면 제한됨
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('MS_SET_DEVICE')) {
    switch(MS_SET_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}
//==============================================================================


$g5_object = new G5_object_cache();

//==============================================================================
// 스킨경로
//------------------------------------------------------------------------------
if (MS_IS_MOBILE) {
    $board_skin_path    = get_skin_path('board', $board['bo_mobile_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_mobile_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_mobile_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_mobile_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_mobile_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_mobile_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_mobile_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_mobile_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_mobile_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_mobile_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_mobile_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_mobile_faq_skin']);
} else {
    $board_skin_path    = get_skin_path('board', $board['bo_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_faq_skin']);
}
//==============================================================================



//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
// MS_MOBILE_AGENT : config.php 에서 선언
//------------------------------------------------------------------------------
if(MS_USE_MOBILE && $set_device) {
    if($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if($_REQUEST['device']=='mobile')
        $is_mobile = true;
	else if(defined('MS_USERIN_MOBILE'))
        $is_mobile = true;
    else if(isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if(is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('MS_IS_MOBILE', $is_mobile);
define('MS_DEVICE_BUTTON_DISPLAY', $set_device);
if(MS_IS_MOBILE) {
    $ms['mobile_path'] = MS_PATH.'/'.$ms['mobile_dir'];
    $g5['mobile_path'] = MS_PATH.'/'.$g5['mobile_dir'];
}
//==============================================================================

// 가맹점 쇼핑몰설정
include_once(MS_PATH.'/minishop.config.php');


// 일정 기간이 지난 DB 데이터 삭제 및 최적화
include_once(MS_LIB_PATH.'/db_table.optimize.php');


include_once(MS_PLUGIN_PATH."/zentool/zentool.crond.php"); // ZenTool Library


// common.php 파일을 수정할 필요가 없도록 확장합니다.
$extend_file = array();
$tmp = dir(MS_EXTEND_PATH);
while($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(MS_EXTEND_PATH.'/'.$file);
    }
}
unset($extend_file);

if( isset($_GET['_from']) && $_GET['_from'] == 'app') {
    set_cookie('_from_app', 'Y', 24 * 60 * 26);
} else if( false ) {
    set_cookie('_from_app', 'N', 0);
}
if( isset($_GET['_limit']) && $_GET['_limit'] == 'mypage') {
    set_cookie('_limit_app', 'Y', 24 * 60 * 26);
} else if( false  ){
    set_cookie('_limit_app', 'N', 0);
}

if( get_cookie('_from_app') == 'Y' ){
    define('MS_FROM_APP', true);
} else {
    define('MS_FROM_APP', false);
}

if( !$is_member && (get_cookie('_limit_app') == 'Y' || get_cookie('_from_app') == 'Y') ){
    define('MS_LIMIT_MYPAGE', true);
} else {
    define('MS_LIMIT_MYPAGE', false);
}

if( false && MS_LIMIT_MYPAGE ) {

    switch($_SERVER['SCRIPT_NAME']) {
        case '/m/shop/mypage.php' :
        case '/m/bbs/login.php' :
        case '/m/bbs/login_check.php' :
        case '/m/bbs/logout.php' :
        case '/m/bbs/password_lost.php';
        case '/m/bbs/password_lost2.php';
        case '/m/bbs/password_lost_certify.php';
        case '/m/bbs/register.php';
        case '/m/bbs/register_form.php';
        case '/m/bbs/register_form_update.php';
        case '/m/bbs/policy.php';
        case '/m/bbs/provision.php';
        case '/m/index.php';
        case '/plugin/zentool/minishop/ajax.find_user.php';
            break;
        default :
            dd( $_SERVER['SCRIPT_NAME'] );
            goto_url('/m/shop/mypage.php' );
            break;

    }
}
ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0


$html_process = new html_process();

?>
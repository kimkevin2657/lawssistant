<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 14:04
 */
include_once(__DIR__.'/vendor/autoload.php');

define('ZEN_DIR'               , __DIR__ );
// Line Up 점수 사용
define("USE_LINE_UP"           , false);
// 매칭 상용
define('USE_ANEWMATCH'         , true);
// 매칭 현황 사용
define('ZEN_USE_MATCHING_ADMIN', true);
// 라인 현황 사용
define('ZEN_USE_LINE_ADMIN'    , true);
// 조직도 사용
define('USE_ORGCHART'          , true);
// 가맹점 관리 페이지 심플 메뉴
define('USE_SIMPLE_ADMIN_MENU' , true);
// 가맹점 메뉴 마이페이지로 통합
define('USE_MYPAGE'            , true);
// 간단 모바일
define('USE_SIMPLE_MOBILE'     , true);
// 공유점수
define('USE_KPOINT'            , true);
// 가맹 상품 사용
define('USE_BUY_PARTNER_GRADE' , true);
define('USE_ROLLUP'            , false);
define('USE_SHOPPING_PAY'      , false);
// 숖핑페이 구매 사용 여부
define('USE_SHOPPING_PAY_BUY'  , false);
// 쇼페페이 환전 사용 여부
define('USE_SHOPPING_PAY_EXCHANGE', false);

define('USE_PG_TEST'               , false);

define('ORD_DONE_DAN'              , 'USER_OK');

include_once(__DIR__.'/exceptions/SameSourceTargetException.php');
include_once(__DIR__.'/exceptions/NotExistsMemberException.php');
include_once(__DIR__.'/exceptions/ExistsMemberException.php');
include_once(__DIR__.'/lib/Current.php');
include_once(__DIR__.'/lib/JsonResult.php');
include_once(__DIR__.'/lib/JsonResponse.php');
include_once(__DIR__.'/lib/JsonResponseData.php');
include_once(__DIR__.'/test/UnitTest.php');
include_once(__DIR__.'/http/Request.php');

include_once(__DIR__."/orgchart/Organization.php"); // 조직도 Chart
include_once(__DIR__."/mcrypt/Mcrypt.php"); // 암호화/복호화

include_once(__DIR__."/order/Order.php"); // 주문관리
include_once(__DIR__."/match/Match.php"); // 매칭 시스템
include_once(__DIR__.'/match/LinePoint.php');
include_once(__DIR__."/good/Good.php");// 상품관리
include_once(__DIR__."/minishop/minishop.php");// 상품관리

include_once(__DIR__."/point/Point.php");// 상품관리
include_once(__DIR__.'/minishop/Member.php');
include_once(__DIR__.'/seller/Seller.php');
include_once(__DIR__.'/theme/Theme.php');

include_once(__DIR__.'/minishop/Shop.php');
include_once(__DIR__.'/minishop/ShoppingPay.php');

define('USE_MOBILE_JOIN_FORM', true);
$hosting_services = array(
    '1'=>array('free'=>'', 'price'=>'10000'),
    '3'=>array('free'=>'', 'price'=>'30000'),
    '6'=>array('free'=>'5', 'price'=>'60000'),
    '12'=>array('free'=>'4', 'price'=>'100000')
);


if( !function_exists('maybe') ) {

    function maybe($val, $defaultValue) {
        return ( empty($val) ) ? $defaultValue : $val;
    }

}
if( !function_exists('dd') ) {
    function dd($val){
        var_dump($val);
        debug_print_backtrace();
        die();
    }
}

if( !function_exists('isCompanyBank')) {
    function isCompanyBank($bankList, $bank){
        $arr_bank = explode(' ', $bank.' ');
        $bank_name= trim($arr_bank[0]);
        return isset( $bankList[$bank_name] );
    }
}

if( !function_exists('logger')){
    function logger($file_name = ''){

        if( $file_name == '' ) {
            $file_name = ZEN_DIR.'/logs/debug.log';
        }
        \Monolog\Logger::setTimezone(new DateTimeZone('Asia/Seoul'));

        $logger = new \Monolog\Logger(basename($file_name));
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($file_name));

        return $logger;
    }
}

// return message
use App\service\MessageService;


if( !function_exists('_r')){
    function _r(){
        return call_user_func_array([MessageService::class, 'getMessage'], func_get_args());
    }
}

// echo message
if( !function_exists( '_e')){
    function _e() {
        $args = func_get_args();
        $dic = [
            '타임세일'=>'핫딜',
            '쇼핑특가'=>'오늘의 득템'
        ];
        if( isset($dic[$args[0]]) ) {
            return $dic[$args[0]];
        }
        echo call_user_func_array('_r', func_get_args());
    }
}

if( !function_exists('_er')){
    function _er(){
        return call_user_func_array('_r', func_get_args());
    }
}

// javascript
if( !function_exists( '_jr')) {
    function _jr(){
        return str_replace("\n", "\\n",addslashes(call_user_func_array('_r', func_get_args())));
    }
}

if( !function_exists('_j')){
    function _j(){
        echo call_user_func_array('_jr', func_get_args());
    }
}

if( !function_exists( '_erj')) {
    function _erj(){
        call_user_func_array('_j', func_get_args());
    }
}
if( !function_exists('_ej')){
    function _ej(){
        call_user_func_array('_j', func_get_args());
    }
}

// strip html
if( !function_exists('_eh')){
    function _eh($msg){
        echo strip_tags(call_user_func_array('_r', func_get_args()));
    }
}

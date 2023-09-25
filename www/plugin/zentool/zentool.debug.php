<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 14:11
 */

if( false && $_SERVER['SERVER_ADDR'] != '127.0.0.1') die('Local Developer Server Debugging Only!!!');


// SQL 실행 로그 담기
//$_sql_log = array();

include_once(__DIR__."/_common.php");
include_once(__DIR__."/test/UnitTest.php");

$method = $_REQUEST['method'];

if( method_exists(UnitTest::class, $method) ) {

}

//header("Content-type: application/json; charset=utf-8");

//UnitTest::joinFromOrderTest();
//UnitTest::matchUpLineUpTest();
//UnitTest::mathUpTest();
//UnitTest::linePoint();

//UnitTest::initAnewPay();
//UnitTest::replaceEncToDecInMemo();
//UnitTest::encodeSellerCode();
//UnitTest::transSpPointToPoint();
//UnitTest::changeId();
//UnitTest::rollbackAnewPoint();

//UnitTest::resetHierarchy();

//UnitTest::insertPointFb33();

//UnitTest::updateHierarchyUp();

// UnitTest::resetHierarchyPt();
// UnitTest::resetHierarchyUp();

//UnitTest::updateGrade();

//UnitTest::migUpgradePay();

//UnitTest::migAnewUpPoint();

//insert_anew_pay('L0R3ZFVmTlJ6NmRlUEV3Qk1iK3gyaDRUM09kV0RGdFU5MVFNT3JBVGliaz0');

// UnitTest::migShareMonthly();
//UnitTest::encrypteIdToPlainId();

//UnitTest::resetHierarchy();

//UnitTest::migPhoneChinguImages();

// UnitTest::doOrderStatus();

UnitTest::migminishopPoint();


if( isset($_sql_log) && is_array($_sql_log)) var_dump($_sql_log);

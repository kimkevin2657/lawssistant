<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-02
 * Time: 21:54
 */
include(__DIR__."/_common.php");

if( ! is_admin() ) return;
$pt_id = $_POST['pt_id'];
$mb_id = $_POST['mb_id'];
$reg_price = $_POST['reg_price'];
$anew_grade= $_POST['anew_grade'];

if( Match::matchUp($pt_id, $mb_id, $reg_price, $anew_grade) ) {
    JsonResponse::response(JsonResult::SUCCESS, "매칭 되었습니다.")->response();
} else {
    JsonResponse::response(JsonResult::FAIL, "매칭에 실패 했습니다.")->response();
}



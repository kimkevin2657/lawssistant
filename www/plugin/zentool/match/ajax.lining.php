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
$anew_grade= $_POST['anew_grade'];

if( Match::lineUp($pt_id, $anew_grade) ) {
    JsonResponse::response(JsonResult::SUCCESS, "라인 생성 되었습니다.")->response();
} else {
    JsonResponse::response(JsonResult::FAIL, "라인 생성 실패 했습니다.")->response();
}



<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 17:53
 */

class ApiResponse
{
    const SUCCESS = 'S000';
    const NOT_EXIST_CLIENT_ID = 'E001'; //존재하지 않는 Client ID 또는 Client Secret 입니다.
    const NOT_EXIST_TOKEN = 'E002';//토큰이 존재 하지 않습니다.
    const NOT_EXIST_LOGIN_ID = 'E003';//로그인 후 이용하세요.
    const NOT_ENOUGH_POINT = 'E004';//보유 포인트가 부족합니다.
    const NOT_EXIST_ACTION = 'E005';//존재 하지 않는 동작입니다.
    const INVALID_PARAMETER= 'E006';//파라미터 오류 입니다.
    const INVALID_LOGIN_USER='E007';//로그인 오류 입니다.
    const NOT_EXIST_TRAN_ID= 'E008';//존재 하지 않는 Tran ID 입니다.
    const INVALID_NUMBER='E009';//Point 는 숫자여야 합니다.
    const UNKNOWN_ERROR = 'E999'; //알수 없는 오류 입니다.

    const KEY_RSLT_CODE = "RsltCode";
    const KEY_RSLT_MESSAGE = "RsltMessage";

    public $RsltCode = 'S000';
    public $RsltMessage = '성공';

    public $Data = [];

}
<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 17:51
 */

class ApiAuth
{
    public static function auth($client_id, $client_secret)
    {
        $client = sql_fetch("select * from shop_api_auth where client_id = '{$client_id}' and client_secret='{$client_secret}'");
        if( $client ) {
            $token = get_uniqid();
            sql_query("insert into shop_api_token(token, client_id, expire_at) values('{$token}', '{$client_id}', date_add(NOW(), interval 1 hour))");
            set_session('api_token_'.$token, serialize(['client_id'=>$client_id,'client_secret'=>$client_secret]));
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::SUCCESS, 'token'=>$token];
        } else {
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_CLIENT_ID, ApiResponse::KEY_RSLT_MESSAGE=>'존재하지 않는 Client ID 또는 Client Secret 입니다.'];
        }
    }
}
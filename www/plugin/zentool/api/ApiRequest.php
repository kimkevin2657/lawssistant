<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 17:53
 */

class ApiRequest
{
    public $remote_addr;
    public $client_id;
    public $client_secret;
    public $version;
    public $token;
    public $action;
    public $mb_id;
    public $param;

    public function __construct($param)
    {
        $this->remote_addr = $_SERVER['REMOTE_ADDR'];
        $this->client_id = $param['client_id'];
        $this->client_secret = $param['client_secret'];
        $this->version   = $param['version'];
        $this->token     = $param['token'];
        $this->action    = $param['action'];

        if( $this->action != 'get_token' && ! empty($this->token) ) {
            $sql = "SELECT a.client_id, a.client_secret FROM shop_api_auth a, shop_api_token b WHERE a.client_id =b.client_id AND b.token = '{$this->token}'";
            $client = sql_fetch($sql);

            if( $client ) {
                $this->client_id = $client['client_id'];
                $this->client_secret = $client['client_secret'];
            }
        }

        $this->param     = (object) $param;
        $this->mb_id     = get_session('api_mb_id');
        //$this->token     = get_session('api_token');

    }

    public function do_action()
    {
        switch($this->action) {
            case 'get_token' :
                $rslt = ApiAuth::auth($this->client_id, $this->client_secret);
                break;
            case 'do_login':
                if( empty($this->client_id) ) {
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_TOKEN, ApiResponse::KEY_RSLT_MESSAGE=>'토큰이 존재 하지 않습니다.'];
                    break;
                }
                $rslt = Member::do_login($this->param->mb_id, $this->param->mb_passwd);
                if( $rslt[ApiResponse::KEY_RSLT_CODE] == ApiResponse::SUCCESS) {
                    set_session('api_mb_id', $this->param->mb_id);
                }
                break;
            case 'add_point':
                if( empty($this->mb_id) ){
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_LOGIN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'로그인 후 이용하세요.'];
                    break;
                }
                $rslt = Point::add_point($this->mb_id, abs($this->param->point),$this->param->content.'-API('.$this->client_id.') 포인트 적립@'.date('YmdHis'),Point::API_TABLE, $this->client_id);
                break;
            case 'get_point' :
                if( empty($this->mb_id) ){
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_LOGIN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'로그인 후 이용하세요.'];
                    break;
                }
                $rslt = Point::get_point($this->mb_id);
                break;
            case 'use_point':
                if( empty($this->mb_id) ){
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_LOGIN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'로그인 후 이용하세요.'];
                    break;
                }
                $cur_point = get_point_sum($this->mb_id);
                $use_point = abs($this->param->point);
                if( $cur_point < $use_point )  {
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_ENOUGH_POINT, ApiResponse::KEY_RSLT_MESSAGE=>'보유 포인트가 부족합니다.'];
                    break;
                }
                $rslt = Point::use_point($this->mb_id, -($use_point), $this->param->content.'-API('.$this->client_id.') 포인트 사용@'.date('YmdHis'), Point::API_TABLE, $this->client_id);
                break;
            case 'rollback':
                if( empty($this->mb_id) ){
                    $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_LOGIN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'로그인 후 이용하세요.'];
                    break;
                }
                $rslt = Point::rollback($this->mb_id, $this->param->tran_id, Point::API_TABLE, $this->client_id);
                break;
            default :
                $rslt = [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_ACTION, ApiResponse::KEY_RSLT_MESSAGE=>'존재 하지 않는 동작 입니다.'];
                break;
        }


        if( $rslt[ApiResponse::KEY_RSLT_CODE] == ApiResponse::SUCCESS ) {

        }

        return $rslt;
    }
}
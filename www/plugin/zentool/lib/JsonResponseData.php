<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 11:55
 */

class JsonResponseData
{
    var $result;
    var $data;
    var $message;

    public static function success($data)
    {
        $instance = new self;

        $instance->result = JsonResult::SUCCESS;
        $instance->message= $instance->data   = $data;

        return $instance;
    }

    public static function error($data)
    {
        $instance = new self;

        $instance->result = JsonResult::FAIL;
        $instance->message= $instance->data   = $data;

        return $instance;
    }

    public function response()
    {
        if( Request::isAjax() )
            header("Content-type: application/json; charset=utf-8");


        echo json_encode($this);
    }
}
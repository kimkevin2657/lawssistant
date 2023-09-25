<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 11:53
 */

class JsonResponse
{

    public static function response($rslt, $data)
    {
        if( $rslt == JsonResult::SUCCESS ) {
            return JsonResponseData::success($data);
        } else {
            return JsonResponseData::error($data);
        }
    }
}
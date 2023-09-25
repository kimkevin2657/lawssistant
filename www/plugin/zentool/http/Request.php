<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-01
 * Time: 20:38
 */

class Request
{

    public static function isAjax()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }
}
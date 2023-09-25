<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 12:33
 */

class Current
{

    public static function getLoggedId()
    {
        global $_SESSION;

        return $_SESSION['ss_mb_id'];
    }
}
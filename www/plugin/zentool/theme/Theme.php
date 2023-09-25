<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-01-14
 * Time: 18:22
 */

class Theme
{

    public static function get_theme_part($_path, $_skin)
    {
        @extract($GLOBALS);
        if( file_exists($_path.$_skin) ){
			include_once($_path.$_skin);
        }else {
            $_path = preg_replace('/^(.*?)\/([^\/]+)$/', '$1/basic', $_path);
            if( file_exists($_path.$_skin)) include_once($_path.$_skin);
            else var_dump(array($_path,$_skin));
        }
    }
}
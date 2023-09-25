<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 14:11
 */


// SQL 실행 로그 담기
//$_sql_log = array();

include_once(__DIR__."/_common.php");

$encrypted = $_REQUEST['encrypted'];
$plain     = $_REQUEST['plain'];
if( !empty($encrypted ) ) echo $encrypted.'=>'.Mcrypt::zen_decrypt($encrypted);
if( !empty($plain)      ) echo $plain.'=>'.Mcrypt::zen_encrypt($plain);


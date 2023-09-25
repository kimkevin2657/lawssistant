<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 11:38
 */
include_once("../zentool.ajax.php");

Partner::check($_POST['id'],$_POST['grade'])->response();
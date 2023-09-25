<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 12:27
 */

include_once("../zentool.ajax.php");

echo json_encode(Partner::findTopId($_POST['id']));
<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-28
 * Time: 03:24
 */
include_once("../zentool.ajax.php");

Seller::payCalcRollback($_POST)->response();
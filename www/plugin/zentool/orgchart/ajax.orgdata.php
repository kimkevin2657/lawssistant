<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 21/11/2018
 * Time: 12:33 PM
 */
include(__DIR__."/_common.php");

$mb_id = $_POST['mb_id'];
$up_nm = $_POST['up_nm'];
$max_depth = $_POST['max_depth'];
$max_node  = $_POST['max_node'];

$orgChart = new Organization($mb_id, $up_nm, $max_depth, $max_node);

echo $orgChart->toJson();

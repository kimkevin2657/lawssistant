<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 11:38
 */
include_once("../zentool.ajax.php");

$stx = $_POST['stx'];
$sfl = $_POST['sfl'];

$where = "";
if( $sfl != '' ) {
    $where.= " AND a.id LIKE '%".$sfl."%'";
}
if( $stx != '' ) {
    $where.= " AND a.name LIKE '%{$stx}%'";
}

if( $member && is_admin($member['grade'])) {
    $fetches = 'a.id, a.name, a.reg_time';
} else {
    $fetches = 'a.id, a.name, a.reg_time';
}

$sql = "SELECT {$fetches}
          FROM shop_member a 
         WHERE 1 {$where}";

$rslt= sql_query($sql);
$data= array();
while($row = sql_fetch_array($rslt)){
    $row['plain_name'] = $row['name'];

    $row['id'] = $row['id'];

    array_push($data, $row);
}
echo json_encode($data);

<?
    include_once("./_common.php");
    $mb_id = $_GET['mb_id'];
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $addr = $_GET['addr'];


    sql_query("UPDATE shop_member SET mb_lat = '{$lat}', mb_lng = '{$lng}', mb_addr = '{$addr}' where id = '{$mb_id}' ");
    /* echo $lat."<br>";
    echo $lng."<br>";
    echo $addr."<br>"; */


?>
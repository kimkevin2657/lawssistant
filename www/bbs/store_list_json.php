<?
    include_once("./_common.php");

    $mb_6 = $_GET['mb_6'];
    $mb_7 = $_GET['mb_7'];

    $positions = array();

    //전체중 거리에서 가까운 순으로
    $sql = sql_query("SELECT * , ( 6371 * ACOS( COS( RADIANS( {$mb_6} ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( {$mb_7} ) ) + SIN( RADIANS( {$mb_6} ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM shop_member having distance <= 1 ");

    for($i=0; $row = sql_fetch_array($sql); $i++){

        $positions['positions'][$i]['lat'] = (float)$row['lat'];
        $positions['positions'][$i]['lng'] = (float)$row['lng'];
        $positions['positions'][$i]['name'] = $row['name'];
    }

    //print_r($positions);
    echo json_encode($positions);
?>
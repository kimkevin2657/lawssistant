<?php
include_once("./_common.php");

if($_POST["mode"]=='point') {//룰렛 포인트

	$mid	=	$member['id'];
	$point	=	$_POST["point"];

 $sql = " select count(*) as cnt
				   from shop_point
                  where mb_id = '$mid'
                    and po_content = '출석! 룰렛 지급'
                    and DATE_FORMAT(po_datetime, '%Y-%m-%d') = '".date("Y-m-d")."' ";
        $arr = sql_fetch($sql);
	
	if($arr["cnt"] > 0){
		echo "FAIL";
		exit;
	}
	else{
		insert_point($mid, $point, "출석! 룰렛 지급");
		echo "OK";
		exit;
	}

}
?>

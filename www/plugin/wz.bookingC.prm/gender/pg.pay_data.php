<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

if(empty($_POST))
    die('정보가 넘어오지 않았습니다.');

$od_id = get_session('ss_order_id');

// 일정 기간이 경과된 임시 데이터 삭제
$limit_time = date("Y-m-d H:i:s", (G5_SERVER_TIME - 86400 * 1));
$sql = " delete from {$g5['wzb_booking_data_table']} where dt_time < '$limit_time' ";
sql_query($sql);

$dt_data = base64_encode(serialize($_POST));

//alert($dt_data);

// 동일한 주문번호가 있는지 체크
$sql = " select count(*) as cnt from {$g5['wzb_booking_data_table']} where od_id = '$od_id' ";
//echo $sql;
$row = sql_fetch($sql);
if($row['cnt'])
    sql_query(" delete from {$g5['wzb_booking_data_table']} where od_id = '$od_id' ");

$sql = " insert into {$g5['wzb_booking_data_table']}
            set od_id   = '$od_id',
                dt_pg   = '{$wzpconfig['pn_pg_service']}',
                mb_id   = '{$member['mb_id']}',
                dt_data = '$dt_data',
                dt_time = '".G5_TIME_YMDHIS."' ";
sql_query($sql);

die('');
?>
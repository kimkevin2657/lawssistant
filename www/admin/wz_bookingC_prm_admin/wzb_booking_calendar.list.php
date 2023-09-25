<?php
$sub_menu = '790420';
include_once('./_common.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

$sql_common = " from {$g5['wzb_room_status_table']} as rms inner join {$g5['wzb_room_table']} as rm inner join {$g5['wzb_booking_table']} as bk on rms.rm_ix = rm.rm_ix and rms.bk_ix = bk.bk_ix ";
$sql_search = " where rms_status <> '취소' ";
$sql_order = " order by bk.bk_ix desc, rms.rms_time asc ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql, true);
$total_count = $row['cnt'];

$rows = "40";
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_list);
$arr_list = array();
$query = "select rms.*, rm.rm_subject, bk.bk_name, bk.bk_hp, bk.od_id, bk.bk_time, bk.bk_time {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_list[] = $row;
}
$cnt_list = count($arr_list);
if ($res) sql_free_result($res);
?>

<table cellpadding="0" cellspacing="0" border="0">
<thead>
<tr>
    <th scope="col">예약일시</th>
    <th scope="col">방이름</th>
    <th scope="col">예약번호</th>
    <th scope="col">예약자</th>
    <th scope="col">날짜</th>
    <th scope="col">시간</th>
    <th scope="col">인원</th>
    <th scope="col">연락처</th>
</tr>
</thead>
<tbody>
<?php
if ($cnt_list > 0) {
    foreach ($arr_list as $k => $v) {
        ?>
        <tr>
            <td><?php echo $v['bk_time']?></td>
            <td><?php echo $v['rm_subject']?></td>
            <td><a href="./wzb_booking_view.php?bk_ix=<?php echo $v['bk_ix'];?>" style="text-decoration: underline;"><?php echo $v['od_id']?></a></td>
            <td><?php echo $v['bk_name']?></td>
            <td><?php echo $v['rms_date']?></td>
            <td><?php echo $v['rms_time']?></td>
            <td><?php echo $v['rms_cnt']?></td>
            <td><?php echo $v['bk_hp']?></td>
        </tr>
        <?php
    }
}
else {
    ?>
    <tr>
        <td colspan="9" class="td_alignc">데이터가 존재하지 않습니다.</td>
    </tr>
    <?php
}
?>

</tbody>
</table>

<div class="get-ajax-page">
<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>
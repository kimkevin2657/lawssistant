<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$qstr .= '&amp;bo_table='.$bo_table.'&mode=orderlist';
if (isset($_REQUEST['page'])) { // 리스트 페이지
    $page = (int)$_REQUEST['page'];
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

// 충전정보
$sql_common = " from {$g5['wpot_order_table']} where mb_id = '{$member['id']}' ";
$query = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($query);

$total_count = $row['cnt'];
$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_order);
$arr_order = array();
$query = "select * {$sql_common} order by od_id desc limit {$from_record}, {$rows} ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res) sql_free_result($res);

include_once(WPOT_PLUGIN_PATH.'/navi_reserv.php');
?>

<div class="panel panel-default">

	<div class="panel-heading"><strong><i class="fa fa-credit-card fa-lg"></i> Oh!포인트 충전내역</strong></div>
	<div class="table-responsive">
		<table class="table form-group form-group-sm table-bordered font-color-gray">
        <thead>
		<tr>
            <th scope="col">결제번호</th>
            <th scope="col">충전포인트</th>
            <th scope="col">결제금액</th>
            <th scope="col">결제상태</th>
            <th scope="col">결제방식</th>
            <th scope="col">신청일시</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($cnt_order > 0) {
            foreach ($arr_order as $k => $v) {

            $uid = md5($v['od_id'].$v['bk_time'].$v['bk_ip']);
            ?>
            <tr>
                <td>
                    <a href="javascript:;" onclick="show_page('<?php echo $uid;?>', '<?php echo $v['od_id'];?>');" class="linker" title="결제번호 <?php echo $v['od_id'];?> 의 상세정보 확인"><?php echo $v['od_id'];?> <i class="fa fa-search-plus"></i></a>
                </td>
                <td style="text-align:right"><?php echo number_format($v['bk_charge_point']);?></td>
                <td style="text-align:right"><?php echo number_format($v['bk_price']);?> 원</td>
                <td><?php echo $v['bk_status'];?></td>
                <td><?php echo $v['bk_payment'];?></td>
                <td><?php echo $v['bk_time'];?></td>
            </tr>
            <?php
            }
        }
        else {
            ?>
            <tr>
                <td colspan="6" style="text-align:center">
                    충전내역이 존재하지 않습니다.
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        </table>

    </div>
</div>

<?php
echo wz_get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page=');
?>

<?php if ($wzcnf['cf_con_refund']) {?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-file-text-o fa-lg"></i> 환불규정</strong></div>
    <div class="panel-body">
        <div class="bs-callout bs-callout-warning">
            <?php echo $wzcnf['cf_con_refund'];?>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
<!--
    function show_page(uid, od_id) {
        location.href = '<?php echo WPOT_STATUS_HTTPS_URL?>&mode=orderdetail&uid='+uid+'&od_id='+od_id;
    }
//-->
</script>
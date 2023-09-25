<?php
include_once('./_common.php');

$sql_common = " from wpot_order ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
    $is_sch = true;
}

if ($sch_frdate1 && $sch_todate1) {
    $sql_search .= " and DATE(bk_time) between '".$sch_frdate1."' and '".$sch_todate1."' ";
    $qstr .= "&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1;
    $is_sch = true;
}

if ($sch_status) {
    $sql_search .= " and bk_status = '".$sch_status."' ";
    $qstr .= "&sch_status=".$sch_status;
    $is_sch = true;
}

if ($sch_payment) {
    $sql_search .= " and bk_payment = '".$sch_payment."' ";
    $qstr .= "&sch_payment=".$sch_payment;
    $is_sch = true;
}

if (!$sst) {
    $sst = "od_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select
                count(*) as tcnt,
                sum(case when bk_status = '대기' then 1 else 0 end) as waiting_cnt,
                sum(case when bk_status = '완료' then 1 else 0 end) as complete_cnt,
                sum(case when bk_status = '취소' then 1 else 0 end) as cancel_cnt
         {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count    = $row['tcnt'];
$waiting_count  = $row['waiting_cnt'];
$complete_count = $row['complete_cnt'];
$cancel_count   = $row['cancel_cnt'];

$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_order);
$arr_order = array();
$query = "select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res) sql_free_result($res);

$g5['title'] = '충전정보 목록보기';

$pg_title = ADMIN_MENU12;
$pg_num = 12;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

if($code == "order_list")			$pg_title2 = ADMIN_MENU12_01;
if($code == "pay_list")				$pg_title2 = ADMIN_MENU12_02;
if($code == "config")				$pg_title2 = ADMIN_MENU12_03;

include_once(MS_ADMIN_PATH."/admin_topmenu.php");
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?code=order_list" class="ov_listall">전체목록</a>';
?>
<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	    include_once(MS_ADMIN_PATH."/wz_chargepoint_admin/{$code}.php");
	?>
</div>
<?php
include_once(MS_ADMIN_PATH."/wz_chargepoint_admin/admin_tail_config.php");
?>
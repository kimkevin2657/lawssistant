<?php
include_once('./_common.php');

$sql_common = " from {$g5['wzb_booking_table']} ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
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

if ($sch_cp_ix) {
    $sql_search .= " and cp_ix = '".$sch_cp_ix."' ";
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

if ($sch_room) {
    $sql_search .= " and bk_ix in (select bk_ix from {$g5['wzb_booking_room_table']} where bkr_subject like '%".$sch_room."%') ";
    $qstr .= "&sch_room=".$sch_room;
    $is_sch = true;
}

if ($sch_frdate1 && $sch_todate1) {
    $sql_search .= " and bk_ix in (select bk_ix from {$g5['wzb_booking_room_table']} where bkr_date between '".$sch_frdate1."' AND '".$sch_todate1."') ";
    $qstr .= "&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1;
    $is_sch = true;
}

if ($sch_frdate2 && $sch_todate2) {
    $sql_search .= " and DATE(bk_time) between '".$sch_frdate2."' and '".$sch_todate2."' ";
    $qstr .= "&sch_frdate2=".$sch_frdate2."&sch_todate2=".$sch_todate2;
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
    $sst = "bk_ix";
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

$rows = '40';
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_order);
$arr_order = array();
$query = "select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $query2 = "select rms.*, rm.rm_subject from {$g5['wzb_room_status_table']} as rms inner join {$g5['wzb_room_table']} as rm on rms.rm_ix = rm.rm_ix where bk_ix = '{$row['bk_ix']}' order by rms_ix asc "; // 객실정보
    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) {
        $row['rm'][] = $row2;
    }

    $query2 = "select cp_title from {$g5['wzb_corp_table']} where cp_ix = '{$row['cp_ix']}' "; // 업체정보
    $row2 = sql_fetch($query2);
    $row['cp_title'] = $row2['cp_title'];

    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res) sql_free_result($res);

$g5['title'] = '예약정보 목록보기';

$pg_title = ADMIN_MENU11;
$pg_num = 11;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

if($pn == "wzb_booking_list")			$pg_title2 = ADMIN_MENU11_01;
if($pn == "wzb_booking_status")				$pg_title2 = ADMIN_MENU11_02;
if($pn == "wzb_booking_calendar")				$pg_title2 = ADMIN_MENU11_03;
if($pn == "wzb_room_list")			$pg_title2 = ADMIN_MENU11_04;
if($pn == "wzb_room_status")	$pg_title2 = ADMIN_MENU11_05;
if($pn == "wzb_price_list")				$pg_title2 = ADMIN_MENU11_06;
if($pn == "wzb_room_option_list")			$pg_title2 = ADMIN_MENU11_07;
if($pn == "wzb_holiday_list")			$pg_title2 = ADMIN_MENU11_08;
if($pn == "wzb_pay_list")				$pg_title2 = ADMIN_MENU11_09;
if($pn == "wzb_popup_list")			$pg_title2 = ADMIN_MENU11_10;
if($pn == "wzb_config")			$pg_title2 = ADMIN_MENU11_11;

include_once(MS_ADMIN_PATH."/admin_topmenu.php");
//include_once (MS_ADMIN_PATH.'/admin.head.php');
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?code=wzb_booking_list" class="ov_listall">전체목록</a>';
?>
<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(MS_ADMIN_PATH."/wz_bookingC_prm_admin/{$code}.php");
	?>
</div>
<?php
include_once(MS_ADMIN_PATH."/wz_bookingC_prm_admin/admin_tail_config.php");
?>
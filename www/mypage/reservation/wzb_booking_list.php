<?php
if(!defined('_TUBEWEB_')) exit;
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

$sql_search .= " and store_mb_id = '{$member['id']}' ";

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
//echo $query;
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

$pg_title = '예약정보 목록보기';

/* $pg_title = ADMIN_MENU11;
$pg_num = 11;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

include_once (TB_ADMIN_PATH.'/admin.head.php');
include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php'); */
include_once("./admin_head.sub.php");
$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?code=wzb_booking_list" class="ov_listall">전체목록</a>';
?>
<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    전체 <?php echo number_format($total_count); ?>건, 대기 <?php echo number_format($waiting_count); ?>건, 완료 <?php echo number_format($complete_count); ?>건, 취소 <?php echo $cancel_count;?> 건
</div>

<form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get" onsubmit="return getSearch(this);">
<input type="hidden" name="code" value="wzb_booking_list" />
<input type="hidden" name="sch_cp_ix" id="sch_cp_ix" value="<?php echo $sch_cp_ix;?>" />
<div>
    <strong>이용서비스명</strong>
    <input type="text" id="sch_room" name="sch_room" value="<?php echo $sch_room;?>" class="frm_input" size="20" maxlength="20">
</div>
<div>
    <strong>검색방법</strong>
    <select name="sfl" id="sfl">
        <option value="bk_name" <?php echo ($sfl == "bk_name" ? "selected=selected" : "");?>>예약자명</option>
        <option value="bk_deposit_name" <?php echo ($sfl == "bk_deposit_name" ? "selected=selected" : "");?>>입금자명</option>
        <option value="bk_birthday" <?php echo ($sfl == "bk_birthday" ? "selected=selected" : "");?>>생년월일</option>
        <option value="bk_hp" <?php echo ($sfl == "bk_hp" ? "selected=selected" : "");?>>핸드폰번호</option>
        <option value="bk_email" <?php echo ($sfl == "bk_email" ? "selected=selected" : "");?>>이메일</option>
        <option value="od_id" <?php echo ($sfl == "od_id" ? "selected=selected" : "");?>>예약번호</option>
    </select>
    <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" class="frm_input" style="width:170px;" maxlength="50" />
</div>
<div>
    <strong>결제수단</strong>
    <label><input type="radio" name="sch_payment" id="sch_payment1" value="" <?php echo ($sch_payment == "" ? "checked=checked" : "");?>> 전체</label>&nbsp;
    <label><input type="radio" name="sch_payment" id="sch_payment1" value="무통장" <?php echo ($sch_payment == "무통장" ? "checked=checked" : "");?>> 무통장</label>&nbsp;
    <label><input type="radio" name="sch_payment" id="sch_payment2" value="신용카드" <?php echo ($sch_payment == "신용카드" ? "checked=checked" : "");?>> 신용카드</label>&nbsp;
    <label><input type="radio" name="sch_payment" id="sch_payment3" value="계좌이체" <?php echo ($sch_payment == "계좌이체" ? "checked=checked" : "");?>> 계좌이체</label>&nbsp;
    <label><input type="radio" name="sch_payment" id="sch_payment4" value="가상계좌" <?php echo ($sch_payment == "가상계좌" ? "checked=checked" : "");?>> 가상계좌</label>
</div>
<div>
    <strong>예약상태</strong>
    <input type="radio" name="sch_status" value="" id="sch_status1" <?php echo ($sch_status == "" ? "checked=checked" : "");?>>
    <label for="sch_status1">전체</label>
    <input type="radio" name="sch_status" value="대기" id="sch_status2" <?php echo ($sch_status == "대기" ? "checked=checked" : "");?>>
    <label for="sch_status2">대기</label>
    <input type="radio" name="sch_status" value="완료" id="sch_status3" <?php echo ($sch_status == "완료" ? "checked=checked" : "");?>>
    <label for="sch_status3">완료</label>
    <input type="radio" name="sch_status" value="취소" id="sch_status4" <?php echo ($sch_status == "취소" ? "checked=checked" : "");?>>
    <label for="sch_status4">취소</label>
</div>
<div>
    <strong>예약신청일</strong>
    <input type="text" id="sch_frdate2" name="sch_frdate2" value="<?php echo $sch_frdate2;?>" class="frm_input" size="10" maxlength="10"> ~
    <input type="text" id="sch_todate2" name="sch_todate2" value="<?php echo $sch_todate2;?>" class="frm_input" size="10" maxlength="10">
    <button type="button" onclick="javascript:set_date2('오늘');">오늘</button>
    <button type="button" onclick="javascript:set_date2('어제');">어제</button>
    <button type="button" onclick="javascript:set_date2('이번주');">이번주</button>
    <button type="button" onclick="javascript:set_date2('이번달');">이번달</button>
    <button type="button" onclick="javascript:set_date2('지난주');">지난주</button>
    <button type="button" onclick="javascript:set_date2('지난달');">지난달</button>
    <button type="button" onclick="javascript:set_date2('전체');">전체</button>
</div>
<div class="sch_last">
    <strong>이용일</strong>
    <input type="text" id="sch_frdate1" name="sch_frdate1" value="<?php echo $sch_frdate1;?>" class="frm_input" size="10" maxlength="10"> ~
    <input type="text" id="sch_todate1" name="sch_todate1" value="<?php echo $sch_todate1;?>" class="frm_input" size="10" maxlength="10">
    <button type="button" onclick="javascript:set_date1('오늘');">오늘</button>
    <button type="button" onclick="javascript:set_date1('어제');">어제</button>
    <button type="button" onclick="javascript:set_date1('이번주');">이번주</button>
    <button type="button" onclick="javascript:set_date1('이번달');">이번달</button>
    <button type="button" onclick="javascript:set_date1('지난주');">지난주</button>
    <button type="button" onclick="javascript:set_date1('지난달');">지난달</button>
    <button type="button" onclick="javascript:set_date1('전체');">전체</button>
    <input type="submit" value="검색" class="btn_submit">
    <input type="button" value="엑셀다운로드" class="btn_submit" onclick="getExcel(document.forms.fsearch);">
</div>
</form>

<div class="local_desc01 local_desc">
    <p>
        목록화면에서 예약취소는 PG결제 승인취소가 되지 않으므로 반드시 상세화면에서 PG결제 승인취소를 처리해주세요.
    </p>
</div>

<form name="frmlist" action="./rpage.php?code=wzb_booking_list_update&<?php echo $qstr;?>" method="post" onsubmit="return frm_submit(this);">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">예약번호</th>
        <th scope="col">예약정보</th>
        <th scope="col">예약자명</th>
        <th scope="col">이용요금</th>
        <th scope="col">결제상태</th>
        <th scope="col">결제방식</th>
        <th scope="col">날짜</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($cnt_order > 0) {
        for ($z = 0; $z < $cnt_order; $z++) {
            $cnt_room = count($arr_order[$z]['rm']);
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_chk">
                    <input type="hidden" name="bk_ix[<?php echo $z ?>]" value="<?php echo $arr_order[$z]['bk_ix'] ?>">
                    <input type="checkbox" name="chk[]" value="<?php echo $z ?>" id="chk_<?php echo $z ?>">
                </td>
                <td class="td_alignc"><a href="./wzb_booking_view.php?code=<? echo $code; ?>&bk_ix=<?php echo $arr_order[$z]['bk_ix'].'&'.$qstr?>"><?php echo $arr_order[$z]['od_id'];?></a></td>
                <td>
                    <?php
                    if ($cnt_room) {
                        for ($n = 0; $n < $cnt_room; $n++) {

                            $txheader = '';
                            if ($arr_order[$z]['rm'][$n]['rms_status'] == '완료')
                                $txheader   = '<span class="txheader done">완</span>';
                            else if ($arr_order[$z]['rm'][$n]['rms_status'] == '취소')
                                $txheader   = '<span class="txheader canc">취</span>';
                            else
                                $txheader   = '<span class="txheader stay">대</span>';

                            echo '<a href="./wzb_booking_view.php?code='.$code.'&bk_ix='.$arr_order[$z]['bk_ix'].'&'.$qstr.'" class="rm-info">'.$txheader.' <strong>'.$arr_order[$z]['rm'][$n]['rm_subject'].'</strong> <span class="use-dt">'.wz_get_hangul_date_md($arr_order[$z]['rm'][$n]['rms_date']).'('.get_yoil($arr_order[$z]['rm'][$n]['rms_date']).') '.wz_get_hangul_time_hm($arr_order[$z]['rm'][$n]['rms_time']).' '.$arr_order[$z]['rm'][$n]['rms_cnt'].'명 </span></a>';
                        }
                    }
                    if ($arr_order[$z]['bk_status'] == '취소' && $arr_order[$z]['bk_cancel_pos']) {
                        echo '<div class="cancel-info">'.strtoupper($arr_order[$z]['bk_cancel_pos']).' | '.$arr_order[$z]['bk_cancel_time'].' | '.$arr_order[$z]['bk_cancel_ip'].'</div>';
                    }
                    ?>
                </td>
                <td class="td_alignc"><?php echo $arr_order[$z]['bk_name'];?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['bk_price']);?></td>
                <td class="td_alignc">
                    <span class="sm">
                        예약금 (<?php echo ($arr_order[$z]['bk_reserv_price'] <= ($arr_order[$z]['bk_price'] - $arr_order[$z]['bk_misu']) ? '결제완료' : '<font color="red">미결제</font>');?>) /
                        총금액 (<?php echo ($arr_order[$z]['bk_misu'] ? '<font color="red">미결제</font>' : '결제완료');?>)
                    </span>
                </td>
                <td class="td_alignc"><?php echo $arr_order[$z]['bk_payment'];?></td>
                <td class="td_datetime">
                    <?php
                    if ($arr_order[$z]['bk_status'] == '대기') {
                        echo wz_convert_time_last($arr_order[$z]['bk_time']).' 경과';
                    }
                    else {
                        echo '<span class="sm number">'.substr($arr_order[$z]['bk_time'], 0, 10).'</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
    }
    else {
        ?>
        <tr>
            <td colspan="8" class="td_alignc">데이터가 존재하지 않습니다.</td>
        </tr>
        <?php
    }
    ?>


    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택예약완료" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택예약취소" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택예약대기" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
</div>

</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function(){
    $("#sch_frdate1, #sch_frdate2, #sch_todate1, #sch_todate2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-5:c+1"});
});
function getSearch(f) {
    f.action = "./rpage.php?code=wzb_booking_list";
}
function getExcel(f) {
    f.action = "./wzb_booking_excel.php";
    f.target = "_self";
    f.submit();
}
function frm_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
function set_date1(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("sch_frdate1").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("sch_todate1").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("sch_frdate1").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("sch_todate1").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("sch_frdate1").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate1").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("sch_frdate1").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("sch_todate1").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("sch_frdate1").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate1").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("sch_frdate1").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("sch_todate1").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("sch_frdate1").value = "";
        document.getElementById("sch_todate1").value = "";
    }
}
function set_date2(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("sch_frdate2").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("sch_todate2").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("sch_frdate2").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("sch_todate2").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("sch_frdate2").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate2").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("sch_frdate2").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("sch_todate2").value = "<?php echo date('Y-m-31', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("sch_frdate2").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate2").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("sch_frdate2").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("sch_todate2").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("sch_frdate2").value = "";
        document.getElementById("sch_todate2").value = "";
    }
}
</script>
<?
include_once("./admin_tail.sub.php");
?>
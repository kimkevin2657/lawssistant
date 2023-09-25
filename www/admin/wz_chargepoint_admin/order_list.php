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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

include_once (MS_ADMIN_PATH.'/admin.head.php');

?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    <span class="btn_ov01"><span class="ov_txt">전체</span><span class="ov_num"> <?php echo number_format($total_count); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">대기</span><span class="ov_num"> <?php echo number_format($waiting_count); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">완료</span><span class="ov_num"> <?php echo number_format($complete_count); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">취소</span><span class="ov_num"> <?php echo $cancel_count;?>건</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch03 local_sch" method="get" onsubmit="return getSearch(this);">
<input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
<input type="hidden" name="sch_cp_ix" id="sch_cp_ix" value="<?php echo $sch_cp_ix;?>" />
<div>
    <strong>검색방법</strong>
    <select name="sfl" id="sfl">
        <option value="bk_deposit_name" <?php echo ($sfl == "bk_deposit_name" ? "selected=selected" : "");?>>입금자명</option>
        <option value="mb_id" <?php echo ($sfl == "mb_id" ? "selected=selected" : "");?>>아이디</option>
        <option value="bk_hp" <?php echo ($sfl == "bk_hp" ? "selected=selected" : "");?>>핸드폰번호</option>
        <option value="bk_email" <?php echo ($sfl == "bk_email" ? "selected=selected" : "");?>>이메일</option>
        <option value="od_id" <?php echo ($sfl == "od_id" ? "selected=selected" : "");?>>결제번호</option>
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
    <strong>결제상태</strong>
    <input type="radio" name="sch_status" value="" id="sch_status1" <?php echo ($sch_status == "" ? "checked=checked" : "");?>>
    <label for="sch_status1">전체</label>
    <input type="radio" name="sch_status" value="대기" id="sch_status2" <?php echo ($sch_status == "대기" ? "checked=checked" : "");?>>
    <label for="sch_status2">대기</label>
    <input type="radio" name="sch_status" value="완료" id="sch_status3" <?php echo ($sch_status == "완료" ? "checked=checked" : "");?>>
    <label for="sch_status3">완료</label>
    <input type="radio" name="sch_status" value="취소" id="sch_status4" <?php echo ($sch_status == "취소" ? "checked=checked" : "");?>>
    <label for="sch_status4">취소</label>
</div>
<div class="sch_last">
    <strong>충전신청일</strong>
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
</div>
</form>

<div class="local_desc01 local_desc">
    <p>
        목록화면에서 "선택충전취소"는 PG결제 승인취소가 되지 않으므로 반드시 상세화면에서 PG결제 승인취소를 처리해주세요.
    </p>
</div>

<form name="frmlist" action="./order_list_update.php?<?php echo $qstr;?>" method="post" onsubmit="return frm_submit(this);">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">결제번호</th>
        <th scope="col">회원명</th>
        <th scope="col">입금자명</th>
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
        $z = 0;
        //print_r($arr_order);
        foreach ($arr_order as $k => $v) {

            /* $mb = get_member($v['mb_id'], 'id, name, mb_nick, mb_email, mb_homepage');
            $mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']); */

            $mb = get_member($v['mb_id']);
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_chk">
                    <input type="hidden" name="od_id[<?php echo $z ?>]" value="<?php echo $v['od_id'] ?>">
                    <input type="checkbox" name="chk[]" value="<?php echo $z ?>" id="chk_<?php echo $z ?>">
                </td>
                <td class="td_alignc"><a href="./point_list.php?code=order_view&od_id=<?php echo $v['od_id'].'&'.$qstr?>"><?php echo $v['od_id'];?></a></td>
                <td class="td_alignc"><?php echo $mb['name'];?></td>
                <td class="td_alignc"><?php echo $v['bk_deposit_name'];?></td>
                <td class="td_alignc"><?php echo number_format($v['bk_charge_point']).WPOT_POINT_TEXT;?></td>
                <td class="td_alignc"><?php echo number_format($v['bk_price']);?></td>
                <td class="td_alignc"><?php echo $v['bk_status'];?></td>
                <td class="td_alignc"><?php echo $v['bk_payment'];?></td>
                <td class="td_datetime">
                    <?php
                    if ($v['bk_status'] == '대기') {
                        echo wz_convert_time_last($v['bk_time']).' 경과';
                    }
                    else {
                        echo '<span class="sm number">'.substr($v['bk_time'], 0, 10).'</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php
            $z++;
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

<?php if ($is_admin == 'super') { ?>
<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택충전완료" onclick="document.pressed=this.value" class="btn_01 btn">
    <input type="submit" name="act_button" value="선택충전취소" onclick="document.pressed=this.value" class="btn_02 btn">
    <input type="submit" name="act_button" value="선택충전대기" onclick="document.pressed=this.value" class="btn_02 btn">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
</div>
<?php } ?>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function(){
    $("#sch_frdate1, #sch_todate1").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-5:c+1"});
});
function getSearch(f) {
    f.action = "./point_list.php";
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
</script>
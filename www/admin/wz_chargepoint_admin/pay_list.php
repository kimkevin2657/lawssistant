<?php
include_once('./_common.php');

$g5['title'] = '결제통계정보';

$sql_common = " from wpot_order ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

if ($sch_frdate && $sch_todate) {
    $sql_search .= " and DATE(bk_receipt_time) between '".$sch_frdate."' and '".$sch_todate."' ";
    $qstr .= "&sch_frdate=".$sch_frdate."&sch_todate=".$sch_todate;
    $is_sch = true;
}

if ($sch_bo_table) {
    $sql_search .= " and bo_table = '".$sch_bo_table."' ";
    $qstr .= "&sch_bo_table=".$sch_bo_table;
    $is_sch = true;
}

if (!$sst) {
    $sst = "od_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

unset($arr_order);
$arr_order = array();
$query = "  select
                sum(case when bk_status = '완료' then 1 else 0 end) as cnt,
                sum(case when bk_status = '완료' then bk_receipt_price else 0 end) as bk_receipt_price,
                sum(case when bk_payment = '무통장' and bk_status = '완료' then bk_receipt_price else 0 end) as price_bank,
                sum(case when bk_payment = '가상계좌' then bk_pg_price else 0 end) as price_vbank,
                sum(case when bk_payment = '계좌이체' then bk_pg_price else 0 end) as price_dbank,
                sum(case when bk_payment = '신용카드' then bk_pg_price else 0 end) as price_card,
                sum(case when bk_payment = '휴대폰' then bk_pg_price else 0 end) as price_hp
            {$sql_common} {$sql_search} {$sql_order} ";
$res = sql_query($query, true);
while($row = sql_fetch_array($res)) {
    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res) sql_free_result($res);

include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<form name="fsearch" id="fsearch" class="local_sch03 local_sch" method="get" onsubmit="return getSearch(this);">
<input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
<div class="sch_last">
    <strong>결제일</strong>
    <input type="text" id="sch_frdate" name="sch_frdate" value="<?php echo $sch_frdate;?>" class="frm_input" size="10" maxlength="10"> ~
    <input type="text" id="sch_todate" name="sch_todate" value="<?php echo $sch_todate;?>" class="frm_input" size="10" maxlength="10">
    <button type="button" onclick="javascript:set_date('오늘');">오늘</button>
    <button type="button" onclick="javascript:set_date('어제');">어제</button>
    <button type="button" onclick="javascript:set_date('이번주');">이번주</button>
    <button type="button" onclick="javascript:set_date('이번달');">이번달</button>
    <button type="button" onclick="javascript:set_date('지난주');">지난주</button>
    <button type="button" onclick="javascript:set_date('지난달');">지난달</button>
    <button type="button" onclick="javascript:set_date('전체');">전체</button>
    <input type="submit" value="검색" class="btn_submit">
  <!--  <input type="button" value="엑셀다운로드" class="btn_submit" onclick="getExcel(document.forms.fsearch);"> -->
</div>
</form>

<div class="local_desc01 local_desc">
    <p>
        결제일검색에 나타나지 않을경우 예약관리 상세화면에서 결제완료일시 항목을 확인해주세요.
    </p>
</div>

<form name="frmlist" method="post">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">결제완료건수</th>
        <th scope="col">결제합계</th>
        <th scope="col">무통장</th>
        <th scope="col">가상계좌</th>
        <th scope="col">계좌이체</th>
        <th scope="col">신용카드</th>
        <th scope="col">휴대폰</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($cnt_order > 0) {
        for ($z = 0; $z < $cnt_order; $z++) {
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['cnt']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['bk_receipt_price']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['price_bank']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['price_vbank']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['price_dbank']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['price_card']);?></td>
                <td class="td_alignc"><?php echo number_format($arr_order[$z]['price_hp']);?></td>
            </tr>
            <?php
        }
    }
    else {
        ?>
        <tr>
            <td colspan="7" class="td_alignc">데이터가 존재하지 않습니다.</td>
        </tr>
        <?php
    }
    ?>

    </tbody>
    </table>
</div>

</form>

<script>
$(function(){
    $("#sch_frdate, #sch_todate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-5:c+1"});
});
function getSearch(f) {
    f.action = "./point_list.php";
}
function getExcel(f) {
    f.action = "./pay_excel.php";
    f.target = "_self";
    f.submit();
}
function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("sch_frdate").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("sch_todate").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("sch_frdate").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("sch_todate").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("sch_frdate").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("sch_frdate").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("sch_todate").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("sch_frdate").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("sch_todate").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("sch_frdate").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("sch_todate").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("sch_frdate").value = "";
        document.getElementById("sch_todate").value = "";
    }
}
</script>
<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가


if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($rel_field) && $rel_field) {
    $qstr .= "&rel_field=$rel_field";
}

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_minishop_pay a, shop_member b ";
$sql_search = " where a.mb_id = b.id and mb_id = '{$member['id']}' ";

if($rel_field)
    $sql_search .= " and a.pp_rel_table = '{$rel_field}' ";

if($fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "a.pp_id";
    $sod = "desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by {$filed} {$sod} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.name, b.grade {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="/mypage/minishop_payhistoryexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;
?>

<div class="local_ov tac">
    <strong class="fc_107 marr10">총적립액 : <?php echo number_format($sum['pay']); ?>원,</strong>
    <strong class="fc_red marr10">총차감액 : <?php echo number_format($sum['usepay']); ?>원,</strong>
    <strong class="fc_00f">현재잔액 : <?php echo number_format($member['pay']); ?>원</strong>
</div>

<script src="<?php echo MS_JS_URL . "/common.js"; ?>"></script>
<form name="fsearch" id="fsearch" method="get">
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w40">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">기간</th>
                <td>
                    <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">구분</th>
                <td>
                    <?php echo radio_checked('rel_field', $rel_field, '', '전체'); ?>
                    <?php echo radio_checked('rel_field', $rel_field, 'sale', $gw_ptype['sale']); ?>
                    <?php echo radio_checked('rel_field', $rel_field, 'anew', $gw_ptype['anew']); ?>
                    <?php echo radio_checked('rel_field', $rel_field, 'visit', $gw_ptype['visit']); ?>
                    <?php echo radio_checked('rel_field', $rel_field, 'passive', $gw_ptype['passive']); ?>
                    <?php echo radio_checked('rel_field', $rel_field, 'payment', $gw_ptype['payment']); ?>
                    <?php if( defined('USE_ANEWMATCH') && USE_ANEWMATCH ) echo radio_checked('rel_field', $rel_field, 'anew_match', $gw_ptype['anew_match']); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="btn_confirm">
        <input type="submit" value="검색" class="btn_medium">
        <input type="button" value="초기화" id="frmRest" class="btn_medium grey">
    </div>
</form>


<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<style>
    .bbn    td,
    .bbn    th
    { border-bottom:none;}
    .tbn    td,
    .tbn    th
    { border-top:none;}
    .local_ov { padding:10px 0; background: #efefef;}
    ul.payhistory {border-top:2px solid #000;}
    ul.payhistory li { padding:15px 5px; border-bottom:1px solid #333; position:relative; height: 40px;}
    ul.payhistory li .memo {position:absolute; font-size: 1em;}
    ul.payhistory li .type {position:absolute; margin-top:25px;}
    ul.payhistory li .date-time {position:absolute; margin-top:25px; margin-left: 80px;}
    ul.payhistory li .pay { width: 80px; float: right; text-align: right;}
    ul.payhistory li .balance {position:absolute; right: 5px; font-size: .9em; color:#999; margin-top:10px;}
</style>
<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
    <ul class="payhistory">
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $bg = 'list'.($i%2);
            ?>
            <li class="<?php echo $bg; ?>">
                <div class="memo tal"><?php echo $row['pp_content']; ?></div>
                <div class="type"><?php echo $gw_ptype[$row['pp_rel_table']]; ?></div>
                <div class="pay">
                    <div>
                        <?php echo number_format($row['pp_pay']); ?>
                    </div>
                    <div class="balance">
                        <?php echo number_format($row['pp_balance']); ?>
                    </div>
                </div>
                <div class="date-time"><?php echo $row['pp_datetime']; ?></div>
            </li>
            <?php
        }
        if($i==0)
            echo '<li class="empty_table">자료가 없습니다.</li>';
        ?>
    </ul>
</div>
<div class="local_frm02">
    <?php echo $btn_frmline; ?>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
    $(function(){
        // 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
        $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
    });
</script>
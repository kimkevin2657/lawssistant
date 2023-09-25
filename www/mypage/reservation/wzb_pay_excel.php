<?php
$sub_menu = '790700';
include_once('./_common.php');

$sql_common = " from {$g5['wzb_booking_table']} ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

if ($sch_cp_ix) {
    $sql_search .= " and cp_ix = '".$sch_cp_ix."' ";
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

if ($sch_frdate && $sch_todate) {
    $sql_search .= " and DATE(bk_receipt_time) between '".$sch_frdate."' and '".$sch_todate."' ";
    $qstr .= "&sch_frdate=".$sch_frdate."&sch_todate=".$sch_todate;
    $is_sch = true;
}

if (!$sst) {
    $sst = "bk_ix";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

unset($arr_order);
$arr_order = array();
$query = "  select 
                cp_ix,
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
    $query2 = "select cp_title from {$g5['wzb_corp_table']} where cp_ix = '{$row['cp_ix']}' "; // 업체정보
    $row2 = sql_fetch($query2);
    $row['cp_title'] = $row2['cp_title'];

    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res) sql_free_result($res);

if($cnt_order < 1)
    alert('출력할 내역이 없습니다.');

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(G5_DATA_PATH, "tmp-paylist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();
$worksheet->set_column(0, 2, 17); // 부터, 까지, 가로길이
$worksheet->set_column(3, 3, 10); // 부터, 까지, 가로길이
$worksheet->set_column(4, 4, 40); // 부터, 까지, 가로길이
$worksheet->set_column(5, 11, 15); // 부터, 까지, 가로길이
$worksheet->set_column(12, 12, 60); // 부터, 까지, 가로길이
$worksheet->set_column(13, 17, 14); // 부터, 까지, 가로길이
$worksheet->set_row(0, 30); // 행번호, 행높이
$worksheet->freeze_panes(1, 1); # 1 row and column

$header =& $workbook->addformat();
$header->set_bold();
$header->set_font('Courier New');
$header->set_align('center');
$header->set_align('vcenter');
//$header->set_fg_color('gray');
$header->set_border_color('gray');

$body =& $workbook->addformat();
$body->set_align('vcenter');

$number =& $workbook->addformat();
$number->set_align('right');
$number->set_align('vcenter');
$number->set_num_format('#,##0');

$text_wrap =& $workbook->addformat();
$text_wrap->set_text_wrap();
$text_wrap->set_align('vcenter');

// Put Excel data
$data = array('업체명', '결제완료건수', '결제합계', '무통장', '가상계좌', '계좌이체', '신용카드', '휴대폰');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell, $header);
}

$i=1;

if ($cnt_order > 0) {
    for ($z = 0; $z < $cnt_order; $z++) { 
        
        $j = 0;
        $row = array_map('iconv_euckr', $arr_order[$z]);

        $worksheet->write($i, $j++, ' '.$row['cp_title'], $body);
        $worksheet->write($i, $j++, $row['cnt'], $number);
        $worksheet->write($i, $j++, $row['bk_receipt_price'], $number);
        $worksheet->write($i, $j++, $row['price_bank'], $number);
        $worksheet->write($i, $j++, $row['price_vbank'], $number);
        $worksheet->write($i, $j++, $row['price_dbank'], $number);
        $worksheet->write($i, $j++, $row['price_card'], $number);
        $worksheet->write($i, $j++, $row['price_hp'], $number);
    $i++;
    }
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"paylist-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"paylist-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>
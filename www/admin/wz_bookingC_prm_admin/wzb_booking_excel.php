<?php
$sub_menu = '790400';
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

$query = " select * {$sql_common} {$sql_search} {$sql_order} ";

unset($arr_order);
$arr_order = array();
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $query2 = "select * from {$g5['wzb_booking_room_table']} where bk_ix = '{$row['bk_ix']}' order by rm_ix asc "; // 객실정보
    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) { 
        $row['rm'][] = $row2;
    }

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

$fname = tempnam(G5_DATA_PATH, "tmp-bookinglist.xls");
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
$data = array('예약번호', '예약정보', '예약상세정보', '예약자명', '예약금', '잔금', '총이용금액', '회원아이디', '핸드폰', '이메일', '요청사항', '등록일', '상태');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell, $header);
}

$i=1;
if ($cnt_order > 0) {
    for ($z = 0; $z < $cnt_order; $z++) {

    foreach ($arr_order[$z] as $key => $val) {
        if (!is_array($val)) { 
            $row[$key] = iconv_euckr($val);    
        } 
    }

    $cnt_room = count($arr_order[$z]['rm']);
    $rooms = '';
    if ($cnt_room) { 
        for ($n = 0; $n < $cnt_room; $n++) { 
            if ($n > 0) { 
                $rooms .= "\n";
            } 
            $rooms .= '['.$row['bk_status'].'] '.iconv_euckr($arr_order[$z]['rm'][$n]['bkr_subject']);
            $rooms .= ' '.iconv_euckr(wz_get_hangul_date_md($arr_order[$z]['rm'][$n]['bkr_date']));
            $rooms .= '('.iconv_euckr(get_yoil($arr_order[$z]['rm'][$n]['bkr_date'])).') ';
            $rooms .= ' '.iconv_euckr(wz_get_hangul_time_hm($arr_order[$z]['rm'][$n]['bkr_time']));
            $rooms .= ' '.iconv_euckr($arr_order[$z]['rm'][$n]['bkr_cnt'].' 명');
        }   
    } 
    
    $j = 0;

    $worksheet->write($i, $j++, ' '.$row['od_id'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_subject'], $body);

    $worksheet->write($i, $j++, $rooms, $text_wrap);
    $worksheet->write($i, $j++, ' '.$row['bk_name'], $body);

    $worksheet->write($i, $j++, $row['bk_reserv_price'], $number);
    $worksheet->write($i, $j++, ($row['bk_price'] - $row['bk_reserv_price']), $number);
    $worksheet->write($i, $j++, $row['bk_price'], $number);

    $worksheet->write($i, $j++, ' '.$row['mb_id'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_hp'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_email'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_memo'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_time'], $body);
    $worksheet->write($i, $j++, ' '.$row['bk_status'], $body);
    $i++;

    }
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"bookinglist-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"bookinglist-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>